<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Compresse les reponses texte : Brotli (extension php-brotli) si le client l'accepte,
 * sinon gzip. Ne touche pas aux reponses deja encodees (mod_deflate), binaires, en flux,
 * redirigees ou trop petites.
 */
class CompresserReponse
{
    private const MIN_OCTETS = 1024;

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response instanceof BinaryFileResponse || $response instanceof StreamedResponse
            || $response->isRedirection() || $response->headers->has('Content-Encoding')) {
            return $response;
        }

        $type = (string) $response->headers->get('Content-Type');
        if (!preg_match('#^(text/|application/(json|javascript|xml|manifest\+json)|image/svg\+xml)#i', $type)) {
            return $response;
        }

        $contenu = $response->getContent();
        if (!is_string($contenu) || strlen($contenu) < self::MIN_OCTETS) {
            return $response;
        }

        $accept = strtolower((string) $request->headers->get('Accept-Encoding'));
        $encodage = null;
        $compresse = false;
        if (str_contains($accept, 'br') && function_exists('brotli_compress')) {
            $compresse = @brotli_compress($contenu, 5);
            $encodage = 'br';
        } elseif (str_contains($accept, 'gzip') && function_exists('gzencode')) {
            $compresse = gzencode($contenu, 6);
            $encodage = 'gzip';
        }
        if ($compresse === false || $encodage === null) {
            return $response;
        }

        $response->setContent($compresse);
        $response->headers->set('Content-Encoding', $encodage);
        $response->headers->set('Content-Length', (string) strlen($compresse));
        $response->headers->set('Vary', trim($response->headers->get('Vary', '') . ', Accept-Encoding', ', '));

        return $response;
    }
}
