<?php
require_once "Database.php";

//(Qixotl LFC, 2021)
//https://www.youtube.com/watch?v=lSVGLzGBEe0
class Employee 
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function findEmployeeByEmployeename($uid)
    {
        $this->db->query('SELECT employees.*, units.*, departments.* FROM employees JOIN units ON employees.unitId = units.unitId JOIN departments ON departments.departmentId = units.departmentId WHERE employees.employeeUid = :uid');
        $this->db->bind(':uid',$uid);

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

    public function findEmployeeById($id)
    {
        $this->db->query('SELECT employees.*, units.*, departments.* FROM employees JOIN units ON employees.unitId = units.unitId JOIN departments ON departments.departmentId = units.departmentId WHERE employees.employeeId = :id');
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

    public function findEmployeeByRole($role)
    {
        $this->db->query('SELECT * FROM employees WHERE employeeRole = :role');
        $this->db->bind(':role',$role);

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


    public function findAllEmployees()
    {
        $this->db->query('SELECT employees.*, units.*, departments.* FROM employees JOIN units ON employees.unitId = units.unitId JOIN departments ON departments.departmentId = units.departmentId WHERE employees.employeeStatus != "terminated" AND employees.employeeStatus != "retired" AND employees.employeeStatus != "contractend"');
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

    public function findAllTerminatedEmployees()
    {
        $this->db->query('SELECT employees.*, units.*, departments.*, terminations.* FROM employees JOIN units ON employees.unitId = units.unitId JOIN departments ON departments.departmentId = units.departmentId JOIN terminations ON employees.employeeId = terminations.employeeId WHERE employees.employeeStatus != "employed" AND employees.employeeStatus != "oncontract"');
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

    public function findEmployeesByDepartmentId($departmentId)
    {
        $this->db->query("SELECT employees.*, units.*, departments.* FROM employees JOIN units ON employees.unitId = units.unitId JOIN departments ON departments.departmentId = units.departmentId WHERE employees.departmentId = $departmentId AND (employees.employeeStatus != 'terminated' AND employees.employeeStatus != 'retired' AND employees.employeeStatus != 'contractend')");
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

    public function findTerminatedEmployeesByDepartmentId($departmentId)
    {
        $this->db->query("SELECT employees.*, units.*, departments.*, terminations.* FROM employees JOIN units ON employees.unitId = units.unitId JOIN departments ON departments.departmentId = units.departmentId JOIN terminations ON employees.employeeId = terminations.employeeId WHERE employees.departmentId = $departmentId AND (employees.employeeStatus != 'employed' AND employees.employeeStatus != 'oncontract')");
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

    public function register($fields)
    {
        $this->db->query('INSERT INTO employees (employeeFName,employeeLName,employeeUid,employeePwd,employeeRole,employeeAddress,employeePhone,employeeStatus,unitId,departmentId,sickleaveEntitlement,sickleaveAvailable,standardleaveEntitlement,standardleaveAvailable) VALUES (:fname,:lname,:uid,:pwd,:role,:address,:phone,:employeeStatus,:unit,:department,:sickent,:sickavl,:standardent,:standardavl)');
        $this->db->bind(':fname',$fields['fname']);
        $this->db->bind(':lname',$fields['lname']);
        $this->db->bind(':uid',$fields['uid']);
        $this->db->bind(':pwd',$fields['pwd']);
        $this->db->bind(':role',$fields['role']);
        $this->db->bind(':address',$fields['address']);
        $this->db->bind(':phone',$fields['phone']);
        $this->db->bind(':employeeStatus',$fields['employeeStatus']);
        $this->db->bind(':unit',$fields['unit']);
        $this->db->bind(':department',$fields['department']);
        $this->db->bind(':sickent',$fields['sickleaveEntitlement']);
        $this->db->bind(':sickavl',$fields['sickleaveEntitlement']);
        $this->db->bind(':standardent',$fields['standardleaveEntitlement']);
        $this->db->bind(':standardavl',$fields['standardleaveEntitlement']);

        if($this->db->execute())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function updateEmployee($fields,$updateoptions)
    {
        if ($updateoptions == "pass")
        {
            $this->db->query('UPDATE employees SET employeeFName = :fname, employeeLName = :lname, employeePwd = :pwd, employeeRole = :role, employeeAddress = :address, employeePhone = :phone, employeeStatus = :employeeStatus, unitId = :unit, departmentId = :department, sickleaveEntitlement = :sickent, sickleaveAvailable = :sickavl, standardleaveEntitlement = :standardent, standardleaveAvailable = :standardavl  WHERE employeeId = :id');
            $this->db->bind(':pwd',$fields['pwd']);
        }
        elseif ($updateoptions == "nopass")
        {
            $this->db->query('UPDATE employees SET employeeFName = :fname, employeeLName = :lname, employeeRole = :role, employeeAddress = :address, employeePhone = :phone, employeeStatus = :employeeStatus, unitId = :unit, departmentId = :department, sickleaveEntitlement = :sickent, sickleaveAvailable = :sickavl, standardleaveEntitlement = :standardent, standardleaveAvailable = :standardavl  WHERE employeeId = :id');
        }

        $this->db->bind(':id',$fields['id']);
        $this->db->bind(':fname',$fields['fname']);
        $this->db->bind(':lname',$fields['lname']);
        $this->db->bind(':role',$fields['role']);
        $this->db->bind(':address',$fields['address']);
        $this->db->bind(':phone',$fields['phone']);
        $this->db->bind(':employeeStatus',$fields['employeeStatus']);
        $this->db->bind(':unit',$fields['unit']);
        $this->db->bind(':department',$fields['department']);
        $this->db->bind(':sickent',$fields['sickleaveEntitlement']);
        $this->db->bind(':sickavl',$fields['sickleaveAvailable']);
        $this->db->bind(':standardent',$fields['standardleaveEntitlement']);
        $this->db->bind(':standardavl',$fields['standardleaveAvailable']);

        if($this->db->execute())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function deleteEmployee($employeeId)
    {
        $this->db->query('DELETE FROM leavesheet WHERE employeeId = :employeeId AND (standardleaveHRApprovalStatus = "pending" OR standardleaveHODApprovalStatus = "pending")');
        $this->db->bind(':employeeId',$employeeId);

        if($this->db->execute())
        {
            $this->db->query('DELETE FROM employees WHERE employeeId = :employeeId');
            $this->db->bind(':employeeId',$employeeId);
            $this->db->execute();
            return true;
        }
        else
        {
            return false;
        }
    }

    public function terminateEmployee($fields)
    {
        $this->db->query('INSERT INTO terminations (employeeId,terminationDate,reason) VALUES (:employeeId,:terminationDate,:reason)');
        $this->db->bind(':employeeId',$fields['employeeId']);
        $this->db->bind(':terminationDate',$fields['terminationDate']);
        $this->db->bind(':reason',$fields['reason']);
        $this->db->execute();

        $this->db->query('UPDATE employees SET employeeStatus = :employeeStatus, employeePwd = :randompwd WHERE employeeId = :employeeId');
        $this->db->bind(':employeeId',$fields['employeeId']);
        $this->db->bind(':employeeStatus',$fields['employeeStatus']);
        $this->db->bind(':randompwd',md5(rand()));
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