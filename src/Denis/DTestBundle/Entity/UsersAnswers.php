<?php

namespace Denis\DTestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * UsersAnswers
 *
 * @ORM\Table(name="users_answers")
 * @ORM\Entity
 */
class UsersAnswers
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_valid", type="boolean", options={"default":0})
     * @Assert\Type(type="boolean", message="The value {{ value }} is not a valid {{ type }}.")
     * @Assert\NotBlank()
     */
    private $isValid;

    /**
     * @var integer
     *
     * @ORM\Column(name="answers_id", type="integer")
     * @Assert\Type(type="integer", message="The value {{ value }} is not a valid {{ type }}.")
     */
    private $answersId;

    /**
     * @var Answers
     *
     * @ORM\ManyToOne(targetEntity="Denis\DTestBundle\Entity\Answers", inversedBy="usersAnswers")
     * @ORM\JoinColumn(name="answers_id", referencedColumnName="id")
     */
    private $answers;

    /**
     * @var integer
     *
     * @ORM\Column(name="users_questions_answers_id", type="integer")
     * @Assert\Type(type="integer", message="The value {{ value }} is not a valid {{ type }}.")
     */
    private $usersQuestionsAnswersId;

    /**
     * @var UsersQuestionsAnswers
     *
     * @ORM\ManyToOne(targetEntity="Denis\DTestBundle\Entity\UsersQuestionsAnswers", inversedBy="usersAnswers")
     * @ORM\JoinColumn(name="users_questions_answers_id", referencedColumnName="id")
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
     * Set isValid
     *
     * @param boolean $isValid
     * @return UsersAnswers
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
     * Set answersId
     *
     * @param integer $answersId
     * @return UsersAnswers
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
     * Set Answers
     *
     * @param Answers $answers
     * @return UsersAnswers
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
     * Set usersQuestionsAnswersId
     *
     * @param integer $usersQuestionsAnswersId
     * @return UsersAnswers
     */
    public function setUsersQuestionsAnswersId($usersQuestionsAnswersId) {
        $this->usersQuestionsAnswersId = $usersQuestionsAnswersId;

        return $this;
    }

    /**
     * Get usersQuestionsAnswersId
     *
     * @return integer 
     */
    public function getUsersQuestionsAnswersId() {
        return $this->usersQuestionsAnswersId;
    }

    /**
     * Set UsersQuestionsAnswers
     *
     * @param UsersQuestionsAnswers $usersQuestionsAnswers
     * @return UsersAnswers
     */
    public function setUsersQuestionsAnswers(UsersQuestionsAnswers $usersQuestionsAnswers)
    {
        $this->usersQuestionsAnswers = $usersQuestionsAnswers;

        return $this;
    }

    /**
     * Get UsersQuestionsAnswers
     *
     * @return UsersQuestionsAnswers
     */
    public function getUsersQuestionsAnswers()
    {
        return $this->usersQuestionsAnswers;
    }
    
}
