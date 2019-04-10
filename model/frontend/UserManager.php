<?php
/**
 * Created by PhpStorm.
 * User: Claire
 * Date: 30/01/2019
 * Time: 5:32 PM
 */

namespace OpenClassrooms\Blog\Model;

require_once 'model/frontend/Manager.php';
require_once 'model/frontend/Entites.php';

use PDO;

class UserManager extends Manager
{
    //Récupère la liste des utilisateurs - classement par id
    public function getListUser($premierMessageAafficher, $nombreUtilisateurParPage)
    {
        $users = [];

        $db = $this->dbConnect();
        $req = $db->prepare('SELECT id_user, pseudo, email, password, role, validation FROM user ORDER BY id_user DESC LIMIT :start, :length');
        $req->bindParam('start', $premierMessageAafficher, \PDO::PARAM_INT);
        $req->bindParam('length', $nombreUtilisateurParPage, \PDO::PARAM_INT);
        $req->execute();

        while($donneesUser = $req->fetch(PDO::FETCH_ASSOC))
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

        if($currentUser === false){
            return false;
        }

        return new User($currentUser);
    }

    //Récupère les données des utilisateurs
    public function getInfoUser()
    {
        $db = $this->dbConnect();
        $getUser = $db->prepare('SELECT * FROM user');
        $getUser->execute();

        while($donneesUser = $getUser->fetch(PDO::FETCH_ASSOC))
        {
            $users[$donneesUser['id_user']] = new User($donneesUser);
        }

        return $users;
    }

    //Récupère les données d'un utilisateur - tri par email
    public function getUserMail($email)
    {
        $db = $this->dbConnect();
        $getUserMail = $db->prepare('SELECT email FROM user WHERE email = ?');

        $getUserMail->execute(array($email));

        $currentUser = ($getUserMail->fetch(PDO::FETCH_ASSOC));

        if($currentUser === false){
            return false;
        }

        return new User($currentUser);
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

    // On récupère le nombre total d'utilisateurs
    public function countUser()
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT COUNT(id_user) AS nb_messages FROM user');
        $req->execute();

        $totalDesMessages = $req->fetch(PDO::FETCH_ASSOC);

        return $totalDesMessages['nb_messages'];
    }
}
