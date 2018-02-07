<?php

namespace Site\Storage\MySQL;

use Krystal\Db\Sql\RawSqlFragment;

final class FacilitiyItemMapper extends AbstractMapper
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('velveto_facilitiy_items');
    }

    /**
     * {@inheritDoc}
     */
    public static function getTranslationTable()
    {
        return FacilitiyItemTranslationMapper::getTableName();
    }

    /**
     * {@inheritDoc}
     */
    public static function getJunctionTableName()
    {
        return FacilityRelationMapper::getTableName();
    }

    /**
     * Returns shared columns 
     * 
     * @return array
     */
    private function getColumns() : array
    {
        return [
            self::getFullColumnName('id'),
            self::getFullColumnName('icon'),
            self::getFullColumnName('front'),
            self::getFullColumnName('always_free'),
            self::getFullColumnName('category_id'),
            FacilitiyItemTranslationMapper::getFullColumnName('name'),
            FacilitiyItemTranslationMapper::getFullColumnName('lang_id'),
        ];
    }

    /**
     * Fetch item by its ID
     * 
     * @param int $id Category ID
     * @param int $langId Language ID filter
     * @return array
     */
    public function fetchById(int $id, int $langId = 0) : array
    {
        return $this->findEntity($this->getColumns(), $id, $langId);
    }

    /**
     * Updates a relation
     * 
     * @param string $hotelId
     * @param array $data
     * @return boolean
     */
    public function updateRelation(int $hotelId, array $data)
    {
        // Remove all related items
        $this->removeFromJunction(self::getJunctionTableName(), $hotelId);

        return $this->db->insertMany(self::getJunctionTableName(), ['master_id', 'slave_id', 'type'], $data)->execute();
    }

    /**
     * Find all items attached to particular category
     * 
     * @param int $langId Language ID filter
     * @param integer $categoryId Optional category ID filter
     * @param integer $hotelId Optional hotel ID filter
     * @param bool $front Whether to fetch only front items
     * @return array
     */
    public function fetchAll(int $langId, $categoryId = null, $hotelId = null, $front = false)
    {
        // Columns to be selected
        $columns = array_merge($this->getColumns(), [
            new RawSqlFragment(sprintf('(slave_id = %s.id) AS checked', self::getTableName())),
            self::getFullColumnName('type', self::getJunctionTableName()),
        ]);

        $db = $this->db->select($columns)
                       ->from(self::getTableName())
                       // Translation relation
                       ->leftJoin(FacilitiyItemTranslationMapper::getTableName())
                       ->on()
                       ->equals(
                            FacilitiyItemTranslationMapper::getFullColumnName('id'),
                            self::getRawColumn('id')
                       )
                       // Junction relation
                       ->leftJoin(self::getJunctionTableName())
                       ->on()
                       ->equals(
                            self::getFullColumnName('slave_id', self::getJunctionTableName()),
                            self::getRawColumn('id')
                       )
                       ->rawAnd()
                       ->equals(
                            self::getFullColumnName('master_id', self::getJunctionTableName()),
                            $hotelId
                       )
                       // Language ID filter
                       ->whereEquals(FacilitiyItemTranslationMapper::getFullColumnName('lang_id'), $langId);

        if ($categoryId !== null) {
            $db->andWhereEquals(self::getFullColumnName('category_id'), $categoryId);
        }

        if ($front === true) {
            $db->andWhereEquals(self::getFullColumnName('front'), '1');
        }

        return $db->queryAll();
    }
}
