<?php
require_once 'rawmodel.php';
//класс модели номера телефона
class phonenumber extends rawmodel {

    protected $number;
    protected $id;
    protected $client_id;

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
            $this->id=$initial['id'];
            $this->number=$initial['number'];
            $this->client_id=$initial['client_id'];
            $this->created_at=$initial['created_at'];
            $this->updated_at=$initial['updated_at'];

        }
    }

    //геттеры и сеттеры полей объекта

    public function get_number(){
        return $this->number;
    }

    public function set_number($number){
        $this->number=$number;
    }

    public function get_client_id(){
        return $this->client_id;
    }

    public function set_client_id($client_id){
        $this->client_id=$client_id;
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
        $r['client_id']=$this->client_id;
        $r['number']=$this->number;
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
            $c=new phonenumber($this->datasource, $this->tablename,$obj);
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
            $c=new phonenumber($this->datasource, $this->tablename,$obj);
            $result[]=$c;
        }
        return $result;

    }

    //возвращаем только те записи которые подходят под критерий отбора. передаем или три аргумента 
    // поле, тип сравнения и с чем сравниваем либо массив каждый элемент которого содержит такие же поля
    // плюс поле объединения критериев типа и, или итд какие есть в используемой базе
    public function select_where($fieldname='',$compare=null, $fieldvalue=null){
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
                $prep->bindvalue((':param_'.(string)$n),$v[2],PDO::PARAM_INT);
                $n++;
            }
            
            
        }else{
            $sql='select * from '. $this->tablename . ' where '.$fieldname.' '. $compare .':fieldvalue';
            $prep=$this->datasource->prepare($sql);
            $prep->bindvalue(':fieldvalue', $fieldvalue,PDO::PARAM_INT );    
        }
        $prep->execute();
        $arr=$prep->fetchAll();
        $result=array();
        foreach($arr as $obj){
            $c=new phonenumber($this->datasource, $this->tablename,$obj);
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
            $c=new phonenumber($this->datasource, $this->tablename,$obj);
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
            throw new Exception('phonenumber->update need $data as array');
        }
        $sql="update ". $this->tablename . " set number=:number, client_id=:client_id ".
         " updated_at=now() where id=:id";
        $prep=$this->datasource->prepare($sql);
        $prep->bindparam(':id', $data['id'], PDO::PARAM_INT);
        $prep->bindparam(':number',  $data['number'], PDO::PARAM_STR);
        $prep->bindparam(':client_id',  $data['client_id'], PDO::PARAM_INT);
        $prep->execute();

    }
    //передаём массив типа ключ-знач которые будут сохранены в бд в виде объекта
    //если не передавать параметр то сохранение будет произведено с собственными значениями объекта
    public function save($data=null){
        if(!is_array($data)){
            $data=$this->as_array();
        };
        $sql="INSERT INTO ". $this->tablename . " (number,client_id,created_at)".
        " VALUES(:number,:client_id,now())";
        $prep=$this->datasource->prepare($sql);
        $prep->bindparam(':number',  $data['number'], PDO::PARAM_STR);
        $prep->bindparam(':client_id',  $data['client_id'], PDO::PARAM_INT);
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
};