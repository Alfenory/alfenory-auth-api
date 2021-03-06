<?php

namespace Alfenory\Auth\V1\Entity;

use Doctrine\ORM\Annotation as ORM;
use Doctrine\ORM\Mapping as MAPPING;

/**
 * usergroupattribute
 * 
 * @ORM\Table
 * @ORM\Entity
 **/
class UsergroupAttribute implements \JsonSerializable {
    /** @ORM\Id @ORM\Column(type="guid") */
    private $id;
    public function getId() {
        return $this->id;
    }
    private $usergroup_id;
    public function getUsergroupId() { return $this->usergroup_id; }
    public function setUsergroupId($usergroup_id) { $this->usergroup_id = $usergroup_id; }
    
    private $key;
    public function getKey() { return $this->key; }
    public function setKey($key) { $this->key = $key; }
    
    private $value;
    public function getValue() { return $this->value; }
    public function setValue($value) { $this->value = $value; }
    
    function __construct() {
        $this->id = \Alfenory\Auth\V1\Lib\Guid::guid(); 
    }

    public static function get_list_by_usergroup_id($usergroup_id) {
        return usergroup_attribute::get_list_from_instance(new usergroup_attribute(), array("usergroup_id"), array($usergroup_id));
    }
    
    public static function get_by_usergroup_id_key($usergroup_id, $key, $value) {
        $attribute = usergroup_attribute::get_from_instance(new usergroup_attribute(), array("usergroup_id", $key), array($usergroup_id, $value));
        if($attribute === null) {
            return "";
        }
        return $attribute->value;
    }
    
    public static function remove_all($usergroup_id) {
        usergroup_attribute::remove_from_instance(new usergroup_attribute, "`usergroup_id` = $usergroup_id");
    }

    public function jsonSerialize() {
        $vars = get_object_vars($this);
        return $vars;
    }
    
}