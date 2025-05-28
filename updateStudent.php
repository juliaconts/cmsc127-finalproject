<?php
include 'DBConnector.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentID = $conn->real_escape_string($_POST['studentID']);
    $firstName = $conn->real_escape_string($_POST['firstName']);
    $middleName = $conn->real_escape_string($_POST['middleName']);
    $lastName = $conn->real_escape_string($_POST['lastName']);
    $status = $conn->real_escape_string($_POST['status']);
    $upMail = $conn->real_escape_string($_POST['upMail']);
    $yearLevel = $conn->real_escape_string($_POST['yearLevel']);
    $university = $conn->real_escape_string($_POST['university']);
    $degreeProgram = $conn->real_escape_string($_POST['degreeProgram']);
    $contactNo = $conn->real_escape_string($_POST['contactNo']);
    $presentAddress = $conn->real_escape_string($_POST['presentAddress']);
    $homeAddress = $conn->real_escape_string($_POST['homeAddress']);
    $birthday = $conn->real_escape_string($_POST['birthday']);
    $signature = $conn->real_escape_string($_POST['signature']);
    $idPicture = $conn->real_escape_string($_POST['idPicture']);

    $form5 = $conn->real_escape_string($_POST['form5']);
    $roleID = $conn->real_escape_string($_POST['roleID']);
    $acadYear = $conn->real_escape_string($_POST['acadYear']);
    $semester = $conn->real_escape_string($_POST['semester']);

    $orig_acadYear = $conn->real_escape_string($_POST['orig_acadYear']);
    $orig_semester = $conn->real_escape_string($_POST['orig_semester']);
    $orig_roleID = $conn->real_escape_string($_POST['orig_roleID']);

    // Update member table (as before)
    $sql = "UPDATE member SET
            firstName = '$firstName',
            middleName = '$middleName',
            lastName = '$lastName',
            upMail = '$upMail',
            university = '$university',
            degreeProgram = '$degreeProgram',
            homeAddress = '$homeAddress',
            birthday = '$birthday',
            signature = '$signature',
            idPicture = '$idPicture'
            WHERE studentID = '$studentID'";

    if ($conn->query($sql) === TRUE) {
        // Check if assigned record exists with the original keys
        $checkSql = "SELECT COUNT(*) FROM assigned WHERE studentID = '$studentID' AND acadYear = '$orig_acadYear' AND semester = '$orig_semester' AND roleID = '$orig_roleID'";
        $checkResult = $conn->query($checkSql);
        $count = $checkResult->fetch_row()[0];

        if ($count > 0) {
            // If the keys have changed, delete the old and insert new
            if ($orig_acadYear != $acadYear || $orig_semester != $semester || $orig_roleID != $roleID) {
                // Delete old
                $conn->query("DELETE FROM assigned WHERE studentID = '$studentID' AND acadYear = '$orig_acadYear' AND semester = '$orig_semester' AND roleID = '$orig_roleID'");
                // Insert new
                $insertAssignedSql = "INSERT INTO assigned (semester, acadYear, roleID, studentID, yearLevel, status, contactNo, presentAddress, form5)
                    VALUES (
                        '$semester', '$acadYear', '$roleID', '$studentID', " .
                        ($yearLevel !== "" ? "'$yearLevel'" : "NULL") . ", " .
                        ($status !== "" ? "'$status'" : "NULL") . ", " .
                        ($contactNo !== "" ? "'$contactNo'" : "NULL") . ", " .
                        ($presentAddress !== "" ? "'$presentAddress'" : "NULL") . ", " .
                        ($form5 !== "" ? "'$form5'" : "NULL") .
                    ")";
                $conn->query($insertAssignedSql);
            } else {
                // update existing assigned record
                $updateAssignedSql = "UPDATE assigned SET 
                        status = " . ($status !== "" ? "'$status'" : "NULL") . ",
                        yearLevel = " . ($yearLevel !== "" ? "'$yearLevel'" : "NULL") . ",
                        contactNo = " . ($contactNo !== "" ? "'$contactNo'" : "NULL") . ",
                        presentAddress = " . ($presentAddress !== "" ? "'$presentAddress'" : "NULL") . ",
                        form5 = " . ($form5 !== "" ? "'$form5'" : "NULL") . "
                    WHERE studentID = '$studentID' AND acadYear = '$acadYear' AND semester = '$semester' AND roleID = '$roleID'";
                $conn->query($updateAssignedSql);
            }
        } else {
            // insert new assigned record
            $insertAssignedSql = "INSERT INTO assigned (semester, acadYear, roleID, studentID, yearLevel, status, contactNo, presentAddress, form5)
                VALUES (
                    '$semester', '$acadYear', '$roleID', '$studentID', " .
                    ($yearLevel !== "" ? "'$yearLevel'" : "NULL") . ", " .
                    ($status !== "" ? "'$status'" : "NULL") . ", " .
                    ($contactNo !== "" ? "'$contactNo'" : "NULL") . ", " .
                    ($presentAddress !== "" ? "'$presentAddress'" : "NULL") . ", " .
                    ($form5 !== "" ? "'$form5'" : "NULL") .
                ")";
            $conn->query($insertAssignedSql);
        }

        // Update or insert into form5 table (canonical source for Form 5)
        $form5Check = $conn->query("SELECT COUNT(*) FROM form5 WHERE studentID = '$studentID' AND acadYear = '$acadYear' AND semester = '$semester'");
        $form5Count = $form5Check->fetch_row()[0];
        if ($form5Count > 0) {
            $conn->query("UPDATE form5 SET form5 = " . ($form5 !== "" ? "'$form5'" : "NULL") . " WHERE studentID = '$studentID' AND acadYear = '$acadYear' AND semester = '$semester'");
        } else {
            $conn->query("INSERT INTO form5 (acadYear, semester, studentID, form5) VALUES ('$acadYear', '$semester', '$studentID', " . ($form5 !== "" ? "'$form5'" : "NULL") . ")");
        }

        header("Location: homepage.php?acadYear=" . urlencode($acadYear) . "&semester=" . urlencode($semester));
        exit();
    } else {
        echo "Error updating member record: " . $conn->error;
    }

    $conn->close();
}
?>