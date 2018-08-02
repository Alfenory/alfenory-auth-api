<?php

namespace Alfenory\Auth\V1\Routes;

class RoleRoute {
    public function __construct($app) {
        $app->group("/role", function() {
            $this->get('/', \Alfenory\Auth\V1\Controller\RoleController::class.":get");
            $this->put('/{role_id}', \Alfenory\Auth\V1\Controller\RoleController::class.":update");
            $this->put('/', \Alfenory\Auth\V1\Controller\RoleController::class.":update");
            $this->delete("/", \Alfenory\Auth\V1\Controller\RoleController::class.":delete");
            $this->group("/{role_id}/privileg", function() {
                $this->get('', \Alfenory\Auth\V1\Controller\RolePrivilegController::class.":get");
                $this->put('/{privileg_id}', \Alfenory\Auth\V1\Controller\RolePrivilegController::class.":update");
                $this->put('', \Alfenory\Auth\V1\Controller\RolePrivilegController::class.":update");
                $this->delete("/{privileg_id}", \Alfenory\Auth\V1\Controller\RolePrivilegController::class.":delete");
            });
        });
    }
}