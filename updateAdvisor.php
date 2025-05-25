<?php
include 'DBConnector.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $advisorID = $_POST['advisorID'];
    $firstName = $_POST['firstName'];
    $middleInitial = $_POST['middleName'];
    $lastName = $_POST['lastName'];
    $type = $_POST['type'];
    $acadYear = $_POST['acadYear'];
    $semesters = $_POST['semester']; // This is an array

    // 1. Update advisor table (as before, but using prepared statement)
    $sqlAdvisor = "UPDATE advisor SET
                   firstName = ?,
                   middleInitial = ?,
                   lastName = ?
                   WHERE advisorID = ?";

    $stmtAdvisor = $conn->prepare($sqlAdvisor);
    $stmtAdvisor->bind_param("sssi", $firstName, $middleInitial, $lastName, $advisorID);

    if ($stmtAdvisor->execute()) {

        // 2.  Delete existing advises entries for this advisor and acadYear
        $sqlDeleteAdvises = "DELETE FROM advises
                             WHERE advisorID = ? AND acadYear = ?";
        $stmtDeleteAdvises = $conn->prepare($sqlDeleteAdvises);
        $stmtDeleteAdvises->bind_param("is", $advisorID, $acadYear);
        $stmtDeleteAdvises->execute(); // Don't check the result here; just delete.


        // 3. Insert new advises entries for each selected semester
        $sqlInsertAdvises = "INSERT INTO advises (advisorID, type, acadYear, semester)
                             VALUES (?, ?, ?, ?)";
        $stmtInsertAdvises = $conn->prepare($sqlInsertAdvises);
        $stmtInsertAdvises->bind_param("issi", $advisorID, $type, $acadYear, $semesterValue);

        // Iterate through the selected semesters and insert them
        foreach ($semesters as $semesterValue) {
            $stmtInsertAdvises->execute();
        }

        $stmtInsertAdvises->close();

        header("Location: homepage.php"); // Redirect
        exit();

    } else {
        echo "Error updating advisor: " . $stmtAdvisor->error;
    }

    $stmtAdvisor->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
