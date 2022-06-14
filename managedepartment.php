<?php
    //this integrates the navbar and the libraries used into this form
    include_once 'header.php';
    require_once "models/Department.php";

    $departmentModel = new Department;
    $alldepartment = $departmentModel->findAllDepartments();
    unset($departmentModel);
?>

<section class="leavemanage">
    <h3>Departments</h3>
    <br/>
    <a class='btn btn-dark' href="createdepartment.php">Add Department</a>
    <br>
    <br>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Department Name</th>
            <th>Department Description</th>
            <th>Action</th>
 
        </tr>
        </thead>
        <tbody>
            <?php
                foreach($alldepartment as $departments) 
                {
                        echo "<tr>";
                        echo "<td>$departments->departmentName</td>";
                        echo "<td>$departments->departmentDescription</td>";
                        echo "<td>
                        <form action='updatedepartment.php' method='post'>
                        <button class='btn btn-warning' type='submit' name='editId' value='$departments->departmentId'>Edit</button>
                        </form>
                        </td>";
                        // echo "<td>
                        // <form action='controllers/Employees.ctrl.php' method='post'>
                        // <input type='hidden' name='formtype' value='deleteemployee'>
                        // <button class='btn btn-danger' type='submit' name='deleteId' value='$employee->employeeId'>Delete</button>
                        // </form>
                        // </td>";
                        
                        echo "</tr>";
                }  
            ?>
        </tbody>
    </table>
</section>


<?php
    include_once 'footer.php';
?>