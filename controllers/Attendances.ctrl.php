<?php
session_start();
require_once "../models/Employee.php";
require_once "../models/Attendance.php";



class Attendances 
{
    private $attendanceModel;
    private $employeeModel;

    public function __construct()
    {
        $this->attendanceModel = new Attendance;
        $this->employeeModel = new Employee;
    }


    public function logArrivalTime()
    {
        $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);

        $fields = [
            'uid' => trim($_POST["uid"]),
            'pwd' => trim($_POST["pwd"])
        ];

        if(empty($fields['uid']) || empty($fields['pwd']))
        {
            header("location: ../index.php?error=emptyFields");
            exit();
        }

        $row = $this->employeeModel->findEmployeeByEmployeename($fields['uid']);

        if ($row == false)
        {
            header("location: ../index.php?error=invalidLogin");
            exit();
        }

        $hashedpwd = $row->employeePwd;

        if (password_verify($fields['pwd'],$hashedpwd) === false)
        {
            header("location: ../index.php?error=invalidLogin");
            exit();
        }
        else if (password_verify($fields['pwd'],$hashedpwd) === true)
        {
            //log attendance
            $this->attendanceModel->logEmployeeArrivalTime($row->employeeId,date('Y-m-d H:i:s'));
            header("location: ../index.php?error=none");
        }
    }

    public function markAsLeave($type)
    {
        $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);

        $row = $this->employeeModel->findEmployeeById($_POST["employeeId"]);

        if($type == "sick")
        {
            if($row->sickleaveAvailable < 1)
            {
                header("location: ../manageattendance.php?error=noSickLeaveAvailable");
                exit();
            }
        }
        elseif($type == "standard")
        {
            if($row->standardleaveAvailable < 1)
            {
                header("location: ../manageattendance.php?error=noStandardLeaveAvailable");
                exit();
            }
        }

        $fields = [
            'employeeId' => trim($_POST["employeeId"]),
            'date' => date('Y-m-d'),
            'daysTaken' => 1,
            'leaveType' => $type
        ];

        if($this->attendanceModel->markAsLeave($fields))
        {
            header("location: ../manageattendance.php?error=none");
        }
        else
        {
            die("Something Went Wrong. Please Try Again Later");
        }
    }

    public function changeAttendanceView($timeperiod)
    {
        if ($timeperiod == "today")
        {
            $_SESSION["attendanceviewperiod"] = "today";
        }
        elseif ($timeperiod == "week")
        {
            $_SESSION["attendanceviewperiod"] = "week"; 
        }
        elseif ($timeperiod == "month")
        {
            $_SESSION["attendanceviewperiod"] = "month"; 
        }
        elseif ($timeperiod == "all")
        {
            $_SESSION["attendanceviewperiod"] = "all"; 
        }
        header("location: ../manageattendance.php?error=none");
    }

}

$init = new Attendances;

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if($_POST['formtype'] == 'logarrival')
    {
        $init->logArrivalTime();
    }
    elseif($_POST['formtype'] == 'markassickleave')
    {
        $init->markAsLeave("sick");
    }
    elseif($_POST['formtype'] == 'markasstandardleave')
    {
        $init->markAsLeave("standard");
    }
    elseif($_POST['formtype'] == 'attendancetoday')
    {
        $init->changeAttendanceView("today");
    }
    elseif($_POST['formtype'] == 'attendanceweek')
    {
        $init->changeAttendanceView("week");
    }
    elseif($_POST['formtype'] == 'attendancemonth')
    {
        $init->changeAttendanceView("month");
    }
    elseif($_POST['formtype'] == 'attendanceall')
    {
        $init->changeAttendanceView("all");
    }
}