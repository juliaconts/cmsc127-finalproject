<?php
include 'DBConnector.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["studentID"])) {
    $studentID = mysqli_real_escape_string($conn, $_POST["studentID"]);

    $conn->begin_transaction();

    // delete fk constraint (pays)
    if (!$conn->query("DELETE FROM pays WHERE studentID = '$studentID'")) {
        $conn->rollback();
        echo "<script>
                alert('Failed to delete related pays: " . addslashes($conn->error) . "');
                window.history.back();
              </script>";
        $conn->close();
        exit();
    }

    //  delete fk constraint (assigned)
    if (!$conn->query("DELETE FROM assigned WHERE studentID = '$studentID'")) {
        $conn->rollback();
        echo "<script>
                alert('Failed to delete related assigned records: " . addslashes($conn->error) . "');
                window.history.back();
              </script>";
        $conn->close();
        exit();
    }

    //  delete fk constraint (student)
    $conn->query("DELETE FROM student WHERE studentID = '$studentID'");

    // delete from alumni (if exists)
    $conn->query("DELETE FROM alumni WHERE studentID = '$studentID'");

    // delete from member 
    if ($conn->query("DELETE FROM member WHERE studentID = '$studentID'") === TRUE) {
        $conn->commit();
        echo "<script>
                alert('Member with ID $studentID has been successfully deleted.');
                window.location.href='homepage.php';
              </script>";
    } else {
        $conn->rollback();
        echo "<script>
                alert('Error deleting member: " . addslashes($conn->error) . "');
                window.location.href='homepage.php';
              </script>";
    }
}

$conn->close();
?>