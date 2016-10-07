<?php
/**
 * Created by PhpStorm.
 * User: fsynthis
 * Date: 22/05/16
 * Time: 17:19
 */

namespace Mastermind\CoreBundle\Builder;


use Mastermind\CoreBundle\Document\Game;
use Mastermind\CoreBundle\Document\GameConfig;
use Mastermind\CoreBundle\Document\Match;
use Mastermind\CoreBundle\Document\Player;

class MultiplayerBuilder extends BuilderAbstract
{
    /**
     * Creates a multiplayer match
     *
     * @param Player $host
     * @param GameConfig $config
     * @return Match
     */
    public function create(Player $host, GameConfig $config = null)
    {
        if(is_null($config)) {
            $config = (new GameConfig())->setNumberOfPlayers(2);
        }

        $match = new Match(new Game($host, $config), $host);

        $this->manager->persist($match);
        $this->manager->flush();

        return $match;
    }
}