<?php

//connects to the database
require_once "Database.php"; 

class Unit
{
    private $db;

    //Creates a database object that allows us to access its functions (see models/Database.php for more info)
    public function __construct()
    {
        $this->db = new Database;
    }

    public function findAllUnits()
    {
        $this->db->query('SELECT units.*,departments.* FROM units JOIN departments ON units.departmentId = departments.departmentId');
        $rows = $this->db->getRecordSet();

        if($this->db->getRowCount() > 0)
        {
            return $rows;
        }
        else
        {
            return false;
        }
    }

    public function findUnitByUnitId($unitId)
    {
        $this->db->query('SELECT units.*,departments.* FROM units JOIN departments ON units.departmentId = departments.departmentId WHERE units.unitId = :unitId');
        $this->db->bind(':unitId', $unitId);
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

    public function createUnit($fields)
    {
        //the query itself with placeholders for data we wish to push
        $this->db->query('INSERT INTO units (unitName,unitDescription,departmentId) VALUES (:name,:description,:departmentId)');

        //replaces those placeholders with the actual data we passed into this function
        $this->db->bind(':name',$fields['unitName']);
        $this->db->bind(':description',$fields['unitDescription']);
        $this->db->bind(':departmentId',$fields['departmentId']);

        //the execution of the prepared statement
        if($this->db->execute())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function updateUnit($fields)
    {
        //the query itself with placeholders for data we wish to push
        $this->db->query('UPDATE units SET unitName = :unitName, unitDescription = :unitDescription, departmentId = :departmentId WHERE unitId = :unitId');

        //replaces those placeholders with the actual data we passed into this function
        $this->db->bind(':unitName',$fields['unitName']);
        $this->db->bind(':unitDescription',$fields['unitDescription']);
        $this->db->bind(':departmentId',$fields['departmentId']);
        $this->db->bind(':unitId',$fields['unitId']);

        //the execution of the prepared statement
        if($this->db->execute())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

}