<?php

namespace Alfenory\Auth\V1\Controller;

use Alfenory\Auth\V1\Lib\Returnlib;
use Alfenory\Auth\V1\Lib\Webservicelib;

class SettingController {
    
    protected $container;

    public function __construct($container) {
        $this->container = $container;
    }
    
    public static function get($request, $response, $args) {
        $data = array('name' => 'Bob', 'age' => 40);
        return $response->withJson($data);
    }
    
    public static function update($request, $response, $args) {
        return $response;
    }
    
    public static function delete($request, $response, $args) {
        return $response;
    }
    
    
}

