<?php

namespace Alfenory\Auth\V1\Entity;

/**
 * @author alexander höhling
 */
class UserWrapper {
    var $user = null;
    var $plattform_access_list = null;
    var $plattform_list = null;
    function __construct($user) {
        $this->user = $user;
        $this->plattform_access_list = array();
        $this->plattform_list = array();
    }
}
