<?php
class DBModel
{
    protected $conn;

    public function db(){
        $mysqli  = new mysqli('localhost', 'myuser1', '123', 'mounthlyBudget_db');
        $this->conn = $mysqli;
        $mysqli->set_charset("utf8");
        if($this->conn->connect_error){
            die('Connection error!');
        }
        return $this->conn;
    } 
}
/*'localhost', 'doctoral_myuser1', 'AlexPassDb', 'doctoral_doctoral_db'*/