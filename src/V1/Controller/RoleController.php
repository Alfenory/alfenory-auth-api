<?php

namespace Alfenory\Auth\V1\Controller;

class RoleController {
    
    protected $container;

    public function __construct($container) {
        $this->container = $container;
    }
    
    public static function get($request, $response, $args) {
        global $entityManager;
        if(UserController::has_privileg($request, $response, $args, "role.get")) {
            $role_list = $entityManager->getRepository('Alfenory\Auth\V1\Entity')->findBy();
            return $response->withJson(Returnlib::get_success($role_list));
        }
        else {
            return $response->withJson(Returnlib::no_privileg());
        }
    }
    
    public static function update($request, $response, $args) {
        return $response;
    }

    public static function create($request, $response, $args) {
        return $response;
    }
    
    public static function delete($request, $response, $args) {
        return $response;
    }
}
