<?php
$name =$_POST['name'];
$time =$_POST['time'];
//database connection
$conn = new mysqli ('localhost','root','','test');
if($conn->connect_error){
    die('connection Failed:'.&conn->connect_error);
}
else{
    $stmt=&conn->prepare("insert into registration (name,time) values (?,?)");
    $stmt->bind_param("sssssi",$name,$time);
    $stmt->execute();
    echo "registration successfully....";
    $stmt->close();
    $conn->close();
}