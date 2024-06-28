<?php
require_once("post.php");

$p= new post();

var_dump($p->getpost());
var_dump($p->getpostbyusername( 5));



//insert record

public function addpost($data){
    $this->db->query("insert into users(name ,email,username,password") values(:name,);
}