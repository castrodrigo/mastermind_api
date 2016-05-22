<?php
/**
 * Created by PhpStorm.
 * User: fsynthis
 * Date: 21/05/16
 * Time: 16:28
 */

namespace Mastermind\CoreBundle\Builder;


use Doctrine\ODM\MongoDB\DocumentManager;
use Mastermind\CoreBundle\Document\Game;

class GameBuilder extends BuilderAbstract
{
    /**
     * Starts and persists a new Game
     * 
     * @param Game $game
     * @return Game
     */
    public function start(Game $game)
    {
        $this->manager->persist($game);
        $this->manager->flush();
        
        return $game;
    }
}