<?php

namespace Mastermind\CoreBundle\Tests\Document;


use Doctrine\ORM\EntityManagerInterface;
use Mastermind\CoreBundle\Document\Player;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PlayerTest extends KernelTestCase
{

    const PLAYERTEST = "PlayerTest";
    const PLAYERNAME = "PlayerPlayer";
    
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


    public function test_must_persist_user()
    {
        $player = new Player(static::PLAYERTEST);

        $this->dm->persist($player);
        $this->dm->flush();

        $this->assertTrue(is_string($player->getId()));
    }

    protected function tearDown()
    {
        $this->dm->createQueryBuilder(Player::class)
                ->remove()
                ->field('name')->equals(static::PLAYERTEST)
                ->getQuery()
                ->execute();

        $this->dm->flush();
    }
}
