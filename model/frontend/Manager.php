<?php

namespace OpenClassrooms\Blog\Model;


/**
 * Class Manager
 * @package OpenClassrooms\Blog\Model
 * Connexion à la base de données
 */
class Manager
{
    protected function dbConnect()
    {
        $db = new \PDO(PDO_CONNECT, DBUSER, DBPASSWD);
        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
        return $db;
    }
}
