<?php
namespace Bricks\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use FOS\MessageBundle\Entity\ThreadMetadata as BaseThreadMetadata;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\Model\ParticipantInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="message_thread_metadata")
 */
class ThreadMetadata extends BaseThreadMetadata
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\generatedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Bricks\MessageBundle\Entity\Thread", inversedBy="metadata")
     */
    protected $thread;

    /**
     * @ORM\ManyToOne(targetEntity="Bricks\UserBundle\Entity\User")
     */
    protected $participant;

    /**************************************************************************************************
     *	custom functions
    **************************************************************************************************/

    public function setThread(ThreadInterface $thread) {
        $this->thread = $thread;
    }

    public function setParticipant(ParticipantInterface $participant) {
        $this->participant = $participant;
        return $this;
    }
    
    /**************************************************************************************************
     *	getters and setters
    **************************************************************************************************/

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get thread
     *
     * @return \Bricks\MessageBundle\Entity\Thread 
     */
    public function getThread()
    {
        return $this->thread;
    }

    /**
     * Get participant
     *
     * @return \Bricks\UserBundle\Entity\User 
     */
    public function getParticipant()
    {
        return $this->participant;
    }
}
