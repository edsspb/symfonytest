<?php

namespace Denis\DTestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Answers
 *
 * @ORM\Table(name="answers")
 * @ORM\Entity(repositoryClass="Denis\DTestBundle\Repository\AnswersRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Answers
{
    public function __construct()
    {
        $this->usersAnswers = new ArrayCollection();
        $this->usersQuestionsAnswers = new ArrayCollection();
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="text")
     * @Assert\NotBlank()
     * @Assert\Length(max = 4096)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_valid", type="boolean", options={"default":0})
     * @Assert\Type(type="boolean", message="The value {{ value }} is not a valid {{ type }}.")
     */
    private $isValid;

    /**
     * @var integer
     *
     * @ORM\Column(name="questions_id", type="integer")
     * @Assert\Type(type="integer", message="The value {{ value }} is not a valid {{ type }}.")
     */
    private $questionsId;

    /**
     * @var Questions
     *
     * @ORM\ManyToOne(targetEntity="Denis\DTestBundle\Entity\Questions", inversedBy="answers")
     * @ORM\JoinColumn(name="questions_id", referencedColumnName="id")
     */
    private $questions;

    /**
     * @var UsersAnswers
     *
     * @ORM\OneToMany(targetEntity="Denis\DTestBundle\Entity\UsersAnswers", mappedBy="answers", cascade={"persist", "remove"})
     */
    private $usersAnswers;

    /**
     * @var UsersQuestionsAnswers
     *
     * @ORM\OneToMany(targetEntity="Denis\DTestBundle\Entity\UsersQuestionsAnswers", mappedBy="answers", cascade={"persist", "remove"})
     */
    private $usersQuestionsAnswers;

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
     * Set name
     *
     * @param string $name
     * @return Answers
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set isValid
     *
     * @param boolean $isValid
     * @return Answers
     */
    public function setIsValid($isValid)
    {
        $this->isValid = (bool)$isValid;

        return $this;
    }

    /**
     * Get isValid
     *
     * @return boolean 
     */
    public function getIsValid()
    {
        return $this->isValid;
    }

    /**
     * Set questionsId
     *
     * @param integer $questionsId
     * @return Answers
     */
    public function setQuestionsId($questionsId)
    {
        $this->questionsId = $questionsId;

        return $this;
    }

    /**
     * Get questionsId
     *
     * @return integer 
     */
    public function getQuestionsId()
    {
        return $this->questionsId;
    }

    /**
     * Set Questions
     *
     * @param Questions $questions
     * @return Answers
     */
    public function setQuestions(Questions $questions)
    {
        $this->questions = $questions;

        return $this;
    }

    /**
     * Get Questions
     *
     * @return Questions
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Set ArrayCollection UsersAnswers::usersAnswers
     *
     * @param ArrayCollection $usersAnswers
     * @return Answers
     */
    public function setUsersAnswers(ArrayCollection $usersAnswers)
    {
        $this->usersAnswers = $usersAnswers;

        return $this;
    }

    /**
     * Get ArrayCollection UsersAnswers::usersAnswers
     *
     * @return ArrayCollection UsersAnswers::usersAnswers
     */
    public function getUsersAnswers()
    {
        return $this->usersAnswers;
    }

    /** @ORM\PrePersist */
    public function onCreate()
    {
        if(is_null($this->isValid))
            $this->isValid = false;
    }
}
