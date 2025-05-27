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
    $role = $conn->real_escape_string($_POST['role']); 

    // update the member table (only columns that exist in member)
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
        // update the assigned table and get the latest acadYear and semester for this student
        $acadQuery = "SELECT acadYear, semester FROM assigned WHERE studentID = '$studentID' ORDER BY acadYear DESC, semester DESC LIMIT 1";
        $acadResult = $conn->query($acadQuery);
        if ($acadRow = $acadResult->fetch_assoc()) {
            $acadYear = $acadRow['acadYear'];
            $semester = $acadRow['semester'];
        } else {
            // fallback to latest acadYear/semester in academicyear
            $acadYearResult = $conn->query("SELECT acadYear, semester FROM academicyear ORDER BY acadYear DESC, semester DESC LIMIT 1");
            $acadYearRow = $acadYearResult->fetch_assoc();
            $acadYear = $acadYearRow['acadYear'];
            $semester = $acadYearRow['semester'];
        }

        //get the roleID 
        $getRoleIdSql = "SELECT roleID FROM roles WHERE role = '$role'";
        $roleIdResult = $conn->query($getRoleIdSql);

        if ($roleIdResult && $roleIdResult->num_rows > 0) {
            $roleIdRow = $roleIdResult->fetch_assoc();
            $roleId = $roleIdRow['roleID'];

            // check if an assigned record exists for this student/acadYear/semester
            $checkSql = "SELECT COUNT(*) FROM assigned WHERE studentID = '$studentID' AND acadYear = '$acadYear' AND semester = '$semester'";
            $checkResult = $conn->query($checkSql);
            $count = $checkResult->fetch_row()[0];

            if ($count > 0) {
                // update existing assigned record
                $updateAssignedSql = "UPDATE assigned SET 
                        roleID = '$roleId',
                        status = " . ($status !== "" ? "'$status'" : "NULL") . ",
                        yearLevel = " . ($yearLevel !== "" ? "'$yearLevel'" : "NULL") . ",
                        contactNo = " . ($contactNo !== "" ? "'$contactNo'" : "NULL") . ",
                        presentAddress = " . ($presentAddress !== "" ? "'$presentAddress'" : "NULL") . ",
                        form5 = " . ($form5 !== "" ? "'$form5'" : "NULL") . "
                    WHERE studentID = '$studentID' AND acadYear = '$acadYear' AND semester = '$semester'";
                $conn->query($updateAssignedSql);
            } else {
                // insert new assigned record
                $insertAssignedSql = "INSERT INTO assigned (semester, acadYear, roleID, studentID, yearLevel, status, contactNo, presentAddress, form5)
                    VALUES (
                        '$semester', '$acadYear', '$roleId', '$studentID', " .
                        ($yearLevel !== "" ? "'$yearLevel'" : "NULL") . ", " .
                        ($status !== "" ? "'$status'" : "NULL") . ", " .
                        ($contactNo !== "" ? "'$contactNo'" : "NULL") . ", " .
                        ($presentAddress !== "" ? "'$presentAddress'" : "NULL") . ", " .
                        ($form5 !== "" ? "'$form5'" : "NULL") .
                    ")";
                $conn->query($insertAssignedSql);
            }
        }

        header("Location: homepage.php");
        exit();
    } else {
        echo "Error updating member record: " . $conn->error;
    }

    $conn->close();
}
?>