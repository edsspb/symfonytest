<?php

namespace Denis\DTestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Tests
 *
 * @ORM\Table(name="tests")
 * @ORM\Entity(repositoryClass="Denis\DTestBundle\Repository\TestsRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Tests
{
    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->usersTests = new ArrayCollection();
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
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(max = 255)
     */
    private $name;

    /**
     * @var Questions
     *
     * @ORM\OneToMany(targetEntity="Denis\DTestBundle\Entity\Questions", mappedBy="tests", cascade={"persist", "remove"})
     */
    private $questions;

    /**
     * @var UsersTests
     *
     * @ORM\OneToMany(targetEntity="Denis\DTestBundle\Entity\UsersTests", mappedBy="tests", cascade={"persist", "remove"})
     */
    private $usersTests;

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
     * @return Tests
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
     * Set Questions
     *
     * @param ArrayCollection $questions
     * @return Tests
     */
    public function setQuestions(ArrayCollection $questions)
    {
        $this->questions = $questions;

        return $this;
    }

    /**
     * Get ArrayCollection Tests::questions
     *
     * @return ArrayCollection Tests::questions 
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Set ArrayCollection Tests::usersTests
     *
     * @param ArrayCollection $usersTests
     * @return Tests
     */
    public function setUsersTests(ArrayCollection $usersTests)
    {
        $this->usersTests = $usersTests;

        return $this;
    }

    /**
     * Get ArrayCollection Tests::usersTests
     *
     * @return ArrayCollection Tests::usersTests 
     */
    public function getUsersTests()
    {
        return $this->usersTests;
    }

    /**
     * Add Questions
     *
     * @param Questions $questions
     * @return Tests
     */
    public function addQuestions(Questions $questions)
    {
        if (!$this->questions->contains($questions)) {
            $this->questions->add($questions);
        }

        return $this;
    }

    /**
     * Remove Questions
     *
     * @param Questions $questions
     * @return Tests
     */
    public function removeQuestions(Questions $questions)
    {
        if ($this->questions->contains($questions)) {
            $this->questions->removeElement($questions);
        }

        return $this;
    }

    /** @ORM\PrePersist */
    public function prePersist(LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();
        foreach ($this->questions as $question) {
            if($question instanceof Questions) {
                $question->setTests($this);
                $em->persist($question);
            }
        }
    }
}
