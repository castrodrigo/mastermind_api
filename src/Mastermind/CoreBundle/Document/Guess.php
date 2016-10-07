<?php

namespace Mastermind\CoreBundle\Document;


use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class Guess
 * @package Mastermind\CoreBundle\Document
 * @MongoDB\Document
 */
class Guess
{
    /**
     * @Serializer\Exclude
     */
    static $answer = [];

    /**
     * @MongoDB\Id
     * @Serializer\Exclude
     */
    private $id;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Player", cascade={"persist", "remove"})
     * @Serializer\Exclude
     */
    private $player;
    
    /**
     * @MongoDB\Field(name="colors", type="collection")
     * @Serializer\Exclude
     */
    private $colors = [];
    
    /**
     * @MongoDB\Integer
     * @Serializer\Groups({"default","details", "win"})
     */
    private $exact;
    /**
     * @MongoDB\Integer
     * @Serializer\Groups({"default","details", "win"})
     */
    private $near;

    /**
     * Guess constructor.
     * @param array|null|string $colors
     */
    public function __construct($colors = null)
    {
        $this->setColors($colors);
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
     * @return mixed
     */
    public function getGame()
    {
        return $this->game;
    }

    public function generate(Game $game, $solve = false)
    {
        $color = new Color();
        $code_length = $game->getConfig()->getCodeLength();

        for ($i = 0; $i < $code_length; $i++) {

            if ($solve && key_exists($i, static::$answer) && !is_null(static::$answer[$i])) {
                $_color = static::$answer[$i];
            } else {
                $_color = ($color->getColors($code_length)[rand(0, $code_length - 1)]);
            }

            $this->colors[] = $_color;
        }

        return $this;
    }

    /**
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("guess")
     * @Serializer\Groups({"default","details", "win"})
     *
     * @return string
     */
    public function toString()
    {
        return self::__toString();
    }

    public function __toString()
    {
        return implode($this->getColors(), "");
    }

    /**
     * @return array
     */
    public function getColors()
    {
        return $this->colors;
    }

    /**
     * @param array|null|string $colors
     * @return $this
     */
    public function setColors($colors)
    {
        if (is_null($colors)) {
            $colors = [];
        }

        if (is_string($colors)) {
            $colors = str_split($colors, 1);
        }

        if (is_array($colors)) {
            $this->colors = $colors;
            return $this;
        }

        throw new \InvalidArgumentException("Unexpected argument type");
    }

    /**
     * @return int
     */
    public function getNear()
    {
        return $this->near;
    }

    /**
     * Set near
     *
     * @param integer $near
     * @return self
     */
    public function setNear($near)
    {
        $this->near = $near;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Guess $guess
     */
    public function validate(Guess $guess)
    {
        $this->checkExacts($guess);
        $this->checkNear($guess);
    }

    public function checkExacts(Guess $guess)
    {
        $exact = [];
        $_answer = [];

        foreach ($this->getColors() as $key => $color) {
            if ($color == $guess->getColors()[$key]) {
                $exact[] = $color;
                $_answer[$key] = $color;
                continue;
            }

            $_answer[$key] = null;
        }

        static::$answer = $_answer;
        $this->exact = count($exact);

        return $this;
    }

    public function checkNear(Guess $guess)
    {
        $diff = array_diff($guess->getColors(), static::$answer);
        $this->near = count(array_intersect(array_unique($this->getColors()), $diff));
        return $this;
    }

    /**
     * @return int
     */
    public function getExact()
    {
        return $this->exact;
    }

    /**
     * Set exact
     *
     * @param integer $exact
     * @return self
     */
    public function setExact($exact)
    {
        $this->exact = $exact;
        return $this;
    }
}
