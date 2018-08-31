<?php

namespace Alfenory\Auth\V1\Controller;

use Alfenory\Auth\V1\Lib\Returnlib;

class RoleController {
    
    protected $container;

    public function __construct($container) {
        $this->container = $container;
    }
    
    public static function get($request, $response, $args) {
        global $entityManager;
        if(UserController::has_privileg($request, $response, $args, "role.get")) {
            $role_list = $entityManager->getRepository('Alfenory\Auth\V1\Entity\Role')->findAll();
            return $response->withJson(Returnlib::get_success($role_list));
        }
        else {
            return $response->withJson(Returnlib::no_privileg());
        }
    }
    
    public static function update($request, $response, $args) {
        global $entityManager;
        if(UserController::has_privileg($request, $response, $args, "role.push")) {
            $name = $wslib->filter_string_request($request, "name");
            if($wslib->print_error_if_needed($response) === false) {
                $role = new \Alfenory\Auth\V1\Entity\Role();
                $role->setName($name);
                $entityManager->persist($role);
                $entityManager->flush();
                return $response->withJson(Returnlib::succes($role));
            } else {
                return $response->withJson(Returnlib::user_parameter_missing($wslib->error_list));
            }
        }
        else {
            return $response->withJson(Returnlib::no_privileg());
        }
    }

    public static function create($request, $response, $args) {
        global $entityManager;
        if(UserController::has_privileg($request, $response, $args, "role.post")) {
            //TODO
        }
        else {
            return $response->withJson(Returnlib::no_privileg());
        }
    }
    
    public static function delete($request, $response, $args) {
        global $entityManager;
        if(UserController::has_privileg($request, $response, $args, "role.push")) {
            //TODO
        }
        else {
            return $response->withJson(Returnlib::no_privileg());
        }
    }
}
