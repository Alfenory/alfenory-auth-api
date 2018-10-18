<?php

namespace Alfenory\Auth\V1\Entity;

/**
 * @author alexander höhling
 */
class UserWrapper {
    var $user = null;
    var $role_id = null;
    function __construct($user, $role_id) {
        $this->user = $user;
        $this->role_id = $role_id;
    }
    
    public function jsonSerialize() {
        $vars = get_object_vars($this);
        return $vars;
    }
}
