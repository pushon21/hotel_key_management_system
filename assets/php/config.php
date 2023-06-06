<?php
/**
 * Created by PhpStorm.
 * User: CosMOs
 * Date: 9/27/2022
 * Time: 5:14 PM
 */


$is_localhost = 0;


$db_user = 'faylab_root';
$db_password = 'faylab_root';
$db_name = 'faylab_test';
$db_host = 'localhost';

if($_SERVER['HTTP_HOST'] == 'localhost')
{
    $db_user = 'root';
    $db_password = '';
    $db_name = 'key_management';
    $db_host = 'localhost';
}


$mysqli = new mysqli($db_host,$db_user,$db_password,$db_name);

