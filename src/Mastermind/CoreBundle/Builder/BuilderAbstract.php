<?php
/**
 * Created by PhpStorm.
 * User: fsynthis
 * Date: 22/05/16
 * Time: 17:52
 */

namespace Mastermind\CoreBundle\Builder;


abstract  class BuilderAbstract
{
    protected $manager;

    /**
     * GameBuilder constructor.
     * @param DocumentManager $manager
     */
    public function __construct($manager)
    {
        $this->manager = $manager;
    }
}