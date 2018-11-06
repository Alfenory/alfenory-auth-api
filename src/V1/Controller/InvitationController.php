<?php

namespace Alfenory\Auth\V1\Controller;

use Alfenory\Auth\V1\Lib\Returnlib;
use Alfenory\Auth\V1\Lib\Webservicelib;
use Alfenory\Auth\V1\Lib\Sendmail;

class InvitationController {

    protected $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public static function sendInvitation($email, $seccode) {
        global $config;
        $subject = $config["invitation"]["subject"];
        $content = $config["invitation"]["content"];
        $link = $config["invitation"]["link"];
        $content = str_replace("{LINK}", $link, $content);
        Sendmail::sendEmail($email, $emailname, $subject, $content, str_replace("\n", "<br/>", $content));
    }

    public static function create($request, $response, $args) {
        global $entityManager;
        if (UserController::has_privileg($request, $response, $args, "user.post")) { 
            $wslib = new Webservicelib();
            $username  = $wslib->filter_string_request($request, "username");
            $email = $wslib->filter_email_request($request, "email");
            $salutation = $wslib->filter_string_request($request, "salutation");
            $firstname = $wslib->filter_string_request($request, "firstname");
            $lastname = $wslib->filter_string_request($request, "lastname");
            $role_id = $wslib->filter_string_request($request, "role_id");
            $route = $request->getAttribute('route');
            $usergroup_id = $route->getArgument('usergroup_id');
            if ($wslib->print_error_if_needed($response) === false) {
                if (UserGroupController::has_usergroup_priv($request, $response, $args, $usergroup_id)) {
                    error_log("t1");
                    $invitation = new \Alfenory\Auth\V1\Entity\Invitation();
                    $error_log("t1a");
                    $invitation->setUsername($username);
                    $error_log("t1b");
                    $invitation->setEmail($email);
                    $error_log("t1c");
                    $invitation->setUsergroupId($usergroup_id);
                    $error_log("t1d");
                    $invitation->setSalutation($salutation);
                    $error_log("t1e");
                    $invitation->setFirstName($firstname);
                    $error_log("t1f");
                    $invitation->setLastName($lastname);
                    $error_log("t1g");
                    $invitation->setDate(date("Y-m-j G:i:s"));
                    error_log("t2");
                    $entityManager->persist($invitation);
                    $entityManager->flush();
                    error_log("t3");
                    self::sendInvitation($email, $invitation->getId());
                    error_log("t4");
                    return $response->withJson(Returnlib::get_success());
                }
                else {
                    return $response->withJson(Returnlib::no_privileg());
                }
            } else {
                return $response->withJson(Returnlib::user_parameter_missing($wslib->error_list));
            }
        }
        else {
            return $response->withJson(Returnlib::no_privileg());
        }
    }

    public static function createUser($request, $response, $args) {
        global $entityManager;
        $wslib = new Webservicelib();
        $username  = $wslib->filter_string_request($request, "username");
        $email = $wslib->filter_email_request($request, "email");
        $salutation = $wslib->filter_string_request($request, "salutation");
        $firstname = $wslib->filter_string_request($request, "firstname");
        $lastname = $wslib->filter_string_request($request, "lastname");
        //CHECK IF USERNAME EXISTS
        
    }
    

}