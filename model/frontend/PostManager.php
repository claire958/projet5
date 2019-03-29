<?php

namespace OpenClassrooms\Blog\Model;

require_once("model/frontend/Manager.php");
require_once("model/frontend/Entites.php");

use PDO;

class PostManager extends Manager
{
    //Récupère la liste des articles - classement par id
    public function getListPost($premierMessageAafficher, $nombrePostParPage)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT *, DATE_FORMAT(date_update, \'%d/%m/%Y - %Hh%imin%ss\') AS dateUpdate FROM post ORDER BY id_post DESC LIMIT :start, :length');
        $req->bindParam('start', $premierMessageAafficher, \PDO::PARAM_INT);
        $req->bindParam('length', $nombrePostParPage, \PDO::PARAM_INT);
        $req->execute();

        while($donneesPost = $req->fetch(PDO::FETCH_ASSOC))
        {
            $postList[$donneesPost['id_post']] = new Post($donneesPost);
        }
        return $postList;
    }

    //Récupère les données d'un article
    public function getPost($articleId)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT *, DATE_FORMAT(date_update, \'%d/%m/%Y - %Hh%imin%ss\') AS dateUpdate FROM post WHERE id_post = ?');
        $req->execute(array($articleId));

        $donneesPost = $req->fetch(PDO::FETCH_ASSOC);

        return new Post($donneesPost);
    }

    //Ajoute un article à la bdd
    public function addPost(Post $post)
    {
        $db = $this->dbConnect();
        $addPost = $db->prepare('INSERT INTO post(title, content, date_update, introduction, id_user) VALUES(?, ?, NOW(), ?, ?)');

        $newPost = $addPost->execute(array($post->getTitle(), $post->getContent(), $post->getIntroduction(), $post->getIdUser()));

        return $newPost;
    }

    //Supprime un article
    public function deletePost(Post $post)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM post WHERE id_post = ?');

        $deletePost = $req->execute(array($post->getIdPost()));

        return $deletePost;
    }

    //Mettre à jour un article
    public function updatePost(Post $post)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE post SET title = ?, introduction = ?, content = ?, date_update = NOW(), id_user = ? WHERE id_post= ?');

        $updatePost = $req->execute(array($post->getTitle(), $post->getIntroduction(), $post->getContent(), $post->getIdUser(), $post->getIdPost()));

        return $updatePost;
    }

    // On récupère le nombre total de posts
    public function countPost()
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT COUNT(id_post) AS nb_messages FROM post');
        $req->execute();

        $totalDesMessages = $req->fetch(PDO::FETCH_ASSOC);

        return $totalDesMessages['nb_messages'];
    }
}