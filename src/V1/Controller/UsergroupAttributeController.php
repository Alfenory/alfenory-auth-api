<?php


namespace Alfenory\Auth\V1\Controller;

class UsergroupAttributeController {
    
    protected $container;

    public function __construct($container) {
        $this->container = $container;
    }
    
    public static function get($request, $response) {
        $data = array('info' => 'running');
        return $response->withJson($data);
    }
    
    public static function update($request, $response) {
        return $response;
    }
    
    public static function delete($request, $response) {
        return $response;
    }
    
}  