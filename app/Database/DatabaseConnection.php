<?php
namespace App\Database;

require_once('vendor/autoload.php');

class DatabaseConnection
{   
    private $servername = "localhost";
    private $username ="root";
    private $password ="";
    private $dbname = "task";
    public $con;

    public function __construct()
    {
        $this->con = new \mysqli($this->servername, $this->username, $this->password, $this->dbname);
        
        if ($this->con->connect_error) {
            die('Connection failed: ' . $this->con->connect_error);
        }
    }
    
    public function insert($table, $data)
    {
        //create a code to insert data in any table
        
        if (count($data) > 0) {
            $lastKey = array_key_last($data);
            $keys = array_keys($data);
            $columns = '';
            foreach ($keys as $key) {
                if ($key == $lastKey) {
                    $columns .= "`{$key}`";
                } else {
                    $columns .= "`{$key}`,";
                }
            }

            $values = array_values($data);
            $valueString = '';
            foreach ($values as $value) {
                if ($value == $data[$lastKey]) {
                    $valueString .= "'{$value}'";
                } else {
                    $valueString .= "'{$value}',";
                }
            }

            $query = "INSERT INTO `{$table}` ({$columns}) VALUES({$valueString})";
            mysqli_query($this->con, $query);
            $hotelId = mysqli_insert_id($this->con);
            return $hotelId;
        }

        return false;
        
    }

    public function select($table, $conditions = [], $columns = [])
    {
        $lastElement = end($columns);
        $cols = '*';
        if (count($columns) > 0) {
            $cols = '';
            foreach ($columns as $column) {
                if ($column == $lastElement) {
                    $cols .= "`{$column}`";
                } else {
                    $cols .= "`{$column}`,";
                }
            }
        }

        $conds = '';
        $lastKey = key($conditions);
        //logic
        if (count($conditions) > 0) {
            $conds = 'WHERE ';
            end($conditions);
            foreach ($conditions as $column => $value){
                if($column == $lastKey){
                    $conds .= "`{$column}` = '{$value}';";
                } else {
                    $conds .= "`{$column}` " . (is_numeric($value) ? "= {$value}" : "= '{$value}'") . " AND ";
                }
            }
        }
        $query = "SELECT {$cols} FROM `{$table}` {$conds}";
        $result = mysqli_query($this->con, $query);
        return $result;         
    }
    
    // DELETE QUERY FUNCTION

    public function delete($table , $conditions = [])
    { 
        $cond = '';
        if(count($conditions) > 0){
           $conds = 'WHERE ';
           $lastKey = end($conditions);
           foreach ($conditions as $column => $value){
               if($value == $lastKey){
                   $conds .= "`{$column}` = '{$value}';";
                } else {
                    $conds .= "`{$column}` " . (is_numeric($value) ? "= {$value}" : "= '{$value}'") . " AND ";
                }
            } 
        }
        $deleteQuery = "DELETE FROM `{$table}`  {$conds}";
        $result = mysqli_query($this->con, $deleteQuery);
        $result;
    

    }
}
?>