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
        
        $role_id = $args["role_id"];
        $privileg_list = $entityManager->getRepository('\Alfenory\Auth\V1\Entity\RolePrivileg')->findBy(array('role_id' => $role_id));
        
        $arr = Array();
        foreach($privileg_list as $priv) {
            $arr[] = $priv->getPrivileg();
        }
        
        return $response->withJson(Returnlib::get_success($arr));
    }
    
    public static function update($request, $response, $args) {
        return $response;
    }
    
    public static function delete($request, $response, $args) {
        return $response;
    }
    
    
}