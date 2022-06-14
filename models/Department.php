<?php

//connects to the database
require_once "Database.php"; 

class Department 
{
    private $db;

    //Creates a database object that allows us to access its functions (see models/Database.php for more info)
    public function __construct()
    {
        $this->db = new Database;
    }

    public function createDepartment($fields)
    {
        //the query itself with placeholders for data we wish to push
        $this->db->query('INSERT INTO departments (departmentName,departmentDescription) VALUES (:name,:description)');

        //replaces those placeholders with the actual data we passed into this function
        $this->db->bind(':name',$fields['departmentName']);
        $this->db->bind(':description',$fields['departmentDescription']);

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

    public function findAllDepartments()
    {
        $this->db->query('SELECT * FROM departments');
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
    public function findDepartmentById($id)
    {
        $this->db->query('SELECT * FROM departments WHERE departmentId = :id');
        $this->db->bind(':id',$id);

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
    public function updateDepartment($fields)
    {
            $this->db->query('UPDATE departments SET departmentName = :departmentName, departmentDescription = :departmentDescription WHERE departmentId = :departmentId');
            $this->db->bind(':departmentId',$fields['departmentId']);
            $this->db->bind(':departmentName',$fields['departmentName']);
            $this->db->bind(':departmentDescription',$fields['departmentDescription']);

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