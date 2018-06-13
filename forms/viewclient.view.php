<?php
include 'head.view.php';
include 'topmenu.view.php';
echo "<h1>viewclient</h1>";
echo "<td><form action='clientremove' method='post'><input type='hidden' name='id'"; 
echo "value='".$params['id']."'><input type='submit' value='удалить'></form><br>";
echo "<form action='/clientupdate' method='post'>";
echo "<input type='hidden' name='id' value=".$params['id']."><br>";
echo "имя : <input type='text' size=100 name='name' value=".$params['name'] . "><br>";
echo "фамилия : <input type='text' size=100 name='surname' value=".$params['surname'] . "><br>";
echo "отчество : <input type='text' size=100 name='fathername' value=".$params['fathername'] . "><br>";
echo "дата рождения : <input type='date' size=100 name='birthday' value=".$params['birthday'] . "><br>";

echo "пол : <input type='text' size=100 name='sex' value=".$params['sex']. "><br>";
echo "<input type='radio' name='sex' value='1'"; 
if ($params['sex']=='1') {echo " checked";}
echo "> male<Br>";
echo "<input type='radio' name='sex' value='0'";
if ($params['sex']=='0') {echo " checked";}
echo "> female<Br>";
echo "телефоны :<textarea name='phones'>".$tellist. "</textarea><br>";
echo "<input type='submit' value='сохранить'>";
echo "</form>";
