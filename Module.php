<?php

namespace Encounter;

use Krystal\Application\Module\AbstractModule;
use Encounter\Service\EncounterService;

final class Module extends AbstractModule
{
    /**
     * Returns routes of this module
     * 
     * @return array
     */
    public function getRoutes()
    {
        return array(
            '/profile/encounter' => array(
                'controller' => 'Encounter@indexAction'
            ),

            '/profile/encounter/like/(:var)' => array(
                'controller' => 'Encounter@likeAction'
            ),

            '/profile/encounter/dislike/(:var)' => array(
                'controller' => 'Encounter@dislikeAction'
            ),
            
            '/profile/encounter/my-likes' => array(
                'controller' => 'Encounter@myLikesAction'
            ),

            '/profile/encounter/their-likes' => array(
                'controller' => 'Encounter@theirLikesAction'
            ),
        );
    }

    /**
     * Returns prepared service instances of this module
     * 
     * @return array
     */
    public function getServiceProviders()
    {
        return array(
            'encounterService' => new EncounterService($this->createMapper('\Encounter\Storage\MySQL\EncounterMapper'))
        );
    }
}
