<?php 

    include_once "Database.php";
    $db = new Database;

    $createStatements = 
    [
      " CREATE TABLE IF NOT EXISTS departments(
          departmentId int(10) NOT NULL AUTO_INCREMENT,
          departmentName varchar(128) NOT NULL,
          departmentDescription varchar(128),
          PRIMARY KEY (departmentId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

        
      " CREATE TABLE IF NOT EXISTS units(
          unitId int(10) NOT NULL AUTO_INCREMENT,
          unitName varchar(128) NOT NULL,
          unitDescription varchar(128) NOT NULL,
          departmentId int(10) NOT NULL,
          FOREIGN KEY (departmentId) REFERENCES departments(departmentId),
          PRIMARY KEY (unitId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

      " CREATE TABLE IF NOT EXISTS employees(
          employeeId int(10) NOT NULL AUTO_INCREMENT,
          employeeUid varchar(128) NOT NULL,
          employeePwd varchar(128) NOT NULL,
          employeeRole varchar(10) NOT NULL,
          employeeFName varchar(128) NOT NULL,
          employeeLName varchar(128) NOT NULL,
          employeeAddress varchar(128) NOT NULL,
          employeePhone varchar(128) NOT NULL,
          employeeStatus varchar(12) NOT NULL,
          sickleaveEntitlement int,
          sickleaveAvailable int,
          standardleaveEntitlement int,
          standardleaveAvailable int,
          unitId int(10) NOT NULL,
          departmentId int(10) NOT NULL,
          FOREIGN KEY (unitId) REFERENCES units(unitId),
          FOREIGN KEY (departmentId) REFERENCES departments(departmentId),
          PRIMARY KEY (employeeId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

      "  CREATE TABLE IF NOT EXISTS leavesheet( 
          leaveId  int NOT NULL AUTO_INCREMENT,
          employeeId   int(10) NOT NULL,
          requestDate DATETIME,
          leaveReason varchar(128),
          leaveStart DATE NOT NULL,
          leaveEnd DATE NOT NULL,
          daysTaken int,
          leaveType varchar(32),
          standardleaveHRApprovalStatus varchar(8),
          standardleaveHODApprovalStatus varchar(8),
          PRIMARY KEY (leaveId),
          FOREIGN KEY (employeeId) REFERENCES employees(employeeId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

      " CREATE TABLE IF NOT EXISTS timesheet( 
        attendanceId  int NOT NULL AUTO_INCREMENT,
        employeeId   int(10) NOT NULL,
        signInTime DATETIME,
        attendanceStatus varchar(10),
        leaveId int,
        PRIMARY KEY (attendanceId),
        FOREIGN KEY (employeeId) REFERENCES employees(employeeId),
        FOREIGN KEY (leaveId) REFERENCES leavesheet(leaveId)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

      " CREATE TABLE IF NOT EXISTS terminations( 
        terminationId  int NOT NULL AUTO_INCREMENT,
        employeeId   int(10) NOT NULL,
        terminationDate DATETIME NOT NULL,
        reason varchar(128),
        PRIMARY KEY (terminationId),
        FOREIGN KEY (employeeId) REFERENCES employees(employeeId)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

      " CREATE TABLE IF NOT EXISTS hrmsconfig(
          configId int NOT NULL AUTO_INCREMENT, 
          workStart TIME NOT NULL,
          workEnd TIME NOT NULL,
          maxLeaveAccumulation INT NOT NULL,
          PRIMARY KEY (configId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
    ];

    try 
    {
      foreach($createStatements as $statement) {
          $db->query($statement);
          $db->execute();
      }
    }
    catch (PDOException $e) 
    {
        echo $e->getMessage();
    }

    $fields = [
      'departmentName' => "admin",
      'departmentDescription' => "admin",
      'unitName' => "admin",
      'unitDescription' => "admin",
      'uid' => "admin",
      'fname' => "admin",
      'lname' => "admin",
      'pwd' => "admin",
      'role' => "hr",
      'address' => "admin",
      'phone' => "0000000",
      'employeeStatus' => "employed",
      'sickleaveEntitlement' => 100,
      'sickleaveAvailable' => 100,
      'standardleaveEntitlement' => 100,
      'standardleaveAvailable' => 100,
      'workStart' => "9:00",
      'workEnd' => "5:00",
      'maxLeaveAccumulation' => 2
  ];

  $fields['pwd'] = password_hash($fields['pwd'], PASSWORD_DEFAULT);

  //add default config
  $db->query("SELECT * FROM hrmsconfig");
  $db->execute();
  if($db->getRowCount() > 0)
  {
    return;
  }
  else
  {
    $db->query('INSERT INTO hrmsconfig (configId,workStart,workEnd,maxLeaveAccumulation) VALUES (1,:start,:end,:maxLeaveAccumulation)');
    $db->bind(':start',$fields['workStart']);
    $db->bind(':end',$fields['workEnd']);
    $db->bind(':maxLeaveAccumulation',$fields['maxLeaveAccumulation']);
    try
    {
      $db->execute();
    }
    catch (PDOException $e)
    {
      echo $e->getMessage();
    }
  }
  
  //add hr department
  $db->query("SELECT * FROM departments WHERE departmentName = 'admin'");
  $db->execute();
  if($db->getRowCount() > 0)
  {
    return;
  }
  else
  {
    $db->query('INSERT INTO departments (departmentName,departmentDescription) VALUES (:name,:description)');
    $db->bind(':name',$fields['departmentName']);
    $db->bind(':description',$fields['departmentDescription']);
    try
    {
      $db->execute();
    }
    catch (PDOException $e)
    {
      echo $e->getMessage();
    }
  }

  //add hr unit
  $departmentId = $db->getLastId();
  $db->query("SELECT * FROM units WHERE unitName = 'admin'");
  $db->execute();
  if($db->getRowCount() > 0)
  {
    return;
  }
  else
  {
    $db->query('INSERT INTO units (unitName,unitDescription,departmentId) VALUES (:name,:description,:department)');
    $db->bind(':name',$fields['unitName']);
    $db->bind(':description',$fields['unitDescription']);
    $db->bind(':department',$departmentId);
    try
    {
      $db->execute();
    }
    catch (PDOException $e)
    {
      echo $e->getMessage();
    }
  }

  //add hr employee
  $unitId = $db->getLastId();
  $db->query("SELECT * FROM employees WHERE employeeRole = 'hr'");
  $db->execute();
  if($db->getRowCount() > 0)
  {
    return;
  }
  else
  {
    $db->query('INSERT INTO employees (employeeFName,employeeLName,employeeUid,employeePwd,employeeRole,employeeAddress,employeePhone,employeeStatus,unitId,departmentId,sickleaveEntitlement,sickleaveAvailable,standardleaveEntitlement,standardleaveAvailable) VALUES (:fname,:lname,:uid,:pwd,:role,:address,:phone,:empstatus,:unit,:department,:sickent,:sickavl,:standardent,:standardavl)');
    $db->bind(':fname',$fields['fname']);
    $db->bind(':lname',$fields['lname']);
    $db->bind(':uid',$fields['uid']);
    $db->bind(':pwd',$fields['pwd']);
    $db->bind(':role',$fields['role']);
    $db->bind(':address',$fields['address']);
    $db->bind(':phone',$fields['phone']);
    $db->bind(':empstatus',$fields['employeeStatus']);
    $db->bind(':unit',$unitId);
    $db->bind(':department',$departmentId);
    $db->bind(':sickent',$fields['sickleaveEntitlement']);
    $db->bind(':sickavl',$fields['sickleaveAvailable']);
    $db->bind(':standardent',$fields['standardleaveEntitlement']);
    $db->bind(':standardavl',$fields['standardleaveAvailable']);
    try
    {
      $db->execute();
      echo "<h4>Default Username/Password Is admin/admin.</h4>";
      echo "<h5>Please Change Accordingly. This Message Will Not Be Displayed Again.</h5>";
    }
    catch (PDOException $e)
    {
      echo $e->getMessage();
    }
  }

  //set event schedulers
  //https://stackoverflow.com/questions/32291790/add-value-to-a-column-each-month-in-mysql-data-base/32291806
  $db->query("SET GLOBAL event_scheduler = 'ON';");
  try
  {
    $db->execute();
  }
  catch (PDOException $e)
  {
    echo $e->getMessage();
  }

  $db->query(" CREATE EVENT IF NOT EXISTS monthlyResetSickLeaveDaysEvent
  ON SCHEDULE EVERY '1' MONTH
  STARTS '2022-03-01 00:00:00'
  DO 
  BEGIN
   UPDATE employees SET sickleaveAvailable = sickleaveEntitlement;
  END;");
  try
  {
    $db->execute();
  }
  catch (PDOException $e)
  {
    echo $e->getMessage();
  }

  $db->query(" CREATE EVENT IF NOT EXISTS yearlyResetStandardLeaveDaysEvent
  ON SCHEDULE EVERY '1' YEAR
  STARTS '2023-01-01 00:00:00'
  DO 
  BEGIN
  UPDATE employees SET standardleaveAvailable = IF((standardleaveAvailable+standardleaveEntitlement) <= (standardleaveEntitlement * (SELECT maxLeaveAccumulation FROM hrmsconfig WHERE configId = 1)), standardleaveAvailable+standardleaveEntitlement, standardleaveEntitlement); 
  END;");
  try
  {
    $db->execute();
  }
  catch (PDOException $e)
  {
    echo $e->getMessage();
  }

  unset($db);




