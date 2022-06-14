<?php
session_start();

//Connects to the Department Model
require_once "../models/Department.php";

class Departments 
{
    private $departmentModel;

    //Creates an instance of the Department model that allows us to access its functions
    public function __construct()
    {
        $this->departmentModel = new Department;
    }

    public function createDepartment()
    {
        $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);

        //stores data sent by POST from the form
        $fields = [
            'departmentName' => trim($_POST["departmentName"]),
            'departmentDescription' => trim($_POST["departmentDescription"])
        ];

        //checks if a field in the form is empty
        if(empty($fields['departmentName']))
        {
            //returns the user to the form if successful with the GET "error" variable showing the reason
            header("location: ../createdepartment.php?error=emptyFields");
            exit();
        }

        //checks size of string in specific field of the form (change the 128 to the max size of the database column)
        if(preg_match('/\S{128,}/',$fields['departmentName'])) //https://stackoverflow.com/questions/12414258/how-to-find-out-if-some-word-in-a-string-is-bigger-than-50-characters-in-php
        {
                header("location: ../createdepartment.php?error=tooManyCharsInName");
                exit();
        }
        if(preg_match('/\S{128,}/',$fields['departmentDescription']))
        {
            header("location: ../createdepartment.php?error=tooManyCharsInDescription");
            exit();
        }

        //sends the validated data from the fields to the createDepartment function in the model which will push it to the database
        if ($this->departmentModel->createDepartment($fields))
        {
            //returns the user to the homescreen if successful
            header("location: ../managedepartment.php?error=none");
            exit();
        }
        else
        {
            die("Something Went Wrong. Please Try Again Later");
        }

    }

    public function updateDepartment()
    {
        $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);

        $fields = [
            'departmentId' => trim($_POST['editId']),
            'departmentName' => trim($_POST["departmentName"]),
            'departmentDescription' => trim($_POST["departmentDescription"])
        ];

        $_SESSION['editId'] = $fields['departmentId'];

        if(empty($fields['departmentName']))
        {
            header("location: ../updatedepartment.php?error=emptyFields");
            exit();
        }

        if(preg_match('/\S{128,}/',$fields['departmentName'])) //https://stackoverflow.com/questions/12414258/how-to-find-out-if-some-word-in-a-string-is-bigger-than-50-characters-in-php
        {
                header("location: ../updatedepartment.php?error=tooManyCharsInName");
                exit();
        }

        if(preg_match('/\S{128,}/',$fields['departmentDescription'])) //https://stackoverflow.com/questions/12414258/how-to-find-out-if-some-word-in-a-string-is-bigger-than-50-characters-in-php
            {
                    header("location: ../updatedepartment.php?error=tooManyCharsInDescription");
                    exit();
            }

        if ($this->departmentModel->updateDepartment($fields))
        {
            unset($_SESSION['editId']);
            header("location: ../managedepartment.php?error=none");
            exit();
        }
        else
        {
            unset($_SESSION['editId']);
            die("Something Went Wrong. Please Try Again Later");
        }

    }


}

//START HERE
//When the form sends data to the page, a hidden input in the form will state its purpose. The below code will read that and call the necessary function that corresponds to the required purpose.
$init = new Departments;

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if($_POST['formtype'] == 'createdepartment')
    {
        $init->createDepartment();
    }
    else if($_POST['formtype'] == 'updatedepartment')
    {
        $init->updateDepartment();
    }

}
