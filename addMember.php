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
    $yearGraduated = $_POST['yearGraduated'] ?? null;


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
        $sqlAlumni = "INSERT INTO alumni (studentID, yearGraduated) VALUES ('$studentID', '$yearGraduated')";
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

        //role logic so that the latest person overwrites the previous one
        if ($roleID >= 1 && $roleID <= 8) {
            // remove any existing assignment for this role in this acadYear/semester
            $conn->query("DELETE FROM assigned WHERE acadYear='$acadYear' AND semester='$semester' AND roleID='$roleID'");
        }

        // batchrep logic
        if ($roleID == 9 && $yearLevel !== null && $yearLevel !== "") {
            // count batch reps for this year level
            $batchRepCount = $conn->query("SELECT COUNT(*) FROM assigned WHERE acadYear='$acadYear' AND semester='$semester' AND roleID=9 AND yearLevel='$yearLevel'");
            $count = $batchRepCount ? $batchRepCount->fetch_row()[0] : 0;
            if ($count >= 4) {
                // remove the oldest (or any) batch rep for this year level
                $conn->query("DELETE FROM assigned WHERE acadYear='$acadYear' AND semester='$semester' AND roleID=9 AND yearLevel='$yearLevel' ORDER BY studentID ASC LIMIT 1");
            }
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

        // insert into pays table
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