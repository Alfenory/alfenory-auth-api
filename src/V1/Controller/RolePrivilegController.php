<?php
namespace Alfenory\Auth\V1\Controller;

use Alfenory\Auth\V1\Lib\Returnlib;
use Alfenory\Auth\V1\Lib\Webservicelib;

class RolePrivilegController {
    
    protected $container;
    public static $buffer;
    
    public function __construct($container) {
        $this->container = $container;
    }
    
    public static function get($request, $response, $args) {
        global $config, $entityManager;
    
        $user = \Alfenory\Auth\V1\Lib\Webservicelib::get_user_or_return_error($request, $response);
        
        if($user === null) {
            return $response;
        }

        $route = $request->getAttribute('route');
        $membership_id = $route->getArgument('membership_id');

        $membership_list = $entityManager->getRepository('\Alfenory\Auth\V1\Entity\UsergroupUser')->findBy(array('id' => $membership_id));
        if(count($membership_list) === 1) {
            $privileg_list = $entityManager->getRepository('\Alfenory\Auth\V1\Entity\RolePrivileg')->findBy(array('role_id' => $membership_list[0]->getRoleId()));
            $arr = Array();
            foreach($privileg_list as $priv) {
                $arr[] = $priv->getPrivileg();
            }
            return $response->withJson(Returnlib::get_success($arr));
        } else {
            return $response->withJson(Returnlib::object_not_found("membership", $membership_id));
        }
    }

    public static function get_list($request, $response, $args) {
        global $config, $entityManager;
    
        $user = \Alfenory\Auth\V1\Lib\Webservicelib::get_user_or_return_error($request, $response);
        
        if($user === null) {
            return $response;
        }

        $route = $request->getAttribute('route');
        $membership_id = $route->getArgument('membership_id');
        $role_id = $route->getArgument('role_id');

        if(UserController::has_privileg($request, $response, $args, "privileg.get")) {
            $privileg_list = $entityManager->getRepository('\Alfenory\Auth\V1\Entity\RolePrivileg')->findBy(array('role_id' => $role_id));
            $arr = Array();
            foreach($privileg_list as $priv) {
                $arr[] = $priv->getPrivileg();
            }
            return $response->withJson(Returnlib::get_success($arr));
        } else {
            return $response->withJson(Returnlib::object_not_found("membership", $membership_id));
        }
    }
    
    public static function update($request, $response, $args) {
        global $config, $entityManager;
    
        if(UserController::has_privileg($request, $response, $args, "privileg.post")) {
            $wslib = new Webservicelib();
            $route = $request->getAttribute('route');
            $id = $route->getArgument('role_id');
            $privileg =  $wslib->filter_string_request($request, "privileg");

            if($wslib->print_error_if_needed($response) === false) {
                $priv = new \Alfenory\Auth\V1\Entity\RolePrivileg();
                $priv->setRoleId($id);
                $priv->setPrivileg($privileg);
                $entityManager->persist($priv);
                $entityManager->flush();
                return $response->withJson(Returnlib::get_success($priv));
            }
            else {
                return $response->withJson(Returnlib::user_parameter_missing($wslib->error_list));
            }
        } 
        else {
            return $response->withJson(Returnlib::no_privileg());
        }
    }

    
    public static function delete($request, $response, $args) {
        global $config, $entityManager;
        if(UserController::has_privileg($request, $response, $args, "privileg.delete")) {
            $route = $request->getAttribute('route');
            $role_id = $route->getArgument('role_id');
            $role_privileg = $route->getArgument('role_privileg');
            $role_list = $entityManager->getRepository('Alfenory\Auth\V1\Entity\RolePrivileg')->findBy(array('role_id' => $role_id, 'privileg' => $role_privileg));
            if(count($role_list) === 1) {
                $entityManager->remove($role_list[0]);
                $entityManager->flush();
                return $response->withJson(Returnlib::get_success());
            }
            else {
                return $response->withJson(Returnlib::object_not_found("role_privileg", $role_privileg));
            }
        } 
        else {
            return $response->withJson(Returnlib::no_privileg());
        }
    }

    public static function priv_list($request, $response, $args) {
        global $config;
        if(UserController::has_privileg($request, $response, $args, "priv_list.get")) {
            $priv_list = array();
            $priv_list = array_merge($priv_list, InfoController::get_priv_list());
            if(isset($config["modules"])) {
                for ($i = 0; $i < count($config["modules"]); $i++) {
                    $func_name = $config["modules"][$i]."\Controller\InfoController::priv_list";
                    $priv_list = array_merge($priv_list, call_user_func($func_name));
                }
            }
            return $response->withJson(Returnlib::get_success($priv_list));
        } 
        else {
            return $response->withJson(Returnlib::no_privileg());
        }
    }
}