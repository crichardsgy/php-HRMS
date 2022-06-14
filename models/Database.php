<?php 

class Database 
{
    private $host = 'localhost';
    private $db = 'hrms';
    private $user = 'root';
    private $password = '';

    private $dbh;
    private $stmt;
    private $error;

    public function __construct() 
    {

        $dsn = 'mysql:host=' . $this->host .';dbname='. $this->db;
        $opt = array(PDO::ATTR_PERSISTENT => true,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
        try
        {
            $this->dbh = new PDO($dsn, $this->user, $this->password, $opt);
        }
        catch(PDOException $e)
        {
            $this->error = $e->getMessage();
            echo $this->error;
        }

    }

    public function query($sql)
    {
        $this->stmt = $this->dbh->prepare($sql);
    }

    public function bind($param, $value, $type = null)
    {
        if(is_null($type))
        {
            if (is_int($value))
            {
                $type = PDO::PARAM_INT;
            }
            else if (is_null($value))
            {
                $type = PDO::PARAM_NULL;
            }
            else if (is_bool($value))
            {
                $type = PDO::PARAM_BOOL;
            }
            else
            {
                $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param,$value,$type);
    }

    public function execute()
    {
        return $this->stmt->execute();
    }

    public function getRowCount()
    {
        return $this->stmt->rowCount();
    }

    public function getLastId()
    {
        return $this->dbh->lastInsertId();
    }

    public function getRecord()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getRecordSet()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

}
