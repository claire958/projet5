<?php

namespace OpenClassrooms\Blog\Model;

require_once("model/frontend/Manager.php");
require_once("model/frontend/Entites.php");

use PDO;

class PostManager extends Manager
{
    public function getListPost()
    {
        $db = $this->dbConnect();
        $req = $db->query('SELECT id_post, title, introduction, content, id_user, DATE_FORMAT(date_update, \'%d/%m/%Y - %Hh%imin%ss\') AS dateUpdate FROM post ORDER BY id_post DESC LIMIT 10');

        while($donneesPost = $req->fetch(PDO::FETCH_ASSOC))
        {
            $postList[] = new Post($donneesPost);
        }
        return $postList;
    }

    public function getPost($articleId)
    {

        $db = $this->dbConnect();
        $req = $db->prepare('SELECT id_post, title, introduction, content, id_user, DATE_FORMAT(date_update, \'%d/%m/%Y - %Hh%imin%ss\') AS dateUpdate FROM post WHERE id_post = ?');
        $req->execute(array($articleId));

        $donneesPost = $req->fetch(PDO::FETCH_ASSOC);
        return new Post($donneesPost);
    }
}