<?php

namespace Alfenory\Auth\V1\Routes;

class UsergroupRoute {
    public function __construct($app) {
        $app->group('/usergroup/{membership_id}', function () {
            $this->get("/", \Alfenory\Auth\V1\Controller\UsergroupController::class.":get");
            $this->post("/", \Alfenory\Auth\V1\Controller\UsergroupController::class.":create");
            $this->group('/{usergroup_id}', function () {
                $this->delete("/", \Alfenory\Auth\V1\Controller\UsergroupController::class.":delete");
                $this->put("/", \Alfenory\Auth\V1\Controller\UsergroupController::class.":update");
                $this->group('/submandatory', function() {
                    $this->get("/", \Alfenory\Auth\V1\Controller\UsergroupController::class.":get_submandatory");
                });
                $this->group('/attribute', function () {
                    $this->get("/", \Alfenory\Auth\V1\Controller\UsergroupAttributeController::class.":get");
                    $this->post("/", \Alfenory\Auth\V1\Controller\UsergroupAttributeController::class.":create");
                    $this->put("/{attribute_id}", \Alfenory\Auth\V1\Controller\UsergroupAttributeController::class.":update");
                    $this->delete("/{attribute_id}", \Alfenory\Auth\V1\Controller\UsergroupAttributeController::class.":delete");
                });
                $this->group('/user', function () {
                    $this->get("/", \Alfenory\Auth\V1\Controller\UsergroupUserController::class.":get");
                    $this->post("/", \Alfenory\Auth\V1\Controller\UsergroupUserController::class.":create");
                    $this->post("/is_double", \Alfenory\Auth\V1\Controller\UserController::class.":is_double");
                    $this->group('/{user_id}', function () {
                        $this->put("/", \Alfenory\Auth\V1\Controller\UsergroupUserController::class.":update");
                        $this->delete("/", \Alfenory\Auth\V1\Controller\UsergroupUserController::class.":delete");
                    });
                });
                $this->group('/invitation', function () {
                    $this->get("/", \Alfenory\Auth\V1\Controller\InvitationController::class.":get");
                    $this->post("/", \Alfenory\Auth\V1\Controller\InvitationController::class.":create");
                    $this->group('/{invitation_id}', function () {
                        $this->put("/", \Alfenory\Auth\V1\Controller\InvitationController::class.":update");
                        $this->delete("/", \Alfenory\Auth\V1\Controller\InvitationController::class.":delete");
                    }); 
                });
            });
        });
    }
}