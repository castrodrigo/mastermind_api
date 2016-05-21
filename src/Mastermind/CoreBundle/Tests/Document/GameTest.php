<?php
/**
 * Created by PhpStorm.
 * User: fsynthis
 * Date: 21/05/16
 * Time: 09:10
 */

namespace Mastermind\CoreBundle\Tests\Models;


use Mastermind\CoreBundle\Document\Game;
use Mastermind\CoreBundle\Document\GameConfig;
use Mastermind\CoreBundle\Document\Player;
use PHPUnit_Framework_TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GameTest extends KernelTestCase
{
    /**
     * @var $dm EntityManagerInterface
     */
    protected $dm;
    protected function setUp()
    {
        self::bootKernel();

        $this->dm = static::$kernel->getContainer()
            ->get('doctrine_mongodb')
            ->getManager();
    }
    
    public function test_must_generate_game_with_generated_guess()
    {
        $game = new Game(new Player("User"), new GameConfig());
        $this->assertEquals($game->getConfig()->getCodeLength(), count($game->getGuess()->getColors()));
    }

    protected function tearDown()
    {
//        $this->dm->createQueryBuilder(Player::class)
//            ->remove()
//            ->field('name')->equals(static::PLAYERTEST)
//            ->getQuery()
//            ->execute();

        $this->dm->flush();
    }
}