<?php

namespace Encounter\Storage\MySQL;

use Krystal\Db\Sql\AbstractMapper;

final class EncounterMapper extends AbstractMapper
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('users_ecounter');
    }

    /**
     * Returns primary column name for current mapper
     * 
     * @return string
     */
    protected function getPk()
    {
        return 'id';
    }
}
