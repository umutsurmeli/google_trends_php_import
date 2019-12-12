<?php
include('conf.php');
ini_set('display_errors',true);
include('class_trends.php');
$trends = new trends();
$con=$trends->baglan(HOST,USER,PASSWORD,DATABASE);
//var_dump($con);
$trends->create_settings();
$trends->create_datatable();

