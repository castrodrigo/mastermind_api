<?php
/**
 * Created by PhpStorm.
 * User: fsynthis
 * Date: 21/05/16
 * Time: 16:26
 */

namespace Mastermind\CoreBundle\Tests\Builder;


use Doctrine\ORM\EntityManagerInterface;
use Mastermind\CoreBundle\Builder\GameBuilder;
use Mastermind\CoreBundle\Document\Game;
use Mastermind\CoreBundle\Document\GameConfig;
use Mastermind\CoreBundle\Document\Player;
use Mastermind\CoreBundle\Tests\Document\PlayerTest;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GameBuilderTest extends KernelTestCase
{
    /**
     * @var $dm EntityManagerInterface
     */
    protected $dm;

    /**
     * @var GameBuilder
     */
    private $builder;

    protected function setUp()
    {
        self::bootKernel();

        $this->dm = static::$kernel->getContainer()
            ->get('doctrine_mongodb')
            ->getManager();

        $this->builder = static::$kernel->getContainer()->get('mastermind.game_builder');
    }

    public function test_must_create_and_persist_a_game()
    {
        $game = new Game(new Player(PlayerTest::PLAYERNAME), new GameConfig());
        $game = $this->builder->start($game);

        $this->assertTrue($game instanceof Game);
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
