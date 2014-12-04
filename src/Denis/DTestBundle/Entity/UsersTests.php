<?php

namespace Denis\DTestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * UsersTests
 *
 * @ORM\Table(name="users_tests")
 * @ORM\Entity(repositoryClass="Denis\DTestBundle\Repository\UsersTestsRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class UsersTests
{
    public function __construct()
    {
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
     * @var integer
     *
     * @ORM\Column(name="users_id", type="integer")
     */
    private $usersId;

    /**
     * @var integer
     *
     * @ORM\Column(name="tests_id", type="integer")
     */
    private $testsId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_checked", type="boolean", options={"default":0})
     */
    private $isChecked;

    /**
     * @var integer
     *
     * @ORM\Column(name="attempt", type="integer")
     * @Assert\Type(type="integer", message="The value {{ value }} is not a valid {{ type }}.")
     * @Assert\NotBlank()
     */
    private $attempt;

    /**
     * @var Users
     *
     * @ORM\ManyToOne(targetEntity="Denis\DTestBundle\Entity\Users", inversedBy="usersTests")
     * @ORM\JoinColumn(name="users_id", referencedColumnName="id")
     */
    private $users;

    /**
     * @var Tests
     *
     * @ORM\ManyToOne(targetEntity="Denis\DTestBundle\Entity\Tests", inversedBy="usersTests")
     * @ORM\JoinColumn(name="tests_id", referencedColumnName="id")
     */
    private $tests;

    /**
     * @var UsersQuestionsAnswers
     *
     * @ORM\OneToMany(targetEntity="Denis\DTestBundle\Entity\UsersQuestionsAnswers", mappedBy="usersTests", cascade={"persist", "remove"})
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
     * Set usersId
     *
     * @param integer $usersId
     * @return UsersTests
     */
    public function setUsersId($usersId)
    {
        $this->usersId = $usersId;

        return $this;
    }

    /**
     * Get usersId
     *
     * @return integer 
     */
    public function getUsersId()
    {
        return $this->usersId;
    }

    /**
     * Set testsId
     *
     * @param integer $testsId
     * @return UsersTests
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
     * Set isChecked
     *
     * @param boolean $isChecked
     * @return UsersTests
     */
    public function setIsChecked($isChecked)
    {
        $this->isChecked = $isChecked;

        return $this;
    }

    /**
     * Get isChecked
     *
     * @return boolean 
     */
    public function getIsChecked()
    {
        return $this->isChecked;
    }

    /**
     * Set Users
     *
     * @param Users $users
     * @return UsersTests
     */
    public function setUsers(Users $users)
    {
        $this->users = $users;
        return $this;
    }

    /**
     * Get Users
     *
     * @return Users
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set Tests
     *
     * @param Tests $tests
     * @return UsersTests
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
     * Set attempt
     *
     * @param integer $attempt
     * @return UsersTests
     */
    public function setAttempt($attempt) {
        $this->attempt = $attempt;

        return $this;
    }

    /**
     * Get attempt
     *
     * @return integer 
     */
    public function getAttempt() {
        return $this->attempt;
    }

    /**
     * Set ArrayCollection UsersTests::usersQuestionsAnswers
     *
     * @param ArrayCollection $usersQuestionsAnswers
     * @return UsersTests
     */
    public function setUsersQuestionsAnswers(ArrayCollection $usersQuestionsAnswers)
    {
        $this->usersQuestionsAnswers = $usersQuestionsAnswers;
        
        return $this;
    }

    /**
     * Get ArrayCollection UsersTests::usersQuestionsAnswers
     *
     * @return ArrayCollection UsersTests::usersQuestionsAnswers 
     */
    public function getUsersQuestionsAnswers()
    {
        return $this->usersQuestionsAnswers;
    }

    /**
     * Add UsersQuestionsAnswers
     *
     * @param UsersQuestionsAnswers $usersQuestionsAnswers
     * @return UsersTests
     */
    public function addUsersQuestionsAnswers(UsersQuestionsAnswers $usersQuestionsAnswers)
    {
        if (!$this->usersQuestionsAnswers->contains($usersQuestionsAnswers)) {
            $this->usersQuestionsAnswers->add($usersQuestionsAnswers);
        }

        return $this;
    }

    /** @ORM\PrePersist */
    public function onCreate()
    {
        $this->isChecked = false;
    }

    /** @ORM\PrePersist */
    public function prePersist(LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();
        foreach ($this->usersQuestionsAnswers as $uqa) {
            if($uqa instanceof UsersQuestionsAnswers) {
                $uqa->setUsersTests($this);
                $em->persist($uqa);
            }
        }
    }

}
