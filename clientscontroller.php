<?php
require_once 'phonenumber.php';
class clientscontroller {

    public function index($r){
        $client= new client(database::instance(),'clients');
        $params=$client->select_all();
        include 'forms/index.view.php';
       
    }

    public function clientview($r){
        $client= new client(database::instance(),'clients');

        $params=$client->select_id($r::params()['id'])[0]->as_array();

        $phone= new phonenumber(database::instance(),'phonenumber');

        $phones=$phone->select_where('client_id','=',$params['id']);
        $tellist='';
        foreach($phones as $p){
            $tellist .= $p->get_number() . "\n";
        }
        include 'forms/viewclient.view.php';
        
    }

    public function clientremove($r){
        $client= new client(database::instance(),'clients');
        $client->remove($r::params()['id']);
        $r::get_router()->redirect('root');
        
    }

    public function clientadd($r){

        if($r::method('get')){
            include 'forms/addclient.view.php';
        };
        if($r::method('post')){
            $client= new client(database::instance(),'clients');
            $client->save($r::params());
            $phonenumber = new phonenumber(database::instance(),'phonenumber');
            //делаем выборку и удаляем старые записи о привязанных телефонах
            $oldphones=$phonenumber->select_where('client_id','=',$r::params()['id']);
            if(count($oldphones)>0){
                foreach($oldphones as $v){
                    $v->remove();
                }
            }
            $phones_array=explode("\r\n", $r::params()['phones']);
            if(is_array($phones_array) && (count($phones_array)>0)){
                foreach($phones_array as $v){
                    
                        $pn=new phonenumber(database::instance(),'phonenumber',
                            ['client_id'=>$client->get_id(), 'number'=>$v]);
                        $pn->save();

                    
                }
            }
            $r::get_router()->redirect('root');
        };
    }

    public function clientupdate($r){
        
        $client= new client(database::instance(),'clients',$r::params());
        $phonenumber = new phonenumber(database::instance(),'phonenumber');
        $client->update($r::params());
        //делаем выборку и удаляем старые записи о привязанных телефонах
        $oldphones=$phonenumber->select_where('client_id','=',$r::params()['id']);
        if(count($oldphones)>0){
            foreach($oldphones as $v){
                $v->remove();
            }
        }
        $phones_array=explode("\r\n", $r::params()['phones']);
        if(is_array($phones_array) && (count($phones_array)>0)){
            foreach($phones_array as $v){
               
                    $pn=new phonenumber(database::instance(),'phonenumber',
                        ['client_id'=>$client->get_id(), 'number'=>$v]);
                    $pn->save();

                
            }
        }
        $r::get_router()->redirect('root',$r::params());

    }

    public function clientsearch($r){
        if($r::method('get')){
            include 'forms/searchclient.view.php';
        };
        if($r::method('post')){
            $client= new client(database::instance(),'clients');
            $params=null;
            $resultok=false;
            if($r::params()['surname']<>''){

                $params=$client->select_where('surname', '=',$r::params()['surname'] );
                if(count($params)>0){$resultok=true;}
                
            }else if($r::params()['phonenumber']<>''){
                
                $phone=new phonenumber(database::instance(),'phonenumber');
                $numbers=array();
                $numbers=$phone->select_where('number','=',$r::params()['phonenumber']);
                if(count($numbers)>0){
                    $resultok=true;
                    $criteries=array();
                    foreach($numbers as $n){

                        $criteries[]=array('id','=',$n->get_client_id(),'or');
                    }
                    $params=$client->select_where($criteries);
                }
                
            };
            include 'forms/searchclient.view.php';   
           
        };
    }

   
}