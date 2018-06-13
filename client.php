<?php

require_once 'rawmodel.php';
require_once 'database.php';
//объектное представление клиента
class client extends rawmodel{

    protected $datasource=null;
    protected $name;
    protected $surname;
    protected $fathername;
    protected $birthday;
    protected $sex;
    protected $id;

    protected $created_at;
    protected $updated_at;



    //конструктор объекта. в случае переданного массива $inital заполняет объект из него
    //входящие параметры:
    //$datasource объект класса database
    //$tablename имя таблицы бд которая привязана к этой модели
    //$inital необязательный. массив вида ключ=значение
    public function __construct($datasource,$tablename,$initial=null) {
        $this->datasource=$datasource;
        $this->tablename=$tablename;
        if(is_array($initial)){
            $this->name=$initial['name'];
            $this->surname=$initial['surname'];
            $this->fathername=$initial['fathername'];
            $this->birthday=$initial['birthday'];
            $this->sex=$initial['sex'];
            $this->id=$initial['id'];
            $this->created_at=$initial['created_at'];
            $this->updated_at=$initial['updated_at'];

        }
    }

    //геттеры и сеттеры полей объекта
    public function get_id(){
        return $this->id;
    }

    public function get_name(){
        return $this->name;
    }

    public function set_name($name){
        $this->name=$name;
    }

    public function get_surname(){

    }

    public function set_surname($surname){
        $this->surname=$surname;
    }

    public function get_fathername(){

    }

    public function set_fathername($fathername){

    }

    public function get_birthday(){

    }

    public function set_birthday($birthday){

    }

    public function get_sex(){

    }

    public function set_sex($sex){

    }

    public function get_created(){
        return $this->created_at;
    }

    public function get_updated(){
        return $this->updated_at;
    }

    //возкращаем представление собственных данных в виде массива
    public function as_array(){
        $r=array();
        $r['id']=$this->id;
        $r['name']=$this->name;
        $r['surname']=$this->surname;
        $r['fathername']=$this->fathername;
        $r['birthday']=$this->birthday;
        $r['sex']=$this->sex;
        $r['created_at']=$this->created_at;
        $r['updated_at']=$this->updated_at;
        return $r;
    }

    //возвращаем всю таблицу в массив объектов клиент
    public function select_all(){
        $sql='select * from '. $this->tablename;//формируем строку sql запроса
        $prep=$this->datasource->prepare($sql);//подготавливаем запрос
        $prep->execute();//выполняем запрос
        $arr=$prep->fetchAll();//разбираем ответ от бд получая массив содержащий массивы вида ключ=значение
        $result=array();
        foreach($arr as $obj){
            $c=new client($this->datasource, $this->tablename,$obj);
            $result[]=$c;
        }
        return $result;

    }
    //получаем колво записей $count начиная с записи $from
    //входящие параметры:
    //$from число определяет с какой записи начинать вборку(смещение от начала таблицы)
    //$count число определяет кол-во выбираемых записей
    public function select_range($from, $count){
        $sql='select * from '. $this->tablename . ' limit '. $count  . ' offset ' . $from;
        $prep=$this->datasource->prepare($sql);
        $prep->execute();
        $arr=$prep->fetchAll();
        $result=array();
        foreach($arr as $obj){
            $c=new client($this->datasource, $this->tablename,$obj);
            $result[]=$c;
        }
        return $result;

    }

