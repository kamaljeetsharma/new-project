<?php
require_once('database.php');
class post{
    private $db;

    public function __construct()
    {
        $this->db=new database();

    }

    public function getpost(){
        $this->db->query("select*from users");
        return $this->db->resultset();
    }



public function getpostbyusername($username){
    $this->db->query("select*from users where username=:username");
        return $this->db->resultset();
        $this->db->bind(':username',$username);
        return $this->db->single();
    }
}