<?php

namespace Mastermind\ApiBundle\Controller;

use Doctrine\ODM\MongoDB\DocumentManager;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use Mastermind\CoreBundle\Builder\GameBuilder;
use Mastermind\CoreBundle\Document\Game;
use Mastermind\CoreBundle\Document\GameConfig;
use Mastermind\CoreBundle\Document\Guess;
use Mastermind\CoreBundle\Document\Player;
use Mastermind\CoreBundle\Document\Color;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @RouteResource("Game")
 */
class GameController extends FOSRestController
{

    const GAME_WON_EXCEPTION = "The game has already been won, you should start a new match!";

    public function postAction(Request $request)
    {
        $color = new Color();
        
        $game = new Game(new Player($request->request->get('user')), new GameConfig());
        $game = $this->getBuilder()->start($game);
        $game->setColors($color->getColors());
        
        $view = View::create();
        $context = new SerializationContext();
        $context->setGroups(['default']);

        return $this->handleView($view->setSerializationContext($context)->setData($game))->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @return GameBuilder
     */
    private function getBuilder()
    {
        return $this->get('mastermind.game_builder');
    }

    public function postGuessAction(Request $request, $game_key)
    {
        /**
         * @var $game Game
         */
        $game = $this->getManager()->find(Game::class, $game_key);

        if ($game->getSolved()) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, static::GAME_WON_EXCEPTION);
        }

        $game->addUserGuess(new Guess($request->request->get('code')));
        $this->getManager()->flush();

        $view = View::create();
        $context = new SerializationContext();
        $context->setGroups([$game->getSolved() ? 'win' : 'details']);

        return $this->handleView($view->setSerializationContext($context)->setData($game))->setStatusCode($game->getSolved() ? Response::HTTP_OK : Response::HTTP_CREATED);
    }

    /**
     * @return DocumentManager
     */
    private function getManager()
    {
        return $this->get('doctrine_mongodb')->getManager();
    }

    /**
     * @return Serializer
     */
    private function getSerializer()
    {
        return $this->get('serializer');
    }
}
