<?php

namespace Alfenory\Auth\V1\Entity;
use Doctrine\ORM\Annotation as ORM;
/**
 * This is a wrapper for usergroup. It has own logic for handling
 * @author alexander höhling
 * @see usergroup
 */
class UsergroupWrapper {
    var $usergroup = null;
    var $usergroup_attribute_list = array();
    var $count_user = null;
    var $sub_usergroup_list = array();
    
    /**
     * Constructor
     * @param usergroup $usergroup
     */
    function __construct($usergroup) {
        $this->usergroup = $usergroup;
        $this->usergroup_attribute_list = array();
        $this->count_user = null;
        $this->sub_usergroup_list = array();
    }
    
    /**alefen
     * 
     * Add Usergroup
     * @param usergroup $usergroup
     * @param usergroup_wrapper $topugwrapper
     * @return boolean
     */
    function add_subusergroup($usergroup, $topugwrapper = null) {
        if($topugwrapper === null) {
            return usergroup_wrapper::add_subusergroupwrapper(new usergroup_wrapper($usergroup), $this);
        }
        else {
            return usergroup_wrapper::add_subusergroupwrapper(new usergroup_wrapper($usergroup), $topugwrapper);
        }
    }
    
    /**
     * Add element to usergroup_wrapper. avoid circle references. Returns true if $usergroupwrapper is added
     * @param usergroup_wrapper $usergroupwrapper
     * @param usergroup_wrapper $topugwrapper
     * @return boolean
     */
    function add_subusergroupwrapper($usergroupwrapper, $topugwrapper) {
        
        if($usergroupwrapper->usergroup->usergroup_id*1 === $this->usergroup->id*1 && usergroup_wrapper::is_usergroup_in_total_structur($topugwrapper, $usergroupwrapper) === false) {
            $this->sub_usergroup_list[] = $usergroupwrapper;
            
            return true;
        }
        else {
            foreach($this->sub_usergroup_list as $subusergroup) {
                if($subusergroup->add_subusergroupwrapper($usergroupwrapper, $topugwrapper) === true) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * 
     * @param user $user
     */
    function get_usergroup_wrapper_for_user($user) {
        if($this->usergroup->id*1 === $user->usergroup_id*1) {
            return $this;
        }
        for($i=0;$i<count($this->sub_usergroup_list);$i++) {
            $erg = $this->sub_usergroup_list[$i]->get_usergroup_wrapper_for_user($user);
            if($erg !== null) {
                return $erg;
            }
        }
        return null;
    }
    
    /**
     * 
     * @param user $user
     */
    public function is_usergroup_in_usergroupstructur($usergroup_id) {
        if($this->usergroup->id*1===$usergroup_id*1) {
            return true;
        }
        
        for($i=0;$i<count($this->sub_usergroup_list);$i++) {
            if($this->sub_usergroup_list[$i]->is_usergroup_in_usergroupstructur($usergroup_id) === true) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * usergroup is in userstructur
     * @param user $user
     * @param INT $usergroup_id
     * @return type
     */
    public static function usergroup_is_in_userstructur($user, $usergroup_id) {
        $mainstructur = usergroup_wrapper_buffer::load_wrapper_structur();
        $substructur = $mainstructur->get_usergroup_wrapper_for_user($user);
        return $substructur->is_usergroup_in_usergroupstructur($usergroup_id);
    }
    
    /**
     * Get total structur of user
     * @param user $user
     * @return null or usergroup_wrapper
     */
    public static function load_total_structur($user) {
        $structur = usergroup_wrapper_buffer::load_wrapper_structur();
        return $structur->get_usergroup_wrapper_for_user($user);
    }
    
    /**
     * Get list representation of structur as array of id list
     * @return array of array(int, ..)
     */
    public function get_array_structur() {
        
        if(count($this->sub_usergroup_list) === 0) {
            return array(array($this->usergroup->id*1));
        }
        
        $arr = array();
        for($i=0;$i<count($this->sub_usergroup_list);$i++) {
            $arr1 = $this->sub_usergroup_list[$i]->get_array_structur();
            for($j=0;$j<count($arr1);$j++) {
                $arr[] = array_merge(array($this->usergroup->id*1), $arr1[$j]);
            }
        }
        
        return $arr;
        
    }
    
    /**
     * 
     * @param usergroup_wrapper $structur 
     * @param usergroup_wrapper $usergroupwrapper
     * @return boolean
     */
    public static function is_usergroup_in_total_structur($structur, $usergroupwrapper) {
        
        if($structur->usergroup->id*1 === $usergroupwrapper->usergroup->id*1) {
            return true;
        }
        
        $arr = $structur->get_array_structur();
        
        foreach($arr as $elements) {        
            if(in_array ($usergroupwrapper->usergroup->id, $elements)) {
                return true;
            }
        }
        
        return false;
    }
    
}
