<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Authentifie les appels serveur-a-serveur du forum GANXO (HMAC-SHA256).
 * En-tetes : X-Forum-Timestamp (secondes), X-Forum-Signature = hex(hmac_sha256(secret, "ts\nMETHODE\n/chemin\nsha256(corps)")).
 * Fenetre de 5 minutes. Refuse tout si FORUM_ALERT_SECRET est vide ou trop court.
 */
class VerifyForumSignature
{
    public function handle(Request $request, Closure $next)
    {
        $secret = (string) config('services.forum_alert.secret');
        if (strlen($secret) < 32) {
            return response()->json(['error' => 'forum_api_disabled'], 503);
        }
        $ts = (string) $request->header('X-Forum-Timestamp');
        $sig = (string) $request->header('X-Forum-Signature');
        if (!ctype_digit($ts) || abs(time() - (int) $ts) > 300 || $sig === '') {
            return response()->json(['error' => 'invalid_signature'], 401);
        }
        $payload = $ts . "\n" . strtoupper($request->method()) . "\n/" . ltrim($request->path(), '/') . "\n" . hash('sha256', (string) $request->getContent());
        if (!hash_equals(hash_hmac('sha256', $payload, $secret), strtolower($sig))) {
            return response()->json(['error' => 'invalid_signature'], 401);
        }
        return $next($request);
    }
}
