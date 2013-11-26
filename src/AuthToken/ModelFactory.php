<?php
namespace AuthToken;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ModelFactory implements FactoryInterface
{
    /**
     * {@inheritdocs}
     */
    public function createService(ServiceLocatorInterface $sl)
    {
        $documentManager  = $sl->get('doctrine.documentmanager.odm_default');

        return new Model\AuthToken($documentManager);
    }
}
