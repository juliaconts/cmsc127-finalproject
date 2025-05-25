<?php
include 'DBConnector.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["studentID"])) {
    $studentID = $_POST["studentID"];

    // Delete from child tables FIRST
    $conn->query("DELETE FROM pays WHERE studentID = '$studentID'");
    $conn->query("DELETE FROM assigned WHERE studentID = '$studentID'");
    $conn->query("DELETE FROM student WHERE studentID = '$studentID'");

    // Then delete from member
    $deleteSQL = "DELETE FROM member WHERE studentID = '$studentID'";

    if ($conn->query($deleteSQL) === TRUE) {
        echo "<script>
                alert('Member with ID $studentID has been successfully deleted.');
                window.location.href='homepage.php';
              </script>";
    } else {
        echo "<script>
                alert('Error deleting member: " . $conn->error . "');
                window.location.href='homepage.php';
              </script>";
    }
}

$conn->close();
?>
