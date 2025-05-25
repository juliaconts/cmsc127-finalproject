<?php
include 'DBConnector.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $memberType = $_POST['memberType']; // "Student" or "Alumni"
    $studentID = $_POST['studentID'];
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $lastName = $_POST['lastName'];
    $status = $_POST['status'];
    $upMail = $_POST['upMail'];
    $yearLevel = $_POST['yearLevel'];
    $university = $_POST['university'];
    $degreeProgram = $_POST['degreeProgram'];
    $contactNo = $_POST['contactNo'];
    $presentAddress = $_POST['presentAddress'];
    $homeAddress = $_POST['homeAddress'];
    $birthday = $_POST['birthday'];
    $signature = $_POST['signature'];
    $idPicture = $_POST['idPicture'];
    $form5 = $_POST['form5'];
    $alumniID = $_POST['alumniID'] ?? null; // Only set if it exists
    $roleID = $_POST['roleID']; 

    // Common member data insertion
    $sql = "INSERT INTO member (
        studentID, firstName, middleName, lastName, status, upMail, yearLevel,
        university, degreeProgram, contactNo, presentAddress, homeAddress,
        birthday, signature, idPicture, form5
    ) VALUES (
        '$studentID', '$firstName', '$middleName', '$lastName', '$status', '$upMail', '$yearLevel',
        '$university', '$degreeProgram', '$contactNo', '$presentAddress', '$homeAddress',
        '$birthday', '$signature', '$idPicture', '$form5'
    )";

    if (mysqli_query($conn, $sql)) {
        // Insert into student or alumni table
        if ($memberType == "Student") {
            $sqlStudent = "INSERT INTO student (studentID) VALUES ('$studentID')";
            if (mysqli_query($conn, $sqlStudent)) {
                echo " Student record added successfully.";
            } else {
                echo " Error adding student record: " . mysqli_error($conn);
            }
        } elseif ($memberType == "Alumni" && $alumniID != null) {
            $sqlAlumni = "INSERT INTO alumni (alumniID, studentID) VALUES ('$alumniID', '$studentID')";
            if (mysqli_query($conn, $sqlAlumni)) {
                echo " Alumni record added successfully.";
            } else {
                echo " Error adding alumni record: " . mysqli_error($conn);
            }
        }  elseif ($memberType == "Alumni" && $alumniID == null) {
            echo " Error: Alumni ID is required.";
        }

                $acadQuery = "SELECT acadYear, semester FROM academicyear ORDER BY acadYear DESC, semester DESC LIMIT 1";
        $acadResult = mysqli_query($conn, $acadQuery);
        if ($acadRow = mysqli_fetch_assoc($acadResult)) {
            $acadYear = $acadRow['acadYear'];
            $semester = $acadRow['semester'];
            $sqlAssigned = "INSERT INTO assigned (semester, acadYear, roleID, studentID) VALUES ('$semester', '$acadYear', '$roleID', '$studentID')";
            mysqli_query($conn, $sqlAssigned);
        }

        echo "Member added successfully.";

        echo "<script>
                alert('A member has been successfully added.');
                window.location.href='homepage.php';
              </script>";
    } else {
        echo "Error adding member: " . mysqli_error($conn);
    }
}

$conn->close();
?>
