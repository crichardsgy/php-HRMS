<?php
require_once "Database.php";

class Leave 
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function findLeaveAvailableByEmployeeId($employeeId)
    {
        $this->db->query("SELECT standardleaveAvailable,sickleaveAvailable FROM employees WHERE employeeId = :employeeId");
        $this->db->bind(':employeeId',$employeeId);

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

    public function requestStandardLeave($fields)
    {
        $this->db->query('INSERT INTO leavesheet (employeeId,requestDate,leaveReason,leaveStart,leaveEnd,daysTaken,leaveType,standardleaveHRApprovalStatus,standardleaveHODApprovalStatus) VALUES (:employeeId,:requestDate,:leaveReason,:leaveStart,:leaveEnd,:daysTaken,:leaveType,:standardleaveHRApprovalStatus,:standardleaveHODApprovalStatus)');
        $this->db->bind(':employeeId',$fields['employeeId']);
        $this->db->bind(':requestDate',$fields['requestDate']);
        $this->db->bind(':leaveReason',$fields['leaveReason']);
        $this->db->bind(':leaveStart',$fields['leaveStart']);
        $this->db->bind(':leaveEnd',$fields['leaveEnd']);
        $this->db->bind(':daysTaken',$fields['daysTaken']);
        $this->db->bind(':leaveType',$fields['leaveType']);
        $this->db->bind(':standardleaveHRApprovalStatus',$fields['standardleaveHRApprovalStatus']);
        $this->db->bind(':standardleaveHODApprovalStatus',$fields['standardleaveHODApprovalStatus']);

        if($this->db->execute())
        {
            $employeeId = $fields['employeeId'];
            $daysTaken = $fields['daysTaken'];
            $this->db->query("UPDATE employees SET standardleaveAvailable = standardleaveAvailable - $daysTaken WHERE employeeId = $employeeId");
            $this->db->execute();
            return true;
        }
        else
        {
            return false;
        }
    }

    public function findLeaveRequestsByEmployeeId($employeeId)
    {
        $this->db->query('SELECT departments.*,leavesheet.*,employees.*,units.* FROM departments JOIN units ON departments.departmentId = units.DepartmentId JOIN employees ON employees.unitId = units.unitId JOIN leavesheet ON leavesheet.employeeId = employees.employeeId WHERE employees.employeeId = :employeeId');

        $this->db->bind(':employeeId',$employeeId);

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

    public function findLeaveRequestsByDepartmentId($departmentId)
    {
        $this->db->query('SELECT departments.*,leavesheet.*,employees.*,units.* FROM departments JOIN units ON departments.departmentId = units.DepartmentId JOIN employees ON employees.unitId = units.unitId JOIN leavesheet ON leavesheet.employeeId = employees.employeeId WHERE departments.departmentId = :departmentId AND leavesheet.standardleaveHRApprovalStatus = "pending"');

        $this->db->bind(':departmentId',$departmentId);

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

    public function findAllNonPendingLeaveRequestsByDepartmentId($departmentId)
    {
        $this->db->query('SELECT departments.*,leavesheet.*,employees.*,units.* FROM departments JOIN units ON departments.departmentId = units.DepartmentId JOIN employees ON employees.unitId = units.unitId JOIN leavesheet ON leavesheet.employeeId = employees.employeeId WHERE departments.departmentId = :departmentId AND leavesheet.standardleaveHODApprovalStatus != "pending" AND leavesheet.standardleaveHRApprovalStatus != "pending"');

        $this->db->bind(':departmentId',$departmentId);

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

    public function findAllNonPendingLeaveRequests()
    {
        $this->db->query('SELECT departments.*,leavesheet.*,employees.*,units.* FROM departments JOIN units ON departments.departmentId = units.DepartmentId JOIN employees ON employees.unitId = units.unitId JOIN leavesheet ON leavesheet.employeeId = employees.employeeId WHERE leavesheet.standardleaveHODApprovalStatus != "pending" AND leavesheet.standardleaveHRApprovalStatus != "pending"');

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

    public function findHODApprovedLeaveRequests()
    {
        $this->db->query('SELECT departments.*,leavesheet.*,employees.*,units.* FROM departments JOIN units ON departments.departmentId = units.DepartmentId JOIN employees ON employees.unitId = units.unitId JOIN leavesheet ON leavesheet.employeeId = employees.employeeId WHERE leavesheet.standardleaveHODApprovalStatus = "approved" AND leavesheet.standardleaveHRApprovalStatus = "pending"');

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

    public function findHODPendingLeaveRequests()
    {
        $this->db->query('SELECT departments.*,leavesheet.*,employees.*,units.* FROM departments JOIN units ON departments.departmentId = units.DepartmentId JOIN employees ON employees.unitId = units.unitId JOIN leavesheet ON leavesheet.employeeId = employees.employeeId WHERE leavesheet.standardleaveHODApprovalStatus != "approved"');

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

    public function findLeaveRequestByLeaveId($leaveId)
    {
        $this->db->query("SELECT * FROM leavesheet WHERE leaveId = $leaveId");

        $rows = $this->db->getRecord();

        if($this->db->getRowCount() > 0)
        {
            return $rows;
        }
        else
        {
            return false;
        }
    }

    public function changeLeaveRequestStatus($leaveId,$action,$force)
    {
        if (isset($_SESSION["employeeRole"]) && $_SESSION["employeeRole"] == "hr")
        {
            if ($action == "approve")
            {
                if($force =="y")
                {
                    $this->db->query("UPDATE leavesheet SET standardleaveHRApprovalStatus='approved',standardleaveHODApprovalStatus='approved' WHERE leaveId = :leaveId");
                    $this->db->bind(':leaveId',$leaveId);
                }
                else
                {
                    $this->db->query("UPDATE leavesheet SET standardleaveHRApprovalStatus='approved' WHERE leaveId = :leaveId");
                    $this->db->bind(':leaveId',$leaveId);
                }

                if($this->db->execute())
                {
                    $this->db->query("SELECT employeeId,leaveStart,leaveEnd,leaveType FROM leavesheet WHERE leaveId = :leaveId");
                    $this->db->bind(':leaveId',$leaveId);
                    $row = $this->db->getRecord();
        
                    $employeeId = $row->employeeId;
                    $leaveType = $row->leaveType;
                    $startDate = strtotime($row->leaveStart);
                    $endDate = strtotime($row->leaveEnd);
        
                    for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate += (86400)) 
                    {
                        $date = date('Y-m-d', $currentDate);
                        $this->db->query('INSERT INTO timesheet (employeeId,signInTime,attendanceStatus,leaveId) VALUES (:employeeId,:signInTime,:attendanceStatus,:leaveId)');
                        $this->db->bind(':employeeId',$employeeId);
                        $this->db->bind(':signInTime',$date);
                        $this->db->bind(':attendanceStatus',$leaveType);
                        $this->db->bind(':leaveId',$leaveId);
                        $this->db->execute();
                    }
                    return true;
                }
                else
                {
                    return false;
                }
            }
            elseif ($action == "undoapprove")
            {    
                if($force =="y")
                {
                    $this->db->query("UPDATE leavesheet SET standardleaveHRApprovalStatus='pending',standardleaveHODApprovalStatus='pending' WHERE leaveId = :leaveId");
                    $this->db->bind(':leaveId',$leaveId);
                }
                else
                {
                    $this->db->query("UPDATE leavesheet SET standardleaveHRApprovalStatus='pending' WHERE leaveId = :leaveId");
                    $this->db->bind(':leaveId',$leaveId);
                }
                
                if($this->db->execute())
                {
                    $this->db->query("DELETE FROM timesheet WHERE leaveId = :leaveId");
                    $this->db->bind(':leaveId',$leaveId);
                    $this->db->execute();
                    return true;
                }
                else
                {
                    return false;
                }
            }
            elseif ($action == "deny")
            {
                $this->db->query("SELECT daysTaken,employeeId FROM leavesheet WHERE leaveId = :leaveId");
                $this->db->bind(':leaveId',$leaveId);
                $rows = $this->db->getRecord();
    
                if($force =="y")
                {
                    $this->db->query("UPDATE leavesheet SET standardleaveHRApprovalStatus='denied',standardleaveHODApprovalStatus='denied' WHERE leaveId = :leaveId");
                    $this->db->bind(':leaveId',$leaveId);
                }
                else
                {
                    $this->db->query("UPDATE leavesheet SET standardleaveHRApprovalStatus='denied' WHERE leaveId = :leaveId");
                    $this->db->bind(':leaveId',$leaveId);
                }
                
                $this->db->execute();
    
                $this->db->query("UPDATE employees SET standardleaveAvailable = standardleaveAvailable + $rows->daysTaken WHERE employeeId = $rows->employeeId");
        
                if($this->db->execute())
                {
                    return true;
                }
                else
                {
                    return false;
                }   
            }
            elseif ($action == "undodeny")
            {
                $this->db->query("SELECT daysTaken,employeeId FROM leavesheet WHERE leaveId = :leaveId");
                $this->db->bind(':leaveId',$leaveId);
                $rows = $this->db->getRecord();
    
                if($force =="y")
                {
                    $this->db->query("UPDATE leavesheet SET standardleaveHRApprovalStatus='pending',standardleaveHODApprovalStatus='pending' WHERE leaveId = :leaveId");
                    $this->db->bind(':leaveId',$leaveId);
                }
                else
                {
                    $this->db->query("UPDATE leavesheet SET standardleaveHRApprovalStatus='pending' WHERE leaveId = :leaveId");
                    $this->db->bind(':leaveId',$leaveId);
                }

                $this->db->execute();
    
                $this->db->query("UPDATE employees SET standardleaveAvailable = standardleaveAvailable - $rows->daysTaken WHERE employeeId = $rows->employeeId");
        
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
        elseif (isset($_SESSION["employeeRole"]) && $_SESSION["employeeRole"] == "hod")
        {
            if ($action == "approve")
            {
                $this->db->query("UPDATE leavesheet SET standardleaveHODApprovalStatus='approved' WHERE leaveId = :leaveId");
                $this->db->bind(':leaveId',$leaveId);
                if($this->db->execute())
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            elseif ($action == "undoapprove")
            {    
                $this->db->query("UPDATE leavesheet SET standardleaveHODApprovalStatus='pending' WHERE leaveId = :leaveId");
                $this->db->bind(':leaveId',$leaveId);
                if($this->db->execute())
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            elseif ($action == "deny")
            {
                $this->db->query("SELECT daysTaken,employeeId FROM leavesheet WHERE leaveId = :leaveId");
                $this->db->bind(':leaveId',$leaveId);
                $rows = $this->db->getRecord();
    
                $this->db->query("UPDATE leavesheet SET standardleaveHODApprovalStatus='denied',standardleaveHRApprovalStatus='denied' WHERE leaveId = :leaveId");
                $this->db->bind(':leaveId',$leaveId);
                $this->db->execute();
    
                $this->db->query("UPDATE employees SET standardleaveAvailable = standardleaveAvailable + $rows->daysTaken WHERE employeeId = $rows->employeeId");
        
                if($this->db->execute())
                {
                    return true;
                }
                else
                {
                    return false;
                }   
            }
            elseif ($action == "undodeny")
            {
                $this->db->query("SELECT daysTaken,employeeId FROM leavesheet WHERE leaveId = :leaveId");
                $this->db->bind(':leaveId',$leaveId);
                $rows = $this->db->getRecord();
    
                $this->db->query("UPDATE leavesheet SET standardleaveHODApprovalStatus='pending',standardleaveHRApprovalStatus='pending'  WHERE leaveId = :leaveId");
                $this->db->bind(':leaveId',$leaveId);
                $this->db->execute();
    
                $this->db->query("UPDATE employees SET standardleaveAvailable = standardleaveAvailable - $rows->daysTaken WHERE employeeId = $rows->employeeId");
        
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

    public function deleteLeaveRequest($leaveId)
    {
        $this->db->query("SELECT daysTaken,employeeId FROM leavesheet WHERE leaveId = :leaveId");
        $this->db->bind(':leaveId',$leaveId);
        $rows = $this->db->getRecord();

        $this->db->query('DELETE FROM leavesheet WHERE leaveId = :leaveId');
        $this->db->bind(':leaveId',$leaveId);

        if($this->db->execute())
        {
            $this->db->query("UPDATE employees SET standardleaveAvailable = standardleaveAvailable + $rows->daysTaken WHERE employeeId = $rows->employeeId");
            $this->db->execute();
            return true;
        }
        else
        {
            return false;
        }
    }

    public function countPendingLeaveRequests($employeeId,$departmentId)
    {
        $this->db->query('SELECT * FROM leavesheet WHERE standardleaveHRApprovalStatus = "pending"');
        $this->db->execute();
        $hr = $this->db->getRowCount();

        $this->db->query("SELECT leavesheet.*,employees.* FROM leavesheet JOIN employees ON leavesheet.employeeId = employees.employeeId WHERE employees.departmentId=$departmentId AND standardleaveHODApprovalStatus = 'pending'");
        $this->db->execute();
        $hod = $this->db->getRowCount();

        $this->db->query("SELECT leavesheet.*,employees.* FROM leavesheet JOIN employees ON leavesheet.employeeId = employees.employeeId WHERE employees.employeeId=$employeeId AND (standardleaveHODApprovalStatus = 'pending' OR standardleaveHRApprovalStatus = 'pending')");
        $this->db->execute();
        $normal = $this->db->getRowCount();

        $ratio = 
        [
            'hr' => $hr,
            'hod' => $hod,
            'normal' => $normal
        ];

        return $ratio;
    }
}