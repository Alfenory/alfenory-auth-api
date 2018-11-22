<?php

namespace Alfenory\Auth\V1\Controller;

use Alfenory\Auth\V1\Entity\User;
use Alfenory\Auth\V1\Entity\UsergroupUser;
use Alfenory\Auth\V1\Entity\Session;
use Alfenory\Auth\V1\Entity\Role;
use Alfenory\Auth\V1\Entity\RolePrivleg;
use Alfenory\Auth\V1\Lib\Returnlib;

class UserController {
    
    protected $container;
    public static $privilegBuffer;
    public static $usergroupBuffer;
    public static $userId;

    public function __construct($container) {
        $this->container = $container;
    }
    
    public static function login($request, $response, $args) {
        global $entityManager;
        
        $wslib = new \Alfenory\Auth\V1\Lib\Webservicelib();
        $username = $wslib->filter_string_request($request, "username");
        $password = $wslib->filter_string_request($request, "password");
        
        $users = $entityManager->getRepository('\Alfenory\Auth\V1\Entity\User')->findBy(array('username' => $username, 'active' => 1));
        
        if(count($users) > 0) {
            for($i=0;$i<count($users);$i++) {
                if($users[0]->check_password($password)) {
                    $id = session::create_session($users[0]->getId());
                    return $response->withJson(\Alfenory\Auth\V1\Lib\Returnlib::get_success(array("session_id" => $id)));
                }
            }
        }
                
        return $response->withJson(\Alfenory\Auth\V1\Lib\Returnlib::wrong_login());
    }
    
    public static function session($request, $response, $args) {
        global $entityManager;
        
        $headerValueArray = $request->getHeader('Accept');
    }
    
    public static function logout($request, $response, $args) {
        return $response;
    }
    
    public static function confirm($request, $response, $args) {
        return $response;
    }
    
    public static function forget_password($request, $response, $args) {
        return $response;
    }
    
    public static function privileg($request, $response, $args) {
        return $response;
    }
    
