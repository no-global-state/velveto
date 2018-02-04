<?php

namespace Site\Storage\MySQL;

use Krystal\Db\Sql\RawSqlFragment;

final class RoomTypeGalleryMapper extends AbstractMapper
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('velveto_room_type_gallery');
    }

    /**
     * Updates a cover
     * 
     * @param int $roomTypeId
     * @param int $photoId
     * @return boolean
     */
    public function updateCover(int $roomTypeId, int $photoId)
    {
        return $this->syncWithJunction(RoomTypeCoverMapper::getTableName(), $roomTypeId, [$photoId]);
    }

    /**
     * Fetch all images associated with unique room type
     * 
     * @param int $roomTypeId
     * @return array
     */
    public function fetchAll(int $roomTypeId) : array
    {
        // Columns to be selected
        $columns = [
            self::getFullColumnName('id'),
            self::getFullColumnName('room_type_id'),
            self::getFullColumnName('file'),
            self::getFullColumnName('order'),
            new RawSqlFragment(sprintf('(%s = %s) AS cover', RoomTypeCoverMapper::getFullColumnName('master_id'), self::getFullColumnName('room_type_id')))
        ];

        return $this->db->select($columns)
                        ->from(self::getTableName())
                        // Room type relation
                        ->leftJoin(RoomTypeMapper::getTableName(), [
                            RoomTypeMapper::getFullColumnName('id') => self::getRawColumn('room_type_id')
                        ])
                        // Room type cover
                        ->leftJoin(RoomTypeCoverMapper::getTableName(), [
                            RoomTypeCoverMapper::getFullColumnName('slave_id') => self::getRawColumn('id')
                        ])
                        ->whereEquals('room_type_id', $roomTypeId)
                        ->orderBy($this->getPk())
                        ->desc()
                        ->queryAll();
    }
}
