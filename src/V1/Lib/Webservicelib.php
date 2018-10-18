<?php

namespace Alfenory\Auth\V1\Lib;

class Webservicelib {
    var $error_list = array();
    public function __construct() {
        $this->error_list = array();
    }
    
    private function filter_request($request,$field, $filtertype, $options = null) {
        $field_value = null;
        
        $params = $request->getQueryParams();
        
        if($request->isPost() || $request->isPut()) {
            $params = $request->getParsedBody();
            if($params === null) {
                $params = $_REQUEST;
            }
        }
        
        if(!isset($params[$field])) {
            $this->error_list[] = $field;
            return null;
        }
        
        if($options === null) {
            $field_value = filter_var($params[$field], $filtertype);
        }
        else {
            $field_value = filter_var($params[$field], $filtertype, $options);
        }
        if($field_value === false) {
            $this->error_list[] = $field;
        }
        
        if(strrpos($field_value, "/") === strlen($field_value)-1) {
            $field_value = substr($field_value, 0, strrpos("/", $field_value)-1);
        }
        
        return $field_value;
    }
    
    public function filter_string_request($request, $field) {
        return $this->filter_request($request, $field, FILTER_SANITIZE_STRING);
    }
    
    public function filter_int_request($request, $field) {
        return $this->filter_request($request, $field, FILTER_SANITIZE_NUMBER_INT);
    }
    
    public function filter_double_request($request, $field) {
        return $this->filter_request($request, $field, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }
    
    public function filter_email_request($request, $field) {
        return $this->filter_request($request, $field, FILTER_SANITIZE_EMAIL);
    }

    public function check_time_string($timestr) {
    	if($timestr === null) {
    		return false;
    	}
    	$time_split = explode(":", $timestr);
    	if(count($time_split) !== 3) {
    		return false;	
    	}
    	$hours = $time_split[0];
    	if(strlen($hours) !== 2) {
    		return false;
    	}
    	$minutes = $time_split[1];
    	if(strlen($minutes) !== 2) {
    		return false;
    	}
    	$seconds = $time_split[2];
    	if(strlen($seconds) !== 2) {
    		return false;
    	}
    	if($hours*1 < 0 && $hours*1 >= 24) {
    		return false;
    	}
    	if($minutes*1 < 0 && $minutes*1 >= 60) {
    		return false;
    	}
    	if($seconds*1 < 0 && $seconds*1 >= 60) {
    		return false;
    	}
    	return true;
    }
    
    public function check_date_str($datestr) {
    	
    	if($datestr === null) {
    		return false;
    	}
    	$date_split = explode("-", $datestr);
    	if(count($date_split) !== 3) {
    		return false;
    	}
    	
    	$year = $date_split[0];
    	
    	if(strlen($year) !== 4) {
    		return false;	
    	}
    	
    	$month = $date_split[1];
    	if(strlen($month) !== 2) {
    		return false;	
    	}
    	$day = $date_split[2];
    	if(strlen($day) !== 2) {
    		return false;	
    	}
    	
    	return checkdate($month, $day, $year);
    	
    }
    
    public function filter_date_request($request, $field) {
    	$date_str = $this->filter_request($request, $field, FILTER_SANITIZE_STRING);
    	
    	if($this->check_date_str($date_str)) {
    		return $date_str;
    	}
    	
    	$this->error_list[] = $field;
    }
    
    public function filter_datetime_request($request, $field) {
    	$datetime_str = $this->filter_request($request, $field, FILTER_SANITIZE_STRING);
    	
    	$datetime_split = explode(" ", $datetime_str);
    	if(count($datetime_split) === 2) {
            if($this->check_date_str($datetime_split[0]) && $this->check_time_string($datetime_split[1])) {
                return $datetime_str;
            }		
    	}
        $this->error_list[] = $field;
    }
    
    public function print_error_if_needed($response = null) {
        
        if(count($this->error_list) > 0) {
            return true;
        } 
        else {
            return false;
        }
        
    }
    
    public static function get_user($session_obj) {
        global $config, $entityManager;
        
        if($session_obj->is_alive()) {
            $user_list = $entityManager->getRepository("\Alfenory\Auth\V1\Entity\User")->findBy(array("id" => $session_obj->getUserId()));
            if(count($user_list) > 0) {
                return $user_list[0];
            }
        }
        return null;
    }
    
    public static function get_user_or_return_error($request, $response) {
        $session_id = null;
        
        $headers = getallheaders();
        
        if(isset($headers["session_id"])) {
            $session_id = $headers["session_id"];
        }
        if($request->getAttribute("session_id") !== null && $request->getAttribute("session_id") != "") {
            $session_id = $request->getAttribute("session_id");
        }
        
        if($session_id === null || strlen($session_id) === 0) {
            $response->getBody()->write(json_encode(Returnlib::session_invalid()));
            return null;
        }
        
        $session_obj = \Alfenory\Auth\V1\Entity\Session::get_session($session_id);
        $user = self::get_user($session_obj);

        if($user !== null) {
            $session_obj->update_session();
            return $user;
        }
        else {
            $response->getBody()->write(returnlib::session_invalid());
            return null;
        }
    }
    
}
