<?php

namespace Site\Storage\MySQL;

use Krystal\Db\Filter\FilterableServiceInterface;

final class HotelMapper extends AbstractMapper implements FilterableServiceInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('velveto_hotels');
    }

    /**
     * {@inheritDoc}
     */
    public static function getTranslationTable()
    {
        return HotelTranslationMapper::getTableName();
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
            self::getFullColumnName('phone'),
            self::getFullColumnName('currency'),
            self::getFullColumnName('start_price'),
            self::getFullColumnName('rate'),
            self::getFullColumnName('discount'),
            self::getFullColumnName('daily_tax'),
            self::getFullColumnName('website'),
            self::getFullColumnName('email'),
            HotelTranslationMapper::getFullColumnName('lang_id'),
            HotelTranslationMapper::getFullColumnName('city'),
            HotelTranslationMapper::getFullColumnName('name'),
            HotelTranslationMapper::getFullColumnName('address'),
            HotelTranslationMapper::getFullColumnName('description')
        ];
    }

    /**
     * Fetch hotel by its ID
     * 
     * @param int $id Hotel ID
     * @param int $langId Language ID filter
     * @return array
     */
    public function fetchById(int $id, int $langId = 0) : array
    {
        return $this->findEntity($this->getColumns(), $id, $langId);
    }

    /**
     * {@inheritDoc}
     */
    public function filter($input, $page, $itemsPerPage, $sortingColumn, $desc, array $parameters = array())
    {
        $db = $this->createEntitySelect($this->getColumns())
                   // Language ID constraint
                   ->whereEquals(HotelTranslationMapper::getFullColumnName('lang_id'), $parameters['lang_id'])
                   ->andWhereLike('name', '%'.$input['name'].'%', true)
                   ->andWhereLike('city', '%'.$input['city'].'%', true)
                   ->andWhereLike('address', '%'.$input['address'].'%', true)
                   ->andWhereLike('phone', '%'.$input['phone'].'%', true)
                   ->andWhereLike('website', '%'.$input['website'].'%', true)
                   ->andWhereEquals('rate', $input['rate'], true);

        $db->orderBy($sortingColumn ? self::getFullColumnName($sortingColumn) : self::getFullColumnName('id'));

        if ($desc) {
            $db->desc();
        }

        return $db->paginate($page, $itemsPerPage)
                  ->queryAll();
    }

    /**
     * Fetch all hotels
     * 
     * @param int $langId
     * @return array
     */
    public function fetchAll(int $langId) : array
    {
        return $this->createEntitySelect($this->getColumns())
                    // Language ID filter
                    ->whereEquals(HotelTranslationMapper::getFullColumnName('lang_id'), $langId)
                    ->orderBy($this->getPk())
                    ->desc()
                    ->queryAll();
    }
}
