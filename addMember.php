<?php
include 'DBConnector.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $memberType = $_POST['memberType']; 
    $studentID = $_POST['studentID'];
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $lastName = $_POST['lastName'];
    $upMail = $_POST['upMail'];
    $university = $_POST['university'];
    $degreeProgram = $_POST['degreeProgram'];
    $homeAddress = $_POST['homeAddress'];
    $birthday = $_POST['birthday'];
    $signature = $_POST['signature'];
    $idPicture = $_POST['idPicture'];

    $status = $_POST['status'] ?? null;
    $roleID = $_POST['roleID'];
    $yearLevel = $_POST['yearLevel'] ?? null;
    $contactNo = $_POST['contactNo'] ?? null;
    $presentAddress = $_POST['presentAddress'] ?? null;
    $form5 = $_POST['form5'] ?? null;

    $conn->begin_transaction();

    // insert into member table
    $sql = "INSERT INTO member (
        studentID, firstName, middleName, lastName, upMail, university, degreeProgram, homeAddress, birthday, signature, idPicture
    ) VALUES (
        '$studentID', '$firstName', '$middleName', '$lastName', '$upMail', '$university', '$degreeProgram', '$homeAddress', '$birthday', '$signature', '$idPicture'
    )";

    if (!mysqli_query($conn, $sql)) {
        $conn->rollback();
        echo "<script>alert('Error adding member: " . addslashes(mysqli_error($conn)) . "'); window.history.back();</script>";
        exit();
    }

    $alumniID = null;
    if ($memberType == "Alumni") {
        // Auto-generate alumniID (use studentID or a unique value)
        $alumniID = $studentID;
    }

    // insert into student or alumni table
    if ($memberType == "Student") {
        $sqlStudent = "INSERT INTO student (studentID) VALUES ('$studentID')";
        if (!mysqli_query($conn, $sqlStudent)) {
            $conn->rollback();
            echo "<script>alert('Error adding student record: " . addslashes(mysqli_error($conn)) . "'); window.history.back();</script>";
            exit();
        }
    } elseif ($memberType == "Alumni") {
        $sqlAlumni = "INSERT INTO alumni (alumniID, studentID) VALUES ('$alumniID', '$studentID')";
        if (!mysqli_query($conn, $sqlAlumni)) {
            $conn->rollback();
            echo "<script>alert('Error adding alumni record: " . addslashes(mysqli_error($conn)) . "'); window.history.back();</script>";
            exit();
        }
    }


    // get latest acadYear and semester 
    $acadQuery = "SELECT acadYear, semester FROM academicyear ORDER BY acadYear DESC, semester DESC LIMIT 1";
    $acadResult = mysqli_query($conn, $acadQuery);
    if ($acadRow = mysqli_fetch_assoc($acadResult)) {
        $acadYear = $acadRow['acadYear'];
        $semester = $acadRow['semester'];

        $checkAssigned = "SELECT COUNT(*) FROM assigned WHERE semester='$semester' AND acadYear='$acadYear' AND roleID='$roleID' AND studentID='$studentID'";
        $assignedResult = mysqli_query($conn, $checkAssigned);
        $assignedCount = mysqli_fetch_row($assignedResult)[0];
        if ($assignedCount > 0) {
            $conn->rollback();
            echo "<script>alert('This member is already assigned for the selected academic year, semester, and role.'); window.history.back();</script>";
            exit();
        }

        // insert into assigned table
        $sqlAssigned = "INSERT INTO assigned (semester, acadYear, roleID, studentID, yearLevel, status, contactNo, presentAddress)
                        VALUES (
                            '$semester', '$acadYear', '$roleID', '$studentID', " .
                            ($yearLevel !== null && $yearLevel !== "" ? "'$yearLevel'" : "NULL") . ", " .
                            ($status !== null && $status !== "" ? "'$status'" : "NULL") . ", " .
                            ($contactNo !== null && $contactNo !== "" ? "'$contactNo'" : "NULL") . ", " .
                            ($presentAddress !== null && $presentAddress !== "" ? "'$presentAddress'" : "NULL") .
                        ")";
        if (!mysqli_query($conn, $sqlAssigned)) {
            $conn->rollback();
            echo "<script>alert('Error assigning role: " . addslashes(mysqli_error($conn)) . "'); window.history.back();</script>";
            exit();
        }

    } else {
        $conn->rollback();
        echo "<script>alert('No academic year found.'); window.history.back();</script>";
        exit();
    }

        if ($form5 !== null && $form5 !== "") {
        $sqlForm5 = "INSERT INTO form5 (acadYear, semester, studentID, form5)
                    VALUES ('$acadYear', '$semester', '$studentID', '$form5')";
        if (!mysqli_query($conn, $sqlForm5)) {
            $conn->rollback();
            echo "<script>alert('Error adding Form 5: " . addslashes(mysqli_error($conn)) . "'); window.history.back();</script>";
            exit();
        }
    }

    $conn->commit();
    echo "<script>
            alert('A member has been successfully added.');
            window.location.href='homepage.php?acadYear=$acadYear&semester=$semester';
          </script>";
}

$conn->close();
?>