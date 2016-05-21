<?php
/**
 * Created by PhpStorm.
 * User: fsynthis
 * Date: 21/05/16
 * Time: 10:41
 */

namespace Mastermind\CoreBundle\Models;


use Symfony\Component\Config\Definition\Exception\Exception;

class Guess
{
    private $colors = [];

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
        if(is_null($colors)) { 
            $colors = [];
        }
        
        if(is_string($colors)) {
            $colors = str_split($colors, 1);
        }
        
        if(is_array($colors)) {
            $this->colors = $colors;
            return $this;
        }
        
        throw new \InvalidArgumentException("Unexpected argument type");
    }


    public function toString()
    {
        return self::__toString();
    }

    public function __toString()
    {
        return implode($this->getColors(), "");
    }

    public function checkExacts(Guess $guess)
    {
        $exact = [];

        foreach ($this->getColors() as $key => $color) {
            if($color == $guess->getColors()[$key]) {
                $exact[] = $color;
            }
        }

        return count($exact);
    }

    public function checkNear(Guess $guess)
    {
        return count(array_intersect(array_unique($this->getColors()), $guess->getColors())) - $this->checkExacts($guess);
    }
}