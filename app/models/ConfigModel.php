<?php

class ConfigModel
{
    /**
     * Récupérer une valeur de configuration
     */
    public static function get($cle)
    {
        $db = Flight::db();
        $stmt = $db->prepare('SELECT valeur FROM config WHERE cle = :cle');
        $stmt->execute(['cle' => $cle]);
        $row = $stmt->fetch();
        return $row ? $row['valeur'] : null;
    }

    /**
     * Mettre à jour une valeur de configuration
     */
    public static function set($cle, $valeur)
    {
        $db = Flight::db();
        $stmt = $db->prepare('UPDATE config SET valeur = :valeur WHERE cle = :cle');
        $stmt->execute(['cle' => $cle, 'valeur' => $valeur]);
    }

    /**
     * Récupérer le frais d'achat en pourcentage
     */
    public static function getFraisAchat()
    {
        $frais = self::get('frais_achat');
        return $frais !== null ? (float) $frais : 10.0;
    }

    /**
     * Mettre à jour le frais d'achat
     */
    public static function setFraisAchat($pourcentage)
    {
        self::set('frais_achat', $pourcentage);
    }
}
