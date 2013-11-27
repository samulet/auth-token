<?php
namespace AuthToken\Model;

use AuthToken\Entity\AuthToken as AuthTokenEntity;
use Doctrine\ODM\MongoDB\DocumentManager;
use User\Entity\User;
use Zend\Crypt\Hmac;

class AuthToken
{
    /**
     * @var \Doctrine\ODM\MongoDB\DocumentManager
     */
    protected $documentManager;

    /**
     * @param DocumentManager $documentManager
     */
    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    /**
     * Создает и сохраняет в хранилище новый токен
     *
     * @param \User\Entity\User $user
     *
     * @return AuthToken
     */
    public function create(User $user)
    {
        $entity = new AuthTokenEntity();
        $entity->setUser($user);
        $entity->setToken(Hmac::compute(uniqid(rand(), true), 'sha256', $user->getEmail()));

        $this->documentManager->persist($entity);
        $this->documentManager->flush();

        return $entity;
    }

    /**
     * Возвращает сущность по символьному представлению токена или сущности пользователя
     *
     * @param string|\User\Entity\User $value Искомое значение. Может быть токеном либо User-entity
     *
     * @return \AuthToken\Entity\AuthToken|null
     */
    public function fetch($value)
    {
        $builder = $this->documentManager->createQueryBuilder('AuthToken\Entity\AuthToken');
        if ($value instanceof User) {
            $builder->field('user')->equals($value->getId());
        } else {
            $builder->field('token')->equals($value);
        }
        return $builder->field('deletedAt')->equals(null)->getQuery()->getSingleResult();
    }

    /**
     * Помечает токен "протухшим"
     *
     * @param string $token
     *
     * @throws \RuntimeException
     */
    public function expire($token)
    {
        $entity = $this->fetch($token);
        if (null === $entity) {
            throw new \RuntimeException(sprintf('Token "%s" not found', $token));
        }
        $this->documentManager->remove($entity);
        $this->documentManager->flush();
    }
}
