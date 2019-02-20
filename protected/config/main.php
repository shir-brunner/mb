<?php

$scriptsVer = time(); //because I'm lazy

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'משפט בקליק',
	'sourceLanguage'=>'en_us',
	'language' => 'en_us',
	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.models.forms.*',
		'application.components.*',
		'application.components.products.*',
		'application.controllers.api.*',
		'application.vendors.mpdf.vendor.setasign.fpdi.*',
		'application.vendors.mpdf.vendor.setasign.fpdi.filters.*',
		'ext.giix-components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'generatorPaths' => array(
				'ext.giix-core',
			),
			'password'=>'123456',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),

	),

	// application components
	'components' => array(
		'cache'=>array(
			'class'=>'system.caching.CFileCache',
		),

		'user' => array(
			'class' => 'BaseWebUser',
			'allowAutoLogin' => true, //enable cookie-based authentication
		),

		'security' => array('class' => 'Security'),

		'authManager' => array(
			'class' => 'CDbAuthManager',
			'connectionID' => 'db',
			'itemTable' => 'auth_item',
			'itemChildTable' => 'auth_item_child',
			'assignmentTable' => 'auth_assignment',
			'defaultRoles' => array('Authenticated', 'Guest'),
		),

		'urlManager' => array(
			'urlFormat' => 'path',
			'showScriptName' => false,
			'rules' => array(
				/************** static routings ***************/
				'login' => 'public/login',
				/************** dynamic routings ***************/
				'pay/<requestHash>' => 'public/pay',
				'<controller:\w+>/<id:\d+>' => '<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
				'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
				'' => 'public/index',
			),
		),

		'clientScript' => array(
			'packages' => array(
				'jquery' => array(
					'baseUrl' => 'https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/',
					'js' => array('jquery.min.js'),
				),
				'jquery.ui' => array(
					'baseUrl' => 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/',
					'js' => array('jquery-ui.min.js'),
					'css' => array('themes/smoothness/jquery-ui.css'),
				),
			),
		),

		// database settings are configured in database.php
		'db' => require(dirname(__FILE__) . '/database.php'),

		'errorHandler' => array(
			// use 'site/error' action to display errors
			'errorAction'=>'public/error',
		),

		/*'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CWebLogRoute',
					'levels'=>'error, warning, info, trace',
				),
				// uncomment the following to show log messages on web pages
			),
		),*/
	),
	
	'params'=>array(
		'jsVersion' => $scriptsVer,
		'cssVersion' => $scriptsVer,
		'uploadsFolder' => 'c:/xampp/htdocs/mb/protected/uploads',
	),
);
