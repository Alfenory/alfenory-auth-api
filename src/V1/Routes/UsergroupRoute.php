<?php

namespace Alfenory\Auth\V1\Routes;

class UsergroupRoute {
    public function __construct($app) {
        $app->group('/usergroup', function () {
            $this->get("/", \Alfenory\Auth\V1\Controller\Usergroup::class.":get");
            $this->put("/", \Alfenory\Auth\V1\Controller\Usergroup::class.":update");
            $this->put("/{usergroup_id}", \Alfenory\Auth\V1\Controller\Usergroup::class.":update");
            $this->delete("/{usergroup_id}", \Alfenory\Auth\V1\Controller\Usergroup::class.":delete");
            $this->get("/{usergroup_id}/attribute", \Alfenory\Auth\V1\Controller\Usergroup::class.":get");
            $this->group('/{usergroup_id}/attribute', function () {
                $this->put("/", \Alfenory\Auth\V1\Controller\UsergroupAttribute::class.":update");
                $this->put("/{attribute_id}", \Alfenory\Auth\V1\Controller\UsergroupAttribute::class.":update");
                $this->delete("/{attribute_id}", \Alfenory\Auth\V1\Controller\UsergroupAttribute::class.":delete");
            });
            $this->group('/{usergroup_id}/user', function () {
                $this->get("/", \Alfenory\Auth\V1\Controller\UsergroupUser::class.":get");
                $this->put("/", \Alfenory\Auth\V1\Controller\UsergroupUser::class.":update");
                $this->put("/{user_id}", \Alfenory\Auth\V1\Controller\UsergroupUser::class.":update");
                $this->delete("/{user_id}", \Alfenory\Auth\V1\Controller\UsergroupUser::class.":delete");
            });
        });
        
    }
}
