<?php

namespace Site\Storage\MySQL;

use Krystal\Date\TimeHelper;
use Krystal\Db\Sql\RawSqlFragment;
use Site\Collection\BookingStatusCollection;

final class BookingMapper extends AbstractMapper
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('velveto_bookings');
    }

    /**
     * Creates shared select
     * 
     * @param array $extra Extra columns to be selected
     * @return \Krystal\Db\Sql\Db
     */
    private function createSharedSelect(array $extra = [])
    {
        // Get current date (without time)
        $today = TimeHelper::getNow(false);

        // Columns to be selected
        $columns = array_merge([
            self::column('id'),
            self::column('hotel_id'),
            self::column('price_group_id'),
            self::column('lang_id'),
            self::column('review_id'),
            self::column('datetime'),
            self::column('status'),
            self::column('arrival'),
            self::column('departure'),
            self::column('phone'),
            self::column('email'),
            self::column('comment'),
            self::column('near_preferred'),
            self::column('amount'),
            self::column('token'),
            self::column('discount'),
            self::column('cancellation_time'),
            PriceGroupMapper::column('name') => 'price_group',
            PriceGroupMapper::column('currency'),
            new RawSqlFragment(sprintf("if ('%s' > departure AND review_id IS NULL, 1, 0) AS can_leave_review", $today))
        ], $extra);

        return $this->db->select($columns)
                        ->from(self::getTableName())
                        // Price group relation
                        ->leftJoin(PriceGroupMapper::getTableName(), [
                            self::column('price_group_id') => PriceGroupMapper::getRawColumn('id')
                        ]);
    }

    /**
     * Fetch today receivers that should get some kind of notifications
     * 
     * @return array
     */
    public function fetchTodayReceivers()
    {
        // Columns to be selected
        $columns = [
            self::column('id'),
            self::column('lang_id'),
            self::column('token'),
            self::column('email'),
            self::column('arrival'),
            self::column('departure'),
            HotelTranslationMapper::column('name') => 'hotel',
            LanguageMapper::column('code') => 'language'
        ];

        $db = $this->db->select($columns)
                       ->from(self::getTableName())
                       // Hotel translation relation
                       ->leftJoin(HotelTranslationMapper::getTableName(), [
                            HotelTranslationMapper::column('id') => self::getRawColumn('hotel_id'),
                            HotelTranslationMapper::column('lang_id') => self::getRawColumn('lang_id')
                       ])
                       // Language relation
                       ->leftJoin(LanguageMapper::getTableName(), [
                            LanguageMapper::column('id') => self::getRawColumn('lang_id')
                       ])
                       ->where(self::column('review_id'), 'IS', new RawSqlFragment('NULL'))
                       ->andWhere(sprintf('DATEDIFF(CURDATE(), %s)', self::column('departure')), '=', new RawSqlFragment(1))
                       ->andWhereNotEquals(self::column('status'), '-1');

        return $db->queryAll();
    }

    /**
     * Updates status by column and value
     * 
     * @param string $column
     * @param string $value
     * @param int $status
     * @param array $extra Extra columns to be updated
     * @return boolean Depending on success
     */
    private function updateStatusByColumn(string $column, string $value, int $status, array $extra = array()) : bool
    {
        // Columns and their values to be updated
        $data = array_merge(['status' => $status], $extra);

        // Affected row count
        $rowCount = $this->db->update(self::getTableName(), $data)
                             ->whereEquals($column, $value)
                             ->execute(true);

        return (bool) $rowCount;
    }

    /**
     * Updates review ID by attached booking ID
     * 
     * @param int $bookingId
     * @param int $reviewId
     * @return boolean
     */
    public function updateReviewId(int $bookingId, int $reviewId) : bool
    {
        return $this->updateColumnsByPk($bookingId, ['review_id' => $reviewId]);
    }

    /**
     * Inserts relation
     * 
     * @param int $bookingId
     * @param array $ids Reservation IDs
     * @return boolean
     */
    public function insertRelation(int $bookingId, array $ids) : bool
    {
        return $this->insertIntoJunction(BookingReservationRelation::getTableName(), $bookingId, $ids);
    }

    /**
     * Updates status by booking ID
     * 
     * @param int $id
     * @param int $status
     * @return boolean Depending on success
     */
    public function updateStatusById(int $id, int $status) : bool
    {
        return $this->updateStatusByColumn('id', $id, $status);
    }

    /**
     * Updates status by token
     * 
     * @param string $token
     * @param int $status
     * @param string $cancellationTime Optional cancellation time to be updated
     * @return boolean Depending on success
     */
    public function updateStatusByToken(string $token, int $status, string $cancellationTime = null) : bool
    {
        if ($cancellationTime !== null) {
            // Extra columns to be updated
            $extra = [
                self::column('cancellation_time') => $cancellationTime
            ];
        } else {
            $extra = [];
        }

        return $this->updateStatusByColumn('token', $token, $status, $extra);
    }

    /**
     * Count rows by status code
     * 
     * @param int $hotelId Attached hotel ID
     * @param int $status Status code
     * @return int
     */
    public function countByStatus($hotelId = null, int $status) : int
    {
        $db = $this->db->select()
                       ->count($this->getPk())
                       ->from(self::getTableName())
                       ->whereEquals('status', $status);

        // Append filter on demand
        if ($hotelId !== null) {
            $db->andWhereEquals('hotel_id', $hotelId);
        }

        return $db->queryScalar();
    }

    /**
     * Find booking row by its associated ID
     * 
     * @param int $id Booking ID
     * @return array
     */
    public function findById(int $id)
    {
        return $this->createSharedSelect()
                    ->whereEquals(self::column('id'), $id)
                    ->query();
    }

    /**
     * Finds only cancellation-related data by its token
     * 
     * @param string $token
     * @return array
     */
    public function findCancelationDataByToken(string $token)
    {
        // Columns to be selected
        $columns = [
            HotelMapper::column('penality_enabled'),
            HotelMapper::column('penality_not_taken_after'),
            HotelMapper::column('penality_not_later_arrival'),
            self::column('datetime'),
            self::column('arrival'),
            self::column('status'),
            self::column('cancellation_time')
        ];

        $db = $this->db->select($columns)
                       ->from(self::getTableName())
                       // Hotel relation
                       ->leftJoin(HotelMapper::getTableName(), [
                            HotelMapper::column('id') => self::getRawColumn('hotel_id')
                       ])
                       ->whereEquals(self::column('token'), $token);

        return $db->query();
    }

    /**
     * Find booking row by its associated token
     * 
     * @param string $token
     * @return array
     */
    public function findByToken(string $token)
    {
        return $this->createSharedSelect()
                    ->whereEquals(self::column('token'), $token)
                    ->query();
    }

    /**
     * Find bookings from all hotels
     * 
     * @param int $langId
     * @param int $page Current page number
     * @param int $perPageCount Per page count
     * @return array
     */
    public function findShared(int $langId, int $page, int $perPageCount) : array
    {
        $db = $this->createSharedSelect([
            HotelTranslationMapper::column('name') => 'hotel'
        ])
        // Hotel translation relation
        ->leftJoin(HotelTranslationMapper::getTableName(), [
            HotelTranslationMapper::column('id') => self::getRawColumn('hotel_id')
        ])
        ->whereEquals(HotelTranslationMapper::column('lang_id'), $langId)
        ->andWhereNotEquals(BookingMapper::column('status'), BookingStatusCollection::STATUS_TEMPORARY)
        ->orderBy(self::column($this->getPk()))
        ->desc();

        // Apply pagination
        $db->paginate($page, $perPageCount);

        return $db->queryAll();
    }

    /**
     * Find all booking rows
     * 
     * @param int $hotelId Attached hotel ID
     * @param int $page Current page number
     * @param int $perPageCount Per page count
     * @return array
     */
    public function findAll(int $hotelId, int $page, int $perPageCount) : array
    {
        $db = $this->createSharedSelect()
                   ->whereEquals('hotel_id', $hotelId)
                   ->andWhereNotEquals(BookingMapper::column('status'), BookingStatusCollection::STATUS_TEMPORARY)
                   ->orderBy(self::column($this->getPk()))
                   ->desc();

        // Apply pagination
        $db->paginate($page, $perPageCount);

        return $db->queryAll();
    }

    /**
     * Find rows by status
     * 
     * @param int $hotelId Attached hotel ID
     * @param int $status Status code
     * @return array
     */
    public function findByStatus(int $hotelId, int $status) : array
    {
        return $this->createSharedSelect()
                    ->whereEquals('status', $status)
                    ->andWhereEquals('hotel_id', $hotelId)
                    ->queryAll();
    }
}
