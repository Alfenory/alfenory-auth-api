<?php

namespace Alfenory\Auth\V1\Routes;

class All {
    
    function __construct(\Slim\App $app) {
        $app->group("/v1/auth", function() {
            new InfoRoute($this);
            new RoleRoute($this);
            new SettingRoute($this);
            new UserRoute($this);
            new UsergroupRoute($this);
            new InvitationRoute($this);
        });
    }
    
}