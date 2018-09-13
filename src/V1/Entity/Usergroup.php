<?php

namespace Alfenory\Auth\V1\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * usergroup
 * 
 * @ORM\Table(name="auth_usergroup")
 * @ORM\Entity
 **/
class Usergroup implements \JsonSerializable {

    /** @ORM\Id @ORM\Column(type="guid") @ORM\GeneratedValue(strategy="UUID") */
    private $id;
    public function getId() {
        return $this->id;
    }
    
    /** @ORM\Column(type="string",length=255) */
    private $name;
    public function getName() { return $this->name; }
    public function setName($name) { $this->name = $name; }
    
    /** @ORM\Column(type="string",length=36) */
    private $usergroup_id;
    public function getUsergroupId() { return $this->usergroup_id; }
    public function setUsergroupId($usergroup_id) { $this->usergroup_id = $usergroup_id; }
    
    public static function get_special_group() {
        $special_user_group = new usergroup();
        $special_user_group->id = 0;
        $special_user_group->name = "Hauptstruktur";
        $special_user_group->usergroup_id = -1;
        return $special_user_group;
    }
    
    public static function get_user_group_structur($name = "") {
        $usergroup_list = ormlib::get_list_from_instance(new usergroup());
        
        if($name !== "") {
            for($i=0;$i<count($usergroup_list);$i++) {
                if(stristr($usergroup_list[$i]->name, $name) === FALSE) {
                    array_splice($usergroup_list, $i, 1);
                    $i--;
                }
            }
        }
        
        $usergroup_wrapper_list = array();
        
        for($i=0;$i<count($usergroup_list);$i++) {
            $usergroup_wrapper = $usergroup_list[$i]->to_usergroup_wrapper();
            $usergroup_wrapper->usergroup_attribute_list = usergroup_attribute::get_list_by_usergroup_id($usergroup_list[$i]->id);
            $usergroup_wrapper->count_user = count(user::get_user_from_usergroup($usergroup_list[$i]->id));
            $usergroup_wrapper_list[] = $usergroup_wrapper;
        }
        
        return $usergroup_wrapper_list;
    }
    
    function to_usergroup_wrapper() {
        return new usergroup_wrapper($this);
    }
    
    public function remove($user_id = NULL) {
        $list = usergroup_attribute::get_list_by_usergroup_id($this->id);
        for($i=0;$i<count($list);$i++) {
            $list[$i]->remove();
        }
        $list1 = user::get_list_from_instance(new user(), array("usergroup_id"), array($this->id)); //finding fitting user
        for($i=0;$i<count($list1);$i++) {
            $list1[$i]->remove(); //call user::do_remove
        }
        parent::remove();
        usergroup_wrapper_buffer::remove_buffer();
    }
    
    public function update($user_id = NULL) {
        $this->change_date = time();
        usergroup_wrapper_buffer::remove_buffer();
        parent::update();
    }
    
    public function insert($user_id = NULL) {
        $this->change_date = time();
        usergroup_wrapper_buffer::remove_buffer();
        parent::insert();
    }

    public function jsonSerialize() {
        $vars = get_object_vars($this);
        return $vars;
    }
    
}