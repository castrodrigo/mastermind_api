<?php

namespace Mastermind\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MastermindCoreBundle:Default:index.html.twig');
    }
}
