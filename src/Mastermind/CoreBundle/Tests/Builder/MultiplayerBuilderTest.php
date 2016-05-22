<?php
/**
 * Created by PhpStorm.
 * User: fsynthis
 * Date: 22/05/16
 * Time: 17:19
 */

namespace Mastermind\CoreBundle\Tests\Builder;


use Doctrine\ORM\EntityManagerInterface;
use Mastermind\CoreBundle\Builder\MultiplayerBuilder;
use Mastermind\CoreBundle\Document\GameConfig;
use Mastermind\CoreBundle\Document\Guess;
use Mastermind\CoreBundle\Document\Player;
use Mastermind\CoreBundle\Tests\Document\PlayerTest;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MultiplayerBuilderTest extends KernelTestCase
{
    /**
     * @var $manager EntityManagerInterface
     */
    protected $manager;

    /**
     * @var MultiplayerBuilder
     */
    private $builder;

    protected function setUp()
    {
        self::bootKernel();

        $this->manager = static::$kernel->getContainer()
            ->get('doctrine_mongodb')
            ->getManager();

        $this->builder = static::$kernel->getContainer()->get('mastermind.multiplayer_builder');
    }
    
    public function test_must_create_a_match()
    {
        $match = $this->builder->create(new Player(PlayerTest::PLAYERTEST), (new GameConfig())->setNumberOfPlayers(2));

        $this->assertEquals(PlayerTest::PLAYERTEST, $match->getHost()->getName());
        $this->assertEquals(2, $match->getGame()->getConfig()->getNumberOfPlayers());
        $this->assertEquals(0, count($match->getGuests()));
        $this->assertEquals(false, $match->getReady());
    }

    public function test_user_join_a_match()
    {
        $match = $this->builder->create(new Player(PlayerTest::PLAYERTEST), (new GameConfig())->setNumberOfPlayers(2));
        $guest = new Player("Guest");
        
        $match->addGuest($guest);
        $this->manager->flush();

        $this->assertEquals(PlayerTest::PLAYERTEST, $match->getHost()->getName());
        $this->assertEquals(2, $match->getGame()->getConfig()->getNumberOfPlayers());
        $this->assertEquals(1, count($match->getGuests()));
        $this->assertEquals("Guest", $match->getGuests()->first()->getName());
    }

    /**
     * @expectedException \Exception
     */
    public function test_players_limit_can_not_be_exceeded()
    {
        $host = new Player(PlayerTest::PLAYERTEST);
        $guest = new Player("Guest");
        $guest2 = new Player("Guest2");

        $match = $this->builder->create($host, (new GameConfig())->setNumberOfPlayers(2));
        $match->addPlayer($guest, $this->manager);
        $match->addPlayer($guest2, $this->manager);
    }
    
    public function test_users_can_send_guesses()
    {
        $host = new Player(PlayerTest::PLAYERTEST);
        $guest = new Player("Guest");

        $match = $this->builder->create($host, (new GameConfig())->setNumberOfPlayers(2));
        $match->addPlayer($guest, $this->manager);

        $guess = new Guess('BBBBBBBB');
        $guess->setPlayer($host);

        $match->addUserGuess($guess);

        $guess = new Guess('YYYYYYYY');
        $guess->setPlayer($guest);
        $match->addUserGuess($guess);

        $this->manager->flush();
        
        $this->assertEquals(1, $match->getAttempts()->count());
    }

    /**
     * @expectedException \Exception
     */
    public function test_user_can_not_send_two_guess_at_same_attempt()
    {
        $host = new Player(PlayerTest::PLAYERTEST);
        $guest = new Player("Guest");

        $match = $this->builder->create($host, (new GameConfig())->setNumberOfPlayers(2));
        $match->addPlayer($guest, $this->manager);

        $guess = new Guess('BBBBBBBB');
        $guess->setPlayer($host);

        $match->addUserGuess($guess);

        $guess = new Guess('YYYYYYYY');
        $guess->setPlayer($host);
        $match->addUserGuess($guess);

        $this->assertEquals(1, $match->getAttempts()->count());
    }

    /**
     * @expectedException \Exception
     */
    public function test_user_can_not_send_guess_until_game_ready()
    {
        $host = new Player(PlayerTest::PLAYERTEST);
        $guest = new Player("Guest");

        $match = $this->builder->create($host, (new GameConfig())->setNumberOfPlayers(2));

        $guess = new Guess('BBBBBBBB');
        $guess->setPlayer($host);

        $match->addUserGuess($guess);

        $this->assertEquals(1, $match->getAttempts()->count());
    }
}