    public static function has_privileg($request, $response, $args, $priv) {
        global $config;
        
        $priv_list = self::get_privileges_basic($request, $response, $args);
        if($priv_list !== null) {
            foreach($priv_list as $pr) {
                if($pr == $priv) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function get_privileges($role_id) {
        global $entityManager;
        $priv_list = Array();
        $privileg_list = $entityManager->getRepository('\Alfenory\Auth\V1\Entity\RolePrivileg')->findBy(array('role_id' => $role_id));
        foreach($privileg_list as $priv) {
            $priv_list[] = $priv->getPrivileg();
        }
        return $priv_list;
    }
    
    public static function get_privileges_basic($request, $response, $args) {
        global $config, $entityManager;
        
        if(self::$privilegBuffer === null) {
        
            $user = \Alfenory\Auth\V1\Lib\Webservicelib::get_user_or_return_error($request, $response);
            
            if($user !== null) {
                self::$userId = $user->getId();
                $memberhip_id = $args["membership_id"];
                $membership_list = $entityManager->getRepository("\Alfenory\Auth\V1\Entity\UsergroupUser")->findBy(array("id" => $memberhip_id, "user_id" => $user->getId()));
                $pages = array();
                if(count($membership_list) > 0) {
                    self::$privilegBuffer = self::get_privileges($membership_list[0]->getRoleId());
                    self::$usergroupBuffer = $membership_list[0]->getUsergroupId();
                }
                return self::$privilegBuffer;
            }
            else {
                return null;
            }
                
        }
        else {
            return self::$privilegBuffer;
        }
    }

    public static function username_exists($username) {
        global $config, $entityManager;
        if (UserController::has_privileg($request, $response, $args, "user.post")) {
            $username_list = $entityManager->getRepository("\Alfenory\Auth\V1\Entity\User")->findBy(array("username" => $username));
            if(count($membership_list) > 0) {
                return true;
            } else {
                return false;
            }
        }
        return true;
    }

    public static function send_confirmation($user) {
        global $config;
        $url = $config["url"];
        $salutation = $user->get_email_salutation();

        $confirmation_subject = $config["email"]["content"]["confirmationsubject"];
        $confirmation_content = $config["email"]["content"]["confirmation"];

        $confirmation_content = str_replace("{url}/user/confirm", $url, $confirmation_content);
        $confirmation_content = str_replace("{salutation}", $url, $confirmation_content);

        $email = $user->getEmail();
        $emailname = $user->getFirstname()+" "+$user->getLastname();
        
        $content = $config["email"]["content"]["confirmation"];
        \Alfenory\Auth\V1\Lib\Sendmail::sendEmailFormated($email, $emailname, $confirmation_subject, $content);
    }

    public static function create($request, $response, $args) {
        global $config, $entityManager;
        if (UsergroupController::has_usergroup_priv()) {
            if (UserController::has_privileg($request, $response, $args, "user.post")) {
                $wslib = new Webservicelib();
                $salutation = $wslib->filter_string_request($request, "salutation");
                $firstname = $wslib->filter_string_request($request, "firstname");
                $lastname = $wslib->filter_string_request($request, "lastname");
                $email = $wslib->filter_string_request($request, "email");
                $username = $wslib->filter_string_request($request, "username");
                $role_id = $wslib->filter_string_request($request, "role_id");
                if ($wslib->print_error_if_needed($response) === false) {
                    if (self::username_exists($username)) {
                        $user = new \Alfenory\Auth\V1\Entity\User();
                        $user->setSalutation($salutation);
                        $user->setFirstName($firstname);
                        $user->setLastName($lastname);
                        $user->setEmail($email);
                        $user->setUsername($username);
                        $user->setSecurecode(\Alfenory\Auth\V1\Entity\User::get_guid());
                        $user->getSecurecodeCreated(date("Y-m-d H:i:s"));
                        $user->setActive(0);
                        $entityManager->persist($user);
                        $entityManager->flush();

                        $route = $request->getAttribute('route');
                        $usergroup_id = $route->getArgument('usergroup_id');

                        $usergroup = new \Alfenory\Auth\V1\Entity\Usergroup();
                        $usergroup->setUserId($user->getId());
                        $usergroup->setUsergroupId($usergroup_id);
                        $usergroup->setRoleId($role_id);
                        $entityManager->persist($usergroup);
                        $entityManager->flush();

                        self::send_confirmation($user);
                        return $response->withJson(Returnlib::get_success());
                    } else {
                        return $response->withJson(Returnlib::user_already_exists());
                    }
                }
                else {
                    return $response->withJson(Returnlib::user_parameter_missing($wslib->error_list));
                }
            } else {
                return $response->withJson(Returnlib::no_privileg());
            }
        } else {
            return $response->withJson(Returnlib::no_privileg());
        }
    }
    
    public static function get_usergroup_membership($request, $response, $args) {
        global $config, $entityManager;
        
        $user = \Alfenory\Auth\V1\Lib\Webservicelib::get_user_or_return_error($request, $response);
        
        if($user === null) {
            return $response;
        }
        
        $usergroup_user_list = $entityManager->getRepository('\Alfenory\Auth\V1\Entity\UsergroupUser')->findBy(array('user_id' => $user->getId()));
        
        for($i=0;$i<count($usergroup_user_list);$i++) {
            $usergroup_user = $usergroup_user_list[$i];
            if($usergroup_user->getUsergroupId() == "0") {
                $usergroup_user_list[$i]->usergroup_name = "Hauptstruktur";
            }
            else {
                $usergroup = $entityManager->getRepository('\Alfenory\Auth\V1\Entity\Usergroup')->findBy(array('id' => $usergroup_user->getUsergroupId()));
                if(count($usergroup) > 0) {
                    $usergroup_user_list[$i]->usergroup_name = $usergroup[0]->getName();
                }
            }
        }
        return $response->withJson(Returnlib::get_success($usergroup_user_list));
    }
    
    public static function getUsergroupBuffer() {
        return self::$usergroupBuffer;
    }

    public static function getUser($request, $response, $args) {

        if(UserController::has_privileg($request, $response, $args, "user.get")) {
            $route = $request->getAttribute('route');
            $id = $route->getArgument('membership_id');
            
            $usergroup_user_list = $entityManager->getRepository('Alfenory\Auth\V1\Entity\UsergroupUser')->findBy(array('id' => $id));
            $user_list = [];
            $usergroup_id = "";

            for($i=0;$i<count($usergroup_user_list);$i++) {
                $usergroup_id = $usergroup_user_list[$i]->getUsergroupId();
            }

            $usergroup_list = $entityManager->getRepository('Alfenory\Auth\V1\Entity\UsergroupUser')->findBy(array('usergroup_id' => $usergroup_id));
            for($i=0;$i<count($usergroup_user_list);$i++) {
                $user_id = $usergroup_user_list[$i]->getUserId();
                $u_list = $entityManager->getRepository('Alfenory\Auth\V1\Entity\User')->findBy(array('id' => $user_id));
                if(count($u_list) > 0) {
                    $user_list[] = $u_list[0];
                }
            }

            for($i=0;$i<count($user_list);$i++) {
                $user_list[$i]->setSalt("");
                $user_list[$i]->setPassword("");
                $user_list[$i]->setSecurecode("");
                $user_list[$i]->setActive("");
            }
            
            return $response->withJson(Returnlib::get_success($user_list));
        }
        else {
            return $response->withJson(Returnlib::no_privileg());
        }

    }

    public static function is_double($request, $response, $args) {
        if(UserController::has_privileg($request, $response, $args, "user.get")) {
            $wslib = new Webservicelib();
            $username = $wslib->filter_string_request($request, "username");
            if($wslib->print_error_if_needed($response) === false) {
                $response->withJson(Returnlib::get_success(array("is_double" => self::is_double_logic($username))));
            } else {
                return $response->withJson(Returnlib::user_parameter_missing($wslib->error_list));
            }
        } else {
            return $response->withJson(Returnlib::no_privileg());
        }
    }

    public static function is_double_logic($username) {
        global $config, $entityManager;
        $user_list = $entityManager->getRepository('Alfenory\Auth\V1\Entity\User')->findBy(array('username' => $username)); 
        if (count($user_list) > 0) {
            return true;
        }
        return false;
    }

}
