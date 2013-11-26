<?php
namespace AuthToken;

return array(
    'service_manager' => array(
        'invokables' => array(
            'AuthToken\Entity\AuthToken' => 'AuthToken\Entity\AuthToken',
        ),
        'factories' => array(
            'AuthToken\Model\AuthToken' => 'AuthToken\ModelFactory',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'odm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    ),
);
