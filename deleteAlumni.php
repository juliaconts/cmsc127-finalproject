<?php
include 'DBConnector.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["studentID"])) {
    $studentID = $_POST["studentID"];

    // Step 1: Delete from pays and assigned (children of student)
    $conn->query("DELETE FROM pays WHERE studentID = '$studentID'");
    $conn->query("DELETE FROM assigned WHERE studentID = '$studentID'");

    // Step 2: Delete from student
    $conn->query("DELETE FROM student WHERE studentID = '$studentID'");

    // Step 3: Delete from member
    $conn->query("DELETE FROM member WHERE studentID = '$studentID'");

    // Step 4: Delete from alumni
    $deleteSQL = "DELETE FROM alumni WHERE studentID = '$studentID'";

    if ($conn->query($deleteSQL) === TRUE) {
        echo "<script>
                alert('Alumni with ID $studentID has been successfully deleted.');
                window.location.href='homepage.php';
              </script>";
    } else {
        echo "<script>
                alert('Error deleting alumni: " . $conn->error . "');
                window.location.href='homepage.php';
              </script>";
    }
}

$conn->close();
?>
