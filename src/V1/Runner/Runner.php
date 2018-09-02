<?php

namespace Alfenory\Auth\V1\Runner;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

class Runner {

    public static function run() {

        global $config, $entityManager;

        $isDevMode = false;

        $dbParams = array(
            'driver' => 'pdo_mysql',
            'host' => $config["db"]["host"],
            'user' => $config["db"]["username"],
            'password' => $config["db"]["password"],
            'dbname' => $config["db"]["name"],
        );
        
        AnnotationRegistry::registerLoader('class_exists');
        
        $config_doctrine = Setup::createConfiguration($isDevMode);
        $driver = new AnnotationDriver(new AnnotationReader());
        
        $logger = new \Doctrine\DBAL\Logging\DebugStack();
        $logger->enabled = true;
        
        $config_doctrine->setMetadataDriverImpl($driver);
        $config_doctrine->setSQLLogger($logger);
        
        $entityManager = EntityManager::create($dbParams, $config_doctrine);

        $env = \Slim\Http\Environment::mock([
            'REQUEST_METHOD' => $_SERVER['REQUEST_METHOD'],
            'REQUEST_URI' => $_SERVER['REQUEST_URI']."/",
            'QUERY_STRING' => '',
            'SERVER_NAME' => 'example.com',
            'CONTENT_TYPE' => 'application/json;charset=utf8',
            'CONTENT_LENGTH' => 15
        ]);

        $slim_config = [
            'settings' => [
                'addContentLengthHeader' => false,
                'displayErrorDetails' => true,
                'routerCacheFile' => __DIR__ . '/../../routes.cache.php',
            ]
        ];
        
        $app = new \Slim\App($slim_config);
        $app->getContainer()['environment'] = $env;

        $app->options('/{routes:.+}', function ($request, $response, $args) {
            return $response;
        });

        $app->add(function ($req, $res, $next) {
            $response = $next($req, $res);
            return $response
                    ->withHeader('Access-Control-Allow-Origin', '*')
                    ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, session_id')
                    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE');
        });
        
        if($config["api_url_path"] != "") {
            $app->group("/".$config["api_url_path"], function() {

                global $config;

                new \Alfenory\Auth\V1\Routes\All($this);

                foreach ($config["modules"] as $module) {
                    $name = $module."\Routes\All";
                    new $name($this);
                };
            });
        }
        else {
            new \Alfenory\Auth\V1\Routes\All($app);

            foreach ($config["modules"] as $module) {
                $name = $module."\Routes\All";
                new $name($app);
            };
        }
        
        $app->run();
        
    }

}
