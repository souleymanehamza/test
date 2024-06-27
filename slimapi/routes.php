<?php
require 'vendor/autoload.php';
$app = new \Slim\App;
// API group
$app->group('/api', function () use ($app) {
    // Version group
    $app->group('/v1', function () use ($app) {
	  $app->post('/create', 'adduser');
	  $app->get('/users', 'getusers');
	  $app->get('/user/{id}', 'getuser');

	  $app->put('/update/{id}', 'updateuser');

	  $app->delete('/delete/{id}', 'deleteuser');

	  $app->post('/login', 'login');
	});

}); 
$app->run();
