<?php

require_once "Database.php"; 

class Attendance 
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function logEmployeeArrivalTime($employeeId, $time)
    {
        $this->db->query('INSERT INTO timesheet (employeeId,signInTime) VALUES (:id,:time)');
        $this->db->bind(':id',$employeeId);
        $this->db->bind(':time',$time);

        $datetime = new DateTime($time);
        $time = $datetime->format('H:i:s');

        if($this->db->execute())
        {
            $attendanceId = $this->db->getLastId();

            $this->db->query("SELECT * FROM hrmsconfig LIMIT 1");
            $hrmsconfig = $this->db->getRecord();

            if($time <= $hrmsconfig->workStart)
            {
                $this->db->query("UPDATE timesheet SET attendanceStatus='ontime' WHERE attendanceId = $attendanceId");
                $this->db->execute();
            }
            elseif($time > $hrmsconfig->workStart)
            {
                $this->db->query("UPDATE timesheet SET attendanceStatus='late' WHERE attendanceId = $attendanceId");
                $this->db->execute();
            }
            return true;
        }
        else
        {
            return false;
        }
    }

    public function findAllEmployeeAttendance()
    {
        if(isset($_SESSION["employeeRole"]) && $_SESSION["employeeRole"] == "hr")
        {
            if ($_SESSION['attendanceviewperiod'] == "today")
            {
                $this->db->query("SELECT departments.*,timesheet.*,employees.*,units.* FROM departments JOIN units ON departments.departmentId = units.DepartmentId JOIN employees ON employees.unitId = units.unitId JOIN timesheet ON timesheet.employeeId = employees.employeeId WHERE DATE(timesheet.signInTime) = CURDATE() ORDER BY timesheet.signInTime DESC");
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
            elseif ($_SESSION['attendanceviewperiod'] == "week")
            {
                $this->db->query("SELECT departments.*,timesheet.*,employees.*,units.* FROM departments JOIN units ON departments.departmentId = units.DepartmentId JOIN employees ON employees.unitId = units.unitId JOIN timesheet ON timesheet.employeeId = employees.employeeId WHERE timesheet.signInTime >= DATE_ADD(CURDATE(),INTERVAL -30 DAY) ORDER BY timesheet.signInTime DESC");
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
            elseif ($_SESSION['attendanceviewperiod'] == "month")
            {
                $this->db->query("SELECT departments.*,timesheet.*,employees.*,units.* FROM departments JOIN units ON departments.departmentId = units.DepartmentId JOIN employees ON employees.unitId = units.unitId JOIN timesheet ON timesheet.employeeId = employees.employeeId WHERE timesheet.signInTime >= DATE_ADD(CURDATE(),INTERVAL -7 DAY) ORDER BY timesheet.signInTime DESC");
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
            elseif ($_SESSION['attendanceviewperiod'] == "all")
            {
                $this->db->query('SELECT departments.*,timesheet.*,employees.*,units.* FROM departments JOIN units ON departments.departmentId = units.DepartmentId JOIN employees ON employees.unitId = units.unitId JOIN timesheet ON timesheet.employeeId = employees.employeeId WHERE DATE(timesheet.signInTime) <= CURDATE() ORDER BY timesheet.signInTime DESC');
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
        }

        elseif(isset($_SESSION["employeeRole"]) && $_SESSION["employeeRole"] == "hod")
        {
            $departmentId = $_SESSION['departmentId'];

            if ($_SESSION['attendanceviewperiod'] == "today")
            {
                $this->db->query("SELECT departments.*,timesheet.*,employees.*,units.* FROM departments JOIN units ON departments.departmentId = units.DepartmentId JOIN employees ON employees.unitId = units.unitId JOIN timesheet ON timesheet.employeeId = employees.employeeId WHERE DATE(timesheet.signInTime) = CURDATE() AND employees.departmentId = $departmentId ORDER BY timesheet.signInTime DESC");
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
            elseif ($_SESSION['attendanceviewperiod'] == "week")
            {
                $this->db->query("SELECT departments.*,timesheet.*,employees.*,units.* FROM departments JOIN units ON departments.departmentId = units.DepartmentId JOIN employees ON employees.unitId = units.unitId JOIN timesheet ON timesheet.employeeId = employees.employeeId WHERE timesheet.signInTime >= DATE_ADD(CURDATE(),INTERVAL -30 DAY) AND employees.departmentId = $departmentId ORDER BY timesheet.signInTime DESC");
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
            elseif ($_SESSION['attendanceviewperiod'] == "month")
            {
                $this->db->query("SELECT departments.*,timesheet.*,employees.*,units.* FROM departments JOIN units ON departments.departmentId = units.DepartmentId JOIN employees ON employees.unitId = units.unitId JOIN timesheet ON timesheet.employeeId = employees.employeeId WHERE timesheet.signInTime >= DATE_ADD(CURDATE(),INTERVAL -7 DAY) AND employees.departmentId = $departmentId ORDER BY timesheet.signInTime DESC");
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
            elseif ($_SESSION['attendanceviewperiod'] == "all")
            {
                $this->db->query("SELECT departments.*,timesheet.*,employees.*,units.* FROM departments JOIN units ON departments.departmentId = units.DepartmentId JOIN employees ON employees.unitId = units.unitId JOIN timesheet ON timesheet.employeeId = employees.employeeId WHERE DATE(timesheet.signInTime) <= CURDATE() AND employees.departmentId = $departmentId ORDER BY timesheet.signInTime DESC");
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
        }


    }

    public function findAttendanceByEmployeeId($employeeId)
    {
        $this->db->query("SELECT departments.*,timesheet.*,employees.*,units.* FROM departments JOIN units ON departments.departmentId = units.DepartmentId JOIN employees ON employees.unitId = units.unitId JOIN timesheet ON timesheet.employeeId = employees.employeeId WHERE timesheet.employeeId = $employeeId AND DATE(timesheet.signInTime) <= CURDATE() ORDER BY timesheet.signInTime DESC");
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

    public function findAllAwolEmployees()
    {
        if(isset($_SESSION["employeeRole"]) && $_SESSION["employeeRole"] == "hr")
        {
            $this->db->query("SELECT employees.*, units.*, departments.* FROM employees JOIN units ON employees.unitId = units.unitId JOIN departments ON departments.departmentId = units.departmentId WHERE employees.employeeId NOT IN (SELECT employeeId FROM timesheet WHERE DATE(timesheet.signInTime) = CURDATE())");
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
        elseif(isset($_SESSION["employeeRole"]) && $_SESSION["employeeRole"] == "hod")
        {
            $departmentId = $_SESSION['departmentId'];
            $this->db->query("SELECT employees.*, units.*, departments.* FROM employees JOIN units ON employees.unitId = units.unitId JOIN departments ON departments.departmentId = units.departmentId WHERE employees.departmentId = $departmentId AND employees.employeeId NOT IN (SELECT employeeId FROM timesheet WHERE DATE(timesheet.signInTime) = CURDATE())");
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
    }

    public function findAttendanceStats($employeeId,$departmentId)
    {

        if($_SESSION['employeeRole'] == "hr")
        {
            $this->db->query("SELECT employees.*, units.*, departments.* FROM employees JOIN units ON employees.unitId = units.unitId JOIN departments ON departments.departmentId = units.departmentId WHERE employees.employeeId NOT IN (SELECT employeeId FROM timesheet WHERE DATE(timesheet.signInTime) = CURDATE())");
            $this->db->execute();
            $awolemployees = $this->db->getRowCount();

            $this->db->query("SELECT timesheet.*,employees.* FROM timesheet JOIN employees ON timesheet.employeeId = employees.employeeId WHERE timesheet.attendanceStatus = 'late' AND DATE(timesheet.signInTime) = CURDATE()");
            $this->db->execute();
            $lateemployees = $this->db->getRowCount();

            $this->db->query("SELECT timesheet.*,employees.* FROM timesheet JOIN employees ON timesheet.employeeId = employees.employeeId WHERE timesheet.attendanceStatus = 'ontime' AND DATE(timesheet.signInTime) = CURDATE()");
            $this->db->execute();
            $ontimeemployees = $this->db->getRowCount();

            $this->db->query("SELECT timesheet.*,employees.* FROM timesheet JOIN employees ON timesheet.employeeId = employees.employeeId WHERE timesheet.attendanceStatus = 'sick' AND DATE(timesheet.signInTime) = CURDATE()");
            $this->db->execute();
            $sickleaveemployees = $this->db->getRowCount();

            $this->db->query("SELECT timesheet.*,employees.* FROM timesheet JOIN employees ON timesheet.employeeId = employees.employeeId WHERE timesheet.attendanceStatus = 'standard' AND DATE(timesheet.signInTime) = CURDATE()");
            $this->db->execute();
            $standardleaveemployees = $this->db->getRowCount();

            $this->db->query("SELECT timesheet.*,employees.* FROM timesheet JOIN employees ON timesheet.employeeId = employees.employeeId WHERE timesheet.attendanceStatus = 'maternity' AND DATE(timesheet.signInTime) = CURDATE()");
            $this->db->execute();
            $maternityleaveemployees = $this->db->getRowCount();

            $this->db->query("SELECT timesheet.*,employees.* FROM timesheet JOIN employees ON timesheet.employeeId = employees.employeeId WHERE timesheet.attendanceStatus = 'paternity' AND DATE(timesheet.signInTime) = CURDATE()");
            $this->db->execute();
            $paternityleaveemployees = $this->db->getRowCount();
        }
        elseif($_SESSION['employeeRole'] == "hod")
        {
            $this->db->query("SELECT employees.*, units.*, departments.* FROM employees JOIN units ON employees.unitId = units.unitId JOIN departments ON departments.departmentId = units.departmentId WHERE employees.departmentId = $departmentId AND employees.employeeId NOT IN (SELECT employeeId FROM timesheet WHERE DATE(timesheet.signInTime) = CURDATE())");
            $this->db->execute();
            $awolemployees = $this->db->getRowCount();

            $this->db->query("SELECT timesheet.*,employees.* FROM timesheet JOIN employees ON timesheet.employeeId = employees.employeeId WHERE employees.departmentId = $departmentId AND timesheet.attendanceStatus = 'late' AND DATE(timesheet.signInTime) = CURDATE()");
            $this->db->execute();
            $lateemployees = $this->db->getRowCount();
    
            $this->db->query("SELECT timesheet.*,employees.* FROM timesheet JOIN employees ON timesheet.employeeId = employees.employeeId WHERE employees.departmentId = $departmentId AND timesheet.attendanceStatus = 'ontime' AND DATE(timesheet.signInTime) = CURDATE()");
            $this->db->execute();
            $ontimeemployees = $this->db->getRowCount();

            $this->db->query("SELECT timesheet.*,employees.* FROM timesheet JOIN employees ON timesheet.employeeId = employees.employeeId WHERE employees.departmentId = $departmentId AND timesheet.attendanceStatus = 'sick' AND DATE(timesheet.signInTime) = CURDATE()");
            $this->db->execute();
            $sickleaveemployees = $this->db->getRowCount();

            $this->db->query("SELECT timesheet.*,employees.* FROM timesheet JOIN employees ON timesheet.employeeId = employees.employeeId WHERE employees.departmentId = $departmentId AND timesheet.attendanceStatus = 'standard' AND DATE(timesheet.signInTime) = CURDATE()");
            $this->db->execute();
            $standardleaveemployees = $this->db->getRowCount();

            $this->db->query("SELECT timesheet.*,employees.* FROM timesheet JOIN employees ON timesheet.employeeId = employees.employeeId WHERE employees.departmentId = $departmentId AND timesheet.attendanceStatus = 'maternity' AND DATE(timesheet.signInTime) = CURDATE()");
            $this->db->execute();
            $maternityleaveemployees = $this->db->getRowCount();

            $this->db->query("SELECT timesheet.*,employees.* FROM timesheet JOIN employees ON timesheet.employeeId = employees.employeeId WHERE employees.departmentId = $departmentId AND timesheet.attendanceStatus = 'paternity' AND DATE(timesheet.signInTime) = CURDATE()");
            $this->db->execute();
            $paternityleaveemployees = $this->db->getRowCount();
        }

        elseif($_SESSION['employeeRole'] == "normal")
        {
            $this->db->query("SELECT employees.*, units.*, departments.* FROM employees JOIN units ON employees.unitId = units.unitId JOIN departments ON departments.departmentId = units.departmentId WHERE employees.employeeId = $employeeId AND employees.employeeId NOT IN (SELECT employeeId FROM timesheet WHERE DATE(timesheet.signInTime) = CURDATE())");
            $this->db->execute();
            $awolemployees = $this->db->getRowCount();

            $this->db->query("SELECT timesheet.*,employees.* FROM timesheet JOIN employees ON timesheet.employeeId = employees.employeeId WHERE employees.employeeId = $employeeId AND timesheet.attendanceStatus = 'late' AND DATE(timesheet.signInTime) >= DATE_ADD(CURDATE(),INTERVAL -30 DAY)");
            $this->db->execute();
            $lateemployees = $this->db->getRowCount();
    
            $this->db->query("SELECT timesheet.*,employees.* FROM timesheet JOIN employees ON timesheet.employeeId = employees.employeeId WHERE employees.employeeId = $employeeId AND timesheet.attendanceStatus = 'ontime' AND DATE(timesheet.signInTime) >= DATE_ADD(CURDATE(),INTERVAL -30 DAY)");
            $this->db->execute();
            $ontimeemployees = $this->db->getRowCount();

            $this->db->query("SELECT timesheet.*,employees.* FROM timesheet JOIN employees ON timesheet.employeeId = employees.employeeId WHERE employees.employeeId = $employeeId AND timesheet.attendanceStatus = 'sick' AND DATE(timesheet.signInTime) >= DATE_ADD(CURDATE(),INTERVAL -30 DAY)");
            $this->db->execute();
            $sickleaveemployees = $this->db->getRowCount();

            $this->db->query("SELECT timesheet.*,employees.* FROM timesheet JOIN employees ON timesheet.employeeId = employees.employeeId WHERE employees.employeeId = $employeeId AND timesheet.attendanceStatus = 'standard' AND DATE(timesheet.signInTime) >= DATE_ADD(CURDATE(),INTERVAL -30 DAY)");
            $this->db->execute();
            $standardleaveemployees = $this->db->getRowCount();

            $this->db->query("SELECT timesheet.*,employees.* FROM timesheet JOIN employees ON timesheet.employeeId = employees.employeeId WHERE employees.employeeId = $employeeId AND timesheet.attendanceStatus = 'maternity' AND DATE(timesheet.signInTime) >= DATE_ADD(CURDATE(),INTERVAL -30 DAY)");
            $this->db->execute();
            $maternityleaveemployees = $this->db->getRowCount();

            $this->db->query("SELECT timesheet.*,employees.* FROM timesheet JOIN employees ON timesheet.employeeId = employees.employeeId WHERE employees.employeeId = $employeeId AND timesheet.attendanceStatus = 'paternity' AND DATE(timesheet.signInTime) >= DATE_ADD(CURDATE(),INTERVAL -30 DAY)");
            $this->db->execute();
            $paternityleaveemployees = $this->db->getRowCount();
        }

        $stats = 
        [
            'awolemployees' => $awolemployees,
            'lateemployees' => $lateemployees,
            'ontimeemployees' => $ontimeemployees,
            'sickleaveemployees' => $sickleaveemployees,
            'standardleaveemployees' => $standardleaveemployees,
            'maternityleaveemployees' => $maternityleaveemployees,
            'paternityleaveemployees' => $paternityleaveemployees
        ];
        return $stats;
    }

    public function findAttendanceStatsByDepartments()
    {
        $this->db->query("SELECT * FROM departments");
        $departments = $this->db->getRecordSet();
        foreach ($departments as $department)
        {
            $this->db->query("SELECT employees.*, timesheet.* FROM employees JOIN timesheet ON timesheet.employeeId = employees.employeeId WHERE employees.departmentId = $department->departmentId AND timesheet.attendanceStatus = 'late' AND DATE(timesheet.signInTime) = CURDATE()");
            $this->db->execute();
            $latecount = $this->db->getRowCount();
            $this->db->query("SELECT employees.*, timesheet.* FROM employees JOIN timesheet ON timesheet.employeeId = employees.employeeId WHERE employees.departmentId = $department->departmentId AND timesheet.attendanceStatus = 'sick' AND DATE(timesheet.signInTime) = CURDATE()");
            $this->db->execute();
            $sickcount = $this->db->getRowCount();
            $this->db->query("SELECT employees.*, units.*, departments.* FROM employees JOIN units ON employees.unitId = units.unitId JOIN departments ON departments.departmentId = units.departmentId WHERE employees.departmentId = $department->departmentId AND employees.employeeId NOT IN (SELECT employeeId FROM timesheet WHERE DATE(timesheet.signInTime) = CURDATE())");
            $this->db->execute();
            $absentcount = $this->db->getRowCount();
            $this->db->query("SELECT timesheet.*,employees.* FROM timesheet JOIN employees ON timesheet.employeeId = employees.employeeId WHERE employees.departmentId = $department->departmentId AND timesheet.attendanceStatus = 'standard' AND DATE(timesheet.signInTime) = CURDATE()");
            $this->db->execute();
            $standardleavecount = $this->db->getRowCount();
            $stats[] = array("department" => $department->departmentName, "late" => $latecount, "sick" => $sickcount, "awol" => $absentcount, "standardleave" => $standardleavecount);
        }
        return $stats;
    }

    public function findAttendanceStatsByUnits()
    {

        if ($_SESSION['employeeRole'] == "hr")
        {
            $this->db->query("SELECT * FROM units");
            $units = $this->db->getRecordSet();

            foreach ($units as $unit)
            {
                $this->db->query("SELECT employees.*, timesheet.* FROM employees JOIN timesheet ON timesheet.employeeId = employees.employeeId WHERE employees.unitId = $unit->unitId AND timesheet.attendanceStatus = 'late' AND DATE(timesheet.signInTime) = CURDATE()");
                $this->db->execute();
                $latecount = $this->db->getRowCount();
                $this->db->query("SELECT employees.*, timesheet.* FROM employees JOIN timesheet ON timesheet.employeeId = employees.employeeId WHERE employees.unitId = $unit->unitId AND timesheet.attendanceStatus = 'sick' AND DATE(timesheet.signInTime) = CURDATE()");
                $this->db->execute();
                $sickcount = $this->db->getRowCount();
                $this->db->query("SELECT employees.*, units.*, departments.* FROM employees JOIN units ON employees.unitId = units.unitId JOIN departments ON departments.departmentId = units.departmentId WHERE employees.unitId = $unit->unitId AND employees.employeeId NOT IN (SELECT employeeId FROM timesheet WHERE DATE(timesheet.signInTime) = CURDATE())");
                $this->db->execute();
                $absentcount = $this->db->getRowCount();
                $this->db->query("SELECT timesheet.*,employees.* FROM timesheet JOIN employees ON timesheet.employeeId = employees.employeeId WHERE employees.unitId = $unit->unitId AND timesheet.attendanceStatus = 'standard' AND DATE(timesheet.signInTime) = CURDATE()");
                $this->db->execute();
                $standardleavecount = $this->db->getRowCount();
                $stats[] = array("unit" => $unit->unitName, "late" => $latecount, "sick" => $sickcount, "awol" => $absentcount, "standardleave" => $standardleavecount);
            }
            return $stats;
        }
        elseif ($_SESSION['employeeRole'] == "hod")
        {
            $departmentId = $_SESSION['departmentId'];

            $this->db->query("SELECT * FROM units WHERE departmentId = $departmentId");
            $units = $this->db->getRecordSet();

            foreach ($units as $unit)
            {
                $this->db->query("SELECT employees.*, timesheet.* FROM employees JOIN timesheet ON timesheet.employeeId = employees.employeeId WHERE employees.unitId = $unit->unitId AND employees.departmentId = $departmentId AND timesheet.attendanceStatus = 'late' AND DATE(timesheet.signInTime) = CURDATE()");
                $this->db->execute();
                $latecount = $this->db->getRowCount();
                $this->db->query("SELECT employees.*, timesheet.* FROM employees JOIN timesheet ON timesheet.employeeId = employees.employeeId WHERE employees.unitId = $unit->unitId AND employees.departmentId = $departmentId AND timesheet.attendanceStatus = 'sick' AND DATE(timesheet.signInTime) = CURDATE()");
                $this->db->execute();
                $sickcount = $this->db->getRowCount();
                $this->db->query("SELECT employees.*, units.*, departments.* FROM employees JOIN units ON employees.unitId = units.unitId JOIN departments ON departments.departmentId = units.departmentId WHERE employees.unitId = $unit->unitId AND employees.departmentId = $departmentId AND employees.employeeId NOT IN (SELECT employeeId FROM timesheet WHERE DATE(timesheet.signInTime) = CURDATE())");
                $this->db->execute();
                $absentcount = $this->db->getRowCount();
                $this->db->query("SELECT timesheet.*,employees.* FROM timesheet JOIN employees ON timesheet.employeeId = employees.employeeId WHERE employees.unitId = $unit->unitId AND employees.departmentId = $departmentId AND timesheet.attendanceStatus = 'standard' AND DATE(timesheet.signInTime) = CURDATE()");
                $this->db->execute();
                $standardleavecount = $this->db->getRowCount();
                $stats[] = array("unit" => $unit->unitName, "late" => $latecount, "sick" => $sickcount, "awol" => $absentcount, "standardleave" => $standardleavecount);
            }
            return $stats;
        }

    }

    public function markAsLeave($fields)
    {
        $this->db->query('INSERT INTO leavesheet (employeeId,leaveStart,leaveEnd,daysTaken,leaveType) VALUES (:employeeId,:leaveStart,:leaveEnd,:daysTaken,:leaveType)');
        $this->db->bind(':employeeId',$fields['employeeId']);
        $this->db->bind(':leaveStart',$fields['date']);
        $this->db->bind(':leaveEnd',$fields['date']);
        $this->db->bind(':daysTaken',$fields['daysTaken']);
        $this->db->bind(':leaveType',$fields['leaveType']);
        $this->db->execute();
        $leaveId = $this->db->getLastId();
        $employeeId = $fields['employeeId'];

        $this->db->query('INSERT INTO timesheet (employeeId,signInTime,attendanceStatus,leaveId) VALUES (:employeeId,:signInTime,:attendanceStatus,:leaveId)');
        $this->db->bind(':employeeId',$fields['employeeId']);
        $this->db->bind(':signInTime',$fields['date']);
        $this->db->bind(':attendanceStatus',$fields['leaveType']);
        $this->db->bind(':leaveId',$leaveId);
        $this->db->execute();

        if($fields['leaveType'] == 'sick')
        {
            $this->db->query("UPDATE employees SET sickleaveAvailable = sickleaveAvailable - 1 WHERE employeeId = $employeeId");
            if($this->db->execute())
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        elseif($fields['leaveType'] == 'standard')
        {
            $this->db->query("UPDATE employees SET standardleaveAvailable = standardleaveAvailable - 1 WHERE employeeId = $employeeId");
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

}