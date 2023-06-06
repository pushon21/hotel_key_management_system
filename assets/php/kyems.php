<?php
/**
 * Created by PhpStorm.
 * User: CosMOs
 * Date: 9/27/2022
 * Time: 6:29 PM
 */

// use mysqli database
// $mysqli = new mysqli

// simple key management system
class keyms{

    public $mysqli;
    public $is_admin = 0;
    public $is_user = 0;


    function __construct(mysqli  $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    function get_key_status($floor,$room){

    // $stat = 'give';
        $keystat = [];
       $q = $this->mysqli->query("SELECT * FROM `keylogs` where room = '$room' and floor = '$floor' and  lookup = '0' order by id desc limit 1");
       $available = 0;
        while ($row = mysqli_fetch_assoc($q))
        {
            return  $row;
        }
        return $keystat;
    }

    /**
     * @param $floor
     * @param $room
     * @param $name
     * @param $doing  Give|take
     */

    function take_key($floor,$room,$name,$doing){

        $keystat = '';
        $row = $this->get_key_status($floor,$room);
        if(isset($row['giveortake']))
        {
            $keystat = $row['giveortake'];
        }

        if( $keystat !== 'take')
        {
            $time = time();
            $mysqltime = date ('Y-m-d H:i:s');
            $ip = $_SERVER['REMOTE_ADDR'];
            $ua = $this->mysqli->real_escape_string(substr($_SERVER['HTTP_USER_AGENT'],0,500));
            $this->mysqli->query("INSERT INTO `keylogs`(`time`,`tdate`, `name`, `giveortake`, `ip`, `ua`, `floor`, `room`)
 VALUES ('{$time}','{$mysqltime}','{$name}','{$doing}','{$ip}','{$ua}','{$floor}','{$room}')");
            return 1;
        }
        return 0;
    }
    function give_key($floor,$room,$name,$doing){

        $keystat = '';
        $unm = '';
        $row = $this->get_key_status($floor,$room);
        if(isset($row['giveortake']))
        {
            $keystat = $row['giveortake'];
            $unm = $row['name'];
        }
      //  print_r($row);

      //  if($keystat !== 'give' && $unm == $name){
        if($keystat !== 'give'){ //
            $time = time();
            $mysqltime = date ('Y-m-d H:i:s');
            $ip = $_SERVER['REMOTE_ADDR'];
            $ua = $this->mysqli->real_escape_string(substr($_SERVER['HTTP_USER_AGENT'],0,500));
            $this->mysqli->query("INSERT INTO `keylogs`(`time`,`tdate`, `name`, `giveortake`, `ip`, `ua`, `floor`, `room`, `lookup`)
 VALUES ('{$time}','{$mysqltime}','{$name}','{$doing}','{$ip}','{$ua}','{$floor}','{$room}','1')");
          //  $this->gv_update_alltakes($floor,$room,$name);
            $this->gv_update_alltakes($floor,$room,$row['name']);
            return 1;
        }
        return 0;
    }

    function gv_update_alltakes($floor,$room,$user)
    {
        $sql = "UPDATE `keylogs` SET `lookup`='1' WHERE name = '{$user}' and lookup = '0' and floor = '{$floor}' and room = '{$room}' ";
        $this->mysqli->query($sql);
    }


    function get_log_list_html($time = 0,$room = 0,$floor = 0)
    {
        $trs_html = '';
        $q = $this->mysqli->query("SELECT * FROM `keylogs` where room = '$room' and floor = '$floor' order by id");
        while ($row = mysqli_fetch_assoc($q))
        {
            $upload_time = humanTiming($row['time']);
            $taken_by = htmlspecialchars($row['name']);
            $did = $row['giveortake'];
            $roomname = $row['room'];
            $floorname = $row['floor'];
            $date = '';
            $trs_html .= "<tr><td>{$date}</td><td>{$time}</td><td>{$taken_by}</td><td>{$did}</td><td>{$floorname}</td><td>{$roomname}</td></tr>";
        }

    }

    function get_floorrooms_status()
    {
        $sql = "SELECT * FROM keylogs WHERE id IN ( SELECT MAX(id) FROM keylogs GROUP BY room) ORDER BY id DESC;";

        $datum = [];
        $q = $this->mysqli->query($sql);
        while ($row = mysqli_fetch_assoc($q))
        {
            $datum[] = $row;
        }
        return $datum;
    }

// brute force foul coding...
    function ex_get_all_floor()
    {
        $floors = [];
        $sql = "SELECT floor FROM `keylogs` GROUP by floor; ";
        $query = $this->mysqli->query($sql);
        while ($row = mysqli_fetch_assoc($query))
        {
            $floors[] = $row['floor'];
        }
        return $floors;
    }
    function ex_get_all_room($floor)
    {
        $rooms = [];
        $sql = "SELECT room FROM `keylogs` where floor = '{$floor}' GROUP by room; ";
        $query = $this->mysqli->query($sql);
        while ($row = mysqli_fetch_assoc($query))
        {
            $rooms[] = $row['room'];
        }
        return $rooms;
    }

    function ex_check_roomstatus()
    {
        $floors = $this->ex_get_all_floor();
        $all_rooms = [];
        foreach ($floors as  $floor)
        {
            $rooms = $this->ex_get_all_room($floor);
            foreach ($rooms as $room)
            {
                $sql = $this->mysqli->query("SELECT * FROM `keylogs` where floor = '{$floor}' and room = '{$room}' and lookup = '0' order by id desc limit 1");
                while ($row = mysqli_fetch_assoc($sql))
                {
                   $all_rooms[] = $row;
                }
            }
        }
        return $all_rooms;
    }


    function get_data_by_date($date)
    {
        $data = [];
        $sql = "SELECT * FROM `keylogs` where tdate = '{$date}' order by id asc";
        $query = $this->mysqli->query($sql);
        while ($row = mysqli_fetch_assoc($query))
        {
            $originalDate = $row['tdate'];
            $newDate = date("d/m/Y", strtotime($originalDate));
           // $x =  date('m/d/Y H:i:s', $row['time']);
            $x =  date('H:i:s', $row['time']);
            $row['datetime'] = $x;
            $row['date'] = $newDate;
            $data[] = $row;
        }
        return $data;
    }

    function get_i_taken_keys($username)
    {
        $sql = "SELECT * FROM `keylogs` where name = '{$username}' and  giveortake = 'take' order by id desc ";
        $query = $this->mysqli->query($sql);
        while ($row = mysqli_fetch_assoc($query))
        {

        }
    }


    function get_my_info_data($username){

        $xnm = $username;
        $logs = [];
        /*
        $username = $this->mysqli->real_escape_string($username);
        $sql = "SELECT * FROM `keylogs` where name = '{$username}' order id desc limit 100";
        $q = $this->mysqli->query($sql);
        while ($row = mysqli_fetch_assoc($q))
        {
            $logs[] = $row;
        }
*/

        $mydata = [];
        $filterd_info = $this->ex_check_roomstatus();
        foreach ($filterd_info as $value)
        {
            if($value['name'] == $xnm)
            {
                $value['time'] = humanTiming($value['time']);
                $mydata[] = $value;
            }
        }
        return $mydata;

    }


    function cron_take_give_fixer()
    {
        $sql = "SELECT * FROM `keylogs`  where lookup = 0 and giveortake = 'take' order  by id asc";
        $query = $this->mysqli->query($sql);
        while ($row = mysqli_fetch_assoc($query))
        {
            $room = $row['room'];
            $floor = $row['floor'];
            $act = $row['giveortake'];



        }
    }
    function chk_n_update_fixer($id)
    {
        $sql = "UPDATE `keylogs` SET `lookup`='1' WHERE id = ''$id";
        $this->mysqli->query($sql);
        return 1;
    }
}