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
        $req = $db->prepare('SELECT id_comment, comment, id_post, DATE_FORMAT(date_comment, \'%d/%m/%Y - %Hh%imin%ss\') AS dateComment, validation, id_user FROM comment WHERE id_post = ? ORDER BY dateComment ASC');
        $req->execute(array($postId));

        while($donneesComment = $req->fetch(PDO::FETCH_ASSOC))
        {
            $comments[$donneesComment['id_comment']] = new Comment($donneesComment);
        }
        return $comments;
    }
}