<?php

namespace Mastermind\CoreBundle\Document;


use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Mastermind\CoreBundle\Interfaces\GameInterface;

/**
 * Class User
 * @package Mastermind\CoreBundle\Document
 * @MongoDB\Document
 */
class Player
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\String
     */
    private $name;

    /**
     * User constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

}
