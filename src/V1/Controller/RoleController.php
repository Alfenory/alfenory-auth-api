<?php

namespace Alfenory\Auth\V1\Controller;

use Alfenory\Auth\V1\Lib\Returnlib;
use Alfenory\Auth\V1\Lib\Webservicelib;

class RoleController {
    
    protected $container;

    public function __construct($container) {
        $this->container = $container;
    }
    
    public static function get_roles() {
        global $entityManager;
        return $entityManager->getRepository('Alfenory\Auth\V1\Entity\Role')->findAll();
    }

    public static function is_role_inheritage_of_role($request, $response, $args, $role_id) {
        $priv_list = UserController::get_privileges($role_id);
        for ($i=0;$i<count($priv_list);$i++) {
            if (UserController::has_privileg($request, $response, $args, $priv_list[$i]) === false) {
                return false;
            }
        }
        return true;
    }

    public static function get_inheritage_roles($request, $response, $args) {
        global $entityManager;
        
        error_log("testA");
        if (UserController::has_privileg($request, $response, $args, "role.inheritage_roles")) {
            error_log("testB");
            $role_list1 = $entityManager->getRepository('Alfenory\Auth\V1\Entity\Role')->findBy(array('usergroup_id' => null));
            error_log("testC");
            $role_list2 = $entityManager->getRepository('Alfenory\Auth\V1\Entity\Role')->findBy(array('usergroup_id' => UserController::$usergroupBuffer));
            error_log("testD");
            $role_list = array_merge($role_list1, $role_list2);
            error_log("testE");
            $target_roles = Array();
            error_log("testE");
            for ($i=0;$i<count($role_list);$i++) {
                error_log("testE");
                if (self::is_role_inheritage_of_role($request, $response, $args, $role_list[$i]->getId())) {
                    $target_roles[] = $role_list[$i];
                }
            }
            
            return $response->withJson(Returnlib::get_success($target_roles));
        } else {
            return $response->withJson(Returnlib::no_privileg());
        }
    }

    public static function get($request, $response, $args) {
        global $entityManager;
        if (UserController::has_privileg($request, $response, $args, "role.get")) {
            return $response->withJson(Returnlib::get_success(self::get_roles()));
        }
        else {
            return $response->withJson(Returnlib::no_privileg());
        }
    }
    
    public static function update($request, $response, $args) {
        global $entityManager;
        if (UserController::has_privileg($request, $response, $args, "role.put")) {
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
        if (UserController::has_privileg($request, $response, $args, "role.post")) {
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
        if (UserController::has_privileg($request, $response, $args, "role.delete")) {
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