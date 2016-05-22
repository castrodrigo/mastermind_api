<?php
/**
 * Created by PhpStorm.
 * User: fsynthis
 * Date: 22/05/16
 * Time: 18:34
 */

namespace Mastermind\CoreBundle\Document;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Mastermind\CoreBundle\Document\Guess;

/**
 * Class Attempt
 * @package Mastermind\CoreBundle\Document
 * @MongoDB\Document
 */
class Attempt
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Guess", cascade={"persist", "remove"})
     */
    private $guesses = [];

    /**
     * Attempt constructor.
     */
    public function __construct()
    {
        $this->guesses = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getGuesses()
    {
        return $this->guesses;
    }

    /**
     * @param ArrayCollection $guesses
     */
    public function setGuesses($guesses)
    {
        $this->guesses = $guesses;
    }

    /**
     * @param Guess $guess
     * @return $this
     */
    public function addGuess(Guess $guess)
    {
        $this->guesses->add($guess);
        return $this;
    }

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Remove guess
     *
     * @param Guess $guess
     */
    public function removeGuess(Guess $guess)
    {
        $this->guesses->removeElement($guess);
    }
}
