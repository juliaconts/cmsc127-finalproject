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
        <link rel="stylesheet" type="text/css" href="paymentDesign.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@40,400,0,0&icon_names=arrow_forward_ios" />    
    </head>

    <body>
        <header>
            <img src="komsai_logo.png" alt="icon" width="60px" height="60px">
		    <h1 id="title">UPV KOMSAI.ORG</h1>
            <a href="homepage.php">
                <button id="back-button" type="button"><span class="material-symbols-outlined">arrow_forward_ios</span></button>
            </a>
	    </header>
        <hr>

    <!-- Top bar with Add buttons, left-controls, and right-controls side by side -->
    <div class="top-bar" style="display: flex; align-items: flex-end; justify-content: space-between; gap: 30px; margin-bottom: 20px;">
        <div style="display: flex; align-items: flex-end; gap: 30px;">

            <div class="left-controls">
            <form name="filterAYSem" method="get" id="filterAYSem" action="paymentpage.php">
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
        </div>
    </div>

        
    <!-- Display for search -->
    <div class="search">
        <!-- for shen to add whoever comes up in the search -->
    </div>

    <!--First Year display-->
        <div class="container">
            <h2>1st Year</h2>
        
            <div class="dropDowns">
                <form action="homepage.php" method="GET">
                    <select name="sort1By" id="sort" onchange="sessionStorage.setItem('scrollY', window.scrollY); this.form.submit()">
                            <option value="none" selected disabled hidden>Sort by</option>
                            <option value="alphabet-asc">A-Z</option>
                            <option value="alphabet-desc">Z-A</option>
                            <option value="role">Role</option>
                            <option value="status">Status</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="scrollbox">
            <table style="width:100%">
                <tr>
                    <th>Name</th>
                    <th>ID</th>
                    <th>Membership Fee</th>
                    <th>AKWE Fee 2024</th>
                    <th>AKWE Fee 2023</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            <?php
                include 'firstyearPayment.php';
            ?>
            </table>
        </div>
        <br>

    <!--Second Year display-->
        <div class="container">
            <h2>2nd Year</h2>
        
            <div class="dropDowns">
                <form action="homepage.php" method="GET">
                    <select name="sort2By" id="sort" onchange="sessionStorage.setItem('scrollY', window.scrollY); this.form.submit()">
                            <option value="none" selected disabled hidden>Sort by</option>
                            <option value="alphabet-asc">A-Z</option>
                            <option value="alphabet-desc">Z-A</option>
                            <option value="role">Role</option>
                            <option value="status">Status</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="scrollbox">
            <table style="width:100%">
                <tr>
                    <th>Name</th>
                    <th>ID</th>
                    <th>Membership Fee</th>
                    <th>AKWE Fee 2024</th>
                    <th>AKWE Fee 2023</th>
                    <th>Status</th>
                    <th></th>
                <tr>
            <?php
                include 'secondyearPayment.php';
            ?>
            </table>
        </div>
        <br>

    <!--Third Year display-->
        <div class="container">
            <h2>3rd Year</h2>
            
            <div class="dropDowns">
                <form action="homepage.php" method="GET">
                    <select name="sort3By" id="sort" onchange="sessionStorage.setItem('scrollY', window.scrollY); this.form.submit()">
                            <option value="none" selected disabled hidden>Sort by</option>
                            <option value="alphabet-asc">A-Z</option>
                            <option value="alphabet-desc">Z-A</option>
                            <option value="role">Role</option>
                            <option value="status">Status</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="scrollbox">
            <table style="width:100%">
               <tr>
                    <th>Name</th>
                    <th>ID</th>
                    <th>Membership Fee</th>
                    <th>AKWE Fee 2024</th>
                    <th>AKWE Fee 2023</th>
                    <th>Status</th>
                    <th></th>
                <tr>
            <?php
                include 'thirdyearPayment.php';
            ?>
            </table>
        </div>
        <br>

    <!--Fourth Year display-->
       <div class="container">
            <h2>4th Year</h2>
            
                <form action="homepage.php" method="GET">
                    <select name="sort4By" id="sort" onchange="sessionStorage.setItem('scrollY', window.scrollY); this.form.submit()">
                            <option value="none" selected disabled hidden>Sort by</option>
                            <option value="alphabet-asc">A-Z</option>
                            <option value="alphabet-desc">Z-A</option>
                            <option value="role">Role</option>
                            <option value="status">Status</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="scrollbox">
            <table style="width:100%">
                <tr>
                    <th>Name</th>
                    <th>ID</th>
                    <th>Membership Fee</th>
                    <th>AKWE Fee 2024</th>
                    <th>AKWE Fee 2023</th>
                    <th>Status</th>
                    <th></th>
                <tr>
            <?php
                include 'fourthyearPayment.php';
            ?>
            </table>
        </div>
        <br>

    <!--Nth Year display-->
        <div class="container">
            <h2>Nth Year</h2>
            
                <form action="homepage.php" method="GET">
                    <select name="sortNthBy" id="sort" onchange="sessionStorage.setItem('scrollY', window.scrollY); this.form.submit()">
                            <option value="none" selected disabled hidden>Sort by</option>
                            <option value="alphabet-asc">A-Z</option>
                            <option value="alphabet-desc">Z-A</option>
                            <option value="role">Role</option>
                            <option value="status">Status</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="scrollbox">
            <table style="width:100%">
                <tr>
                    <th>Name</th>
                    <th>ID</th>
                    <th>Membership Fee</th>
                    <th>AKWE Fee 2024</th>
                    <th>AKWE Fee 2023</th>
                    <th>Status</th>
                    <th></th>
                <tr>
            <?php
                include 'nthyearPayment.php';
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

// restores scroll position after page load
window.addEventListener("load", () => {
    const y = sessionStorage.getItem('scrollY');
    if (y !== null) {
    window.scrollTo(0, y);
    sessionStorage.removeItem('scrollY');
    }
});
</script>