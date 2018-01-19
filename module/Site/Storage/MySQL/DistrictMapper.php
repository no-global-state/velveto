<?php

namespace Site\Storage\MySQL;

final class DistrictMapper extends AbstractMapper
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('velveto_regions_districts');
    }

    /**
     * {@inheritDoc}
     */
    public static function getTranslationTable()
    {
        return DistrictTranslationMapper::getTableName();
    }

    /**
     * Returns shared columns to be selected
     * 
     * @return array
     */
    private function getColumns() : array
    {
        return [
            self::getFullColumnName('id'),
            self::getFullColumnName('region_id'),
            self::getFullColumnName('order'),
            DistrictTranslationMapper::getFullColumnName('lang_id'),
            DistrictTranslationMapper::getFullColumnName('name'),
        ];
    }

    /**
     * Fetch district by its ID
     * 
     * @param int $id District ID
     * @param int $langId Language ID filter
     * @return array
     */
    public function fetchById(int $id, int $langId = 0)
    {
        return $this->findEntity($this->getColumns(), $id, $langId);
    }

    /**
     * Fetch all districts
     * 
     * @param int $langId Language ID filter
     * @return array
     */
    public function fetchAll(int $langId) : array
    {
        return $this->createEntitySelect($this->getColumns())
                    ->whereEquals(DistrictTranslationMapper::getFullColumnName('lang_id'), $langId)
                    ->orderBy($this->getPk())
                    ->desc()
                    ->queryAll();
    }
}