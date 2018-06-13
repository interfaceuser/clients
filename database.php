<?php
// объект операций с бд
class  database {

    public static $db_host='';
    public static $db_name='';
    public static $db_user='';
    public static $db_password='';

    private static $instance=null;
    // дескриптор соединения
    private static $db_conn;

    public static function instance(){
        if(null===self::$instance){
            self::$instance= new database(self::$db_host, self::$db_name, self::$db_user, self::$db_password);
            return self::$db_conn;

        } return self::$db_conn;
    }
    private function __construct($host,$db_name,$db_user,$db_password){
        try {
            self::$db_conn = new PDO('mysql:host='.$host.';dbname='.$db_name, $db_user, $db_pass);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    
    
};