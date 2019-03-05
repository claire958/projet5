<?php
/**
 * Created by PhpStorm.
 * User: Claire
 * Date: 30/01/2019
 * Time: 5:32 PM
 */

namespace OpenClassrooms\Blog\Model;

require_once("model/frontend/Manager.php");
require_once("model/frontend/Entites.php");

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
        $db = $this->dbConnect();
        $addUser = $db->prepare('INSERT INTO user(pseudo, email, password, role, validation) VALUES(?, ?, ?, ?, ?)');

        $role = "user";
        $validation = "non";

        $newUser = $addUser->execute(array($pseudo, $email, $password, $role, $validation));

        return $newUser;
    }

    public function getUser($pseudo)
    {
        $db = $this->dbConnect();
        $getUser = $db->prepare('SELECT * FROM user WHERE pseudo = ?');

        $getUser->execute(array($pseudo));

        $currentUser = ($getUser->fetch(PDO::FETCH_ASSOC));

        if($currentUser == false){
            return false;
        }
        else{
            return new User($currentUser);
        }
    }

    public function getUserMail($email)
    {
        $db = $this->dbConnect();
        $getUserMail = $db->prepare('SELECT email FROM user WHERE email = ?');

        $getUserMail->execute(array($email));

        $currentUser = ($getUserMail->fetch(PDO::FETCH_ASSOC));

        if($currentUser == false){
            return false;
        }
        else{
            return new User($currentUser);
        }
    }
}
