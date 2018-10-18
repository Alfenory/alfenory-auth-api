<?php

namespace Alfenory\Auth\V1\Controller;

use Alfenory\Auth\V1\Entity\User;
use Alfenory\Auth\V1\Entity\UserWrapper;
use Alfenory\Auth\V1\Entity\UsergroupUser;
use Alfenory\Auth\V1\Entity\Session;
use Alfenory\Auth\V1\Entity\Role;
use Alfenory\Auth\V1\Entity\RolePrivleg;
use Alfenory\Auth\V1\Lib\Returnlib;

class UsergroupUserController {
    
    protected $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public static function get_user($user_id) {
        global $entityManager;
        $user_list = $entityManager->getRepository('Alfenory\Auth\V1\Entity\User')->findBy(array('id' => $user_id));
        if (count($user_list)>0) {
            return $user_list[0];
        }
        return null;
    }
    
    public static function get($request, $response, $args) {
        global $entityManager;

        if (UserController::has_privileg($request, $response, $args, "user.get")) {
            $route = $request->getAttribute('route');
            $usergroup_id = $route->getArgument('usergroup_id');
            if (UsergroupController::has_usergroup_priv($request, $response, $args, $usergroup_id)) {
                $usergroup_list = $entityManager->getRepository('Alfenory\Auth\V1\Entity\UsergroupUser')->findBy(array('usergroup_id' => $usergroup_id));
                $userwrapper_list = array();
                for ($i=0;$i<count($usergroup_list);$i++) {
                    $userwrapper_list[] = new UserWrapper(self::get_user($usergroup_list[$i]->getUserId()), $usergroup_list[$i]->getRoleId());
                }
                return $response->withJson(Returnlib::get_success($userwrapper_list));
            } else {
                return $response->withJson(Returnlib::no_privileg());
            }
        } else {
            return $response->withJson(Returnlib::no_privileg());
        }
    }
    
    public static function update($request, $response, $args) {
        return $response;
    }
    
    public static function delete($request, $response, $args) {
        return $response;
    }
    
}  