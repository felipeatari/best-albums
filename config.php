<?php

/** 
 * LINKS DO PROJETO
*/ 
define('URI', '/000best-albums');
define('ROOT', 'http://192.168.0.100/mini-project/best-albums');
define('CADASTRAR', ROOT.'/cadastrar');
define('ADMIN', ROOT.'/admin');
define('ABOUT', ROOT.'/sobre');
// define('APIBUSCAR', ROOT.'api/data');

/** 
 * CONEXÃO COM O BANCO DE DADOS
*/
// define('USER', 'atari86');
// define('PASSWD', 'novaera123');
// define('DBNAME', 'db_atari');
// define('HOST', '85.10.205.173');
// define('PORT', '3306');

define('USER', 'root');
define('PASSWD', '');
define('DBNAME', 'best_albums');
define('HOST', '127.0.0.1');
define('PORT', '3306');
define('OPTIONS', [
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    PDO::ATTR_CASE => PDO::CASE_NATURAL
]);