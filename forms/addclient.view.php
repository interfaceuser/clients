<?php
include 'head.view.php';
include 'topmenu.view.php';
echo "<h1>viewclient</h1>";
echo "<form action='/clientadd' method='post'>";
echo "<input type='hidden' name='id' value=''><br>";
echo "имя : <input type='text' size=100 name='name' value=''><br>";
echo "фамилия : <input type='text' size=100 name='surname' value=''><br>";
echo "отчество : <input type='text' size=100 name='fathername' value=''><br>";
echo "дата рождения : <input type='date' size=100 name='birthday' value=''><br>";
echo "<input type='radio' name='sex' value='1' checked> male<Br>";
echo "<input type='radio' name='sex' value='0'> female<Br>";
echo "телефоны :<textarea name='phones'>". "</textarea><br>";
echo "<input type='submit' value='добавить в базу'>";
echo "</form>";