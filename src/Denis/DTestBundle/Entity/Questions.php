<?php

namespace Denis\DTestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Questions
 *
 * @ORM\Table(name="questions")
 * @ORM\Entity(repositoryClass="Denis\DTestBundle\Repository\QuestionsRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Questions
{
    public function __construct()
    {
        $this->answers = new ArrayCollection();
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
     * @var integer
     *
     * @ORM\Column(name="tests_id", type="integer")
     * @Assert\Type(type="integer", message="The value {{ value }} is not a valid {{ type }}.")
     */
    private $testsId;

    /**
     * @var Tests
     *
     * @ORM\ManyToOne(targetEntity="Denis\DTestBundle\Entity\Tests", inversedBy="questions")
     * @ORM\JoinColumn(name="tests_id", referencedColumnName="id")
     */
    private $tests;

    /**
     * @var Answers
     *
     * @ORM\OneToMany(targetEntity="Denis\DTestBundle\Entity\Answers", mappedBy="questions", cascade={"persist", "remove"})
     */
    private $answers;

    /**
     * @var UsersQuestionsAnswers
     *
     * @ORM\OneToMany(targetEntity="Denis\DTestBundle\Entity\UsersQuestionsAnswers", mappedBy="questions", cascade={"persist", "remove"})
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
     * @return Questions
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
     * Set testsId
     *
     * @param integer $testsId
     * @return Questions
     */
    public function setTestsId($testsId)
    {
        $this->testsId = $testsId;

        return $this;
    }

    /**
     * Get testsId
     *
     * @return integer 
     */
    public function getTestsId()
    {
        return $this->testsId;
    }

    /**
     * Set Tests
     *
     * @param Tests $tests
     * @return Questions
     */
    public function setTests(Tests $tests)
    {
        $this->tests = $tests;

        return $this;
    }

    /**
     * Get Tests
     *
     * @return Tests
     */
    public function getTests()
    {
        return $this->tests;
    }

    /**
     * Set ArrayCollection Questions::answers
     *
     * @param ArrayCollection $answers
     * @return Questions
     */
    public function setAnswers(ArrayCollection $answers)
    {
        $this->answers = $answers;

        return $this;
    }

    /**
     * Get ArrayCollection Questions::answers
     *
     * @return ArrayCollection Questions::answers
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * Set ArrayCollection Questions::usersQuestionsAnswers
     *
     * @param ArrayCollection $usersQuestionsAnswers
     * @return Questions
     */
    public function setUsersQuestionsAnswers(ArrayCollection $usersQuestionsAnswers)
    {
        $this->usersQuestionsAnswers = $usersQuestionsAnswers;

        return $this;
    }

    /**
     * Get ArrayCollection Questions::usersQuestionsAnswers
     *
     * @return ArrayCollection Questions::usersQuestionsAnswers
     */
    public function getUsersQuestionsAnswers()
    {
        return $this->usersQuestionsAnswers;
    }

    /**
     * Add Answers
     *
     * @param Answers $answers
     * @return Questions
     */
    public function addAnswers(Answers $answers)
    {
        if (!$this->answers->contains($answers)) {
            $this->answers->add($answers);
        }

        return $this;
    }

    /**
     * Remove Answers
     *
     * @param Answers $answers
     * @return Questions
     */
    public function removeQuestions(Answers $answers)
    {
        if ($this->answers->contains($answers)) {
            $this->answers->removeElement($answers);
        }

        return $this;
    }

    /** @ORM\PrePersist */
    public function prePersist(LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();
        foreach ($this->answers as $answer) {
            if($answer instanceof Answers) {
                $answer->setQuestions($this);
                $em->persist($answer);
            }
        }
    }
}
