<?php

namespace Mastermind\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation as Serializer;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Game
 * @package Mastermind\CoreBundle\Document
 * @MongoDB\Document
 * @Serializer\AccessorOrder("custom", custom = {"colors", "getCodeLength", "game_key", "getLastGuessColors", "getNumGuesses", "past_results", "getLastGuessResult", "solved"})
 */
class Game
{
    const WIN_RESULT = "You win!";
    const INSTRUCTIONS = "Solve the challenge to see this!";

    /**
     * @Serializer\Exclude
     */
    public static $answer = [];

    /**
     * @MongoDB\Id
     * @Serializer\Groups({"default", "details", "win"})
     */
    private $game_key;

    /**
     * @MongoDB\Field(name="colors", type="collection")
     * @Serializer\SerializedName("colors")
     * @Serializer\Groups({"default", "details", "win"})
     * 
     */
    private $colors;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Player", cascade={"persist", "remove"})
     * @Serializer\Exclude
     */
    private $player;

    /**
     * @MongoDB\ReferenceOne(targetDocument="GameConfig", cascade={"persist", "remove"})
     * @Serializer\Exclude
     */
    private $config;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Guess", cascade={"persist", "remove"})
     * @Serializer\Groups({"default", "details", "win"})
     */
    private $past_results = [];

    /**
     * @MongoDB\Boolean
     * @Serializer\Groups({"default", "details", "win"})
     */
    private $solved = false;

    /**
     * @var Guess
     * @Serializer\Exclude
     */
    private $last_guess;

    /**
     * @MongoDB\Date
     * @Gedmo\Timestampable(on="create")
     * @Serializer\Exclude
     */
    private $created_at;

    /**
     * @MongoDB\Date
     * @Gedmo\Timestampable
     * @Serializer\Exclude
     */
    private $updated_at;

    /**
     * Game constructor.
     * @param Player $player
     * @param GameConfig $config
     */
    public function __construct(Player $player, GameConfig $config)
    {
        $this->player = $player;
        $this->config = $config;
        $this->colors = (new Guess())->generate($this)->getColors();
    }

    public function __toString()
    {
        return $this->game_key;
    }

    /**
     * @return Player
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @param Player $player
     */
    public function setPlayer(Player $player)
    {
        $this->player = $player;
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
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("num_guesses")
     * @Serializer\Groups({"default", "details", "win"})
     *
     * @return int
     */
    public function getNumGuesses()
    {
        return count($this->getPastResults());
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

    /**
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("code_length")
     * @Serializer\Groups({"default", "details", "win"})
     *
     * @return int
     */
    public function getCodeLength()
    {
        return $this->getConfig()->getCodeLength();
    }

    /**
     * @return GameConfig
     */
    public function getConfig()
    {
        return $this->config;
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
        $user_guess->validate(new Guess($this->getColors()));
        $this::$answer = $user_guess::$answer;
        $this->setSolved($user_guess->getExact() == $this->getConfig()->getCodeLength());
        $this->last_guess = $user_guess;
        $this->addPastResult($this->last_guess);
    }

    public function getColors()
    {
        return $this->colors;
    }

    /**
     * Set colors
     *
     * @param collection $colors
     * @return self
     */
    public function setColors($colors)
    {
        $this->colors = $colors;
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
     * @return Guess
     */
    public function getGameGuess()
    {
        return new Guess($this->colors);
    }

    /**
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("guess")
     * @Serializer\Groups({"details", "win"})
     * @return string
     */
    public function getLastGuessColors()
    {
        return $this->last_guess ? $this->last_guess->toString() : null;
    }

    /**
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("result")
     * @Serializer\Groups({"details", "win"})
     *
     * @return array|null
     */
    public function getLastGuessResult()
    {
        if($this->getSolved()) {
            return static::WIN_RESULT;
        }

        if ($this->last_guess) {
            return [
                'exact' => $this->last_guess->getExact(),
                'near' => $this->last_guess->getNear()
            ];
        }

        return null;
    }

    /**
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("further_instructions")
     * @Serializer\Groups({"win"})
     *
     * @return string
     */
    public function getInstructions()
    {
        return static::INSTRUCTIONS;
    }

    /**
     * Set createdAt
     *
     * @param timestamp $createdAt
     * @return self
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return timestamp $createdAt
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updatedAt
     *
     * @param timestamp $updatedAt
     * @return self
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return timestamp $updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
}
