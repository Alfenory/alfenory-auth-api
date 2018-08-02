<?php

namespace Alfenory\Auth\V1\Routes;

class InfoRoute {
    
    public function __construct($app) {
        $app->group("/info", function() {
            $this->get('/', \Alfenory\Auth\V1\Controller\InfoController::class.':info');
        });
        $app->group("/get_js", function() {
            $this->get('/', \Alfenory\Auth\V1\Controller\InfoController::class.':get_js');
        });
    }
    
}

