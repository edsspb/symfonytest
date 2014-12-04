<?php

namespace Denis\DTestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Users
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Denis\DTestBundle\Repository\UsersRepository")
 */
class Users implements UserInterface, \Serializable {
    
    public function __construct()
    {
        $this->usersQuestionsAnswers = new ArrayCollection();
        $this->usersTests = new ArrayCollection();
        $this->roles = new ArrayCollection();
        $this->isActive = true;
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
     * @ORM\Column(name="username", type="string", length=255)
     * @Assert\NotBlank(groups={"registration"})
     * @Assert\Length(max = 255, groups={"registration"})
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\NotBlank(groups={"registration", "authentication"})
     * @Assert\Email(groups={"registration", "authentication"}, message="The email '{{ value }}' is not a valid email.")
     * @Assert\Length(max = 255, groups={"registration", "authentication"})
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     * @Assert\NotBlank(groups={"registration"})
     * @Assert\Length(max = 255, groups={"registration"})
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(max = 255)
     */
    private $salt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var UsersTests
     *
     * @ORM\OneToMany(targetEntity="Denis\DTestBundle\Entity\UsersTests", mappedBy="users")
     */
    private $usersTests;

    /**
     * @var Roles
     *
     * @ORM\ManyToMany(targetEntity="Denis\DTestBundle\Entity\Roles")
     * @ORM\JoinTable(name="users_roles",
     *      joinColumns={@ORM\JoinColumn(name="users_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="roles_id", referencedColumnName="id")}
     *      )
     */
    private $roles;

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
     * Set username
     *
     * @param string $username
     * @return Users
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

    /**
     * Set email
     *
     * @param string $email
     * @return Users
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Users
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Users
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set isActive
     *
     * @param string $isActive
     * @return Users
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return string 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set ArrayCollection UsersTests
     *
     * @param ArrayCollection $usersTests
     * @return Users
     */
    public function setUsersTests(ArrayCollection $usersTests)
    {
        $this->usersTests = $usersTests;
        
        return $this;
    }

    /**
     * Get ArrayCollection Users::usersTests
     *
     * @return ArrayCollection Users::usersTests 
     */
    public function getUsersTests()
    {
        return $this->usersTests;
    }

    /**
     * Get ArrayCollection Users::roles
     *
     * @return ArrayCollection Users::roles
     */
    public function getRoles()
    {
        return $this->roles->toArray();
    }

    /**
     * Set Roles
     *
     * @param ArrayCollection $roles
     * @return Users
     */
    public function setRoles(ArrayCollection $roles)
    {
        $this->roles = $roles;
        
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            $this->salt,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            $this->salt
        ) = unserialize($serialized);
    }
}