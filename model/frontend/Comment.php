<?php
/**
 * Created by PhpStorm.
 * User: Claire
 * Date: 16/01/2019
 * Time: 4:19 PM
 */

namespace OpenClassrooms\Blog\Model;


class Comment
{
    private $idComment;
    private $comment;
    private $idPost;
    private $dateComment;
    private $validation;
    private $idUser;

    public function __construct(array $donneesComment)
    {
        $this->hydrate($donneesComment);
    }

    public function hydrate(array $donneesComment)
    {
        foreach ($donneesComment as $key => $value)
        {
            // On récupère le nom du setter correspondant à l'attribut.
            $method = 'set'.str_replace('_', '', ucwords($key, '_'));

            // Si le setter correspondant existe.
            if (method_exists($this, $method))
            {
                // On appelle le setter.
                $this->$method($value);
            }
        }
    }

    /**
     * @return mixed
     */
    public function getIdComment()
    {
        return $this->idComment;
    }

    /**
     * @param mixed $idComment
     */
    public function setIdComment($idComment)
    {
        $this->idComment = $idComment;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return mixed
     */
    public function getIdPost()
    {
        return $this->idPost;
    }

    /**
     * @param mixed $idPost
     */
    public function setIdPost($idPost)
    {
        $this->idPost = $idPost;
    }

    /**
     * @return mixed
     */
    public function getDateComment()
    {
        return $this->dateComment;
    }

    /**
     * @param mixed $dateComment
     */
    public function setDateComment($dateComment)
    {
        $this->dateComment = $dateComment;
    }

    /**
     * @return mixed
     */
    public function getValidation()
    {
        return $this->validation;
    }

    /**
     * @param mixed $validation
     */
    public function setValidation($validation)
    {
        $this->validation = $validation;
    }

    /**
     * @return mixed
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * @param mixed $idUser
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;
    }
}