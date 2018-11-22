<?php

namespace Alfenory\Auth\V1\Routes;

class UserRoute {
    public function __construct($app) {
        $app->group("/user", function() {
            $this->get('/membership', \Alfenory\Auth\V1\Controller\UserController::class.":get_usergroup_membership");
            $this->get('/get_privileges', \Alfenory\Auth\V1\Controller\UserController::class.":get_privileges");
            $this->get('/login', \Alfenory\Auth\V1\Controller\UserController::class.":login");
            $this->get('/logout', \Alfenory\Auth\V1\Controller\UserController::class.":logout"); //TODO
            $this->get('/{securecode}/confirm', \Alfenory\Auth\V1\Controller\UserController::class.":confirm"); //TODO
            $this->get("/forget_password", \Alfenory\Auth\V1\Controller\UserController::class.":forget_password"); //TODO
        });
    }
}