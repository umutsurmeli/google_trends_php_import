<?php
$host='localhost';
$user = 'root';
$password = '';
$db = 'trends';
ini_set('display_errors',true);
include('class_trends.php');
$trends = new trends();
$con=$trends->baglan($host,$user,$password,$db);
//var_dump($con);
$trends->show_create('settings');