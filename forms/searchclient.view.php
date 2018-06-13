<?php
//форма поиска и вывода результатов поиска
include 'head.view.php';
include 'topmenu.view.php';
echo '<h1>search</h1><br>';
echo 'введите значение в нужное поле и нажмите искать. если заполнены оба поля то приоритет имеет поле с фамилией<br>';
echo "<form action='clientsearch' method='post'>".
    "телефон: <input type='text' name='phonenumber' value=''> или ".
    "фамилия:<input type='text' name='surname' value=''>".
    "<input type='submit' value='search'>".
    "</form><br>";

//участок отображения результатов поиска скопипащен из вида списка клиентов

echo '<h3>search results:</h3>';
if(false==$resultok){
    echo 'ничего не найдено';
};
if(is_array($params) && (true==$resultok)){
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