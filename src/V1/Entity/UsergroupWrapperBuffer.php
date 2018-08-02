<?php

namespace Alfenory\Auth\V1\Entity;

/**
 * A class for handling buffer and structur of usergroup_wrapper
 * @see usergroup_wrapper
 * @author alexander höhling
 */
class UsergroupWrapperBuffer {
    
    /**
     * 
     * @return type
     */
    public static function get_buffer_folder() {
        return realpath(dirname(__FILE__))."/../tmp/";
    }
    
    public static function get_buffer_file() {
        global $dbconnection;
        //1. max date ermitteln
        $query = "select max(change_date) as cd from usergroup";
        $result = $dbconnection->do_query_response($query);
        if($dbconnection->has_error()) {
            error_log("plattformerror:$query");
        }
        $maxdate = 0;
        while($myrow = mysql_fetch_array($result)) {
            $maxdate = $myrow["cd"];
        }
        return usergroup_wrapper_buffer::get_buffer_folder().$maxdate.".structur";
    }
    
    /**
     * get wrapper for special usergroup
     * @return \usergroup_wrapper
     */
    public static function get_special_usergroup_wrapper() {
        return new usergroup_wrapper(usergroup::get_special_group());
    }
    
    /**
     * add list
     * @param array of usergroup $usergrouplist
     * @param \usergroup_wrapper $top
     * @return usergroup_wrapper
     */
    public static function get_wrapper_from_list($usergrouplist, $top) {
        $found = true;
        while($found) {
            $found = false;
            for($i=0;$i<count($usergrouplist);$i++) {
                if($top->add_subusergroup($usergrouplist[$i])) {
                    $found = true;
                    array_splice($usergrouplist, $i, 1);
                    $i--;
                }
            }
        }
        return $top;
    }
    
    /**
     * Reads structur from db and put it into a usergroup wrapper structur
     * @return type
     */
    public static function get_wrapper_from_db() {
        $usergrouplist = usergroup::get_list_from_instance(new usergroup());
        $top = usergroup_wrapper_buffer::get_special_usergroup_wrapper();
        return usergroup_wrapper_buffer::get_wrapper_from_list($usergrouplist, $top);
    }
    
    public static function save_buffer_file($filename, $structur) {
        file_put_contents($filename, serialize($structur));
    }
    
    /**
     * Load total wrapper struktur from buffer or from database.
     * @return \usergroup_wrapper
     */
    public static function load_wrapper_structur() {
        $filename = usergroup_wrapper_buffer::get_buffer_file();
        if(file_exists($filename)) {
            return unserialize(file_get_contents($filename));
        }
        else {
            $structur = usergroup_wrapper_buffer::get_wrapper_from_db();
            usergroup_wrapper_buffer::save_buffer_file($filename, $structur);
            return $structur;
        }
    }
    
    public static function remove_buffer() {
        $files = glob(usergroup_wrapper_buffer::get_buffer_folder()."*"); // get all file names
        foreach($files as $file){ // iterate files
            if(is_file($file)) {
                unlink($file); // delete file
            }
        }
    }
    
    /**
     * check whether change of a usergroup ($usergroup_id) would fit circle relations
     * @param INT $id
     * @param INT $usergroup_id
     */
    public static function check_usergroup_for_circle_relation($id, $usergroup_id) {
        
        error_log("check_usergroup_for_circle_relation");
        
        if($id*1 === $usergroup_id*1) {
            error_log("error1");
            return false;
        }
        
        $list = usergroup::get_list_from_instance(new usergroup());
        
        $found = false;
        for($i=0;$i<count($list);$i++) {
            if($list[$i]->id*1 === $id*1) { //find usergroup with id = $id
                $list[$i]->usergroup_id = $usergroup_id;
                $found = true;
                $i = count($list);
            }
        }
        
        if($found === false) { //if no usergroup is found
            
            error_log("error2");
            return false; 
        }
        
        return usergroup_wrapper_buffer::check_usergroup_for_circle_relation_detail($list);
    }
    
    public static function check_usergroup_for_circle_relation_detail($list) {
        
        
        error_log("check_usergroup_for_circle_relation_detail");
        
        error_log(var_export($list, true));
        
        $list_clone = $list; //clone list
        $top = usergroup_wrapper_buffer::get_wrapper_from_list($list, usergroup_wrapper_buffer::get_special_usergroup_wrapper()); //create structur, circle relation are removed
        for($i=0;$i<count($list_clone);$i++) { //check whether "new" structur is complete in usergroup_wrapper structur
            if($top->is_usergroup_in_usergroupstructur($list_clone[$i]->id) === false) {
                
                return false;
            }
        }
        return true;
        
    }
    
}
