<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apptitle;
use App\Models\AssistantKb;
use App\Models\AssistantLog;
use App\Models\AssistantSetting;
use App\Models\Footertext;
use App\Models\Pages;
use App\Models\Seosetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * Administration de l'assistant GANXÔ : activation, textes, garde-fous, escalade, base de connaissances
 * et statistiques anonymes (aucun texte de visiteur n'existe en base).
 */
class AssistantAdminController extends Controller
{
    private const URL_RULE = '/^(\{alert\}|\{forum\}|\/(?!\/).*|#.*|https?:\/\/\S+)$/i';

    public function index()
    {
        $this->authorize('Assistant Access');

        $data['title'] = Apptitle::first();
        $data['basic'] = $data['title'];
        $data['footertext'] = Footertext::first();
        $data['seopage'] = Seosetting::first();
        $data['page'] = Pages::all();

        $data['s'] = AssistantSetting::allSettings();
        $data['guardrails'] = AssistantSetting::GUARDRAILS;
        $data['kb'] = AssistantKb::orderBy('sort_order')->orderBy('id')->get();

        $since = now()->subDays(30);
        $tot = AssistantLog::selectRaw('count(*) as total, coalesce(sum(matched),0) as matched, coalesce(sum(escalated),0) as escalated')->first();
        $rec = AssistantLog::where('created_at', '>=', $since)->selectRaw('count(*) as total, coalesce(sum(matched),0) as matched, coalesce(sum(escalated),0) as escalated')->first();
        $data['stats'] = [
            'all' => ['total' => (int) $tot->total, 'matched' => (int) $tot->matched, 'escalated' => (int) $tot->escalated],
            'recent' => ['total' => (int) $rec->total, 'matched' => (int) $rec->matched, 'escalated' => (int) $rec->escalated],
        ];
        $data['perEntry'] = AssistantLog::whereNotNull('kb_id')->select('kb_id', DB::raw('count(*) as n'))->groupBy('kb_id')->pluck('n', 'kb_id')->all();
        $data['canEdit'] = auth()->user() && auth()->user()->can('Assistant Edit');

        return view('admin.assistant.index')->with($data);
    }

    public function saveSettings(Request $request)
    {
        $this->authorize('Assistant Edit');

        $rules = [
            'greeting' => 'required|string|max:600',
            'fallback' => 'required|string|max:900',
            'offtopic' => 'required|string|max:900',
            'disclaimer' => 'required|string|max:400',
            'quick_questions' => 'nullable|string|max:800',
            'escalation_message' => 'required|string|max:1500',
            'no_diagnosis_text' => 'required|string|max:900',
            'pii_text' => 'required|string|max:600',
            'escalation_keywords' => 'nullable|string|max:3000|required_if:escalation_enabled,1',
        ];
        $v = Validator::make($request->all(), $rules);
        if ($v->fails()) {
            return back()->withErrors($v)->withInput()->with('error', 'Certains champs sont invalides : vérifiez le formulaire.');
        }

        $values = [];
        foreach (['greeting', 'fallback', 'offtopic', 'disclaimer', 'quick_questions', 'escalation_message', 'no_diagnosis_text', 'pii_text', 'escalation_keywords'] as $k) {
            $values[$k] = trim((string) $request->input($k, ''));
        }
        foreach (AssistantSetting::FLAGS as $k) {
            $values[$k] = $request->boolean($k) ? '1' : '0';
        }
        AssistantSetting::put($values);

        return back()->with('success', 'Réglages de l’assistant enregistrés.');
    }

    public function kbSave(Request $request)
    {
        $this->authorize('Assistant Edit');

        $v = Validator::make($request->all(), [
            'question' => 'required|string|min:5|max:255',
            'answer' => 'required|string|min:20|max:3000',
            'keywords' => 'nullable|string|max:1500',
            'cta_label' => 'nullable|string|max:80|required_with:cta_url',
            'cta_url' => ['nullable', 'string', 'max:255', 'regex:' . self::URL_RULE, 'required_with:cta_label'],
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'id' => 'nullable|integer|exists:assistant_kb,id',
        ], [
            'cta_url.regex' => 'L’adresse doit commencer par /, #, http:// ou https://, ou être {alert} ou {forum}.',
        ]);
        if ($v->fails()) {
            return back()->withErrors($v)->withInput()->with('kb_form_open', true)->with('error', 'Entrée non enregistrée : vérifiez le formulaire.');
        }

        $row = [
            'question' => trim($request->input('question')),
            'answer' => trim($request->input('answer')),
            'keywords' => trim((string) $request->input('keywords', '')),
            'cta_label' => trim((string) $request->input('cta_label', '')) ?: null,
            'cta_url' => trim((string) $request->input('cta_url', '')) ?: null,
            'active' => $request->boolean('active'),
            'sort_order' => (int) $request->input('sort_order', 0),
        ];
        if ($request->filled('id')) {
            AssistantKb::findOrFail($request->input('id'))->update($row);
        } else {
            if (!$request->filled('sort_order')) {
                $row['sort_order'] = ((int) AssistantKb::max('sort_order')) + 10;
            }
            AssistantKb::create($row);
        }

        return back()->with('success', 'Entrée enregistrée.');
    }

    public function kbToggle($id)
    {
        $this->authorize('Assistant Edit');
        $e = AssistantKb::findOrFail($id);
        $e->update(['active' => !$e->active]);

        return back()->with('success', $e->active ? 'Entrée activée.' : 'Entrée désactivée.');
    }

    public function kbDelete($id)
    {
        $this->authorize('Assistant Edit');
        AssistantKb::findOrFail($id)->delete();

        return back()->with('success', 'Entrée supprimée.');
    }
}
