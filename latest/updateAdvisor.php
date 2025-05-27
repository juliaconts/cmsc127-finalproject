<?php
include 'DBConnector.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $advisorID = $conn->real_escape_string($_POST['advisorID']);
    $firstName = $conn->real_escape_string($_POST['firstName']);
    $middleInitial = $conn->real_escape_string($_POST['middleInitial']);
    $lastName = $conn->real_escape_string($_POST['lastName']);

    // update advisor table
    $sqlAdvisor = "UPDATE advisor SET firstName = '$firstName', middleInitial = '$middleInitial', lastName = '$lastName' WHERE advisorID = '$advisorID'";
    $conn->query($sqlAdvisor);

    // update advises entries that were checked for update
    if (isset($_POST['adv_update'])) {
        foreach ($_POST['adv_update'] as $idx) {
            $acadYear = $conn->real_escape_string($_POST['adv_acadYear'][$idx]);
            $semester = $conn->real_escape_string($_POST['adv_semester'][$idx]);
            $type = $conn->real_escape_string($_POST['adv_type'][$idx]);
            $sqlUpdateAdv = "UPDATE advises SET type = '$type' WHERE advisorID = '$advisorID' AND acadYear = '$acadYear' AND semester = '$semester'";
            $conn->query($sqlUpdateAdv);
        }
    }

    // add new advises entry if all fields are filled
    if (!empty($_POST['new_acadYear']) && !empty($_POST['new_semester']) && !empty($_POST['new_type'])) {
        $new_acadYear = $conn->real_escape_string($_POST['new_acadYear']);
        $new_semester = $conn->real_escape_string($_POST['new_semester']);
        $new_type = $conn->real_escape_string($_POST['new_type']);
        // Only insert if not already existing
        $check = $conn->query("SELECT * FROM advises WHERE advisorID = '$advisorID' AND acadYear = '$new_acadYear' AND semester = '$new_semester'");
        if ($check->num_rows == 0) {
            $conn->query("INSERT INTO advises (advisorID, type, acadYear, semester) VALUES ('$advisorID', '$new_type', '$new_acadYear', '$new_semester')");
        }
    }
    header("Location: homepage.php");
    exit();
} else {
    echo "Invalid request.";
}
$conn->close();
?>