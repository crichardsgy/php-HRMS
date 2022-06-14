<?php
    include_once 'header.php';
    require_once "models/Leave.php";

    $leaveModel = new Leave;
    $leaverequests = $leaveModel->findLeaveRequestsByEmployeeId($_SESSION["employeeId"]);
    $leaveRemaining = $leaveModel->findLeaveAvailableByEmployeeId($_SESSION["employeeId"]);
    $_SESSION["sickleaveAvailable"] = $leaveRemaining->sickleaveAvailable;
    $_SESSION["standardleaveAvailable"] = $leaveRemaining->standardleaveAvailable;
    unset($leaveModel);
?>

<section class="leavemanage">
    <h3>My Leave</h3>
    <br/>
    <?php echo "<h5>You Have ".$_SESSION["sickleaveAvailable"]." Sick Leave Days And ".$_SESSION["standardleaveAvailable"]." Standard Leave Days Remaining</h5>"?>
    <br/>
    <a class='btn btn-dark' href="requestleave.php">Request Standard Leave</a>
    <br/><br/>
    <h5>Leave Requests</h5>


    <table class="table table-bordere ">
        <thead>
        <tr>
            <th>Date Of Request</th>
            <th>Leave Start Time</th>
            <th>Leave End Time</th>
            <th>Days Taken</th>
            <th>Leave Type</th>
            <th>HOD Approval Status</th>
            <th>HR Approval Status</th>
        </tr>
        </thead>

        <?php if(!empty($leaverequests)):?>
            <tbody>
                <?php
                    foreach($leaverequests as $leaverequest) 
                    {
                        echo "<tr>";
                            echo "<td>$leaverequest->requestDate</td>";
                            echo "<td>$leaverequest->leaveStart</td>";
                            echo "<td>$leaverequest->leaveEnd</td>";
                            echo "<td>$leaverequest->daysTaken</td>";
                            echo "<td>$leaverequest->leaveType</td>";
                            echo "<td>$leaverequest->standardleaveHODApprovalStatus</td>";
                            echo "<td>$leaverequest->standardleaveHRApprovalStatus</td>";
                            if ($leaverequest->standardleaveHODApprovalStatus != "denied" && $leaverequest->standardleaveHRApprovalStatus != "denied" && $leaverequest->standardleaveHODApprovalStatus != "approved" && $leaverequest->standardleaveHRApprovalStatus != "approved" && $leaverequest->standardleaveHODApprovalStatus != NULL && $leaverequest->standardleaveHRApprovalStatus != NULL)
                            {
                                echo "<td>
                                <form action='controllers/Leaves.ctrl.php' method='post'>
                                <input type='hidden' name='formtype' value='deleteleaverequest'>
                                <input type='hidden' name='leaveId' value='$leaverequest->leaveId'>
                                <button class='btn btn-danger' type='submit'>Delete</button>
                                </form>
                                </td>";
                            }
                        echo "</tr>";
                    }  
                ?>
            </tbody>
            <?php endif;?>
        </table>
</section>

<?php
    include_once 'footer.php';
?>