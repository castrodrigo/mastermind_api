<?php
/**
 * Created by PhpStorm.
 * User: fsynthis
 * Date: 21/05/16
 * Time: 16:28
 */

namespace Mastermind\CoreBundle\Builder;


use Doctrine\Common\Persistence\ObjectManager;
use Mastermind\CoreBundle\Document\Game;

class GameBuilder
{
    private $manager;

    /**
     * GameBuilder constructor.
     * @param ObjectManager $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

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