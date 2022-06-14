<?php
session_start();
require_once "../models/Employee.php";



class Employees 
{
    private $employeeModel;

    public function __construct()
    {
        $this->employeeModel = new Employee;
    }

    //(Qixotl LFC, 2021)
    //https://www.youtube.com/watch?v=lSVGLzGBEe0
    public function register()
    {
        $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);

        $fields = [
            'uid' => trim($_POST["uid"]),
            'fname' => trim($_POST["fname"]),
            'lname' => trim($_POST["lname"]),
            'pwd' => trim($_POST["pwd"]),
            'pwdconfirm' => trim($_POST['pwdconfirm']),
            'role' => trim($_POST['role']),
            'address' => trim($_POST['address']),
            'phone' => trim($_POST['phone']),
            'sickleaveEntitlement' => trim($_POST['sickleaveEntitlement']),
            'standardleaveEntitlement' => trim($_POST['standardleaveEntitlement']),
            'department' => trim($_POST['department']),
            'unit' => trim($_POST['unit']),
            'employeeStatus' => trim($_POST['employeeStatus'])
        ];

        if(empty($fields['uid']) || empty($fields['fname']) || empty($fields['lname']) || empty($fields['pwd']) || empty($fields['pwdconfirm']) || empty($fields['address']) || empty($fields['phone']) ||empty($fields['sickleaveEntitlement']) || empty($fields['standardleaveEntitlement']) || empty($fields['unit']) || empty($fields['department']) || empty($fields['employeeStatus']))
        {
            header("location: ../createemployee.php?error=emptyFields");
            exit();
        }

        if(preg_match('/\S{128,}/',$fields['uid'])) //https://stackoverflow.com/questions/12414258/how-to-find-out-if-some-word-in-a-string-is-bigger-than-50-characters-in-php
        {
                header("location: ../createemployee.php?error=tooManyCharsInUid");
                exit();
        }
        if(preg_match('/\S{128,}/',$fields['fname']))
        {
            header("location: ../createemployee.php?error=tooManyCharsInFName");
            exit();
        }
        if(preg_match('/\S{128,}/',$fields['lname'])) //https://stackoverflow.com/questions/12414258/how-to-find-out-if-some-word-in-a-string-is-bigger-than-50-characters-in-php
        {
                header("location: ../createemployee.php?error=tooManyCharsInLName");
                exit();
        }
        if(preg_match('/\S{128,}/',$fields['pwd']))
        {
            header("location: ../createemployee.php?error=tooManyCharsInPwd");
            exit();
        }
        if(preg_match('/\S{128,}/',$fields['address']))
        {
            header("location: ../createemployee.php?error=tooManyCharsInAddress");
            exit();
        }
        if(preg_match('/\S{10,}/',$fields['phone']))
        {
            header("location: ../createemployee.php?error=tooManyCharsInPhone");
            exit();
        }
        if($fields['role'] !== "hod" && $fields['role'] !== "normal" &&  $fields['role'] !== "hr")
        {
            header("location: ../createemployee.php?error=invalidRole");
            exit();
        }
        if($fields['employeeStatus'] !== "employed" && $fields['employeeStatus'] !== "oncontract" &&  $fields['employeeStatus'] !== "retired" &&  $fields['employeeStatus'] !== "terminated" &&  $fields['employeeStatus'] !== "contractend")
        {
            header("location: ../createemployee.php?error=invalidStatus");
            exit();
        }
        if(!preg_match("/^[a-zA-Z0-9]*$/",$fields['uid'])) //https://www.youtube.com/watch?v=gCo6JqGMi30
        {
            header("location: ../createemployee.php?error=invalidUid");
            exit();
        }
        if ($fields['pwd'] !== $fields['pwdconfirm'])
        {
            header("location: ../createemployee.php?error=invalidPwdMatch");
            exit();
        }
        if ($this->employeeModel->findEmployeeByEmployeename($fields['uid']) !== false)
        {
            header("location: ../createemployee.php?error=employeenameTaken");
            exit();
        }

