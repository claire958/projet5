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
    public function getComment($postId)
    {
        $comments = [];

        $db = $this->dbConnect();
        $req = $db->prepare('SELECT id_comment, comment, id_post, DATE_FORMAT(date_comment, \'%d/%m/%Y - %Hh%imin%ss\') AS dateComment, validation, id_user FROM comment WHERE id_post = ? ORDER BY date_comment DESC');
        $req->execute(array($postId));

        while($donneesComment = $req->fetch(PDO::FETCH_ASSOC))
        {
            $comments[$donneesComment['id_comment']] = new Comment($donneesComment);
        }
        return $comments;
    }

    public function addComment($comment, $idPost, $idUser)
    {
        $validation = "non";

        $db = $this->dbConnect();
        $addComment = $db->prepare('INSERT INTO comment(comment, id_post, date_comment, validation, id_user) VALUES(?, ?, NOW(), ?, ?)');

        $newComment = $addComment->execute(array($comment, $idPost, $validation, $idUser));

        return $newComment;
    }
}