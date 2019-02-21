<?php

namespace Alfenory\Auth\V1\Entity;

use Doctrine\ORM\Annotation as ORM;

/**
 * usergroupuser
 * 
 * @ORM\Table(name="auth_usergroupuser")
 * @ORM\Entity
 **/
class UsergroupUser implements \JsonSerializable {
    /** @ORM\Id @ORM\Column(type="guid")*/
    private $id;
    public function getId() {
        return $this->id;
    }
    
    /** @ORM\Column(type="string") **/
    private $user_id;
    public function getUserId() {
        return $this->user_id;
    }
    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }
    
    /** @ORM\Column(type="string") **/
    private $usergroup_id;
    public function getUsergroupId() {
        return $this->usergroup_id;
    }
    public function setUsergroupId($usergroup_id) {
        $this->usergroup_id = $usergroup_id;
    }
    
    /** @ORM\Column(type="string") **/
    private $role_id;
    public function getRoleId() {
        return $this->role_id;
    }
    public function setRoleId($role_id) {
        $this->role_id = $role_id;
    }

    function __construct() {
        $this->id = \Alfenory\Auth\V1\Guid::guid(); 
    }

    // no db relation
    private $usergroup_name;
    public function getUsergroupName() {
        return $this->usergroup_name;
    }
    public function setUsergroupName($usergroup_name) {
        $this->usergroup_name = $usergroup_name;
    }
    
    public function jsonSerialize() {
        $vars = get_object_vars($this);
        return $vars;
    }
    
}