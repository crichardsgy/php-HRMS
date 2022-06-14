<?php
    include_once 'header.php';
    require_once "models/Leave.php";
?>

<?php if(isset($_SESSION["employeeRole"]) && $_SESSION["employeeRole"] == "hr"): ?>
    <?php 
            $leaveModel = new Leave;
            $hodapprovedleaverequests = $leaveModel->findHODApprovedLeaveRequests();
            $hodpendingleaverequests = $leaveModel->findHODPendingLeaveRequests();
            $nonpendingleaverequests = $leaveModel->findAllNonPendingLeaveRequests();
            unset($leaveModel);
    ?>

    <section class="leavemanagehr">
        <h3>Manage Leave Requests</h3>
        <br/>

        <table class="table table-bordered table-responsive">
            <thead>
            <tr>
                <th>Request Date</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Department</th>
                <th>Unit</th>
                <th>Leave Start Time</th>
                <th>Leave End Time</th>
                <th>Days Taken</th>
                <th>Leave Type</th>
                <th>HOD Approval Status</th>
                <th>HR Approval Status</th>
            </tr>
            </thead>
            <?php if(!empty($hodapprovedleaverequests)):?>
            <tbody>
                <?php
                    foreach($hodapprovedleaverequests as $leaverequest) 
                    {
                        echo "<tr>";
                            echo "<td>$leaverequest->requestDate</td>";
                            echo "<td>$leaverequest->employeeFName</td>";
                            echo "<td>$leaverequest->employeeLName</td>";
                            echo "<td>$leaverequest->departmentName</td>";
                            echo "<td>$leaverequest->unitName</td>";
                            echo "<td>$leaverequest->leaveStart</td>";
                            echo "<td>$leaverequest->leaveEnd</td>";
                            echo "<td>$leaverequest->daysTaken</td>";
                            echo "<td>$leaverequest->leaveType</td>";
                            echo "<td>$leaverequest->standardleaveHODApprovalStatus</td>";
                            echo "<td>$leaverequest->standardleaveHRApprovalStatus</td>";

                            if ($leaverequest->standardleaveHRApprovalStatus == "pending")
                            {
                                echo "<td>
                                <form action='controllers/Leaves.ctrl.php' method='post'>
                                <input type='hidden' name='formtype' value='approveleaverequest'>
                                <button class='btn btn-normal' type='submit' name='leaveId' value='$leaverequest->leaveId'>Approve</button>
                                </form>
                                </td>";
                                echo "<td>
                                <form action='controllers/Leaves.ctrl.php' method='post'>
                                <input type='hidden' name='formtype' value='denyleaverequest'>
                                <button class='btn btn-danger' type='submit' name='leaveId' value='$leaverequest->leaveId'>Deny</button>
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

    <section class="leavemanagehr">
        <h3>All Non Pending Leave Requests</h3>
        <br/>
        <div>
            <input type="text" id="searchbar" onkeyup="searchFilter('searchbar','confirmedleavetable','searchoption')" placeholder="Search Via..">
            <input type="radio" name="searchoption" value="0"> Request Date
            <input type="radio" name="searchoption" value="1"> Name
            <input type="radio" name="searchoption" value="2"> Department
            <input type="radio" name="searchoption" value="3"> Unit
            <input type="radio" name="searchoption" value="7"> Type
        </div>
        <br/>
        <table id="confirmedleavetable" class="table table-bordered table-responsive">
            <thead>
            <tr>
                <th>Request Date</th>
                <th>Name</th>
                <th>Department</th>
                <th>Unit</th>
                <th>Leave Start Time</th>
                <th>Leave End Time</th>
                <th>Days Taken</th>
                <th>Leave Type</th>
                <th>HOD Approval Status</th>
                <th>HR Approval Status</th>
            </tr>
            </thead>
            <?php if(!empty($nonpendingleaverequests)):?>
            <tbody>
                <?php
                    foreach($nonpendingleaverequests as $leaverequest) 
                    {
                        echo "<tr>";
                            $employeeFullNameAndUid = $leaverequest->employeeFName . " " . $leaverequest->employeeLName . " (" . $leaverequest->employeeUid . ")";
                            echo "<td>$leaverequest->requestDate</td>";
                            echo "<td>$employeeFullNameAndUid</td>";
                            echo "<td>$leaverequest->departmentName</td>";
                            echo "<td>$leaverequest->unitName</td>";
                            echo "<td>$leaverequest->leaveStart</td>";
                            echo "<td>$leaverequest->leaveEnd</td>";
                            echo "<td>$leaverequest->daysTaken</td>";
                            echo "<td>$leaverequest->leaveType</td>";
                            echo "<td>$leaverequest->standardleaveHODApprovalStatus</td>";
                            echo "<td>$leaverequest->standardleaveHRApprovalStatus</td>";
                            if ($leaverequest->standardleaveHRApprovalStatus == "approved" && date('Y-m-d') <= $leaverequest->leaveStart)
                            {
                                echo "<td>
                                <form action='controllers/Leaves.ctrl.php' method='post'>
                                <input type='hidden' name='formtype' value='undoapproveleaverequest'>
                                <button class='btn btn-normal' type='submit' name='leaveId' value='$leaverequest->leaveId'>Undo Approve</button>
                                </form>
                                </td>";
                            }
                            elseif ($leaverequest->standardleaveHRApprovalStatus == "denied" && date('Y-m-d') <= $leaverequest->leaveStart)
                            {
                                echo "<td>
                                <form action='controllers/Leaves.ctrl.php' method='post'>
                                <input type='hidden' name='formtype' value='undodenyleaverequest'>
                                <button class='btn btn-normal' type='submit' name='leaveId' value='$leaverequest->leaveId'>Undo Deny</button>
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

    <section class="leavemanagehr">
        <h3>HOD Pending Leave Requests</h3>
        <br/>

        <table class="table table-bordered table-responsive">
            <thead>
            <tr>
                <th>Request Date</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Department</th>
                <th>Unit</th>
                <th>Leave Start Time</th>
                <th>Leave End Time</th>
                <th>Days Taken</th>
                <th>Leave Type</th>
                <th>HOD Approval Status</th>
                <th>HR Approval Status</th>
            </tr>
            </thead>
            <?php if(!empty($hodpendingleaverequests)):?>
            <tbody>
                <?php
                    foreach($hodpendingleaverequests as $leaverequest) 
                    {
                        echo "<tr>";
                            echo "<td>$leaverequest->requestDate</td>";
                            echo "<td>$leaverequest->employeeFName</td>";
                            echo "<td>$leaverequest->employeeLName</td>";
                            echo "<td>$leaverequest->departmentName</td>";
                            echo "<td>$leaverequest->unitName</td>";
                            echo "<td>$leaverequest->leaveStart</td>";
                            echo "<td>$leaverequest->leaveEnd</td>";
                            echo "<td>$leaverequest->daysTaken</td>";
                            echo "<td>$leaverequest->leaveType</td>";
                            echo "<td>$leaverequest->standardleaveHODApprovalStatus</td>";
                            echo "<td>$leaverequest->standardleaveHRApprovalStatus</td>";

                            if ($leaverequest->standardleaveHRApprovalStatus == "pending")
                            {
                                echo "<td>
                                <form action='controllers/Leaves.ctrl.php' method='post'>
                                <input type='hidden' name='formtype' value='approveleaverequest'>
                                <input type='hidden' name='force' value='y'>
                                <button class='btn btn-normal' type='submit' name='leaveId' value='$leaverequest->leaveId'>Force Approve</button>
                                </form>
                                </td>";
                                echo "<td>
                                <form action='controllers/Leaves.ctrl.php' method='post'>
                                <input type='hidden' name='force' value='y'>
                                <input type='hidden' name='formtype' value='denyleaverequest'>
                                <button class='btn btn-danger' type='submit' name='leaveId' value='$leaverequest->leaveId'>Force Deny</button>
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

<?php elseif(isset($_SESSION["employeeRole"]) && $_SESSION["employeeRole"] == "hod"): ?>
    <?php 
            $leaveModel = new Leave;
            $departmentleaverequests = $leaveModel->findLeaveRequestsByDepartmentId($_SESSION['departmentId']);
            $nonpendingleaverequests = $leaveModel->findAllNonPendingLeaveRequestsByDepartmentId($_SESSION['departmentId']);
            unset($leaveModel);
    ?>
    <section class="leavemanagehod">
        <h3>Manage Leave Requests For The Department</h3>
        <br/>

        <table class="table table-bordered table-responsive">
            <thead>
            <tr>
                <th>Request Date</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Department</th>
                <th>Unit</th>
                <th>Leave Start Time</th>
                <th>Leave End Time</th>
                <th>Days Taken</th>
                <th>Leave Type</th>
                <th>HOD Approval Status</th>
                <th>HR Approval Status</th>
            </tr>
            </thead>
            <?php if(!empty($departmentleaverequests)):?>
            <tbody>
                <?php
                    foreach($departmentleaverequests as $leaverequest) 
                    {
                        echo "<tr>";
                            echo "<td>$leaverequest->requestDate</td>";
                            echo "<td>$leaverequest->employeeFName</td>";
                            echo "<td>$leaverequest->employeeLName</td>";
                            echo "<td>$leaverequest->departmentName</td>";
                            echo "<td>$leaverequest->unitName</td>";
                            echo "<td>$leaverequest->leaveStart</td>";
                            echo "<td>$leaverequest->leaveEnd</td>";
                            echo "<td>$leaverequest->daysTaken</td>";
                            echo "<td>$leaverequest->leaveType</td>";
                            echo "<td>$leaverequest->standardleaveHODApprovalStatus</td>";
                            echo "<td>$leaverequest->standardleaveHRApprovalStatus</td>";

                            if ($leaverequest->standardleaveHODApprovalStatus == "pending")
                            {
                                echo "<td>
                                <form action='controllers/Leaves.ctrl.php' method='post'>
                                <input type='hidden' name='formtype' value='approveleaverequest'>
                                <button class='btn btn-normal' type='submit' name='leaveId' value='$leaverequest->leaveId'>Approve</button>
                                </form>
                                </td>";
                                echo "<td>
                                <form action='controllers/Leaves.ctrl.php' method='post'>
                                <input type='hidden' name='formtype' value='denyleaverequest'>
                                <button class='btn btn-danger' type='submit' name='leaveId' value='$leaverequest->leaveId'>Deny</button>
                                </form>
                                </td>";
                            }
                            elseif ($leaverequest->standardleaveHODApprovalStatus == "approved")
                            {
                                echo "<td>
                                <form action='controllers/Leaves.ctrl.php' method='post'>
                                <input type='hidden' name='formtype' value='undoapproveleaverequest'>
                                <button class='btn btn-normal' type='submit' name='leaveId' value='$leaverequest->leaveId'>Undo Approve</button>
                                </form>
                                </td>";
                            }
                            elseif ($leaverequest->standardleaveHODApprovalStatus == "denied")
                            {
                                echo "<td>
                                <form action='controllers/Leaves.ctrl.php' method='post'>
                                <input type='hidden' name='formtype' value='undodenyleaverequest'>
                                <button class='btn btn-normal' type='submit' name='leaveId' value='$leaverequest->leaveId'>Undo Deny</button>
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

    <section class="leavemanagehod">
        <h3>Non Pending Leave Requests For The Department</h3>
        <br/>
        <div>
            <input type="text" id="searchbar" onkeyup="searchFilter('searchbar','confirmedleavetable','searchoption')" placeholder="Search Via..">
            <input type="radio" name="searchoption" value="0"> Request Date
            <input type="radio" name="searchoption" value="1"> Name
            <input type="radio" name="searchoption" value="2"> Department
            <input type="radio" name="searchoption" value="3"> Unit
            <input type="radio" name="searchoption" value="7"> Type
        </div>
        <br/>
        <table id="confirmedleavetable" class="table table-bordered table-responsive">
            <thead>
            <tr>
                <th>Request Date</th>
                <th>Name</th>
                <th>Department</th>
                <th>Unit</th>
                <th>Leave Start Time</th>
                <th>Leave End Time</th>
                <th>Days Taken</th>
                <th>Leave Type</th>
                <th>HOD Approval Status</th>
                <th>HR Approval Status</th>
            </tr>
            </thead>
            <?php if(!empty($nonpendingleaverequests)):?>
            <tbody>
                <?php
                    foreach($nonpendingleaverequests as $leaverequest) 
                    {
                        echo "<tr>";
                            $employeeFullNameAndUid = $leaverequest->employeeFName . " " . $leaverequest->employeeLName . " (" . $leaverequest->employeeUid . ")";
                            echo "<td>$leaverequest->requestDate</td>";
                            echo "<td>$employeeFullNameAndUid</td>";
                            echo "<td>$leaverequest->departmentName</td>";
                            echo "<td>$leaverequest->unitName</td>";
                            echo "<td>$leaverequest->leaveStart</td>";
                            echo "<td>$leaverequest->leaveEnd</td>";
                            echo "<td>$leaverequest->daysTaken</td>";
                            echo "<td>$leaverequest->leaveType</td>";
                            echo "<td>$leaverequest->standardleaveHODApprovalStatus</td>";
                            echo "<td>$leaverequest->standardleaveHRApprovalStatus</td>";
                        echo "</tr>";
                    }  
                ?>
            </tbody>
            <?php endif;?>
        </table>
    </section>
<?php endif;?>

<script>
//https://www.w3schools.com/howto/howto_js_filter_table.asp
function searchFilter(searchbarid,tableid,radioboxid) {
  var input, filter, table, col, tr, td, i, txtValue;
  col = document.querySelector('input[name="'+radioboxid+'"]:checked').value;
  input = document.getElementById(searchbarid);
  filter = input.value.toUpperCase();
  table = document.getElementById(tableid);
  tr = table.getElementsByTagName("tr");

  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[col];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
</script>

<?php
    include_once 'footer.php';
?>