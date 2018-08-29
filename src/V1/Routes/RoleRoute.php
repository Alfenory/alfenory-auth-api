<?php

namespace Alfenory\Auth\V1\Routes;

class RoleRoute {
    public function __construct($app) {
        $app->group("/role/{membership_id}", function() {
            $this->get('/', \Alfenory\Auth\V1\Controller\RoleController::class.":get");
            $this->put('/{role_id}', \Alfenory\Auth\V1\Controller\RoleController::class.":update");
            $this->post('/', \Alfenory\Auth\V1\Controller\RoleController::class.":create");
            $this->delete("/", \Alfenory\Auth\V1\Controller\RoleController::class.":delete");
            $this->group("/privileg", function() {
                $this->get('/', \Alfenory\Auth\V1\Controller\RolePrivilegController::class.":get");
            });

            $this->group("/roleprivileg/{role_id}", function() {
                $this->get('/', \Alfenory\Auth\V1\Controller\RolePrivilegController::class.":get");
                $this->put('/{privileg_id}', \Alfenory\Auth\V1\Controller\RolePrivilegController::class.":update");
                $this->post('', \Alfenory\Auth\V1\Controller\RolePrivilegController::class.":update");
                $this->delete("/{privileg_id}", \Alfenory\Auth\V1\Controller\RolePrivilegController::class.":delete");
            });

        });

        $app->group("/privileges/{membership_id}", function() {
            $this->get('/', \Alfenory\Auth\V1\Controller\RolePrivilegController::class.":priv_list");
        });    
    }
}