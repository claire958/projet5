<?php
/**
 * Created by PhpStorm.
 * User: Claire
 * Date: 30/01/2019
 * Time: 5:32 PM
 */

namespace OpenClassrooms\Blog\Model;

require_once("model/frontend/Manager.php");

use PDO;

class UserManager extends Manager
{
    public function getListUser()
    {
        $users = [];

        $db = $this->dbConnect();
        $listUser = $db->query('SELECT id_user, pseudo, email, password, role, validation FROM user');

        while($donneesUser = $listUser->fetch(PDO::FETCH_ASSOC))
        {
            $users[$donneesUser['id_user']] = new User($donneesUser);
        }

        return $users;
    }

    public function addUser($pseudo, $email, $password)
    {
        $role = 'user';
        $validation = 'non';
        $db = $this->dbConnect();
        $addUser = $db->prepare('INSERT INTO user(pseudo, email, password, role, validation) VALUES(?, ?, ?, ?, ?)');

        $newUser = $addUser->execute(array($pseudo, $email, $password, $role, $validation));

        return $newUser;


    }

    public function getUser($pseudo)
    {
        $db = $this->dbConnect();
        $getUser = $db->query('SELECT pseudo FROM user WHERE pseudo="' . $pseudo . '"');

        return $getUser->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserMail($email)
    {
        $db = $this->dbConnect();
        $getUserMail = $db->query('SELECT email FROM user WHERE email="' . $email . '"');

        $getUserMail->fetch(PDO::FETCH_ASSOC);

        return $getUserMail;
    }
}
