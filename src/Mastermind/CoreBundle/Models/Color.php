<?php
/**
 * Created by PhpStorm.
 * User: fsynthis
 * Date: 21/05/16
 * Time: 10:57
 */

namespace Mastermind\CoreBundle\Models;


final class Color
{
    const COLORS = "RBGYOPCM";

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