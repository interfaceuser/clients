<?php
// разбирает запрос юзера в объект
class request {

    private static $method=null;
    private static $path=null;
    private static $params=null;
    private static $router=null;

 
    public static function set_router($router){
        self::$router=$router;
    }

    public static function get_router(){
        return self::$router;
    }
    //возвращает путь запроса например для www.site.ru/pages/page?id=1 это будет pages/page
    public static function path(){
        if (null==self::$path){
            self::$path = $_SERVER['REQUEST_URI'];
            $n=strpos(self::$path,'?');
            if (false !== $n){
                self::$path=substr(self::$path,0,$n);    
            }
         }
         
        return self::$path;
         
            
    }
    //возвращает парметры запроса универсально массивом независимо от метода запроса
    public static function params(){
        if (null==self::$params){
            switch(self::method()){
                case 'POST':{
                    $target=$_POST;
                    break;
                }
                case 'GET':{
                    $target=$_GET;
                    break;
                }
                default: return array();
            }
            foreach ($target as $k => $v){
                
                self::$params[$k] = $v;
            }  
        }
        
        return self::$params;
         
    }
    //возвращает метод запроса
    public static function method($comparewith=null){
        if (null==self::$method){
           self::$method = $_SERVER['REQUEST_METHOD'];
        }
        if(null==$comparewith){
            return strtoupper(self::$method);
        }else{
            return (strtoupper($comparewith)==self::$method);
        }
        
        
    }
}
