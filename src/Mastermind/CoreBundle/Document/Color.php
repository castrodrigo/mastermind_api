<?php

namespace Mastermind\CoreBundle\Document;


final class Color
{
    const COLORS = "RBGYOPCM";
    
    const RED = "R";
    const BLUE = "B";
    const GREEN = "G";
    const YELLOW = "Y";
    const ORANGE = "O";
    const PURPLE = "P";
    const CYAN = "C";
    const MAGENTA = "M";
    
    public function __toString()
    {
        return static::COLORS;
    }

    public function toArray()
    {
        return str_split(static::COLORS, 1);
    }

    /**
     * Returns the colors
     *
     * @param int $limit
     * @return string
     */
    public function getColors($limit = null)
    {
        return is_int($limit) ? substr(static::COLORS, 0, $limit) : static::COLORS;
    }
}