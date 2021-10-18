<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Component\ApiHelper;
use App\Controller\Component\ResponseBuilder;


class FlyersController extends AppController {
    private ApiHelper $helper;
    private ResponseBuilder $responseBuilder;

    private string $today;
    private array $filters_allowed = array('category', 'is_published');

    public function initialize(): void {
        parent::initialize();
        $this->today = date("Y-m-d");
        $this->helper = new APIHelper();
        $this->responseBuilder = new ResponseBuilder($this->response);
    }

    public function index() {
        $page_query = $this->request->getQuery('page');
        $limit_query = $this->request->getQuery('limit');
        $fields_query = $this->request->getQuery('fields', '');
        $filters_query = $this->request->getQuery('filter', '');
        $page = $this->helper->checkValue($page_query, 1);
        $limit = $this->helper->checkValue($limit_query, 100);

        $file = fopen(ROOT . "/resources/flyers_data.csv", "r");
        $keys = fgetcsv($file, null, ",");

        if ($fields_query !== '') {
            $check_fields = $this->helper->checkFields(explode(',', $fields_query), $keys);
            if ($check_fields !== '') {
                $debug = 'Not allowed fields: ' . $check_fields;
                return $this->responseBuilder->returnError400($debug);
            }
        }

        if ($filters_query !== '') {
            $check_filters = $this->helper->checkFields(array_keys($filters_query), $this->filters_allowed);
            if ($check_filters !== '') {
                $debug = 'Not allowed filters: ' . $check_filters;
                return $this->responseBuilder->returnError400($debug);
            }
        }

        $results = array();
        while (($line = fgetcsv($file, null, ",")) !== FALSE) {
            if ($line[2] <= $this->today && $this->today <= $line[3]) {
                $results[] = array_combine($keys, $line);
            }
        }
        fclose($file);

        $this->helper->setArray($results);
        if ($filters_query !== '') {
            $this->helper->applyFilters($filters_query, $keys);
        }
        if ($fields_query !== '') {
            $this->helper->applyFields(explode(',', $fields_query), $keys);
        }
        $this->helper->paginate($page, $limit);
        $results = $this->helper->getArray();

        if (count($results) === 0) {
            $debug = 'Empty set';
            return $this->responseBuilder->returnError404($debug);
        }

        return $this->responseBuilder->returnSuccessList($results);
    }

    public function view($id)
    {
        $fields_query = $this->request->getQuery('fields', '');

        $file = fopen(ROOT . "/resources/flyers_data.csv", "r");
        $keys = fgetcsv($file, null, ",");

        if ($fields_query !== '') {
            $check_fields = $this->helper->checkFields(explode(',', $fields_query), $keys);
            if ($check_fields !== '') {
                $debug = 'Not allowed fields: ' . $check_fields;
                return $this->responseBuilder->returnError400($debug);
            }
        }

        $results = [];
        while (($line = fgetcsv($file, null, ",")) !== FALSE) {
            if ($line[0] == $id) {
                $results[] = array_combine($keys, $line);
                break;
            }
        }
        fclose($file);

        if (count($results) === 0) {
            $debug = 'Resource: ' . $id . ' not found';
            return $this->responseBuilder->returnError404($debug);
        }

        $this->helper->setArray($results);
        if ($fields_query !== '') {
            $this->helper->applyFields(explode(',', $fields_query), $keys);
        }
        $results = $this->helper->getArray()[0];

        return $this->responseBuilder->returnSuccess($results);
    }
}
