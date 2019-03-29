<?php

/**
 * Created by PhpStorm.
 * User: Claire
 * Date: 01/02/2019
 * Time: 1:59 PM
 */

namespace OpenClassrooms\Blog\Model;

require_once("model/frontend/Manager.php");

use PDO;


class CommentManager extends Manager
{
    //Récupère la liste des commentaires d'un article - classement par date + DESC
    public function getComment($postId, $premierMessageAafficher, $nombreCommmentaireParPage)
    {
        $comments = [];
        $validation = 'oui';

        $db = $this->dbConnect();
        $req = $db->prepare('SELECT *, DATE_FORMAT(date_comment, \'%d/%m/%Y - %Hh%imin%ss\') AS dateComment FROM comment WHERE id_post = :id AND validation = :validation ORDER BY date_comment DESC LIMIT :start, :length');
        $req->bindParam('start', $premierMessageAafficher, \PDO::PARAM_INT);
        $req->bindParam('length', $nombreCommmentaireParPage, \PDO::PARAM_INT);
        $req->bindParam('id', $postId);
        $req->bindParam('validation', $validation);
        $req->execute();

        while($donneesComment = $req->fetch(PDO::FETCH_ASSOC))
        {
            $comments[$donneesComment['id_comment']] = new Comment($donneesComment);
        }
        return $comments;
    }

    //Ajouter un commentaire
    public function addComment(Comment $newComment)
    {
        $db = $this->dbConnect();
        $addComment = $db->prepare('INSERT INTO comment(comment, id_post, date_comment, validation, id_user) VALUES(?, ?, NOW(), ?, ?)');

        $newComment = $addComment->execute(array($newComment->getComment(), $newComment->getIdPost(), $newComment->getValidation(), $newComment->getIdUser()));

        return $newComment;
    }

    //Récupère l'ensemble des commentaires - classement par date
    public function getListComment($premierMessageAafficher, $nombreCommmentaireParPage)
    {
        $comments = [];

        $db = $this->dbConnect();
        $req = $db->prepare('SELECT *, DATE_FORMAT(date_comment, \'%d/%m/%Y - %Hh%imin%ss\') AS dateComment FROM comment ORDER BY date_comment DESC LIMIT :start, :length');
        $req->bindParam('start', $premierMessageAafficher, \PDO::PARAM_INT);
        $req->bindParam('length', $nombreCommmentaireParPage, \PDO::PARAM_INT);
        $req->execute();

        while($donneesComment = $req->fetch(PDO::FETCH_ASSOC))
        {
            $comments[$donneesComment['id_comment']] = new Comment($donneesComment);
        }
        return $comments;
    }

    //Mettre à jour l'état de validation d'un commentaire - non->oui
    public function validationComment(Comment $newComment)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE comment SET validation = ? WHERE id_comment= ?');

        $validationComment = $req->execute(array($newComment->getValidation(), $newComment->getIdComment()));

        return $validationComment;
    }

    //Supprime un commentaire
    public function deleteComment(Comment $newComment)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM comment WHERE id_comment = ?');

        $deleteComment = $req->execute(array($newComment->getIdComment()));

        return $deleteComment;
    }

    //Récupère les données d'un commentaire
    public function getCommentUpdate($idComment)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT *, DATE_FORMAT(date_comment, \'%d/%m/%Y - %Hh%imin%ss\') AS dateComment FROM comment WHERE id_comment = ?');
        $req->execute(array($idComment));

        $donneesComment = $req->fetch(PDO::FETCH_ASSOC);

        return new Comment($donneesComment);
    }

    //Mettre à jour un commentaire
    public function updateComment(Comment $newComment)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE comment SET comment = ?, date_comment = NOW(), id_user = ? WHERE id_comment = ? AND id_post = ?');

        $updateComment = $req->execute(array($newComment->getComment(), $newComment->getIdUser(), $newComment->getIdComment(), $newComment->getIdPost()));

        return $updateComment;
    }

    // On récupère le nombre total de commentaires
    public function countComment()
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT COUNT(id_comment) AS nb_messages FROM comment');
        $req->execute();

        $totalDesMessages = $req->fetch(PDO::FETCH_ASSOC);

        return $totalDesMessages['nb_messages'];
    }

    // On récupère le nombre total de commentaires pour un article
    public function countCommentForPost($postId)
    {
        $validation = "oui";

        $db = $this->dbConnect();
        $req = $db->prepare('SELECT COUNT(id_comment) AS nb_messages FROM comment WHERE id_post = ? AND validation = ?');
        $req->execute(array($postId, $validation));

        $totalDesMessages = $req->fetch(PDO::FETCH_ASSOC);

        return $totalDesMessages['nb_messages'];
    }
}