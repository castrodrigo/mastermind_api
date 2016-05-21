<?php
/**
 * Created by PhpStorm.
 * User: fsynthis
 * Date: 21/05/16
 * Time: 10:36
 */

namespace Mastermind\CoreBundle\Models;


class Game
{
    private $guess;
    private $user;
    private $config;
    private $game_key;
    private $num_guesses;
    private $past_results;
    private $solved;

    /**
     * Game constructor.
     * @param User $user
     * @param GameConfig $config
     */
    public function __construct(User $user, GameConfig $config)
    {
        $this->user = $user;
        $this->config = $config;
        $this->guess = (new Guess())->generate($this);
    }


    public function __toString()
    {
        return $this->game_key;
    }

    /**
     * @return Guess
     */
    public function getGuess()
    {
        return $this->guess;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getGameKey()
    {
        return $this->game_key;
    }

    /**
     * @param string $game_key
     */
    public function setGameKey($game_key)
    {
        $this->game_key = $game_key;
    }

    /**
     * @return int
     */
    public function getNumGuesses()
    {
        return $this->num_guesses;
    }

    /**
     * @param int $num_guesses
     */
    public function setNumGuesses($num_guesses)
    {
        $this->num_guesses = $num_guesses;
    }

    /**
     * @return ArrayCollection
     */
    public function getPastResults()
    {
        return $this->past_results;
    }

    /**
     * @param ArrayCollection $past_results
     */
    public function setPastResults($past_results)
    {
        $this->past_results = $past_results;
    }

    /**
     * @return boolean
     */
    public function getSolved()
    {
        return $this->solved;
    }

    /**
     * @param boolean $solved
     */
    public function setSolved($solved)
    {
        $this->solved = $solved;
    }

    /**
     * @return GameConfig
     */
    public function getConfig()
    {
        return $this->config;
    }
}