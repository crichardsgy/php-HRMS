<div id="top"></div>

<!-- PROJECT LOGO -->
<br />
<!-- GETTING STARTED -->
## Getting Started

A simple Xampp installation should be sufficient. However the tomsik68 Docker image for Xampp was utilized to develop the project.

### Prerequisites

NOTE: An internet connection is required to load crucial libraries such as Bootstrap and Chartjs.

Upon installing Xampp, navigate to the phpMyAdmin web interface and create a new database called 'hrms'.

OR

Use the SQL interactive shell bundled with Xampp.
* MySQL
  ```sql
  CREATE DATABASE hrms;
  ```

### Installation While Importing The SQL Dump

1. Copy the project folder to your HTDocs or www folder (do not open the web application before importing the SQL dump). 

2. Import the SQL dump into the hrms database (Either through phpMyAdmin or the interactive shell).

3. Navigate to the index.php page in your browser and log in with the credentials in the usage section.

NOTE: The initial index.php run should also create a MariaDB event to auto accumulate leave every year (This may require additional server configuration).

### Installation Without Importing The SQL Dump

1. Copy the project folder to your HTDocs or www folder. 

2. Navigate to the index.php page in your browser to prepare the program for first use (this will run the initialization script which will create the relevant tables and create a default admin user with username and password 'admin'). See models/Database.php for troubleshooting.

NOTE: The initial index.php run should also create a MariaDB event to auto accumulate leave every year (This may require additional server configuration). 


<p align="right">(<a href="#top">back to top</a>)</p>


<!-- USAGE EXAMPLES -->
## Usage

NOTE: An internet connection is required to load crucial libraries such as Bootstrap and Chartjs.

The "Log Arrival Time" section on the log in screen is used for the Attendance tracking feature. Essentially, all employees should be required to perform this action as soon as they enter the building (e.g a computer can be placed by the entrance and employees should be required to "swipe in" by entering their credentials). In technical terms, this acts as a log in function without establishing a persistent session for the user. 

Upon launch (after importing the SQL dump) the credenials in the below section can be used to log in via the index.html screen. From here, other accounts can be created and the main features of the application can be accessed.

OR

Upon first launch (without importing the SQL dump) a default admin account with the username and password "admin" will be created (kindly change accordingly). From here, other accounts can be created and the main features of the application can be accessed.

This application comprises of three perspectives for three roles, the Human Resources perspective, the Head Of Department perspective, and the Normal user perspective.

Human Resources Should Be Able To:
* Organize employees into departments and units
  * Create, View and Update departments 
  * Create, View and Update departments into units
  * Create, View and Update employees into departments and units
* Terminate employees (i.e mark as retired, contract expired, or terminated)
* Track general employee attendance 
	* show employees arrival times and filter according to (late, ontime, or on leave) 
	* show missing employees, 
	* give option to mark as sick/standard leave, 
	* show graphs for departments containing statistics for lateness or the attendance rate
* View their attendance (and statistics)
* Track and approve general employee leave
* View their remaining standard/sick leave
* Request leave 

Heads Of Departments Should Be Able To:
* Track employee attendance In his/her respective department  
	* show employees arrival times and filter according to (late, ontime, or on leave), 
	* show missing employees, 
	* give option to mark as sick/standard leave, 
	* show graphs for units containing statistics for lateness or the attendance rate
  * View their attendance (and statistics)
* Track and approve employee leave In his/her respective department
* View their remaining standard/sick leave
* Request leave  

Normal Staff Should Be Able To
* View their attendance (and statistics)
* View their remaining standard/sick leave 
* Request leave  

### Notable Credentials From The SQL Dump (Username -- Password):

Human Resources Accounts:
* admin -- admin
* username -- password

Head Of Department Accounts:
* username -- password

Normal Staff Accounts:
* username -- password

<p align="right">(<a href="#top">back to top</a>)</p>


<!-- ACKNOWLEDGMENTS -->
## Acknowledgments

References to resources that were useful to the development of this project.

* [How to create a filter table with JavaScript](https://www.w3schools.com/howto/tryit.asp?filename=tryhow_js_filter_table)
* [Toggle between hiding and showing an element with JavaScript](https://www.w3schools.com/howto/tryit.asp?filename=tryhow_js_toggle_hide_show)
* [How to Create A Bar Chart With Chart.js](https://www.w3schools.com/js/tryit.asp?filename=tryai_chartjs_bars_colors_more)
* [ChartJS-PieChart](https://github.com/WebDevSHORTS/ChartJS-PieChart/blob/master/js/script.js)
* [How To Create A Login System In PHP For Beginners | Procedural MySQLi | PHP Tutorial](https://www.youtube.com/watch?v=gCo6JqGMi30)
* [Build A Login System in PHP With MVC & PDO | Includes Forgotten Password](https://www.youtube.com/watch?v=lSVGLzGBEe0)
* [Buttons - Bootstrap](https://getbootstrap.com/docs/4.0/components/buttons/)
* [Best README Template](https://github.com/othneildrew/Best-README-Template/blob/master/README.md)

<p align="right">(<a href="#top">back to top</a>)</p>


