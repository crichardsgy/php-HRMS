<?php

//connects to the database
require_once "Database.php"; 

class HRMSConfig 
{
    private $db;

    //Creates a database object that allows us to access its functions (see models/Database.php for more info)
    public function __construct()
    {
        $this->db = new Database;
    }

    public function updateConfig($fields)
    {
        $this->db->query('UPDATE hrmsconfig SET workStart = :start, workEnd = :end, maxLeaveAccumulation = :maxLeaveAccumulation WHERE configId = 1');
        $this->db->bind(':start',$fields['workStart']);
        $this->db->bind(':end',$fields['workEnd']);
        $this->db->bind(':maxLeaveAccumulation',$fields['maxLeaveAccumulation']);

        if($this->db->execute())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function findConfigs()
    {
        $this->db->query('SELECT * FROM hrmsconfig WHERE configId = 1');
        $row = $this->db->getRecord();

        if($this->db->getRowCount() > 0)
        {
            return $row;
        }
        else
        {
            return false;
        }
    }

    public function showVariables()
    {
        $this->db->query("show variables where variable_name='event_scheduler'");
        $row = $this->db->getRecord();

        if($this->db->getRowCount() > 0)
        {
            return $row;
        }
        else
        {
            return false;
        }
    }
}