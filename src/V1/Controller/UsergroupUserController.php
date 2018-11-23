<?php

namespace Alfenory\Auth\V1\Controller;

use Alfenory\Auth\V1\Entity\User;
use Alfenory\Auth\V1\Entity\UserWrapper;
use Alfenory\Auth\V1\Entity\UsergroupUser;
use Alfenory\Auth\V1\Entity\Session;
use Alfenory\Auth\V1\Entity\Role;
use Alfenory\Auth\V1\Entity\RolePrivleg;

use Alfenory\Auth\V1\Lib\Returnlib;
use Alfenory\Auth\V1\Lib\Webservicelib;

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

        if (UserController::has_privileg($request, $response, $args, "usergroupuser.get")) {
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

    public static function create($request, $response, $args) {
        global $entityManager;
        if (UserController::has_privileg($request, $response, $args, "usergroupuser.post")) {
            $route = $request->getAttribute("route");
            $usergroup_id = $route->getArgument('usergroup_id');
            if (UsergroupController::has_usergroup_priv($request, $response, $args, $usergroup_id)) {
                $wslib = new Webservicelib();
                $username  = $wslib->filter_string_request($request, "username");
                if (UserController::is_double_logic($username) === false) {
                    $email = $wslib->filter_email_request($request, "email");
                    $salutation = $wslib->filter_string_request($request, "salutation");
                    $firstname = $wslib->filter_string_request($request, "firstname");
                    $lastname = $wslib->filter_string_request($request, "lastname");
                    $role_id = $wslib->filter_string_request($request, "role_id");
                    $password = $wslib->filter_string_request($request, "password");
                    $active = $wslib->filter_int_request($request, "active");
                    if ($wslib->print_error_if_needed($response) === false) {
                        if (UserGroupController::has_usergroup_priv($request, $response, $args, $usergroup_id)) {
                            $user = new \Alfenory\Auth\V1\Entity\User();
                            $user->setSalutation($salutation);
                            $user->setFirstName($firstname);
                            $user->setLastName($lastname);
                            $user->setEmail($email);
                            $user->initSalt();
                            $user->setPassword($user->get_password($user->getSalt(), $password));
                            $user->setActive($active);
                            $entityManager->persist($user);
                            $entityManager->flush();
                            $ugu = new \Alfenory\Auth\V1\Entity\UsergroupUser();
                            $ugu->setRoleId($role_id);
                            $ugu->setUserId($user->getId());
                            $ugu->setUsergroupId($usergroup_id);
                            $entityManager->persist($ugu);
                            $entityManager->flush();
                            return $response->withJson(Returnlib::get_success());
                        } else {
                            return $response->withJson(Returnlib::no_privileg());
                        }
                    } else {
                        return $response->withJson(Returnlib::user_parameter_missing($wslib->error_list));
                    }
                } else {
                    return $response->withJson(Returnlib::error('X', 'username already exists'));
                }
            } else {
                return $response->withJson(Returnlib::no_privileg());
            }
        } else {
            return $response->withJson(Returnlib::no_privileg());
        }
        return $response;
    }

    public static function update($request, $response, $args) {
        return $response;
    }

    public static function delete($request, $response, $args) {
        return $response;
    }

}