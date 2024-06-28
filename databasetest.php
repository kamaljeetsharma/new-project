<?php 
require_once("Database.php");

$db=new Database();
echo $db->isconnected()? "DB Connected" :  "db not connected";

if(!$db->isconnected()){
    echo$db->getError();
    die('unable to Connect to DB');
}
$db->query("select*from users");
var_dump($db->resultset() );
echo "Rows: ".$db->rowCount();
var_dump($db->single());
$db->query("select*From users where username=username");
$db->bind(':username',1);
var_dump($db->single());