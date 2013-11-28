<?php

namespace Bricks\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Loggable\Entity\LogEntry;

/**
 * Bricks\SiteBundle\Entity\BrickLogEntry
 *
 * @ORM\Table(name="brick_log_entry")
 * @ORM\Entity()
 */
class BrickLogEntry extends LogEntry
{
    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @var string $action
     */
    protected $action;

    /**
     * @var \DateTime $loggedAt
     */
    protected $loggedAt;

    /**
     * @var string $objectId
     */
    protected $objectId;

    /**
     * @var string $objectClass
     */
    protected $objectClass;

    /**
     * @var integer $version
     */
    protected $version;

    /**
     * @var array $data
     */
    protected $data;

    /**
     * @var string $username
     */
    protected $username;


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
     * Set action
     *
     * @param string $action
     * @return BrickLogEntry
     */
    public function setAction($action)
    {
        $this->action = $action;
    
        return $this;
    }

    /**
     * Get action
     *
     * @return string 
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set loggedAt
     *
     * @param \DateTime $loggedAt
     * @return BrickLogEntry
     */
    public function setLoggedAt()
    {
        $this->loggedAt = new \Datetime();
    
        return $this;
    }

    /**
     * Get loggedAt
     *
     * @return \DateTime 
     */
    public function getLoggedAt()
    {
        return $this->loggedAt;
    }

    /**
     * Set objectId
     *
     * @param string $objectId
     * @return BrickLogEntry
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;
    
        return $this;
    }

    /**
     * Get objectId
     *
     * @return string 
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * Set objectClass
     *
     * @param string $objectClass
     * @return BrickLogEntry
     */
    public function setObjectClass($objectClass)
    {
        $this->objectClass = $objectClass;
    
        return $this;
    }

    /**
     * Get objectClass
     *
     * @return string 
     */
    public function getObjectClass()
    {
        return $this->objectClass;
    }

    /**
     * Set version
     *
     * @param integer $version
     * @return BrickLogEntry
     */
    public function setVersion($version)
    {
        $this->version = $version;
    
        return $this;
    }

    /**
     * Get version
     *
     * @return integer 
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set data
     *
     * @param array $data
     * @return BrickLogEntry
     */
    public function setData($data)
    {
        $this->data = $data;
    
        return $this;
    }

    /**
     * Get data
     *
     * @return array 
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return BrickLogEntry
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }
}