    //возвращаем только те записи которые подходят под критерий отбора. передаем или три аргумента 
    // поле, тип сравнения и с чем сравниваем либо массив каждый элемент которого содержит такие же поля
    // плюс поле объединения критериев типа и, или итд какие есть в используемой базе
    public function select_where($fieldname=null,$compare=null, $fieldvalue=null){

        if(is_array($fieldname)){
            
            $len=count($fieldname);
            $criteries='';
            //склеиваем строку из критериев отбора
            $n=0;
            foreach($fieldname as $v){
                if($n==($len-1)){break;};//выходим на предпоследнем элементе чтобы в строку не попало объединение критериев последнее
                $criteries .=' '.$v[0].' ' .$v[1].' :param_'.(string)$n.' '.$v[3].' ';

                
                $n++;
                
            }
            $criteries .=' '.$fieldname[$n][0].' ' .$fieldname[$n][1]. ' :param_'.($n);
            
            $sql='select * from '. $this->tablename . ' where '.$criteries;
            $prep=$this->datasource->prepare($sql);
            //теперь привязываем значения к подстановочным параметрам
            $n=0;
            foreach($fieldname as $v){
                if(gettype($v[2])=='integer'){
                    $vartype=PDO::PARAM_INT;
                }else{
                    $vartype=PDO::PARAM_STR;
                }
                $prep->bindparam((':param_'.(string)$n),$v[2],$vartype);
                $n++;
            }
            
            
        }else{
            $vartype='';
            if(gettype($fieldvalue)=='integer'){
                $vartype=PDO::PARAM_INT;
            }else{
                $vartype=PDO::PARAM_STR;
            }
            $sql='select * from '. $this->tablename . ' where '.$fieldname.' '. $compare .' :fieldvalue';
            $prep=$this->datasource->prepare($sql);
            $prep->bindparam(':fieldvalue', $fieldvalue,$vartype );    
        }
        $prep->execute();
        $arr=$prep->fetchAll();
        $result=array();
        foreach($arr as $obj){
            $c=new client($this->datasource, $this->tablename,$obj);
            $result[]=$c;
        }
        return $result;


    }
    //возвращаем максимум одно значение если оно найдено по совпадению ид
    //входные параметры:
    //$id необязателен. если не передан то выборка будет произведена по собственному ид объекта
    public function select_id($id=null){
        if(null==$id){
            $id=$this->id;
        }
        $sql='select * from '. $this->tablename . ' where id=:id  limit 1';//текст запроса с подстановочными параметрами
        $prep=$this->datasource->prepare($sql);
        $prep->bindparam(':id', $id, PDO::PARAM_INT);//привязываем реальные данные к подстановочному параметру
        $prep->execute();
        $arr=$prep->fetchAll();
        $result=array();
        foreach($arr as $obj){
            $c=new client($this->datasource, $this->tablename,$obj);
            $result[]=$c;
        }
        return $result;
    
    }
    //передаём массив типа ключ=знач. апдейт идет по отбору на равенство значения ключа id из переданного массива
    //входящие параметры:
    //$data массив вида ключ=значение. содержит значения на которые будут заменены имеющиеся в таблице
    public function update($data=null){
        if(is_array($data)){

        }else{
            throw new Exception('client->update need $data as array');
        }
        $sql="update ". $this->tablename . " set name=:name, surname=:surname, fathername=:fathername,".
         " birthday=:birthday, sex=:sex, updated_at=now() where id=:id";
        $prep=$this->datasource->prepare($sql);
        $prep->bindparam(':id', $data['id'], PDO::PARAM_INT);
        $prep->bindparam(':name',  $data['name'], PDO::PARAM_STR);
        $prep->bindparam(':surname',  $data['surname'], PDO::PARAM_STR);
        $prep->bindparam(':fathername',  $data['fathername'], PDO::PARAM_STR);
        $prep->bindparam(':birthday',  $data['birthday'],  PDO::PARAM_STR);
        $prep->bindparam(':sex',  $data['sex'], PDO::PARAM_BOOL);
        $prep->execute();

    }
    //передаём массив типа ключ-знач которые будут сохранены в бд в виде объекта
    //если не передавать параметр то сохранение будет произведено с собственными значениями объекта
    public function save($data=null){
        if(!is_array($data)){
            $data=$this->as_array();
        };
        $sql="INSERT INTO ". $this->tablename . " (name,surname,fathername,birthday,sex,created_at)".
        " VALUES(:name,:surname,:fathername,:birthday,:sex,now())";
        $prep=$this->datasource->prepare($sql);
        $prep->bindparam(':name',  $data['name'], PDO::PARAM_STR);
        $prep->bindparam(':surname',  $data['surname'], PDO::PARAM_STR);
        $prep->bindparam(':fathername',  $data['fathername'], PDO::PARAM_STR);
        $prep->bindparam(':birthday',  $data['birthday'],  PDO::PARAM_STR);
        $prep->bindparam(':sex',  $data['sex'], PDO::PARAM_BOOL);
        $prep->execute();
        $this->id=$this->datasource->lastInsertId();

    }
    //удаляет объект. если не передан параметр то удаление будет по собственному id
    //входящие параметры:
    //$id необязателен. если передан определяет ид объекта который будет удален из бд
    public function remove($id=null){
        if(null==$id){
            $id=$this->id; 
        }
        $sql="delete from " . $this->tablename . " where id=:id";
        $prep=$this->datasource->prepare($sql);
        $prep->bindparam(':id', $id, PDO::PARAM_INT);
        $prep->execute();

    }

    
}