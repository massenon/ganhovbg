<?php

namespace App\Http\Controllers;

use App\Models\Articles\Article;
use App\Models\Ticket\Comment;
use App\Models\Ticket\Ticket;
use Auth;
use File;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Pièces jointes (images et fichiers des alertes, des réponses, des actualités).
 * Accès contrôlé : personnel autorisé, client propriétaire de l'alerte, ou invité vérifié pour CETTE alerte.
 * Sans droit, la réponse est un 404 (on ne révèle pas l'existence du fichier).
 */
class ImageController extends Controller
{
    /** Retourne [Media, chemin] si l'identifiant et le nom correspondent à un fichier existant. */
    private function resolve($id, $image)
    {
        $media = Media::find((int) $id);
        if (!$media || $media->file_name !== $image) {
            return null;
        }
        $path = public_path('media/' . $media->id . '/' . $media->file_name);
        return File::exists($path) ? [$media, $path] : null;
    }

    private function isStaff(): bool
    {
        $u = Auth::check() ? Auth::user() : null;
        return $u && ($u->hasRole('superadmin') || $u->can('Ticket Access') || $u->can('Ticket Edit'));
    }

    private function canAccess(Media $media): bool
    {
        $model = $media->model;
        if (!$model) {
            return false;
        }

        $ticket = null;
        if ($model instanceof Ticket) {
            $ticket = $model;
        } elseif ($model instanceof Comment) {
            $ticket = $model->ticket;
        }
        if ($ticket) {
            if ($this->isStaff()) {
                return true;
            }
            if (Auth::guard('customer')->check() && (int) Auth::guard('customer')->id() === (int) $ticket->cust_id) {
                return true;
            }
            return session('guestimageaccess') !== null && session('guestimageaccess') === $ticket->ticket_id;
        }

        if ($model instanceof Article) {
            if (strtolower((string) $model->status) !== 'published') {
                return Auth::check();
            }
            if ($model->privatemode == 1) {
                return Auth::check() || Auth::guard('customer')->check();
            }
            return true;
        }

        return Auth::check();
    }

    public function index($id, $image)
    {
        $r = $this->resolve($id, $image);
        if (!$r || !$this->canAccess($r[0])) {
            abort(404);
        }
        return response()->file($r[1]);
    }

    public function imagedownload($id, $image)
    {
        $r = $this->resolve($id, $image);
        if (!$r || !$this->canAccess($r[0])) {
            abort(404);
        }
        return response()->download($r[1]);
    }

    private function emailFile($image)
    {
        $path = public_path('uploads/emailtoticket/' . basename($image));
        $logged = (Auth::check() && Auth::user()) || (Auth::guard('customer')->check() && Auth::guard('customer')->user());
        return ($logged && File::exists($path)) ? $path : null;
    }

    public function emailtoticketshow($id, $image)
    {
        $path = $this->emailFile($image);
        if (!$path) {
            abort(404);
        }
        return response()->file($path);
    }

    public function emailtoticketdownload($id, $image)
    {
        $path = $this->emailFile($image);
        if (!$path) {
            abort(404);
        }
        return response()->download($path);
    }

    public function guestimage($id, $image)
    {
        $r = $this->resolve($id, $image);
        if (!$r || !session()->has('guestimageaccess') || !$this->canAccess($r[0])) {
            abort(404);
        }
        return response()->file($r[1]);
    }
}
