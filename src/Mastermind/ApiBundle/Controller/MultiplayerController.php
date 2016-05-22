<?php

namespace Mastermind\ApiBundle\Controller;

use Doctrine\ODM\MongoDB\DocumentManager;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use Mastermind\CoreBundle\Builder\GameBuilder;
use Mastermind\CoreBundle\Builder\MultiplayerBuilder;
use Mastermind\CoreBundle\Document\Game;
use Mastermind\CoreBundle\Document\GameConfig;
use Mastermind\CoreBundle\Document\Guess;
use Mastermind\CoreBundle\Document\Match;
use Mastermind\CoreBundle\Document\Player;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @RouteResource("Match")
 */
class MultiplayerController extends FOSRestController
{
    /**
     * @return MultiplayerBuilder
     */
    private function getBuilder()
    {
        return $this->get('mastermind.multiplayer_builder');
    }

    /**
     * @return DocumentManager
     */
    private function getManager()
    {
        return $this->get('doctrine_mongodb')->getManager();
    }
    
    public function postAction(Request $request)
    {
        $match = $this->getBuilder()->create(new Player($request->request->get('user')));

        $this->getManager()->persist($match);
        $this->getManager()->flush();
        
        $view = View::create();
        $context = new SerializationContext();
        $context->setGroups(['default']);

        return $this->handleView($view->setSerializationContext($context)->setData($match))->setStatusCode(Response::HTTP_CREATED);
    }

    public function postPlayerAction(Request $request, $match)
    {
        /**
         * @var $_match Match
         */
        $_match = $this->getManager()->find(Match::class, $match);
        $_match->addPlayer(new Player($request->request->get('user')), $this->getManager());
        
        $view = View::create();
        $context = new SerializationContext();
        $context->setGroups(['default']);

        return $this->handleView($view->setSerializationContext($context)->setData($_match))->setStatusCode(Response::HTTP_CREATED);
    }

    public function postPlayerGuessAction(Request $request, $match, $user)
    {
        /**
         * @var $_match Match
         */
        $_match = $this->getManager()->find(Match::class, $match);
        $_player = $this->getManager()->find(Player::class, $user);
        
        $player_guess = new Guess($request->request->get('code'));
        $player_guess->setPlayer($_player);
        
        $_match->addUserGuess($player_guess);
        $this->getManager()->flush($_match);
        
        $view = View::create();
        $context = new SerializationContext();
        $context->setGroups(['default']);

        return $this->handleView($view->setSerializationContext($context)->setData($_match))->setStatusCode(Response::HTTP_CREATED);
    }
}
