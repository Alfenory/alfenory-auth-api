<?php
namespace Alfenory\Auth\V1\Controller;

class InfoController {
    
    protected $container;

    public function __construct($container) {
        $this->container = $container;
    }
    
    public function info($request, $response, $args) {
        $data = array('info' => 'running');
        return $response->withJson($data);
    }
    
    public function get_js($request, $response, $args) {
        $data = "var test = ''; function show_test() {}";
        $newResponse = $response->withHeader("Content-type", "text/javascript");
        $newResponse->getBody()->write($data);
        return $newResponse;
    }

    public static function get_priv_list() {
        $priv_list = array();
        $priv_list[] = "priv_list.get";
        $priv_list[] = "role.crud";
        $priv_list[] = "roleprivileg.crud";
        $priv_list[] = "usergroup.crud";
        $priv_list[] = "usergroupuser.crud";

        return $priv_list;
    }
    
}