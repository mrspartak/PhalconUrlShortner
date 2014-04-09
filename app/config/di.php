<?php

$di = new \Phalcon\DI\FactoryDefault();

$di->setShared(
	'config',
	function () use ($config) {
		return $config;
	}
);

$di->setShared(
	'view',
	function () use ($config) {
		$view = new Phalcon\Mvc\View\Simple();
		$view->setViewsDir(ROOTDIR . '/app/views/');
		$view->registerEngines(
			array(
				'.phtml' => function ($view) use ($config) {
					$volt = new Phalcon\Mvc\View\Engine\Volt($view);
					$volt->setOptions(
						array(
							'compiledPath'      => ROOTDIR . '/tmp/volt/',
							'compiledExtension' => '.php',
							'compiledSeparator' => '_',
							'compileAlways'     => true
						)
					);
					return $volt;
				}
			)
		);
		return $view;
	}
);

$di->setShared(
	'db',
	function () use ($config) {
		$db = new \Phalcon\Db\Adapter\Pdo\Mysql($config->db->toArray());
		$db->execute('SET NAMES UTF8', array());
		return $db;
	}
);

$di->setShared(
	'modelsMetadata',
	function () use ($config) {
		if ($config->app->cache_apc) {
			$metaData = new Phalcon\Mvc\Model\MetaData\Apc(array(
				"lifetime" => 3600,
				"prefix"   => $config->app->suffix . "-meta-db-main"
			));
		} else {
			$metaData = new \Phalcon\Mvc\Model\Metadata\Files(array(
				'metaDataDir' => ROOTDIR . '/tmp/cache/'
			));
		}
		return $metaData;
	}
);