        $fields['pwd'] = password_hash($fields['pwdconfirm'], PASSWORD_DEFAULT);

        if ($this->employeeModel->register($fields))
        {
            header("location: ../manageemployees.php?error=none");
            exit();
        }
        else
        {
            die("Something Went Wrong. Please Try Again Later");
        }

    }

    public function updateEmployee()
    {
        $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);

        $fields = [
            'id' => trim($_POST["editId"]),
            'fname' => trim($_POST["fname"]),
            'lname' => trim($_POST["lname"]),
            'pwd' => trim($_POST["pwd"]),
            'pwdconfirm' => trim($_POST['pwdconfirm']),
            'role' => trim($_POST['role']),
            'address' => trim($_POST['address']),
            'phone' => trim($_POST['phone']),
            'sickleaveEntitlement' => trim($_POST['sickleaveEntitlement']),
            'standardleaveEntitlement' => trim($_POST['standardleaveEntitlement']),
            'sickleaveAvailable' => trim($_POST['sickleaveAvailable']),
            'standardleaveAvailable' => trim($_POST['standardleaveAvailable']),
            'department' => trim($_POST['department']),
            'unit' => trim($_POST['unit']),
            'employeeStatus' => trim($_POST['employeeStatus'])
        ];

        $_SESSION['editId'] = $fields['id'];

        if(empty($fields['fname']) || empty($fields['lname']) || empty($fields['address']) || empty($fields['sickleaveEntitlement']) || empty($fields['standardleaveEntitlement']) || empty($fields['unit']) || empty($fields['department']))
        {
            header("location: ../updateemployee.php?error=emptyFields");
            exit();
        }

        if(preg_match('/\S{128,}/',$fields['uid'])) //https://stackoverflow.com/questions/12414258/how-to-find-out-if-some-word-in-a-string-is-bigger-than-50-characters-in-php
        {
                header("location: ../updateemployee.php?error=tooManyCharsInUid");
                exit();
        }
        if(preg_match('/\S{128,}/',$fields['fname']))
        {
            header("location: ../updateemployee.php?error=tooManyCharsInFName");
            exit();
        }
        if(preg_match('/\S{128,}/',$fields['lname'])) //https://stackoverflow.com/questions/12414258/how-to-find-out-if-some-word-in-a-string-is-bigger-than-50-characters-in-php
        {
                header("location: ../updateemployee.php?error=tooManyCharsInLName");
                exit();
        }
        if(preg_match('/\S{128,}/',$fields['pwd']))
        {
            header("location: ../updateemployee.php?error=tooManyCharsInPwd");
            exit();
        }
        if(preg_match('/\S{128,}/',$fields['address']))
        {
            header("location: ../updateemployee.php?error=tooManyCharsInAddress");
            exit();
        }
        if($fields['role'] !== "hod" && $fields['role'] !== "normal" &&  $fields['role'] !== "hr")
        {
            header("location: ../updateemployee.php?error=invalidRole");
            exit();
        }
        if($fields['employeeStatus'] !== "employed" && $fields['employeeStatus'] !== "oncontract")
        {
            header("location: ../updateemployee.php?error=invalidStatus");
            exit();
        }
        if(!preg_match("/^[a-zA-Z0-9]*$/",$fields['uid'])) //https://www.youtube.com/watch?v=gCo6JqGMi30
        {
            header("location: ../updateemployee.php?error=invalidUid");
            exit();
        }

        if (!empty($fields['pwd']) && !empty($fields['pwdconfirm']))
        {
            if ($fields['pwd'] !== $fields['pwdconfirm'])
            {
                header("location: ../updateemployee.php?error=invalidPwdMatch");
                exit();
            }
            $fields['pwd'] = password_hash($fields['pwdconfirm'], PASSWORD_DEFAULT);
            $updateoptions = "pass";
        }
        else
        {
            $updateoptions = "nopass";
        }

        if ($this->employeeModel->updateEmployee($fields,$updateoptions))
        {
            unset($_SESSION['editId']);
            header("location: ../manageemployees.php?error=none");
            exit();
        }
        else
        {
            unset($_SESSION['editId']);
            die("Something Went Wrong. Please Try Again Later");
        }

    }

    public function login()
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
            session_start();
            $_SESSION["employeeId"] = $row->employeeId;
            $_SESSION["unitId"] = $row->unitId;
            $_SESSION["departmentId"] = $row->departmentId;
            $_SESSION["departmentName"] = $row->departmentName;
            $_SESSION["unitName"] = $row->unitName;
            $_SESSION["employeeFullName"] = $row->employeeFName . " " . $row->employeeLName;
            $_SESSION["employeeUid"] = $row->employeeUid;
            $_SESSION["employeeRole"] = $row->employeeRole;
            $_SESSION["sickleaveAvailable"] = $row->sickleaveAvailable;
            $_SESSION["standardleaveAvailable"] = $row->standardleaveAvailable;
            $_SESSION["employeeFullNameandUid"] = $_SESSION["employeeFullName"] . " (" . $_SESSION["employeeUid"] . ")";
            $_SESSION["attendanceviewperiod"] = "today";
            header("location: ../index.php");
        }
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        header("location: ../index.php");
        exit();
    }

    public function deleteEmployee()
    {
        $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);

        $employeeId = $_POST['deleteId'];

        if($employeeId == $_SESSION['employeeId'])
        {
            header("location: ../manageemployees.php?error=currentlyLoggedInEmployee");
            exit();
        }

        if($this->employeeModel->deleteEmployee($employeeId))
        {
            header("location: ../manageemployees.php?error=none");
        }
        else
        {
            die("Something Went Wrong. Please Try Again Later");
        }
    }

    public function terminateEmployee()
    {
        $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);

        $fields = [
            'employeeId' => trim($_POST["employeeId"]),
            'terminationDate' => date('Y-m-d H:i:s'),
            'reason' => trim($_POST["reason"]),
            'employeeStatus' => trim($_POST["employeeStatus"]),
        ];

        $_SESSION['terminationId'] = $fields['employeeId'];

        if($fields['employeeId'] == $_SESSION['employeeId'])
        {
            header("location: ../terminateemployee.php?error=currentlyLoggedInEmployee");
            exit();
        }

        if(preg_match('/\S{128,}/',$fields['reason']))
        {
            header("location: ../terminateemployee.php?error=tooManyCharsInReason");
            exit();
        }

        if($fields['employeeStatus'] !== "retired" &&  $fields['employeeStatus'] !== "terminated" &&  $fields['employeeStatus'] !== "contractend")
        {
            header("location: ../terminateemployee.php?error=invalidRole");
            exit();
        }

        if($this->employeeModel->terminateEmployee($fields))
        {
            unset($_SESSION['terminationId']);
            header("location: ../manageemployees.php?error=none");
        }
        else
        {
            die("Something Went Wrong. Please Try Again Later");
        }
    }

}

$init = new Employees;

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if($_POST['formtype'] == 'register')
    {
        $init->register();
    }
    else if ($_POST['formtype'] == 'login')
    {
        $init->login();
    }
    else if ($_POST['formtype'] == 'updateemployee')
    {
        $init->updateEmployee();
    }
    //removed for hrms archival purposes
    // else if ($_POST['formtype'] == 'deleteemployee')
    // {
    //     $init->deleteEmployee();
    // }
    else if ($_POST['formtype'] == 'terminateemployee')
    {
        $init->terminateEmployee();
    }
}
else
{
    if($_GET['state'] == 'logout')
    {
        $init->logout();
    }
    else
    {
        header("location: ../index.php");
    }
}