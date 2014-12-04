<?php

namespace Denis\DTestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * UsersQuestionsAnswers
 *
 * @ORM\Table(name="users_questions_answers")
 * @ORM\Entity
 */
class UsersQuestionsAnswers
{
    public function __construct()
    {
        $this->usersAnswers = new ArrayCollection();
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
     * @var integer
     *
     * @ORM\Column(name="questions_id", type="integer")
     * @Assert\Type(type="integer", message="The value {{ value }} is not a valid {{ type }}.")
     */
    private $questionsId;

    /**
     * @var integer
     *
     * @ORM\Column(name="answers_id", type="integer", nullable=true)
     * @Assert\Type(type="integer", message="The value {{ value }} is not a valid {{ type }}.")
     */
    private $answersId;

    /**
     * @var string
     *
     * @ORM\Column(name="comments", type="text", nullable=true)
     * @Assert\Length(max = 4096)
     */
    private $comments;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_valid", type="boolean", nullable=true, options={"default":0})
     * @Assert\Type(type="boolean", message="The value {{ value }} is not a valid {{ type }}.")
     */
    private $isValid;

    /**
     * @var Questions
     *
     * @ORM\ManyToOne(targetEntity="Denis\DTestBundle\Entity\Questions", inversedBy="usersQuestionsAnswers")
     * @ORM\JoinColumn(name="questions_id", referencedColumnName="id")
     */
    private $questions;

    /**
     * @var Answers
     *
     * @ORM\ManyToOne(targetEntity="Denis\DTestBundle\Entity\Answers", inversedBy="usersQuestionsAnswers")
     * @ORM\JoinColumn(name="answers_id", referencedColumnName="id")
     */
    private $answers;

    /**
     * @var integer
     *
     * @ORM\Column(name="users_tests_id", type="integer")
     * @Assert\Type(type="integer", message="The value {{ value }} is not a valid {{ type }}.")
     */
    private $usersTestsId;

    /**
     * @var UsersTests
     *
     * @ORM\ManyToOne(targetEntity="Denis\DTestBundle\Entity\UsersTests", inversedBy="usersQuestionsAnswers")
     * @ORM\JoinColumn(name="users_tests_id", referencedColumnName="id")
     */
    private $usersTests;

    /**
     * @var UsersAnswers
     *
     * @ORM\OneToMany(targetEntity="Denis\DTestBundle\Entity\UsersAnswers", mappedBy="usersQuestionsAnswers", cascade={"persist", "remove"})
     */
    private $usersAnswers;

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
     * Set questionsId
     *
     * @param integer $questionsId
     * @return UsersQuestionsAnswers
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
     * Set answersId
     *
     * @param integer $answersId
     * @return UsersQuestionsAnswers
     */
    public function setAnswersId($answersId)
    {
        $this->answersId = $answersId;

        return $this;
    }

    /**
     * Get answersId
     *
     * @return integer 
     */
    public function getAnswersId()
    {
        return $this->answersId;
    }

    /**
     * Set comments
     *
     * @param string $comments
     * @return UsersQuestionsAnswers
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return string 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set isValid
     *
     * @param boolean $isValid
     * @return UsersQuestionsAnswers
     */
    public function setIsValid($isValid) {
        $this->isValid = $isValid;

        return $this;
    }

    /**
     * Get isValid
     *
     * @return boolean 
     */
    public function getIsValid() {
        return $this->isValid;
    }

    /**
     * Set usersTestsId
     *
     * @param integer $usersTestsId
     * @return UsersQuestionsAnswers
     */
    public function setUsersTestsId($usersTestsId) {
        $this->usersTestsId = $usersTestsId;

        return $this;
    }

    /**
     * Get usersTestsId
     *
     * @return integer 
     */
    public function getUsersTestsId() {
        return $this->usersTestsId;
    }

    /**
     * Set Questions
     *
     * @param Questions $questions
     * @return UsersQuestionsAnswers
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
     * Set Answers
     *
     * @param Answers $answers
     * @return UsersQuestionsAnswers
     */
    public function setAnswers(Answers $answers)
    {
        $this->answers = $answers;
        return $this;
    }

    /**
     * Get Answers
     *
     * @return Answers
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * Set UsersTests
     *
     * @param UsersTests $usersTests
     * @return UsersQuestionsAnswers
     */
    public function setUsersTests(UsersTests $usersTests)
    {
        $this->usersTests = $usersTests;
        return $this;
    }

    /**
     * Get UsersTests
     *
     * @return UsersTests
     */
    public function getUsersTests()
    {
        return $this->usersTests;
    }
    
    /**
     * Set ArrayCollection UsersQuestionsAnswers::usersAnswers
     *
     * @param ArrayCollection $usersTests
     * @return UsersQuestionsAnswers
     */
    public function setUsersAnswers(ArrayCollection $usersAnswers)
    {
        $this->usersAnswers = $usersAnswers;
        return $this;
    }

    /**
     * Get ArrayCollection UsersQuestionsAnswers::usersAnswers
     *
     * @return ArrayCollection UsersQuestionsAnswers::usersAnswers
     */
    public function getUsersAnswers()
    {
        return $this->usersAnswers;
    }

    /**
     * Add UsersAnswers
     *
     * @param UsersAnswers $usersAnswers
     * @return UsersQuestionsAnswers
     */
    public function addUsersAnswers(UsersAnswers $usersAnswers)
    {
        if (!$this->usersAnswers->contains($usersAnswers)) {
            $this->usersAnswers->add($usersAnswers);
        }

        return $this;
    }

    /** @ORM\PrePersist */
    public function prePersist(LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();
        foreach ($this->usersAnswers as $ua) {
            if($ua instanceof UsersQuestionsAnswers) {
                $ua->setUsersQuestionsAnswers($this);
                $em->persist($ua);
            }
        }
    }
}
