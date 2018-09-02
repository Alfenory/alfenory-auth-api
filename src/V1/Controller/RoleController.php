<?php

namespace Alfenory\Auth\V1\Controller;

use Alfenory\Auth\V1\Lib\Returnlib;
use Alfenory\Auth\V1\Lib\Webservicelib;

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
        error_log("update");
        if(UserController::has_privileg($request, $response, $args, "role.put")) {
            $route = $request->getAttribute('route');
            $id = $route->getArgument('role_id');
            $name = $request->getParam('name');
            $role_list = $entityManager->getRepository('Alfenory\Auth\V1\Entity\Role')->findBy(array('id' => $id));
            if(count($role_list) === 1) {
                $role = $role_list[0];
                $role->setName($name);
                $entityManager->persist($role);
                $entityManager->flush();
                return $response->withJson(Returnlib::get_success());
            }
            else {
                return $response->withJson(Returnlib::no_privileg());
            }
        }
        else {
            return $response->withJson(Returnlib::no_privileg());
        }
    }

    public static function create($request, $response, $args) {
        global $entityManager;
        if(UserController::has_privileg($request, $response, $args, "role.post")) {
            $wslib = new Webservicelib();
            $name = $wslib->filter_string_request($request, "name");
            if($wslib->print_error_if_needed($response) === false) {
                $role = new \Alfenory\Auth\V1\Entity\Role();
                $role->setName($name);
                $entityManager->persist($role);
                $entityManager->flush();
                return $response->withJson(Returnlib::get_success());
            } else {
                return $response->withJson(Returnlib::user_parameter_missing($wslib->error_list));
            }
        }
        else {
            return $response->withJson(Returnlib::no_privileg());
        }
    }
    
    public static function delete($request, $response, $args) {
        global $entityManager;
        error_log("delete");
        if(UserController::has_privileg($request, $response, $args, "role.delete")) {
            $route = $request->getAttribute('route');
            $id = $route->getArgument('role_id');
            $role_list = $entityManager->getRepository('Alfenory\Auth\V1\Entity\Role')->findBy(array('id' => $id));
            if(count($role_list) === 1) {
                $entityManager->remove($role_list[0]);
                $entityManager->flush();
                return $response->withJson(Returnlib::get_success());
            }
            else {
                return $response->withJson(Returnlib::no_privileg());
            }
        }
        else {
            return $response->withJson(Returnlib::no_privileg());
        }
    }
}