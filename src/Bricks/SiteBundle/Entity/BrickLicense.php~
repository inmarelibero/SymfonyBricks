<?php

namespace Bricks\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use FOS\UserBundle\Entity\User;

/**
 * Bricks\SiteBundle\Entity\BrickLicense
 *
 * @ORM\Entity(repositoryClass="Bricks\SiteBundle\Entity\BrickLicenseRepository")
 * @ORM\Table(name="brick_license")
 */
class BrickLicense
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var text $title
     *
     * @ORM\Column(type="text")
     */
    private $title;

    /**
     * @var text $titleShort
     *
     * @ORM\Column(name="title_short", type="text")
     */
    private $titleShort;

    /**
     * @var text $content
     *
     * @ORM\Column(type="text")
     */
    private $content;

    /**************************************************************************************************
     *	custom functions
    **************************************************************************************************/
    public function __toString()
    {
        return $this->title;
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
     * Set title
     *
     * @param string $title
     * @return BrickLicense
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set titleShort
     *
     * @param string $titleShort
     * @return BrickLicense
     */
    public function setTitleShort($titleShort)
    {
        $this->titleShort = $titleShort;
    
        return $this;
    }

    /**
     * Get titleShort
     *
     * @return string 
     */
    public function getTitleShort()
    {
        return $this->titleShort;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return BrickLicense
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }
}