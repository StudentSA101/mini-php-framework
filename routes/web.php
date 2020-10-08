<?php
/**
 * Routes file that resolves uri to controller
 */
$router->get('/', 'HomeController@index');
$router->get('/contact', 'HomeController@contact');
$router->get('/time-zones', 'HomeController@timeZones');
$router->get('/local-date', 'HomeController@localDateAndTime');
$router->get('/redis', 'HomeController@redis');
$router->get('/*', 'HomeController@fourOhFour');
