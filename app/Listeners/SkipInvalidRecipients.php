<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSending;

/**
 * Garde-fou : les adresses « *.invalid » (domaine réservé, RFC 2606) sont des adresses de remplacement
 * créées pour les alertes déposées via l'assistant sans e-mail. On n'y envoie jamais rien.
 * Les adresses réelles ne sont pas modifiées ; si plus aucun destinataire ne reste, l'envoi est annulé.
 */
class SkipInvalidRecipients
{
    public function handle(MessageSending $event)
    {
        $msg = $event->message;
        $skipped = false;
        foreach (['To', 'Cc', 'Bcc'] as $f) {
            $get = 'get' . $f;
            $set = 'set' . $f;
            $list = $msg->$get();
            if (!$list) {
                continue;
            }
            $keep = [];
            foreach ($list as $addr => $name) {
                if (preg_match('/\.invalid$/i', (string) $addr)) {
                    $skipped = true;
                } else {
                    $keep[$addr] = $name;
                }
            }
            if (count($keep) !== count($list)) {
                $msg->$set($keep ?: null);
            }
        }
        if ($skipped && !$msg->getTo() && !$msg->getCc() && !$msg->getBcc()) {
            return false;
        }
    }
}
