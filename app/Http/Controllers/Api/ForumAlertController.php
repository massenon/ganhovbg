<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GuestticketController;
use App\Models\Ticket\Category;
use App\Models\Ticket\Comment;
use App\Models\Ticket\Ticket;
use App\Services\AssistantEngine;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * API serveur-a-serveur : alertes VBG creees depuis le forum GANXO.
 * Reutilise GuestticketController@gueststore1 (meme creation de ticket, notifications et e-mails que le formulaire public).
 */
class ForumAlertController extends Controller
{
    public function config()
    {
        $c = AssistantEngine::alertConfig();
        return response()->json([
            'ok' => $c['ok'], 'reason' => $c['reason'],
            'categories' => $c['categories'],
            'subject_max' => $c['subject_max'], 'desc_min' => $c['desc_min'], 'desc_max' => $c['desc_max'],
        ])->header('Cache-Control', 'no-store');
    }

    public function store(Request $request)
    {
        $c = AssistantEngine::alertConfig();
        if (!$c['ok']) {
            return response()->json(['error' => 'alerts_unavailable', 'reason' => $c['reason']], 503);
        }
        $v = validator($request->all(), [
            'category' => 'required|integer',
            'message' => 'required|string|min:' . $c['desc_min'] . '|max:' . $c['desc_max'],
            'subject' => 'nullable|string|max:' . $c['subject_max'],
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'forum_user' => 'nullable|string|max:60',
        ]);
        if ($v->fails()) {
            return response()->json(['error' => 'validation', 'messages' => $v->errors()->all()], 422);
        }
        $cat = Category::whereIn('display', ['ticket', 'both'])->where('status', '1')->find($request->input('category'));
        if (!$cat) {
            return response()->json(['error' => 'validation', 'messages' => ['Catégorie inconnue.']], 422);
        }

        $email = $request->input('email') ?: ('alerte-' . bin2hex(random_bytes(6)) . '@anonyme.invalid');
        $message = trim((string) $request->input('message'));
        $extra = [];
        if ($request->filled('phone')) {
            $extra[] = 'Téléphone indiqué : ' . $request->input('phone');
        }
        $extra[] = 'Alerte envoyée depuis le forum GANXÔ' . ($request->filled('forum_user') ? ' (membre : ' . $request->input('forum_user') . ')' : '') . '.';
        $subject = trim((string) $request->input('subject')) ?: Str::limit($cat->name, $c['subject_max'], '');

        $sub = Request::create('/guest/openticketnootp', 'POST', [
            'subject' => $subject,
            'category' => $cat->id,
            'message' => $message . "\n\n" . implode("\n", $extra),
            'email' => $email,
            'agree_terms' => 'agreed',
        ]);
        $sub->setLaravelSession(app('session.store'));
        $sub->headers->set('Accept', 'application/json');

        try {
            $res = app(GuestticketController::class)->gueststore1($sub);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'validation', 'messages' => collect($e->errors())->flatten()->all()], 422);
        }
        $body = method_exists($res, 'getData') ? json_decode(json_encode($res->getData()), true) : [];
        $t = $body['data'] ?? null;
        if (!$t || empty($t['ticket_id'])) {
            return response()->json(['error' => 'not_created', 'detail' => $body['error'] ?? $body['warning'] ?? null], 422);
        }
        return response()->json(['ok' => true, 'ticket' => $t['ticket_id'], 'anonymous' => str_ends_with($email, '.invalid')])->header('Cache-Control', 'no-store');
    }

    public function messages($ticket)
    {
        $t = Ticket::where('ticket_id', $ticket)->first();
        if (!$t) {
            return response()->json(['error' => 'not_found'], 404);
        }
        $items = [['from' => 'me', 'text' => (string) $t->message, 'at' => optional($t->created_at)->toIso8601String()]];
        foreach (Comment::where('ticket_id', $t->id)->orderBy('id')->get() as $c) {
            $items[] = ['from' => $c->user_id ? 'team' : 'me', 'text' => strip_tags((string) $c->comment), 'at' => optional($c->created_at)->toIso8601String()];
        }
        return response()->json(['ticket' => $t->ticket_id, 'status' => (string) $t->status, 'closed' => $t->status === 'Closed', 'messages' => $items])->header('Cache-Control', 'no-store');
    }

    public function reply(Request $request, $ticket)
    {
        $t = Ticket::where('ticket_id', $ticket)->first();
        if (!$t) {
            return response()->json(['error' => 'not_found'], 404);
        }
        if ($t->status === 'Closed') {
            return response()->json(['error' => 'closed'], 409);
        }
        $v = validator($request->all(), ['comment' => 'required|string|min:1|max:4000']);
        if ($v->fails()) {
            return response()->json(['error' => 'validation', 'messages' => $v->errors()->all()], 422);
        }
        $sub = Request::create('/guest/ticketdetails/' . $t->ticket_id, 'POST', ['comment' => strip_tags((string) $request->input('comment')), 'ticket_id' => $t->id]);
        $sub->setLaravelSession(app('session.store'));
        app(GuestticketController::class)->postComment($sub, $t->ticket_id);
        return response()->json(['ok' => true])->header('Cache-Control', 'no-store');
    }
}
