<?php

namespace Site\Storage\MySQL;

use Krystal\Db\Sql\AbstractMapper;
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
    public static function getJunctionTableName()
    {
        return self::getWithPrefix('velveto_facilitiy_relations');
    }

    /**
     * {@inheritDoc}
     */
    protected function getPk()
    {
        return 'id';
    }

    /**
     * Updates a relation
     * 
     * @param string $hotelId
     * @param array $ids
     * @return boolean
     */
    public function updateRelation($hotelId, array $ids)
    {
        // Synchronize relations if provided
        if (!empty($ids)) {
            return $this->syncWithJunction(self::getJunctionTableName(), $hotelId, $ids);
        } else {
            return $this->removeFromJunction(self::getJunctionTableName(), $hotelId);
        }
    }

    /**
     * Find all items attached to particular category
     * 
     * @param integer $categoryId Optional category ID filter
     * @return array
     */
    public function fetchAll($categoryId = null)
    {
        // Columns to be selected
        $columns = array(
            self::getFullColumnName('id'),
            self::getFullColumnName('category_id'),
            self::getFullColumnName('name'),
            new RawSqlFragment('(slave_id = id) AS checked')
        );

        $db = $this->db->select($columns)
                       ->from(self::getTableName())
                       // Junction relation
                       ->leftJoin(self::getJunctionTableName())
                       ->on()
                       ->equals(
                            self::getFullColumnName('slave_id', self::getJunctionTableName()),
                            self::getRawColumn('id')
                       );

        if ($categoryId !== null) {
            $db->whereEquals('category_id', $categoryId);
        }

        return $db->queryAll();
    }
}