<?php
/**
 * Created by PhpStorm.
 * User: fsynthis
 * Date: 21/05/16
 * Time: 09:10
 */

namespace Mastermind\CoreBundle\Tests\Models;


use Mastermind\CoreBundle\Models\Game;
use Mastermind\CoreBundle\Models\GameConfig;
use Mastermind\CoreBundle\Models\User;
use PHPUnit_Framework_TestCase;

class GameTest extends PHPUnit_Framework_TestCase
{
    public function test_must_generate_game_with_generated_guess()
    {
        $game = new Game(new User("User"), new GameConfig());
        $this->assertEquals($game->getConfig()->getCodeLength(), count($game->getGuess()->getColors()));
    }
}