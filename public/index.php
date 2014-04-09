<?php

define('ROOTDIR', realpath(dirname(__FILE__) . '/../'));

$config = new \Phalcon\Config\Adapter\Ini(ROOTDIR . '/app/config/config.ini');

$loader = new \Phalcon\Loader();
$loader->registerDirs(
    array(
        ROOTDIR . '/app/models/',
        ROOTDIR . '/app/vendor/'
    )
)->register();

require_once(ROOTDIR . '/app/config/di.php');

$app = new Phalcon\Mvc\Micro($di);

$app->url->setBaseUri($app->config->app->base_uri);

$app->before(
	function () use ($app) {
		if(!$app->security->getSessionToken()) $app->security->getToken();
	}
);

require_once(ROOTDIR . '/app/config/routes.php');

try {
	$app->handle();
} catch (Exception $e) {
	if ($app->config->app->debug == 0) {
		$app->response->redirect("error")->sendHeaders();
	} else {
		$s = get_class($e) . ": " . $e->getMessage() . "<br>" . " File=" . $e->getFile() . "<br>" . " Line="
		. $e->getLine() . "<br>" . $e->getTraceAsString();
	
		$app->response->setContent($s)->send();
	}
}
