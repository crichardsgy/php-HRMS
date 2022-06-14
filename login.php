<section class="loginform">
    <h3>Log In To Access Features</h3>
    <form action="controllers/Employees.ctrl.php" method="post">
        <input type="hidden" name="formtype" value="login">
        <input type="text" name="uid" placeholder="Username">
        <input type="password" name="pwd" placeholder="Password">
        <button class='btn btn-primary' type="submit" name="submit">Log In</button> 
    </form>
    <br/>

    <h3>Or Log Arrival Time</h3>
    <form action="controllers/Attendances.ctrl.php" method="post">
        <input type="hidden" name="formtype" value="logarrival">
        <input type="text" name="uid" placeholder="Username">
        <input type="password" name="pwd" placeholder="Password">
        <button class='btn btn-primary' type="submit" name="submit">Log Arrival Time</button> 
    </form>
    <br/>
    <?php
        include_once 'includes/errorhandler.inc.php'
    ?>
</section>