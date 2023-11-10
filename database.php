<?php

class Database
{
    private $host;
    private $username;
    private $password;
    private $databaseName;
    private $conn;

    public function __construct($host, $username, $password, $databaseName){
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->databaseName = $databaseName;
    }
    public function connect(){
        $this->conn=new mysqli($this->host, $this->username, $this->password, $this->databaseName);
        if($this->conn->connect_error){
            die("Connection Failed". $this->conn->connect_error);
    }else{
        return $this->conn;
    }
}
public function create($table, $data)
{
    $columns = implode(", ", array_keys($data));
    $values = "'" . implode("', '", array_values($data)) . "'";
    $query = "INSERT INTO $table ($columns) VALUES ($values)";

    if ($this->conn->query($query)) {
        return $this->conn->insert_id;
    } else {
        return false;
    }
}
public function read($table, $condition = "")
{
    $query = "SELECT * FROM $table";
    // if i want to retreive a specific row by id or email
    if (!empty($condition)) {
        $query .= " WHERE $condition";
    }
    $result = $this->conn->query($query);
    if ($result->num_rows > 0) {
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    } else {
        return false;
    }
}

// implement the UPDATE Column From database
public function update($table, $data, $condition)
{
    $set = "";
    foreach ($data as $column => $value) {
        $set .= "$column = '$value', ";
    }
    $set = rtrim($set, ", ");
    $query = "UPDATE $table SET $set WHERE $condition";
    return $this->conn->query($query);
}

// implement DELETE row from Database
public function delete($table, $condition)
{
    $query = "DELETE FROM $table WHERE $condition";
    return $this->conn->query($query);
}

public function close()
{
    $this->conn->close();
}
}
$host="localhost";
$username= "root";
$password= "";
$databaseName="clinic";
$connection=new Database($host, $username, $password, $databaseName);
$connection->connect();