<?php

namespace Site\Controller;

use Krystal\Iso\ISO3166\Country;
use Krystal\Validate\Pattern;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Stdlib\ArrayUtils;
use Krystal\Db\Filter\InputDecorator;
use Krystal\Db\Filter\FilterInvoker;
use Site\Service\ReservationCollection;
use Site\Service\PurposeCollection;
use Site\Service\PaymentTypeCollection;
use Site\Service\LegalStatusCollection;

class Reservation extends AbstractSiteController
{
    /**
     * Creates a form
     * 
     * @param \Krystal\Db\Filter\InputDecorator|array $client
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
            // Collections
            'states' => (new ReservationCollection)->getAll(),
            'purposes' => (new PurposeCollection)->getAll(),
            'paymentTypes' => (new PaymentTypeCollection)->getAll(),
            'legalStatuses' => (new LegalStatusCollection)->getAll(),
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
        $route = '/reservation/index/';

        $mapper = $this->createMapper('\Site\Storage\MySQL\ReservationMapper');
        $countries = new Country();

        $invoker = new FilterInvoker($this->request->getQuery(), $route);
        $data = $invoker->invoke($mapper, 20);

        $paginator = $mapper->getPaginator();

        return $this->view->render('reservation/index', array(
            'route' => $route,
            'data' => $data,
            'paginator' => $paginator,
            'countries' => $countries->getAll(),
            'rooms' => $this->createRooms(),
            'reservationCollection' => new ReservationCollection
        ));
    }

    /**
     * Deletes a reservation
     * 
     * @param string $id
     * @return string
     */
    public function deleteAction($id)
    {
        $mapper = $this->createMapper('\Site\Storage\MySQL\ReservationMapper');
        $mapper->deleteById($id);

        $this->flashBag->set('danger', 'Selected reservation has been removed successfully');

        return $this->redirectToRoute('Site:Reservation@indexAction');
    }

    /**
     * Renders adding form
     * 
     * @return string
     */
    public function addAction()
    {
        $entity = new InputDecorator();
        $entity['legal_status'] = 1;

        return $this->createForm($entity);
    }

    /**
     * Edits a reservation
     * 
     * @param string $id
     * @return string
     */
    public function editAction($id)
    {
        $mapper = $this->createMapper('\Site\Storage\MySQL\ReservationMapper');
        $entity = $mapper->fetchById($id);

        if ($entity) {
            // Append IDs
            $entity['service_ids'] = array_column($entity['services'], 'id');

            return $this->createForm($entity);
        } else {
            return false;
        }
    }

    /**
     * Saves a reservation
     * 
     * @return string
     */
    public function saveAction()
    {
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

            if (!empty($data['id'])) {
                $mapper->update($data);
            } else {
                $mapper->insert($data);
            }

            $this->flashBag->set('success', 'Your request has been sent!');
            return '1';
        } else {
            return $formValidator->getErrors();
        }
    }
}
