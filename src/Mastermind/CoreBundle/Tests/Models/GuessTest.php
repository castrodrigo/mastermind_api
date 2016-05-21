<?php
/**
 * Created by PhpStorm.
 * User: fsynthis
 * Date: 21/05/16
 * Time: 10:41
 */

namespace Mastermind\CoreBundle\Tests\Models;


use Mastermind\CoreBundle\Models\Color;
use Mastermind\CoreBundle\Models\Game;
use Mastermind\CoreBundle\Models\GameConfig;
use Mastermind\CoreBundle\Models\Guess;
use Mastermind\CoreBundle\Models\User;

class GuessTest extends \PHPUnit_Framework_TestCase
{
    public function test_must_generate_guess()
    {
        $game = new Game(new User("User"), new GameConfig());
        $guess = (new Guess())->generate($game);

        $this->assertEquals($game->getConfig()->getCodeLength(), strlen($guess));
    }

    public function test_guess_must_return_string()
    {
        $game = new Game(new User("User"), new GameConfig());
        $guess = (new Guess())->generate($game);

        $this->assertTrue(is_string($guess->toString()));
    }

    public function test_guess_must_return_array()
    {
        $game = new Game(new User("User"), new GameConfig());
        $guess = (new Guess())->generate($game);

        $this->assertTrue(is_array($guess->getColors()));
    }

    public function test_must_generate_guess_custom()
    {
        $game = new Game(new User("User"), (new GameConfig())->setCodeLength(6));
        $guess = (new Guess())->generate($game);

        $this->assertEquals($game->getConfig()->getCodeLength(), strlen($guess));
    }

    public function test_check_exact_must_return_all_correct()
    {
        $guess = $this->getMockBuilder(Guess::class)
            ->getMock();

        $guess->method('getColors')
            ->willReturn((new Color())->toArray());

        /**
         * @var $guess Guess
         */
        $user_guess = new Guess(Color::COLORS);

        $this->assertEquals(8, $user_guess->checkExacts($guess));
    }

    public function test_check_exact_must_return_partial_exact()
    {

        $guess = $this->getMockBuilder(Guess::class)
            ->getMock();

        $guess->method('getColors')
            ->willReturn((new Color())->toArray());

        /**
         * @var $guess Guess
         */
        $user_guess = new Guess("RxxYxxxM");

        $this->assertEquals(3, $user_guess->checkExacts($guess));
    }

    public function test_check_near_must_return_all_near()
    {
        $guess = $this->getMockBuilder(Guess::class)
            ->getMock();

        $guess->method('getColors')
            ->willReturn((new Color())->toArray());

        /**
         * @var $guess Guess
         */
        $user_guess = new Guess(strrev(Color::COLORS));
        
        $this->assertEquals(0, $user_guess->checkExacts($guess));
        $this->assertEquals(8, $user_guess->checkNear($guess));
    }

    public function test_check_near_must_return_partial_near()
    {
        $guess = $this->getMockBuilder(Guess::class)
            ->getMock();

        $guess->method('getColors')
            ->willReturn((new Color())->toArray());

        /**
         * @var $guess Guess
         */
        $user_guess = new Guess("xRxxxxxx");

        $this->assertEquals(0, $user_guess->checkExacts($guess));
        $this->assertEquals(1, $user_guess->checkNear($guess));
    }

    public function test_check_near_and_exact_sum_must_not_be_greater_than_code_lenght()
    {
        $guess = $this->getMockBuilder(Guess::class)
            ->getMock();

        $guess->method('getColors')
            ->willReturn((new Color())->toArray());

        /**
         * @var $guess Guess
         */
        $user_guess = new Guess("RB" . strrev('GYOPCM'));

        $this->assertEquals(2, $user_guess->checkExacts($guess));
        $this->assertEquals(6, $user_guess->checkNear($guess));
    }

    public function test_check_near_and_exact()
    {
        $guess = $this->getMockBuilder(Guess::class)
            ->getMock();

        $guess->method('getColors')
            ->willReturn((new Color())->toArray());

        /**
         * @var $guess Guess
         */
        $user_guess = new Guess("RB" . strrev('GYxPCM'));

        $this->assertEquals(2, $user_guess->checkExacts($guess));
        $this->assertEquals(5, $user_guess->checkNear($guess));
    }
}