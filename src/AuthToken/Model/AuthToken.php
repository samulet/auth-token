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
     * Возвращает сущность по символьному представлению токена
     *
     * @param string $token
     *
     * @return \AuthToken\Entity\AuthToken|null
     */
    public function fetch($token)
    {
        return $this->documentManager
                    ->createQueryBuilder('AuthToken\Entity\AuthToken')
                    ->field('token')->equals($token)
                    ->field('deletedAt')->equals(null)
                    ->getQuery()
                    ->getSingleResult();
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
