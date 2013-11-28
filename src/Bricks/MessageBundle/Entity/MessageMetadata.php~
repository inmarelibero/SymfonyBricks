<?php
namespace Bricks\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use FOS\MessageBundle\Entity\MessageMetadata as BaseMessageMetadata;
use FOS\MessageBundle\Model\MessageInterface;
use FOS\MessageBundle\Model\ParticipantInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="message_message_metadata")
 */
class MessageMetadata extends BaseMessageMetadata
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\generatedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Bricks\MessageBundle\Entity\Message", inversedBy="metadata")
     */
    protected $message;

    /**
     * @ORM\ManyToOne(targetEntity="Bricks\UserBundle\Entity\User")
     */
    protected $participant;

    /**************************************************************************************************
     *	custom functions
    **************************************************************************************************/

    public function setMessage(MessageInterface $message) {
        $this->message = $message;
        return $this;
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
     * Get message
     *
     * @return \Bricks\MessageBundle\Entity\Message 
     */
    public function getMessage()
    {
        return $this->message;
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