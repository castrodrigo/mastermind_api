<?php
/**
 * Created by PhpStorm.
 * User: fsynthis
 * Date: 22/05/16
 * Time: 17:24
 */

namespace Mastermind\CoreBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Mastermind\CoreBundle\Document\Player;

/**
 * Class Match
 * @package Mastermind\CoreBundle\Document
 * @MongoDB\Document
 */
class Match
{
    const MAX_GUEST_EXCEPTION = "The number of guests has been reached.";
    const PLAYER_ALREADY_PLAY_THE_TURN = "You have made ​​your move , wait for the other opponent.";
    const GAME_IS_NOT_READY = "The game is not ready, wait for yout opponent.";
    /**
     * @MongoDB\Id
     * @Serializer\Groups({"default"})
     */
    private $id;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Game", cascade={"persist", "remove"})
     * @Serializer\Groups({"default"})
     *
     */
    private $game;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Player", cascade={"persist", "remove"})
     * @Serializer\Groups({"default"})
     * @Serializer\Expose
     */
    private $host;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Player", cascade={"persist", "remove"})
     * @Serializer\Groups({"default"})
     */
    private $guests = [];

    /**
     * @MongoDB\Boolean
     * @Serializer\Groups({"default"})
     */
    private $ready = false;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Attempt", cascade={"persist", "remove"})
     * @Serializer\Groups({"default"})
     */
    private $attempts;

    /**
     * @MongoDB\Date
     * @Gedmo\Timestampable(on="create")
     * @Serializer\Exclude
     */
    private $created_at;

    /**
     * @MongoDB\Date
     * @Gedmo\Timestampable
     * @Serializer\Exclude
     */
    private $updated_at;

    /**
     * Match constructor.
     * @param Game $game
     * @param Player $host
     */
    public function __construct(Game $game, Player $host)
    {
        $this->game = $game;
        $this->host = $host;
        $this->guests = new ArrayCollection();
        $this->attempts = new ArrayCollection();
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param mixed $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return Game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @param mixed $game
     */
    public function setGame($game)
    {
        $this->game = $game;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param mixed $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @return mixed
     */
    public function getGuests()
    {
        return $this->guests;
    }

    /**
     * @param mixed $guests
     */
    public function setGuests($guests)
    {
        $this->guests = $guests;
    }

    /**
     * @return mixed
     */
    public function getReady()
    {
        return $this->ready;
    }

    /**
     * Add guest
     *
     * @param Player $guest
     * @throws \Exception
     */
    public function addGuest(Player $guest)
    {
        if($this->getReady()) {
            throw new \Exception(static::MAX_GUEST_EXCEPTION);
        }
        $this->guests->add($guest);
    }

    /**
     * Remove guest
     *
     * @param Player $guest
     */
    public function removeGuest(Player $guest)
    {
        $this->guests->removeElement($guest);
    }

    public function isReady()
    {
        return $this->getGuests()->count() >= $this->getGame()->getConfig()->getNumberOfPlayers() - 1;
    }

    /**
     * @param \Mastermind\CoreBundle\Document\Player $guest
     * @param DocumentManager $manager
     * @throws \Exception
     */
    public function addPlayer(Player $guest, DocumentManager $manager)
    {
        $this->addGuest($guest);
        $this->ready = $this->isReady();
        $manager->flush();
    }

    public function addUserGuess(Guess $guess)
    {
        if(!$this->getReady()) {
            throw new \Exception(static::GAME_IS_NOT_READY);
        }
        /**
         * @var $attempt Attempt
         */
        $attempt = $this->getAttempts()->last();
        if((!is_null($attempt) && $attempt instanceof Attempt) && $attempt->getGuesses()->count() < $this->getGame()->getConfig()->getNumberOfPlayers()) {
            foreach($attempt->getGuesses() as $_guess) {
                /**
                 * @var $_guess Guess
                 */
                if($guess->getPlayer()->getId() == $_guess->getPlayer()->getId()) {
                    throw new \Exception(static::PLAYER_ALREADY_PLAY_THE_TURN);
                }
                
                $attempt->addGuess($guess);
            }
        } else {
            $attempt = new Attempt();
            $attempt->addGuess($guess);
            $this->getAttempts()->add($attempt);
        }
    }

    /**
     * @return ArrayCollection
     */
    public function getAttempts()
    {
        return $this->attempts;
    }

    /**
     * Set ready
     *
     * @param boolean $ready
     * @return self
     */
    public function setReady($ready)
    {
        $this->ready = $ready;
        return $this;
    }

    /**
     * Add attempt
     *
     * @param Mastermind\CoreBundle\Document\Attempt $attempt
     */
    public function addAttempt(\Mastermind\CoreBundle\Document\Attempt $attempt)
    {
        $this->attempts[] = $attempt;
    }

    /**
     * Remove attempt
     *
     * @param Mastermind\CoreBundle\Document\Attempt $attempt
     */
    public function removeAttempt(\Mastermind\CoreBundle\Document\Attempt $attempt)
    {
        $this->attempts->removeElement($attempt);
    }
}
