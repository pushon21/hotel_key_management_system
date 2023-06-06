<?php
/**
 * Created by PhpStorm.
 * User: CosMOs
 * Date: 9/27/2022
 * Time: 5:54 PM
 */
date_default_timezone_set('Asia/Dhaka');



session_start();


$action = '';
$response = [
    'status'=> 301,
    'data' => [],
    'time' => time(),
    'action' => $action,
    'msg' => ""
];

if(isset($_REQUEST['action']))
{
    $action = $_REQUEST['action'];
}

include_once 'config.php';
include_once 'functions.php';
include_once 'kyems.php';

$keyms = new keyms($mysqli);
if(isset($_SESSION['is_admin']))
{
    $keyms->is_admin = 1;
}


if($action =='floorroomstatus')
{
 // $data =   $keyms->get_floorrooms_status();
  $dep_arr = $_REQUEST['localdata'];
 // print_r($dep_arr);
 // $response['data'] = $data;
  $response['data'] = $keyms->ex_check_roomstatus();
}
if($action == 'loginadmin')
{
    $adminname = $_REQUEST['adminname'];
    $adminpass = $_REQUEST['adminpassword'];
    if($adminname == 'pushon' && $adminpass == '12345678')
    {
        $keyms->is_admin =1;
        $_SESSION['is_admin'] = 1;
        $response['status'] =200;
    }else{
        $response['status'] = 401;
    }
}

if($action =='update_wantedto')
{
    $wantedto = $_REQUEST['wantedto'];
    $room = $_REQUEST['room'];
    $floor = $_REQUEST['floor'];
    $name = $_REQUEST['name'];
    if($wantedto == 'take')
    {
        if($keyms->take_key($floor,$room,$name,$wantedto))
        {
            $response['data']['wanto'] = $wantedto;
            $response['data']['stat'] = 'done';
            $response['status'] = 200;
            $response['msg'] = "Key takeing complete";
        }else{
            $response['msg'] = "Unable to take key";
        }

    }elseif ($wantedto =='give')
    {
        if($keyms->give_key($floor,$room,$name,$wantedto))
        {
            $response['data']['wanto'] = $wantedto;
            $response['data']['stat'] = 'done';
            $response['status'] = 200;
            $response['msg'] = "Key given complete";
        }else{
            $response['status'] = 603;
            $response['msg'] = "Key Not given";
        }
    }else{
        $response['data']['wanto'] = $wantedto;
        $response['data']['stat'] = 'not done';
        $response['status'] = 501;
        $response['msg'] = "Not done";
    }
}
if($action == 'data_by_date')
{
    if(1)
    {

        $tday = $_REQUEST['tdate'];

        if(preg_match('#([\d]{2})\/([\d]{2})\/([\d]{4})#',$tday,$mtc))
        {
            $tday = "{$mtc[3]}-{$mtc[2]}-{$mtc[1]}";
        }
        $response['tday'] = $tday;
        $response['tdayrq'] = $_REQUEST['tdate'];

        $data =$keyms->get_data_by_date($tday);
        $response['data'] =$data;
        $response['status'] = 200;
    }else{
        $response['msg'] = "you are not admin";
        $response['status'] = 401;
    }
}

if($action == 'getmyinfo')
{
    $username = $_REQUEST['username'];

    $mydata = $keyms->get_my_info_data($username);
    $response['data'] = $mydata;
    $response['status'] = 200;
}


header("Content-Type: application/json");
echo json_encode($response);