<?php
include_once 'DBConnector.php';

$acadYear = $_GET['acadYear'] ?? '';
$semester = $_GET['semester'] ?? '';

$sql_latest = "SELECT acadYear, semester FROM academicyear ORDER BY acadYear DESC, semester DESC LIMIT 1";
$result_latest = $conn->query($sql_latest);
if ($result_latest->num_rows > 0) {
    $latest = $result_latest->fetch_assoc();
} else {
    $latest = [
        'acadYear' => '',
        'semester' => ''
    ];
}

if (empty($acadYear)) {
    $acadYear = $latest['acadYear'];
}
if (empty($semester)){
    $semester = $latest['semester'];
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Komsai.Org Database</title>
        <meta charset = "UTF-8">
        <meta name = "author" content = "Contreras-DelRosario-Quindao-Verde">
        <link rel="stylesheet" type="text/css" href="homeDesign.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    </head>

    <body>
        <header>
            <img src="komsai_logo.png" alt="icon" width="60px" height="60px">
		    <h1 id="title">UPV KOMSAI.ORG</h1>
	    </header>
        <hr>

    <!-- top buttons -->
    <div class="top-bar">

    <div class="left-controls">
        <form name="filterAYSem" method="get" id="filterAYSem" action="homepage.php">
            <label for="acadYear">Academic Year </label>
            <select name="acadYear" id="AY">
                <?php include 'allAcadYear.php'; ?>
            </select>
            <label for="semester">Semester:</label>
            <select name="semester" id="semester">
                <?php include 'semester.php'; ?>
            </select>
        </form>
    </div>

        <div class= "right-controls">
            <form action="">
                <input type="text" placeholder="Search">
             </form>
        </div>
    </div>

        
    <!-- Display of data -->
     <br>

    <!--Advisor display-->
     <div class="container">
        <h2>Advisor</h2>
        
        <div class="dropDowns">
            <form action=" ">
                <select name="sortBy" id="sort">
                        <option value="none" selected disabled hidden>Sort by</option>
                        <option value="roles">Roles</option>
                        <option value="status">Status </option>
                        <option value="add sort">add sort</option>
                        <option value="add sort">add sort</option>
                </select>
            </form>

        </div>
    </div>

        <div class="scrollbox">
            <table style="width:100%">
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Academic Year</th>
                    <th>Semester</th>
                    <th>AdvisorID</th>
                    <th></th>
                </tr>
            <?php
                include 'advisor.php';
            ?>
            </table>
        </div>
        <br>

    <!--First Year display-->
        <div class="container">
            <h2>1st Year</h2>
        
            <div class="dropDowns">
                <form action=" ">
                    <select name="sortBy" id="sort">
                            <option value="none" selected disabled hidden>Sort by</option>
                            <option value="roles">Roles</option>
                            <option value="status">Status </option>
                            <option value="add sort">add sort</option>
                            <option value="add sort">add sort</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="scrollbox">
            <table style="width:100%">
                <tr>
                    <th>Name</th>
                    <th>ID</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>SOA Reqs</th>
                    <th>Missing Reqs</th>
                    <th></th>
                </tr>
            <?php
                include 'firstyear.php';
            ?>
            </table>
        </div>
        <br>

    <!--Second Year display-->
        <div class="container">
            <h2>2nd Year</h2>
        
            <div class="dropDowns">
                <form action=" ">
                    <select name="sortBy" id="sort">
                            <option value="none" selected disabled hidden>Sort by</option>
                            <option value="roles">Roles</option>
                            <option value="status">Status </option>
                            <option value="add sort">add sort</option>
                            <option value="add sort">add sort</option>
                    </select>
                </form>

            </div>
        </div>

        <div class="scrollbox">
            <table style="width:100%">
                <tr>
                    <th>Name</th>
                    <th>ID</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>SOA Reqs</th>
                    <th>Missing Reqs</th>
                    <th></th>
                <tr>
            <?php
                include 'secondyear.php';
            ?>
            </table>
        </div>
        <br>

    <!--Third Year display-->
        <div class="container">
            <h2>3rd Year</h2>
            
            <div class="dropDowns">
                <form action=" ">
                    <select name="sortBy" id="sort">
                            <option value="none" selected disabled hidden>Sort by</option>
                            <option value="roles">Roles</option>
                            <option value="status">Status </option>
                            <option value="add sort">add sort</option>
                            <option value="add sort">add sort</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="scrollbox">
            <table style="width:100%">
               <tr>
                    <th>Name</th>
                    <th>ID</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>SOA Reqs</th>
                    <th>Missing Reqs</th>
                    <th></th>
                <tr>
            <?php
                include 'thirdyear.php';
            ?>
            </table>
        </div>
        <br>

    <!--Fourth Year display-->
       <div class="container">
            <h2>4th Year</h2>
            
            <div class="dropDowns">
                <form action=" ">
                    <select name="sortBy" id="sort">
                            <option value="none" selected disabled hidden>Sort by</option>
                            <option value="roles">Roles</option>
                            <option value="status">Status </option>
                            <option value="add sort">add sort</option>
                            <option value="add sort">add sort</option>
                    </select>
                </form>

            </div>
        </div>

        <div class="scrollbox">
            <table style="width:100%">
                <tr>
                    <th>Name</th>
                    <th>ID</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>SOA Reqs</th>
                    <th>Missing Reqs</th>
                    <th></th>
                <tr>
            <?php
                include 'fourthyear.php';
            ?>
            </table>
        </div>
        <br>

    <!--Nth Year display-->
        <div class="container">
            <h2>Nth Year</h2>
            
            <div class="dropDowns">
                <form action=" ">
                    <select name="sortBy" id="sort">
                            <option value="none" selected disabled hidden>Sort by</option>
                            <option value="roles">Roles</option>
                            <option value="status">Status </option>
                            <option value="add sort">add sort</option>
                            <option value="add sort">add sort</option>
                    </select>
                </form>

            </div>
        </div>

        <div class="scrollbox">
            <table style="width:100%">
                <tr>
                    <th>Name</th>
                    <th>ID</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>SOA Reqs</th>
                    <th>Missing Reqs</th>
                    <th></th>
                <tr>
            <?php
                include 'nthyear.php';
            ?>
            </table>
        </div>
        <br>

        <!--Alumni display-->
        <!-- <div class="container">
            <h2>Alumni</h2>
            
            <div class="dropDowns">
                <form action=" ">
                    <select name="sortBy" id="sort">
                            <option value="none" selected disabled hidden>Sort by</option>
                            <option value="roles">Roles</option>
                            <option value="status">Status </option>
                            <option value="add sort">add sort</option>
                            <option value="add sort">add sort</option>
                    </select>
                </form>

            </div>
        </div>
    
        <div class="scrollbox">
            <table style="width:100%">
                <tr>
                    <th>Name</th>
                    <th>AlumniID</th>
                    <th>StudentID</th>
                    <th></th>
                <tr>
            <?php
                include 'alumni.php';
            ?>
            </table>
        </div> -->
        <br>

    </body>
</html>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("filterAYSem");
    const acadYearDropdown = document.getElementById("AY");
    const semesterDropdown = document.getElementById("semester");

    const getStoredValue = (key, dropdown) => {
        const storedValue = localStorage.getItem(key);
        return storedValue && [...dropdown.options].some(opt => opt.value === storedValue) ? storedValue : null;
    };

    const handleSelectionChange = (dropdown, storageKey) => {
        localStorage.setItem(storageKey, dropdown.value);
        debounceSubmit();
    };

    const debounce = (func, delay) => {
        let timeout;
        return () => {
            clearTimeout(timeout);
            timeout = setTimeout(func, delay);
        };
    };

    const debounceSubmit = debounce(() => form.submit(), 300);

    acadYearDropdown.value = getStoredValue("selectedAcadYear", acadYearDropdown) || acadYearDropdown.value;
    semesterDropdown.value = getStoredValue("selectedSemester", semesterDropdown) || semesterDropdown.value;

    acadYearDropdown.addEventListener("change", () => handleSelectionChange(acadYearDropdown, "selectedAcadYear"));
    semesterDropdown.addEventListener("change", () => handleSelectionChange(semesterDropdown, "selectedSemester"));
});

</script>
