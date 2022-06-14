<?php
session_start();

require_once "../models/Leave.php";

class Leaves
{
    private $leaveModel;

    public function __construct()
    {
        $this->leaveModel = new Leave;
    }

    public function requestStandardLeave()
    {
        $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);

        $leaveStart = new DateTime($_POST['leaveStart']);
        $leaveEnd = new DateTime($_POST['leaveEnd']);
        $interval = $leaveStart->diff($leaveEnd);
        $weeks = $interval->days/7;
        $weekenddays = floor($weeks*2);
        $intervalwithoutweekends = $interval->days - $weekenddays;

        if ($leaveEnd < $leaveStart)
        {
            header("location: ../requestleave.php?error=leaveOutOfBounds");
            exit();  
        }

        $leaveavailable = $this->leaveModel->findLeaveAvailableByEmployeeId($_SESSION["employeeId"]);
        if($intervalwithoutweekends > $leaveavailable->standardleaveAvailable || $intervalwithoutweekends <= 0)
        {
            header("location: ../requestleave.php?error=notEnoughLeave");
            exit();
        }

        $fields = 
        [
            'employeeId' => $_SESSION['employeeId'],
            'leaveReason' => $_POST['leaveReason'],
            'leaveStart' => $_POST['leaveStart'],
            'leaveEnd' => $_POST['leaveEnd'],
            'leaveType' => "standard",
            'standardleaveHRApprovalStatus' => "pending",
            'standardleaveHODApprovalStatus' => "pending",
            'daysTaken' => $intervalwithoutweekends,
            'requestDate' => date('Y-m-d H:i:s')
        ];

        if(empty($fields['leaveStart']) || empty($fields['leaveEnd']))
        {
            header("location: ../requestleave.php?error=emptyFields");
            exit();
        }

        if(preg_match('/\S{128,}/',$fields['leaveReason']))
        {
            header("location: ../requestleave.php?error=tooManyCharsInReason");
            exit();
        }

        if($this->leaveModel->requestStandardLeave($fields))
        {
            header("location: ../myleave.php?error=none");
        }
        else
        {
            die("Something Went Wrong. Please Try Again Later");
        }
    }

    public function changeLeaveRequestStatus($action)
    {
        $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);

        $leaveId = $_POST['leaveId'];

        if(isset($_POST['force']) && $_SESSION['employeeRole'] == "hr")
        {
            $force = "y";
            unset($_POST['force']);
        }
        else
        {
            $force = "n";
        }

        if($this->leaveModel->changeLeaveRequestStatus($leaveId,$action,$force))
        {
            header("location: ../manageleaverequests.php?error=none");
        }
        else
        {
            die("Something Went Wrong. Please Try Again Later");
        }
    }

    public function deleteLeaveRequest()
    {
        $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);

        $leaveId = $_POST['leaveId'];

        $leaveRequest = $this->leaveModel->findLeaveRequestByLeaveId($leaveId);
        if ($leaveRequest->employeeId != $_SESSION["employeeId"])
        {
            header("location: ../myleave.php?error=insufficientpermissions");
        }

        if($this->leaveModel->deleteLeaveRequest($leaveId))
        {
            header("location: ../myleave.php?error=none");
        }
        else
        {
            die("Something Went Wrong. Please Try Again Later");
        }
    }
}

$init = new Leaves;

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if ($_POST['formtype'] == 'requeststandardleave')
    {
        $init->requestStandardLeave();
    }
    else if ($_POST['formtype'] == 'approveleaverequest')
    {
        $init->changeLeaveRequestStatus("approve");
    }
    else if ($_POST['formtype'] == 'denyleaverequest')
    {
        $init->changeLeaveRequestStatus("deny");
    }
    else if ($_POST['formtype'] == 'undoapproveleaverequest')
    {
        $init->changeLeaveRequestStatus("undoapprove");
    }
    else if ($_POST['formtype'] == 'undodenyleaverequest')
    {
        $init->changeLeaveRequestStatus("undodeny");
    }
    else if ($_POST['formtype'] == 'deleteleaverequest')
    {
        $init->deleteLeaveRequest();
    }
}
