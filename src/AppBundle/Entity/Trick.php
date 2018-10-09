<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * trick
 *
 * @ORM\Table(name="sp_trick")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TrickRepository")
 * @UniqueEntity(fields={"nom"}, message="Cette figure existe déjà")
 *
 */
class Trick
{

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", cascade={"persist"})
     */
    public $auteur;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Groupe", cascade={"persist"})
     * @Assert\Valid()
     */
    public $groupe;
    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Image",cascade={"persist","remove"})
     * @ORM\JoinTable(name="sp_trick_image")
     * @Assert\Valid()
     */

    public $images;
    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Video",cascade={"persist","remove"})
     * @ORM\JoinTable(name="sp_trick_video")
     * @Assert\Valid()
     */
    public $videos;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment", mappedBy="trick",cascade={"persist","remove"})
     */

    public $comments;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, unique=true)
     */
    private $nom;
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;
    /**
     * @var bool|null
     *
     * @ORM\Column(name="publie", type="boolean", nullable=true)
     */
    private $publie;

    public function __construct()
    {
        $this->date = new \Datetime();
        $this->images = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function addComment(comment $comment)
    {
        $this->comments[] = $comment;
        return $this;
    }

    public function removeComment(comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function addImage(Image $image)
    {
        $this->images[] = $image;
    }

    public function removeImage(image $image)
    {
        $this->images->removeElement($image);

    }

    public function getImages()
    {
        return $this->images;
    }

    public function addVideo(Video $video)
    {

        $this->videos[] = $video;
    }

    public function removeVideo(video $video)
    {

        $this->videos->removeElement($video);
    }

    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get nom.
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set nom.
     *
     * @param string $nom
     *
     * @return trick
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return trick
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return trick
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get publie.
     *
     * @return bool|null
     */
    public function getPublie()
    {
        return $this->publie;
    }

    /**
     * Set publie.
     *
     * @param bool|null $publie
     *
     * @return trick
     */
    public function setPublie($publie = null)
    {
        $this->publie = $publie;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * @param mixed $auteur
     */
    public function setAuteur(User $auteur)
    {
        $this->auteur = $auteur;
    }

    /**
     * @return mixed
     */
    public function getGroupe()
    {
        return $this->groupe;
    }

    /**
     * @param mixed $groupe
     */
    public function setGroupe(Groupe $groupe)
    {
        $this->groupe = $groupe;
    }

}
