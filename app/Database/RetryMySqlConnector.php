<?php

namespace App\Database;

use Illuminate\Database\Connectors\MySqlConnector;
use PDOException;

/**
 * Connecteur MySQL qui retente brievement quand l'hebergeur sature la limite de connexions par utilisateur
 * (1040 "Too many connections", 1203 et 1226 "max_user_connections") ou coupe une connexion (2002, 2006, 2013).
 * Le forum et cette application partagent le meme utilisateur MySQL : une rafale de requetes suffit a atteindre la limite.
 */
class RetryMySqlConnector extends MySqlConnector
{
    /** Attentes (microsecondes) entre les tentatives : 0,15 s, 0,4 s, 0,9 s (au plus 1,5 s en tout). */
    private const DELAYS = [150000, 400000, 900000];

    protected function createPdoConnection($dsn, $username, $password, $options)
    {
        $attempt = 0;
        while (true) {
            try {
                return parent::createPdoConnection($dsn, $username, $password, $options);
            } catch (PDOException $e) {
                $code = (int) $e->getCode();
                if (!in_array($code, [1040, 1203, 1226, 2002, 2006, 2013], true) || $attempt >= count(self::DELAYS)) {
                    throw $e;
                }
                usleep(self::DELAYS[$attempt] + random_int(0, 80000));
                $attempt++;
            }
        }
    }
}
