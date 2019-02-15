<?php

namespace OpenClassrooms\Blog\Model;

require_once("model/frontend/Manager.php");

use PDO;

class PostManager extends Manager
{
    public function getListPost()
    {
        $postList = [];

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
        $post = [];

        $db = $this->dbConnect();
        $req = $db->prepare('SELECT id_post, title, introduction, content, id_user, DATE_FORMAT(date_update, \'%d/%m/%Y - %Hh%imin%ss\') AS dateUpdate FROM post WHERE id_post = ?');
        $req->execute(array($articleId));
        /**$article = $req->fetch(PDO::FETCH_BOTH);*/

        while($donneesPost = $req->fetch(PDO::FETCH_ASSOC))
        {
            $post[] = new Post($donneesPost);
        }
        return $post;

        /**return new Post($article);*/
        /**return $this->$createPost($article);*/
    }

    /**private function createPost($article)
    {
        $post = new Post();

        $post->setId($article['id_post']);
        $post->setTitle($article['title']);
        $post->setContent($article['content']);
        $post->setDateUpdate($article['date_update']);
        $post->setIntroduction($article['introduction']);

        $user = new User();
        $user->setPseudo($article['pseudo']);

        $post->setUser($user);

        return $post;
    }*/
}