<?php
include 'DBConnector.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $alumniID = $conn->real_escape_string($_POST['alumniID']);
    $studentID = $conn->real_escape_string($_POST['studentID']);
    $firstName = $conn->real_escape_string($_POST['firstName']);
    $middleName = $conn->real_escape_string($_POST['middleName']);
    $lastName = $conn->real_escape_string($_POST['lastName']);
    $upMail = $conn->real_escape_string($_POST['upMail']);
    $university = $conn->real_escape_string($_POST['university']);
    $degreeProgram = $conn->real_escape_string($_POST['degreeProgram']);
    $homeAddress = $conn->real_escape_string($_POST['homeAddress']);
    $birthday = $conn->real_escape_string($_POST['birthday']);
    $signature = $conn->real_escape_string($_POST['signature']);
    $idPicture = $conn->real_escape_string($_POST['idPicture']);

    // update the member table
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
        header("Location: homepage.php");
        exit();
    } else {
        echo "Error updating alumni record: " . $conn->error;
    }

    $conn->close();
}
?>