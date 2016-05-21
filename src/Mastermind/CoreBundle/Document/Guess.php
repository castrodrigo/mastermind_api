<?php

namespace Mastermind\CoreBundle\Document;


use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class Guess
 * @package Mastermind\CoreBundle\Document
 * @MongoDB\Document
 */
class Guess
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\String
     */
    private $colors = [];

    /**
     * @MongoDB\Integer
     */
    private $exact;

    /**
     * @MongoDB\Integer
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


    public function generate(Game $game)
    {
        $color = new Color();
        $code_length = $game->getConfig()->getCodeLength();

        for ($i = 0; $i < $code_length; $i++) {
            $this->colors[] = ($color->getColors($code_length)[rand(0, $code_length - 1)]);
        }

        return $this;
    }

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

    public function checkNear(Guess $guess)
    {
        $this->near = count(array_intersect(array_unique($this->getColors()), $guess->getColors())) - $this->checkExacts($guess)->getExact();
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

    public function checkExacts(Guess $guess)
    {
        $exact = [];

        foreach ($this->getColors() as $key => $color) {
            if ($color == $guess->getColors()[$key]) {
                $exact[] = $color;
            }
        }

        $this->exact = count($exact);

        return $this;
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
}
