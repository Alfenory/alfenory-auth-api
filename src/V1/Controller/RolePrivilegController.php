<?php
namespace Alfenory\Auth\V1\Controller;

use Alfenory\Auth\V1\Lib\Returnlib;

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
        
        $membership_id = $args["membership_id"];
        $membership_list = $entityManager->getRepository('\Alfenory\Auth\V1\Entity\UsergroupUser')->findBy(array('id' => $membership_id));
        if(count($membership_id) === 1) {
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
    
    public static function update($request, $response, $args) {
        global $config, $entityManager;
    
        if(UserController::has_privileg($request, $response, $args, "privileg.post")) {
            $wslib = new Webservicelib();
            
            $privileg = $wslib->filter_string_request($request, "privileg");
            $type = $wslib->filter_int_request($request, "type");
            $article_category_id =  $wslib->filter_string_request($request, "article_category_id");
            
            if($wslib->print_error_if_needed($response) === false) {
                $internal_id = $wslib->filter_string_request($request, "internal_id");
                $active = $wslib->filter_int_request($request, "active");

                $article = new \Alfenory\IAO\V1\Entity\Article();
                $article->setName($name);
                $article->setType($type);
                $article->setArticleCategoryId($article_category_id);
                $article->setInternalId($internal_id);
                $article->setActive($active);
                $article->setUsergroupId(UserController::$usergroupBuffer);
                $entityManager->persist($article);
                $entityManager->flush();

                return $response->withJson(Returnlib::get_success($article));
            }
            else {
                return $response->withJson(Returnlib::user_parameter_missing($wslib->error_list));
            }
        } 
        else {
            return $response->withJson(Returnlib::no_privileg());
        }

        return $response;
    }

    
    public static function delete($request, $response, $args) {
        if(UserController::has_privileg($request, $response, $args, "roleprivileg.delete")) {

        } 
        else {
            return $response->withJson(Returnlib::no_privileg());
        }
    }

    public static function priv_list($request, $response, $args) {
        global $config;

        if(UserController::has_privileg($request, $response, $args, "priv_list.get")) {
            $priv_list = array();
            $priv_list = array_merge($priv_list, InfoController::get_priv_list);
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