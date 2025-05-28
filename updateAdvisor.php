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
            $dupCheck = $conn->query("SELECT * FROM advises WHERE acadYear = '$acadYear' AND semester = '$semester' AND type = '$type' AND advisorID != '$advisorID'");
            if ($dupCheck && $dupCheck->num_rows > 0) {
                echo "<script>alert('Another advisor with the same academic year, semester, and type already exists.'); window.history.back();</script>";
                $conn->close();
                exit();
            }
            $sqlUpdateAdv = "UPDATE advises SET type = '$type' WHERE advisorID = '$advisorID' AND acadYear = '$acadYear' AND semester = '$semester'";
            $conn->query($sqlUpdateAdv);
        }
    }

    // add new advises entry if all fields are filled
    $redir_acadYear = '';
    $redir_semester = '';
    if (!empty($_POST['new_acadYear']) && !empty($_POST['new_semester']) && !empty($_POST['new_type'])) {
        $new_acadYear = $conn->real_escape_string($_POST['new_acadYear']);
        $new_semester = $conn->real_escape_string($_POST['new_semester']);
        $new_type = $conn->real_escape_string($_POST['new_type']);
        // Only insert if not already existing
        $check = $conn->query("SELECT * FROM advises WHERE advisorID = '$advisorID' AND acadYear = '$new_acadYear' AND semester = '$new_semester'");
        if ($check->num_rows == 0) {
            $conn->query("INSERT INTO advises (advisorID, type, acadYear, semester) VALUES ('$advisorID', '$new_type', '$new_acadYear', '$new_semester')");
        }
        $redir_acadYear = $new_acadYear;
        $redir_semester = $new_semester;
    } elseif (!empty($_POST['adv_acadYear'][0]) && !empty($_POST['adv_semester'][0])) {
        // Use the first advises entry if available
        $redir_acadYear = $conn->real_escape_string($_POST['adv_acadYear'][0]);
        $redir_semester = $conn->real_escape_string($_POST['adv_semester'][0]);
    } else {
        // Fallback: get latest acadYear/semester from DB
        $latest = $conn->query("SELECT acadYear, semester FROM academicyear ORDER BY acadYear DESC, semester DESC LIMIT 1");
        if ($latest && $latest->num_rows > 0) {
            $row = $latest->fetch_assoc();
            $redir_acadYear = $row['acadYear'];
            $redir_semester = $row['semester'];
        }
    }

    header("Location: homepage.php?acadYear=" . urlencode($redir_acadYear) . "&semester=" . urlencode($redir_semester));
    exit();
} else {
    echo "Invalid request.";
}
$conn->close();
?>