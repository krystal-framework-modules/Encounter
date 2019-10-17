<?php

namespace Encounter\Service;

use Encounter\Storage\MySQL\EncounterMapper;

final class EncounterService
{
    /**
     * Encounter mapper
     * 
     * @var \Encounter\Storage\MySQL\EncounterMapper
     */
    private $encounterMapper;

    /**
     * State initialization
     * 
     * @param \Encounter\Storage\MySQL\EncounterMapper $encounterMapper
     * @return void
     */
    public function __construct(EncounterMapper $encounterMapper)
    {
        $this->encounterMapper = $encounterMapper;
    }
}
