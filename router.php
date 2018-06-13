<?php
//роутер

class router {

    protected $routelist=array();
    //регистрация маршрута.
    //входящие параметры:
    //$method строка с названием метода например 'get'
    //$path определяет по какому пути на сайте следует маршрут
    //$name задаёт уникальное имя. если передана пустая строка то имя автоматически генерируется из пути и метода
    //$callback функция коллбэк которая будет вызвана в случае обращения по данному маршруту
    public function add_route($method, $path, $name, $callback){
        if (''===$name) {
            $name=$path.$method;
        }
        $this->$routelist[$name]=array(strtoupper($method), $path, $callback);
    }
    //регистрация маршрута с методом в имени функции. визуально облегчает анализ списка маршрутов
    //входящие параметры:
    //аналогично методу add_route
    public function get($path, $name, $callback){
        $this->add_route('get', $path, $name, $callback);
    }

    public function post($path, $name, $callback){
        $this->add_route('post', $path, $name, $callback);
    }
    //возвращает строку с путем на сайте и гет параметрами
    //входящие параметры:
    //$name имя маршрута для поиска
    //$params массив вида ключ=значение элементы которого будут добавлены как гет параметры
    public function route($name,$params=null){
        $temp=$this->$routelist[$name];
        if (null===$temp){
            return '';
        }else{
            $data=' ';
            if(is_array($params)){
                $data="?";
                foreach($params as $k => $v){
                    $data .= $k . '=' . $v . '&';
                }
            }
            return $this->$routelist[$name][1] . $data;
        }

    }

    //выполняет непосредственно процесс маршрутизации т.е. ищет маршрут в списке и вызывает его коллбэк функцию
    //в случае если маршрут не найден вызывается переход на страницу 404 с выводом сообщения о неудачном поиске
    //входящие параметры:
    //$request это класс(не объект) согласно параметрам которого и ищется маршрут
    public function exec($request){
        foreach($this->$routelist as $k => $v){
            if (($v[0]===$request::method())&&($v[1]===$request::path())){
                $request::set_router($this);
                $v[2]($request);
                return;
               
            }
        }
        $this->route404('i can not found route with method='.$request::method()." and path=".$request::path());   
    }
    //возвращает массив с маршрутами
    public function list(){
        return $this->$routelist;
    }
    //маршрут на страницу 404 с возможностью передать выводимое сообщение или какой либо другой объект
    //для отладочного вывода
    //входящие параметры:
    //$e переменная любого типа которая будет показана в развернутом виде
    public function route404($e='oops! we got an error.'){
        header("HTTP/1.0 404 Not Found");
        print_r($e);
    }

    //редирект на какой либо маршрут
    //входящие параметры:
    //$routename имя маршрута на которые нужно перейти
    public function redirect($routename){

        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location: http://".$host.$uri.$this->routelist[$routename]);
    }

    
}