<?php

namespace Alfenory\Auth\V1\Routes;

class RoleRoute {
    public function __construct($app) {
        $app->group("/role/{membership_id}", function() {
            $this->get('/', \Alfenory\Auth\V1\Controller\RoleController::class.":get")->setName("get_v1_auth_role_membership_id");
            $this->put('/{role_id}', \Alfenory\Auth\V1\Controller\RoleController::class.":update")->setName("put_v1_auth_role_membership_id");
            $this->post('/', \Alfenory\Auth\V1\Controller\RoleController::class.":create")->setName("post_v1_auth_role_membership_id");
            $this->delete("/", \Alfenory\Auth\V1\Controller\RoleController::class.":delete")->setName("delete_v1_auth_role_membership_id");
            $this->get('/privileg', \Alfenory\Auth\V1\Controller\RolePrivilegController::class.":get")->setName("get_v1_auth_role_membership_id_privileg");
            
            $this->group("/roleprivileg/{role_id}", function() {
                $this->get('/', \Alfenory\Auth\V1\Controller\RolePrivilegController::class.":get")->setName("get_v1_auth_role_membership_id_roleprivileg_role_id");
                $this->put('/{privileg_id}', \Alfenory\Auth\V1\Controller\RolePrivilegController::class.":update")->setName("put_v1_auth_role_membership_id_roleprivileg_role_id_privileg_id");
                $this->post('', \Alfenory\Auth\V1\Controller\RolePrivilegController::class.":update")->setName("post_v1_auth_role_membership_id_roleprivileg_role_id");
                $this->delete("/{privileg_id}", \Alfenory\Auth\V1\Conxtroller\RolePrivilegController::class.":delete")->setName("delete_v1_auth_role_membership_id_roleprivileg_role_id_privileg_id");
            });
        });

        $app->group("/privileges/{membership_id}", function() {
            $this->get('/', \Alfenory\Auth\V1\Controller\RolePrivilegController::class.":priv_list")->setName("get_v1_auth_privileges_membership_id");
        });    
    }
}