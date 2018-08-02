<?php

namespace Alfenory\Auth\V1\Lib;

class Returnlib {
    
    public static function get_success($data = null) {
        $ret = array();
        $ret["success"] = "true";
        if($data !== null) {
            $ret["data"] = $data;
        }
        return $ret;
    }
    
    public static function error($error_code, $error_text) {
        $ret = array();
        $ret["success"] = "false";
        $ret["error"] = $error_text;
        $ret["error_code"] = $error_code;
        return $ret;
    }
    
    public static function method_not_found($object, $action) {
        return returnlib::error("0", "service " . $object . "_" . $action." does not exists");
    }
    
    public static function session_invalid() {
        return returnlib::error("1","session has run out or session is wrong");
    }
    
    public static function wrong_login() {
        return returnlib::error("2", "username or password are wrong");
    }
    
    public static function missing_logindata() {
        return returnlib::error("3","missing username or password in request parameter");
    }
    
    public static function no_plattform_key_found() {
        return returnlib::error("4","no access to plattform key or plattform key not found");
    }
    
    public static function missing_plattform_key() {
        return returnlib::error("5","missing plattform_key in request parameter");
    }
    
    public static function no_privileg() {
        return returnlib::error("6","you do not have privileg to do action");
    }
    
    public static function no_usergroup_id() {
        return returnlib::error("7","missing usergroup_id");
    }
    
    public static function user_parameter_missing($param) {
        return returnlib::error("8","missing or wrong pattern parameter(".  implode(",", $param).")");
    }
    
    public static function object_not_found($name, $id) {
        return returnlib::error("9","object $name with id $id not found");
    }
    
    public static function circle_relation() {
        return returnlib::error("10", "object create cyrcle relations");
    }
    
    public static function no_privileg_for_object($name, $id) {
        return returnlib::error("11", "user has no privileg to do action with $name::$id");
    }
    
    public static function no_file() {
        return returnlib::error("12", "no file or to many files where transfered");
    }
    
}