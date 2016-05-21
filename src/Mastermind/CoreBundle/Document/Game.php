<?php

namespace Mastermind\CoreBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class Game
 * @package Mastermind\CoreBundle\Document
 * @MongoDB\Document
 */
class Game
{

    public static $answer = [];
    
    /**
     * @MongoDB\Id
     */
    private $game_key;

    /**
     * @MongoDB\String
     */
    private $guess;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Player", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @MongoDB\ReferenceOne(targetDocument="GameConfig", cascade={"persist", "remove"})
     */
    private $config;

    /**
     * @MongoDB\Integer
     */
    private $num_guesses;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Guess", cascade={"persist", "remove"})
     */
    private $past_results;

    /**
     * @MongoDB\Boolean
     */
    private $solved = false;

    /**
     * Game constructor.
     * @param Player $player
     * @param GameConfig $config
     */
    public function __construct(Player $player, GameConfig $config)
    {
        $this->user = $player;
        $this->config = $config;
        $this->guess = (new Guess())->generate($this)->toString();
    }


    public function __toString()
    {
        return $this->game_key;
    }

    /**
     * @return string
     */
    public function getGuess()
    {
        return $this->guess;
    }

    /**
     * @return Player
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param Player $user
     */
    public function setUser(Player $user)
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

    /**
     * Set guess
     *
     * @param string $guess
     * @return self
     */
    public function setGuess($guess)
    {
        $this->guess = $guess;
        return $this;
    }

    /**
     * Set config
     *
     * @param GameConfig $config
     * @return self
     */
    public function setConfig(GameConfig $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * Add pastResult
     *
     * @param Guess $pastResult
     */
    public function addPastResult(Guess $pastResult)
    {
        $this->past_results[] = $pastResult;
    }

    /**
     * Remove pastResult
     *
     * @param Guess $pastResult
     */
    public function removePastResult(Guess $pastResult)
    {
        $this->past_results->removeElement($pastResult);
    }

    /**
     * Validates a given guess and add to a Past Result Collection
     *
     * @param Guess $user_guess
     */
    public function addUserGuess(Guess $user_guess)
    {
        $user_guess->validate(new Guess($this->getGuess()));
        $this::$answer = $user_guess::$answer;
        
        $this->setSolved($user_guess->getExact() == $this->getConfig()->getCodeLength());
        $this->addPastResult($user_guess);
    }

    /**
     * Get pastResults
     *
     * @return \Doctrine\Common\Collections\Collection $pastResults
     */
    public function getPastResults()
    {
        return $this->past_results;
    }
}
