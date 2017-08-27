<?php

namespace Site\Controller;

use Krystal\Iso\ISO3166\Country;
use Krystal\Validate\Pattern;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Stdlib\ArrayUtils;

class Reservation extends AbstractSiteController
{
	/**
	 * Creates a form
	 * 
	 * @param \Krystal\Stdlib\VirtualEntity $client
	 * @return string
	 */
	private function createForm($client)
	{
		// Load view plugins
		$this->view->getPluginBag()
				   ->load(array('chosen', 'datetimepicker'));

		$this->loadApp();

		$countries = new Country();
		$statuses = array(
			'r' => 'Regular',
			'v' => 'VIP'
		);

		return $this->view->render('reservation/form', array(
			'client' => $client,
			'countries' => $countries->getAll(),
			'statuses' => $statuses,
			'services' => ArrayUtils::arrayList($this->createMapper('\Site\Storage\MySQL\RoomServiceMapper')->fetchAll(), 'id', 'name'),
            'rooms' => $this->createRooms(),
			'genders' => array(
				'M' => 'Male',
				'F' => 'Female'
			)
		));
	}

	/**
	 * @return array
	 */
	private function createTable()
	{
		$output = array();

		$roomMapper = $this->createMapper('\Site\Storage\MySQL\RoomMapper');
		$floorMapper = $this->createMapper('\Site\Storage\MySQL\FloorMapper');

		foreach ($floorMapper->fetchAll() as $floor) {
			$floor['rooms'] = $roomMapper->fetchAll($floor['id']);
			$output[] = $floor;
		}

		return $output;
	}

    /**
     * Create rooms
     * 
     * @return array
     */
    private function createRooms()
    {
        $output = array();
        $rows = $this->createTable();

        foreach ($rows as $row) {
            $output[$row['name']] = ArrayUtils::arrayList($row['rooms'], 'id', 'name');
        }

        return $output;
    }

	/**
	 * Renders the table
	 * 
	 * @return string
	 */
	public function tableAction()
	{
		return $this->view->render('reservation/table', array(
			'table' => $this->createTable()
		));
	}

	/**
	 * Renders main grid
	 * 
	 * @return string
	 */
	public function indexAction()
	{
		$mapper = $this->createMapper('\Site\Storage\MySQL\ReservationMapper');
		$countries = new Country();

		return $this->view->render('reservation/index', array(
			'data' => $mapper->fetchAll(),
			'countries' => $countries->getAll(),
            'rooms' => $this->createRooms()
		));
	}

	/**
	 * Default action
	 * 
	 * @return string
	 */
	public function addAction()
	{
		if ($this->request->isPost()) {
			$data = $this->request->getPost();

			$formValidator = $this->createValidator(array(
				'input' => array(
					'source' => $data,
					'definition' => array(
						'full_name' => new Pattern\Name(),
					)
				)
			));

			if ($formValidator->isValid()) {
				$mapper = $this->createMapper('\Site\Storage\MySQL\ReservationMapper');
                
                if (!empty($data['id'])){
                    $mapper->update($data);
                } else {
                    $mapper->insert($data);
                }

				$this->flashBag->set('success', 'Your request has been sent!');
				return '1';
			} else {
				return $formValidator->getErrors();
			}
		} else {
			$client = new VirtualEntity;
			return $this->createForm($client);
		}
	}
}
