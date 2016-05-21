<?php

namespace Mastermind\CoreBundle\Document;


use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class GameConfig
 * @package Mastermind\CoreBundle\Document
 * @MongoDB\Document
 */
class GameConfig
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\Integer
     */
    private $code_length;

    /**
     * @MongoDB\Integer
     */
    private $time_limit;

    /**
     * @MongoDB\Integer
     */
    private $guess_limit;

    /**
     * GameConfig constructor.
     */
    public function __construct()
    {
        $this->code_length = 8;
        $this->time_limit = 5 * 60;
        $this->guess_limit = 0;
    }

    /**
     * @return mixed
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @return int
     */
    public function getCodeLength()
    {
        return $this->code_length;
    }

    /**
     * @param mixed $code_length
     * @return GameConfig
     */
    public function setCodeLength($code_length)
    {
        $this->code_length = $code_length;
        return $this;
    }

    /**
     * @return int
     */
    public function getTimeLimit()
    {
        return $this->time_limit;
    }

    /**
     * @param int $time_limit Time in seconds
     * @return GameConfig
     */
    public function setTimeLimit($time_limit)
    {
        $this->time_limit = $time_limit;
        return $this;
    }

    /**
     * @return int
     */
    public function getGuessLimit()
    {
        return $this->guess_limit;
    }

    /**
     * @param int $guess_limit The max number of user's guesses, 0 if unlimited
     * @return GameConfig
     */
    public function setGuessLimit($guess_limit)
    {
        $this->guess_limit = $guess_limit;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}
