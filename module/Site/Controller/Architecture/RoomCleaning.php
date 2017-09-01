<?php

/**
 * This file is part of the Hotelia CRM Solution
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Site\Controller\Architecture;

use Site\Controller\AbstractSiteController;
use Site\Service\CleaningCollection;
use Krystal\Stdlib\ArrayUtils;

final class RoomCleaning extends AbstractSiteController
{
    /**
     * Renders room cleaning grid
     * 
     * @return string
     */
    public function indexAction()
    {
        $this->loadApp();

        $types = ArrayUtils::arrayList($this->createMapper('\Site\Storage\MySQL\RoomTypeMapper')->fetchAll(), 'id', 'type');
        $floors = ArrayUtils::arrayList($this->createMapper('\Site\Storage\MySQL\FloorMapper')->fetchAll(), 'id', 'name');

        return $this->view->render('architecture/room-cleaning', array(
            'types' => $types,
            'floors' => $floors,
            'data' => $this->createMapper('\Site\Storage\MySQL\RoomMapper')->fetchCleaning()
        ));
    }

    /**
     * Update "cleaned" attribute
     * 
     * @param string $type
     * @return void
     */
    public function markAction($type)
    {
        $collection = new CleaningCollection();

        if ($collection->hasKey($type)) {
            $mapper = $this->createMapper('\Site\Storage\MySQL\RoomMapper');

            $ids = array_keys($this->request->getPost('batch'));

            foreach ($ids as $id) {
                $mapper->updateColumnByPk($id, 'cleaned', $type);
            }

            $this->flashBag->set('success', 'Successfully updated');
            return $this->response->redirectToPreviousPage();

        } else {
            // Invalid request
        }
    }
}