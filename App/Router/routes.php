<?php

/** 
 * ROTAS DO SITE
*/
$router->get('/', 'home@index');
$router->get('/open-album', 'home@openalbum');
$router->get('/cadastrar', 'savealbum@index');
$router->get('/sobre', 'about@index');
/** 
 * ROTAS DA SESSION
*/
$router->get('/destroy', 'destroy@index');
$router->get('/session', 'session@index');
/** 
 * ROTAS DA API
*/
$router->get('/api/data', 'api@data');
$router->post('/api/data', 'api@data');
$router->put('/api/data', 'api@data');
$router->delete('/api/data', 'api@data');
$router->get('/search-album', 'search@index');
/** 
 * ROTAS DO DASHBOARD
*/
$router->get('/admin', 'admin@index');
$router->post('/admin/login-into', 'admin@logininto');
$router->get('/admin/album-update', 'admin@albumupdate');

$router->get('/admin/logged-verify', 'admin@loggedverify');
$router->get('/admin/sign-out', 'admin@signout');

$router->post('/set-data', 'setdata@index');