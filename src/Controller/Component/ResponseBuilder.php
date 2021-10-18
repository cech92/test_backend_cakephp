<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Http\Response;


class ResponseBuilder {
    private Response $response;

    public function __construct($response) {
        $this->response = $response;
    }

    public function returnSuccessList($results): Response {
        $data = array(
            "success" => true,
            "code" => 200,
            "results" => $results
        );
        return $this->response->withStatus(200)->withType('application/json')->withStringBody(json_encode($data));
    }

    public function returnSuccess($results): Response {
        $response = array(
            "success" => true,
            "code" => 200,
            "results" => $results
        );
        return $this->response->withStatus(200)->withType('application/json')->withStringBody(json_encode($response));
    }

    public function returnError400($debug): Response {
        $response = array(
            "success" => false,
            "code" => 400,
            "error" => array(
                "message" => 'Bad request',
                "debug" => $debug
            )
        );
        return $this->response->withStatus(400)->withType('application/json')->withStringBody(json_encode($response));
    }

    public function returnError404($debug): Response {
        $response = array(
            "success" => false,
            "code" => 404,
            "error" => array(
                "message" => 'Not found',
                "debug" => $debug
            )
        );
        return $this->response->withStatus(404)->withType('application/json')->withStringBody(json_encode($response));
    }

}

