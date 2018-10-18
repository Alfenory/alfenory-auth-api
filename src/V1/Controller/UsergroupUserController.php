<?php

namespace Alfenory\Auth\V1\Controller;

use Alfenory\Auth\V1\Entity\User;
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
    
    public static function get($request, $response, $args) {
        global $entityManager;

        if (UserController::has_privileg($request, $response, $args, "user.get")) {
            $route = $request->getAttribute('route');
            $usergroup_id = $route->getArgument('usergroup_id');
            if (UsergroupController::has_usergroup_priv($request, $response, $args, $usergroup_id)) {
                $query = $entityManager->createQuery('SELECT u FROM Alfenory\Auth\V1\Entity\User u WHERE EXISTS (SELECT uu FROM Alfenory\Auth\V1\Entity\UsergroupUser uu WHERE uu.user_id = u.id and uu.usergroup_id = :usergroup_id)');
                $query->setParameter('usergroup_id', $usergroup_id);
                $user_list = $query->getResult();
                return $response->withJson(Returnlib::get_success($user_list));
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