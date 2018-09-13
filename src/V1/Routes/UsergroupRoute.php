<?php

namespace Alfenory\Auth\V1\Routes;

class UsergroupRoute {
    public function __construct($app) {
        $app->group('/usergroup/{membership_id}', function () {
            $this->get("/", \Alfenory\Auth\V1\Controller\Usergroup::class.":get");
            $this->post("/", \Alfenory\Auth\V1\Controller\Usergroup::class.":create");
            $this->group('/{usergroup_id}', function () {
                $this->delete("/", \Alfenory\Auth\V1\Controller\Usergroup::class.":delete");
                $this->put("/", \Alfenory\Auth\V1\Controller\Usergroup::class.":update");
                $this->group('/submandatory', function() {
                    $this->get("/", \Alfenory\Auth\V1\Controller\Usergroup::class.":get_submandatory");
                });
                $this->group('/attribute', function () {
                    $this->get("/", \Alfenory\Auth\V1\Controller\UsergroupAttribute::class.":get");
                    $this->post("/", \Alfenory\Auth\V1\Controller\UsergroupAttribute::class.":create");
                    $this->put("/{attribute_id}", \Alfenory\Auth\V1\Controller\UsergroupAttribute::class.":update");
                    $this->delete("/{attribute_id}", \Alfenory\Auth\V1\Controller\UsergroupAttribute::class.":delete");
                });
                $this->group('/{usergroup_id}/user', function () {
                    $this->get("/", \Alfenory\Auth\V1\Controller\UsergroupUser::class.":get");
                    $this->post("/", \Alfenory\Auth\V1\Controller\UsergroupUser::class.":create");
                    $this->group('/{user_id}', function () {
                        $this->put("/", \Alfenory\Auth\V1\Controller\UsergroupUser::class.":update");
                        $this->delete("/", \Alfenory\Auth\V1\Controller\UsergroupUser::class.":delete");
                    });
                    $this->group('/invite', function () {
                        $this->post("/", \Alfenory\Auth\V1\Controller\UsergroupUser::class.":invite");
                    });
                });
            });
        });
        
    }
}
