<?php

namespace Site\Storage\MySQL;

final class FacilityItemDataTranslationMapper extends AbstractMapper
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('velveto_facility_items_data_translation');
    }
}
