<?php

namespace App\plugins;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class RestResponse
 * @package App\plugins
 *
 * Simple class for rest response;
 *
 * Example;
 * new RestResponse($data, 200);
 */
class RestResponse {

    private $response;
    private $structure;

    public function __construct($data, $status = 200, $message = "", $errors = null)
    {
        $this->structure = array(
            "errors" => $errors,
            "code" => $status,
            "message" => ($message != "") ? $message : null,
            "data" => $data,
        );
        $this->response = new Response();
        $this->setHeaders($status);
        $this->sendResponse();
    }

    public function setHeaders($status = 200){
        $this->response->headers->set('content-type', 'application/json');
        $this->response->setStatusCode($status);
    }

    public function sendResponse(){
        $this->response->setContent(json_encode($this->structure))->send();
    }
}