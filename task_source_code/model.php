<?php
class Model
{

    private $conn = null;
    //connect to db with pdo on model init
    public function __construct()
    {

        try {
            $this->conn = new PDO("mysql:host=" . db_server . ";dbname=" . db_name, db_username, db_password);
            // set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch
        (PDOException $e) {
            //echo "Connection failed: " . $e->getMessage();
        }
    }
    //ready method to get data by query and prepare with pdo
    public function get($query, $opExecute = array(), $type = \PDO::FETCH_ASSOC){
        try{
            $prepare = (is_object($this->conn) && method_exists($this->conn,'prepare')) ? $this->conn->prepare($query) : false;
            if(is_object($prepare)){
                $execute =  (!empty($opExecute) || $opExecute != null) ? $prepare->execute($opExecute) : $prepare->execute();
                if($execute){
                    $data = ($type) ? $prepare->fetchAll($type) : $prepare->fetchAll();
                    if($data){
                        return $data;
                    }
                }
            }
        }catch(\PDOException $e){
            //
        }
        return false;
    }
    //destruct connection on finish
    public function __destruct()
    {
        $this->conn = null;
    }
}