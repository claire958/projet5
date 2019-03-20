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
    //Récupère la liste des utilisateurs - classement par id
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

    //Ajoute un utilisateur à la bdd
    public function addUser(User $user)
    {
        $db = $this->dbConnect();
        $addUser = $db->prepare('INSERT INTO user(pseudo, email, password, role, validation) VALUES(?, ?, ?, ?, ?)');

        $newUser = $addUser->execute(array($user->getPseudo(), $user->getEmail(), $user->getPassword(), $user->getRole(), $user->getValidation()));

        return $newUser;
    }

    //Récupère les données d'un utilisateur - tri par pseudo
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

    //Récupère les données d'un utilisateur - tri par email
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

    //Mettre à jour l'état de validation d'un utilisateur - non->oui
    public function validationUser(User $user)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE user SET validation = ? WHERE id_user = ?');

        $validationUser = $req->execute(array($user->getValidation(), $user->getIdUser()));

        return $validationUser;
    }

    //Supprime un utilisateur
    public function deleteUser(User $user)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM user WHERE id_user = ?');

        $deleteUser = $req->execute(array($user->getIdUser()));

        return $deleteUser;
    }
}
