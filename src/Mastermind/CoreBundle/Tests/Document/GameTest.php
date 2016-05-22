<?php
/**
 * Created by PhpStorm.
 * User: fsynthis
 * Date: 21/05/16
 * Time: 09:10
 */

namespace Mastermind\CoreBundle\Tests\Models;


use Doctrine\ODM\MongoDB\DocumentManager;
use Mastermind\CoreBundle\Document\Color;
use Mastermind\CoreBundle\Document\Game;
use Mastermind\CoreBundle\Document\GameConfig;
use Mastermind\CoreBundle\Document\Guess;
use Mastermind\CoreBundle\Document\Player;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GameTest extends KernelTestCase
{
    /**
     * @var $manager DocumentManager
     */
    protected $manager;
    
    static $game = [];

    protected function setUp()
    {
        self::bootKernel();

        $this->manager = static::$kernel->getContainer()
            ->get('doctrine_mongodb')
            ->getManager();
    }

    public function test_must_generate_game_with_generated_guess()
    {
        $game = new Game(new Player("User"), new GameConfig());

        $this->manager->persist($game);
        $this->manager->flush();

        static::$game[] = $game->getGameKey();

        $this->assertEquals($game->getConfig()->getCodeLength(), count($game->getColors()));
    }

    /**
     * @depends test_must_generate_game_with_generated_guess
     */
    public function test_must_update_game_with_a_new_guess()
    {
        $guess = new Guess([
           Color::BLUE,
           Color::ORANGE,
           Color::YELLOW,
           Color::CYAN,
           Color::RED,
           Color::GREEN,
           Color::PURPLE,
           Color::MAGENTA
        ]);

        /**
         * @var $game Game
         */
        $game = $this->manager->find(Game::class, static::$game[0]);
        $game->addUserGuess($guess);
        $this->manager->flush();

        $this->assertEquals(1, $game->getPastResults()->count());
    }

    public function test_must_stack_guesses_until_solve()
    {
        $game = new Game(new Player("New"), new GameConfig);

        while (!$game->getSolved()) {
            $guess = (new Guess())->generate($game, true);
            $game->addUserGuess($guess);
        }

        $this->manager->persist($game);
        $this->manager->flush();

        $this->assertTrue($game->getSolved());
        $this->assertTrue(is_string($game->getGameKey()));
    }

    protected function tearDown()
    {
        $this->manager->createQueryBuilder(Player::class)
            ->remove()
            ->field('id')->in(static::$game)
            ->getQuery()
            ->execute();

        $this->manager->flush();
    }
}