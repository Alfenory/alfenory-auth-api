<?php

namespace Alfenory\Auth\V1\Runner;

class CorsAction {
    public function __invoke(\Slim\Http\Request $request, \Slim\Http\Response $response) {
        return $response->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, session_id')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }
}
