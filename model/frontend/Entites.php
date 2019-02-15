<?php
/**
 * Created by PhpStorm.
 * User: Claire
 * Date: 14/02/2019
 * Time: 9:35 AM
 */

namespace OpenClassrooms\Blog\Model;


class Entites
{
    public function __construct(array $donnees)
    {
        $this->hydrate($donnees);
    }

    public function hydrate(array $donnees)
    {
        foreach ($donnees as $key => $value)
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
}