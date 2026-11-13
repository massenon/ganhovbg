<?php

namespace App\Http\Controllers;

use App\Models\Apptitle;
use App\Models\Footertext;
use App\Models\Pages;
use App\Models\Seosetting;
use App\Services\AssistantEngine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Assistant GANXÔ côté public : page /assistant et API JSON POST /assistant/ask.
 * Le texte du visiteur n'est ni stocké, ni journalisé : seul un compteur anonyme l'est (AssistantEngine::ask).
 */
class AssistantController extends Controller
{
    public function page()
    {
        $data['title'] = Apptitle::first();
        $data['footertext'] = Footertext::first();
        $data['seopage'] = Seosetting::first();
        $data['page'] = Pages::all();
        $data['assistant'] = AssistantEngine::pageConfig();

        return view('assistantpage')->with($data);
    }

    public function ask(Request $request)
    {
        $cfg = AssistantEngine::pageConfig();
        if (!$cfg['enabled']) {
            return response()->json([
                'answer' => 'L’assistant est momentanément indisponible. Vous pouvez créer une alerte pour écrire à l’équipe GANXÔ.',
                'cta' => [['label' => 'Créer une alerte', 'url' => $cfg['alert_url']]],
                'suggestions' => [], 'escalate' => false, 'matched' => false, 'kind' => 'disabled', 'enabled' => false,
            ], 503);
        }

        $v = Validator::make($request->all(), ['message' => ['required', 'string', 'max:' . AssistantEngine::MAX_LEN]]);
        if ($v->fails()) {
            return response()->json([
                'error' => 'invalid_message',
                'message' => 'Votre message doit contenir entre 1 et ' . AssistantEngine::MAX_LEN . ' caractères.',
            ], 422);
        }
        $text = trim((string) $request->input('message'));
        if ($text === '') {
            return response()->json(['error' => 'invalid_message', 'message' => 'Votre message est vide.'], 422);
        }

        // Pendant la création d'une alerte dans la conversation : seulement la détection d'urgence (rien n'est journalisé)
        if ($request->boolean('check_escalation')) {
            return response()->json(AssistantEngine::escalationCheck($text))->header('Cache-Control', 'no-store');
        }

        return response()->json(AssistantEngine::ask($text))->header('Cache-Control', 'no-store');
    }

    /** Paramètres de la conversation « créer une alerte » (catégories réelles, OTP, limites). Aucune donnée personnelle. */
    public function alertConfig()
    {
        return response()->json(AssistantEngine::alertConfig())->header('Cache-Control', 'no-store');
    }
}
