<?php
/**
 * Created by PhpStorm.
 * User: fsynthis
 * Date: 21/05/16
 * Time: 10:45
 */

namespace Mastermind\CoreBundle\Models;


class GameConfig
{
    private $code_length;
    private $time_limit;
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
     * @param mixed $code_length
     * @return GameConfig
     */
    public function setCodeLength($code_length)
    {
        $this->code_length = $code_length;
        return $this;
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
     * @param int $guess_limit The max number of user's guesses, 0 if unlimited
     * @return GameConfig
     */
    public function setGuessLimit($guess_limit)
    {
        $this->guess_limit = $guess_limit;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodeLength()
    {
        return $this->code_length;
    }

    /**
     * @return int
     */
    public function getTimeLimit()
    {
        return $this->time_limit;
    }

    /**
     * @return int
     */
    public function getGuessLimit()
    {
        return $this->guess_limit;
    }
}
