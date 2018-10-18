<?php

namespace Alfenory\Auth\V1\Routes;

class InvitationRoute {
    
    public function __construct($app) {
        $app->group("/invite", function() {
            $this->post('/', \Alfenory\Auth\V1\Controller\InvitationController::class.':create');
            $this->get("/", \Alfenory\Auth\V1\Controller\InvitationController::class.":get_data");
            $this->post("/setdata", \Alfenory\Auth\V1\Controller\InvitationController::class.":setdata");
        });
    }
    
}