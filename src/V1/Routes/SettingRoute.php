<?php

namespace Alfenory\Auth\V1\Routes;

class SettingRoute {
    function __construct($app) {
        $app->group("/setting", function() {
            $this->get('/', \Alfenory\Auth\V1\Controller\SettingController::class.":get");
            $this->get('', \Alfenory\Auth\V1\Controller\SettingController::class.":get");
            $this->put('/{setting_id}', \Alfenory\Auth\V1\Controller\SettingController::class.":update");
            $this->put('/', \Alfenory\Auth\V1\Controller\SettingController::class.":update");
            $this->put('', \Alfenory\Auth\V1\Controller\SettingController::class.":update");
            $this->delete("/", \Alfenory\Auth\V1\Controller\SettingController::class.":delete");
            $this->delete("", \Alfenory\Auth\V1\Controller\SettingController::class.":delete");
        });
    }
}