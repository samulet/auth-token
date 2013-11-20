<?php
namespace AuthToken\Entity;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Gedmo\Mapping\Annotation as Gedmo;
use User\Entity\User;

/**
 * @ODM\Document(collection="auth_token")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class AuthToken
{
    /**
     * @ODM\Id
     * @var string
     */
    protected $id;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $token;

    /**
     * @var string
     * @ODM\ReferenceOne(targetDocument="User\Entity\User", simple=true)
     */
    protected $user;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ODM\Date
     */
    protected $createdAt;

    /**
     * @var \DateTime
     * @ODM\Date
     */
    protected $deletedAt;

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
     * Set id.
     *
     * @param string $id
     *
     * @return AuthToken
     */
    public function setId($id)
    {
        $this->id = (int) $id;
        return $this;
    }

    /**
     * @param \DateTime $created
     *
     * @return AuthToken
     */
    public function setCreatedAt($created)
    {
        $this->createdAt = $created;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $deletedAt
     *
     * @return AuthToken
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param string $token
     *
     * @return AuthToken
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param User $user
     *
     * @return AuthToken
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
