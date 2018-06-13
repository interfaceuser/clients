<?php    
    include 'head.view.php';
    echo '<h1>index</h1>';
    echo "<form action='clientadd' method='get'><input type='submit' value='add new'></form>";
    echo "<form action='clientsearch' method='get'><input type='submit' value='search'></form><br>";
    if(is_array($params)){
        echo '<table border=1>';  
        foreach($params as $v){
                $arr=$v->as_array();
                echo '<tr>';    
                echo '<td>'. $arr['name'].'</td>';
                echo '<td>'. $arr['surname'].'</td>';
                echo '<td>'. $arr['fathername'].'</td>';
                echo '<td>'. $arr['birthday'].'</td>';
                if(true==$arr['sex']){
                    $sex='male';
                }else{
                    $sex='female';
                }
                echo '<td>'. $sex.'</td>';
                echo '<td><a href="' . ($r::get_router()->route('clientview',['id'=> $arr['id']])) . '"> открыть</a>';
                
                echo '</tr>';

            
            
        }
         echo '</table>';
    }


