<?php

namespace Alfenory\Auth\V1\Controller;

class RoleController {
    
    protected $container;

    public function __construct($container) {
        $this->container = $container;
    }
    
    public static function get($request, $response, $args) {
        return $response->withJson(array());
    }
    
    public static function update($request, $response, $args) {
        return $response;
    }
    
    public static function delete($request, $response, $args) {
        return $response;
    }
}
