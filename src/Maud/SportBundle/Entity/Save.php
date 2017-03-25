<?php

namespace Maud\SportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Save
 *
 * @ORM\Table(name="save")
 * @ORM\Entity(repositoryClass="Maud\SportBundle\Repository\SaveRepository")
 */
class Save
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer",unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="club", type="string", length=255, unique=false)
     */
    private $club;

    /**
     * @var string
     *
     * @ORM\Column(name="cours", type="string", length=255, unique=false)
     */
    private $cours;

    /**
     * @var string
     *
     * @ORM\Column(name="jour", type="string")
     */
    private $jour;

    
      /**
     * @var boolean
     *
     * @ORM\Column(name="réservé", type="boolean")
     */
    private $reservation = false;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set club
     *
     * @param string $club
     *
     * @return Save
     */
    public function setClub($club)
    {
        $this->club = $club;

        return $this;
    }

    /**
     * Get club
     *
     * @return string
     */
    public function getClub()
    {
        return $this->club;
    }

    /**
     * Set cours
     *
     * @param string $cours
     *
     * @return Save
     */
    public function setCours($cours)
    {
        $this->cours = $cours;

        return $this;
    }

    /**
     * Get cours
     *
     * @return string
     */
    public function getCours()
    {
        return $this->cours;
    }

    /**
     * Set jour
     *
     * @param string $jour
     *
     * @return Save
     */
    public function setJour($jour)
    {
        $this->jour = $jour;

        return $this;
    }

    /**
     * Get jour
     *
     * @return string
     */
    public function getJour()
    {
        return $this->jour;
    }
    
      /**
     * Set jour
     *
     * @param string $jour
     *
     * @return Save
     */
    public function setReservation($reservation)
    {
        $this->reservation = $reservation;

        return $this;
    }

    /**
     * Get jour
     *
     * @return string
     */
    public function getReservation()
    {
        return $this->reservation;
    }
}

