<?php
require 'config.php';
require 'database.php';
require 'client.php';
require 'request.php';
require 'router.php';
require 'clientscontroller.php';

//заполняем параметры соединения с БД из конфига
database::$db_host=$config['db_host'];
database::$db_name=$config['db_name'];
database::$db_user=$config['db_user'];
database::$db_password=$config['db_password'];

//создаём роутер и контроллер
$router = new router();
$clctrl= new clientscontroller();

//добавляем роуты
$router->get('/','root',array($clctrl,'index'));
$router->get('/clientview','clientview',array($clctrl,'clientview'));
$router->post('/clientupdate','clientupdate',array($clctrl,'clientupdate'));
$router->post('/clientremove','clientremove',array($clctrl,'clientremove'));
$router->post('/clientadd','clientaddpost',array($clctrl,'clientadd'));//для обработки данных роут с методом пост
$router->get('/clientadd','clientaddget',array($clctrl,'clientadd'));//для получения формы роут с методом гет
$router->post('/clientsearch','clientsearchpost',array($clctrl,'clientsearch'));
$router->get('/clientsearch','clientsearchget',array($clctrl,'clientsearch'));

//отправляем пришедший запрос в роутер для определения маршрута обработки
$router->exec(request::class);
