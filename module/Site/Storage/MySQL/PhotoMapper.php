<?php

namespace Site\Storage\MySQL;

use Krystal\Db\Sql\RawSqlFragment;

final class PhotoMapper extends AbstractMapper
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('velveto_hotels_photos');
    }

    /**
     * Updates a cover
     * 
     * @param int $hotelId
     * @param int $photoId
     * @return boolean
     */
    public function updateCover(int $hotelId, int $photoId)
    {
        return $this->syncWithJunction(PhotoCoverMapper::getTableName(), $hotelId, [$photoId]);
    }

    /**
     * Fetch all photos
     * 
     * @param string $hotelId
     * @param string $limit Optional limit
     * @return array
     */
    public function fetchAll($hotelId, $limit = null) : array
    {
        // Columns to be selected
        $columns = [
            self::column('id'),
            self::column('hotel_id'),
            self::column('file'),
            self::column('order'),
            new RawSqlFragment(sprintf('(%s.id = %s.slave_id) as cover', self::getTableName(), PhotoCoverMapper::getTableName()))
        ];

        $db = $this->db->select($columns)
                        ->from(self::getTableName())
                        // Photo cover relation
                        ->leftJoin(PhotoCoverMapper::getTableName(), [
                            self::column('id') => PhotoCoverMapper::getRawColumn('slave_id')
                        ])
                        ->whereEquals('hotel_id', $hotelId);

        // Use limit if provided
        if (is_integer($limit)) {
            $db->limit($limit);
        }

        return $db->queryAll();
    }
}
