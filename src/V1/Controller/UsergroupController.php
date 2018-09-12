<?php

namespace Alfenory\Auth\V1\Controller;

use Alfenory\Auth\V1\Lib\Returnlib;
use Alfenory\Auth\V1\Lib\Webservicelib;

class UsergroupController {
    
    protected $container;

    public function __construct($container) {
        $this->container = $container;
    }
    
    public static function get($request, $response) {
        global $entityManager;

        if(UserController::has_privileg($request, $response, $args, "usergroup.handle_all")) {
            $usergroup_list = $entityManager->getRepository('Alfenory\Auth\V1\Entity\Usergroup')->findBy(array("getUsergroupId" => ''));
            return $response->withJson(Returnlib::get_success($usergroup_list));
        } else {
            if(UserController::has_privileg($request, $response, $args, "usergroup.handle_own")) {
                $usergroup_list = $entityManager->getRepository('Alfenory\Auth\V1\Entity\Usergroup')->findBy(array("id" => UserController::usergroupBuffer));
                return $response->withJson(Returnlib::get_success($usergroup_list));
            } else {
                return $response->withJson(Returnlib::no_privileg());
            }
        }
    }
    
    public static function get_submandatory($request, $response) {
        global $entityManager;
        if(UserController::has_privileg($request, $response, $args, "usergroup.handle_sub")) {
            $usergroup_list = $entityManager->getRepository('Alfenory\Auth\V1\Entity\Usergroup')->findBy(array("usergroupId" => UserController::$usergroupBuffer));
            return $response->withJson(Returnlib::get_success($usergroup_list));
        } else {
            return $response->withJson(Returnlib::no_privileg());
        }
    }

    public static function has_usergroup_priv($usergroup_id) {
        global $entityManager;
        if(UserController::has_privileg($request, $response, $args, "usergroup.handle_all")) {
            $usergroup_list = $entityManager->getRepository('Alfenory\Auth\V1\Entity\Usergroup')->findBy(array("id" => $usergroup_id));
            if(count($usergroup_list) === 1) {
                return true;
            }
            else {
                return false;
            }
        } else {
            if(UserController::has_privileg($request, $response, $args, "usergroup.handle_sub")) {
                if($usergroup_id === UserController::$usergroupBuffer) {
                    return true;
                }
                else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    public static function create($request, $response) {
        global $entityManager;
        if(UserController::has_privileg($request, $response, $args, "usergroup.post")) {
            $wslib = new Webservicelib();
            $name = $wslib->filter_string_request($request, "name");
            if($wslib->print_error_if_needed($response) === false) {
                $usergroup = new \Alfenory\Auth\V1\Entity\Usergroup();
                $usergroup->setName($name);
                $usergroup->setUsergroupId('');
                $usergroup_id = $wslib->filter_string_request($request, "usergroup_id");
                if($usergroup_id !== '') {
                    if(self::has_usergroup_priv($usergroup_id) === true) {
                        $usergroup->setUsergroupId($usergroup_id);
                        return $response->withJson(Returnlib::get_success($usergroup));
                    }
                    else {
                        return $response->withJson(Returnlib::object_not_found("mandatory", $usergroup_id));    
                    }
                }
                else {
                    return $response->withJson(Returnlib::get_success($usergroup));
                }
            }
            else {
                return $response->withJson(Returnlib::user_parameter_missing($wslib->error_list));
            }
        } else {
            return $response->withJson(Returnlib::no_privileg());
        }
    }

    public static function update($request, $response) {
        global $entityManager;
        if(UserController::has_privileg($request, $response, $args, "usergroup.put")) {
            
        } else {
            return $response->withJson(Returnlib::no_privileg());    
        }
        return $response;
    }
    
    public static function delete($request, $response) {
        return $response;
    }
    
}  