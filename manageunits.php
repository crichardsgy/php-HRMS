<?php
    //this integrates the navbar and the libraries used into this form
    include_once 'header.php';
    require_once "models/Unit.php";

    $unitModel = new Unit;
    $allunits = $unitModel->findAllUnits();
    unset($departmentModel);
?>

<section>
    <h3>Unit</h3>
    <br/>
    <a class='btn btn-dark' href="createunit.php">Add Unit</a>
    <br>
    <br>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Unit Name</th>
            <th>Unit Description</th>
            <th>Department</th>
            <th>Action</th>
 
        </tr>
        </thead>
        <tbody>
            <?php
                foreach($allunits as $unit) 
                {
                        echo "<tr>";
                        echo "<td>$unit->unitName</td>";
                        echo "<td>$unit->unitDescription</td>";
                        echo "<td>$unit->departmentName</td>";
                        echo "<td>
                        <form action='updateunit.php' method='post'>
                        <button class='btn btn-warning' type='submit' name='editId' value='$unit->unitId'>Edit</button>
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