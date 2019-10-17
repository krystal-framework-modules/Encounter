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
