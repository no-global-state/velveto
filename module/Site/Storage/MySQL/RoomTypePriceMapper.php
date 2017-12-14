<?php

namespace Site\Storage\MySQL;

use Krystal\Db\Sql\AbstractMapper;

final class RoomTypePriceMapper extends AbstractMapper
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('velveto_floor_room_type_prices');
    }

    /**
     * {@inheritDoc}
     */
    protected function getPk()
    {
        return 'id';
    }

    /**
     * Adds room type price
     * 
     * @param int $roomTypeId
     * @param array $priceGroupIds
     * @return boolean
     */
    public function add(int $roomTypeId, array $priceGroupIds)
    {
        foreach ($priceGroupIds as $priceGroupId => $price) {
            $this->insert($roomTypeId, $priceGroupId, $price);
        }

        return true;
    }

    /**
     * Adds new item
     * 
     * @param int $roomTypeId
     * @param int $priceGroupId
     * @param float $price
     * @return boolean
     */
    private function insert(int $roomTypeId, int $priceGroupId, float $price)
    {
        $data = [
            'room_type_id' => $roomTypeId,
            'price_group_id' => $priceGroupId,
            'price' => $price
        ];

        return $this->persist($data);
    }
}
