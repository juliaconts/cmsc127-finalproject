<?php
include 'DBConnector.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get all fields from POST
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

    // Original keys (in case the user changes them)
    $orig_acadYear = $conn->real_escape_string($_POST['orig_acadYear']);
    $orig_semester = $conn->real_escape_string($_POST['orig_semester']);
    $orig_roleID = $conn->real_escape_string($_POST['orig_roleID']);

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

    //role logic (overwrite previous role)
    if ($roleID >= 1 && $roleID <= 8) {
        // change the role of previous student to member
        $conn->query("UPDATE assigned SET roleID = 17 WHERE acadYear='$acadYear' AND semester='$semester' AND roleID='$roleID' AND studentID != '$studentID'");
    }

    if ($roleID == 9 && $yearLevel !== "" && $yearLevel !== null) {
        // count batch reps for this year level
        $batchRepCount = $conn->query("SELECT COUNT(*) FROM assigned WHERE acadYear='$acadYear' AND semester='$semester' AND roleID=9 AND yearLevel='$yearLevel' AND studentID != '$studentID'");
        $count = $batchRepCount ? $batchRepCount->fetch_row()[0] : 0;
        if ($count >= 4) {
            // remove the oldest (or any) batch rep for this year level
            $conn->query("DELETE FROM assigned WHERE acadYear='$acadYear' AND semester='$semester' AND roleID=9 AND yearLevel='$yearLevel' AND studentID != '$studentID' ORDER BY studentID ASC LIMIT 1");
        }
    }

    if ($conn->query($sql) === TRUE) {
        // check for duplicates
        $dupCheckSql = "SELECT COUNT(*) FROM assigned WHERE studentID = '$studentID' AND acadYear = '$acadYear' AND semester = '$semester' AND roleID = '$roleID'";
        $dupCheckResult = $conn->query($dupCheckSql);
        $dupCount = $dupCheckResult->fetch_row()[0];

        // If the key changed and the new key already exists, show error
        if ($dupCount > 0 && ($orig_acadYear != $acadYear || $orig_semester != $semester || $orig_roleID != $roleID)) {
            echo "<script>alert('An assigned record with the selected Academic Year, Semester, Role, and Student already exists.'); window.history.back();</script>";
            $conn->close();
            exit();
        }

        // If the key changed, delete the old and insert new
        if ($orig_acadYear != $acadYear || $orig_semester != $semester || $orig_roleID != $roleID) {
            $conn->query("DELETE FROM assigned WHERE studentID = '$studentID' AND acadYear = '$orig_acadYear' AND semester = '$orig_semester' AND roleID = '$orig_roleID'");
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
                    roleID = '$roleID',
                    status = " . ($status !== "" ? "'$status'" : "NULL") . ",
                    yearLevel = " . ($yearLevel !== "" ? "'$yearLevel'" : "NULL") . ",
                    contactNo = " . ($contactNo !== "" ? "'$contactNo'" : "NULL") . ",
                    presentAddress = " . ($presentAddress !== "" ? "'$presentAddress'" : "NULL") . ",
                    form5 = " . ($form5 !== "" ? "'$form5'" : "NULL") . "
                WHERE studentID = '$studentID' AND acadYear = '$acadYear' AND semester = '$semester' AND roleID = '$roleID'";
            $conn->query($updateAssignedSql);
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