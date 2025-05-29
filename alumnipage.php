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
            <form name="filterGradYears" method="GET" id="filterGradYears" action="alumnipage.php">
                <label for="yearGraduated">Graduation Year:</label>
                <select name="yearGraduated" id="yearGraduated" onchange="this.form.submit()">
                    <option value="">-- All --</option>
                        <?php
                        $years = range(date('Y'), 2000); // e.g. from current year back to 2000
                        foreach ($years as $year) {
                            $selected = ($year == $_GET['yearGraduated']) ? "selected" : "";
                            echo "<option value=\"$year\" $selected>$year</option>";
                        } 
                        ?>
                </select>
            </form>
            </div>
        </div>
    </div>

        <!-- Alumni display-->
        <h2>Alumni</h2>

        <div class="scrollbox">
            <table style="width:100%">
                <tr>
                    <th>Name</th>
                    <th>AlumniID</th>
                    <th>StudentID</th>
                    <th>Year Graduated</th>
                    <th></th>
                <tr>
            <?php
                include 'alumni.php';
            ?>
            </table>
        </div>
        <br>

    </body>
</html>