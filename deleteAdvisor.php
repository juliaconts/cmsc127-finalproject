<?php
include 'DBConnector.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['advisorID'])) {
    $advisorID = mysqli_real_escape_string($conn, $_POST['advisorID']);

    // Step 1: Delete related records in advises
    $deleteAdvisesSQL = "DELETE FROM advises WHERE advisorID = '$advisorID'";
    if (!$conn->query($deleteAdvisesSQL)) {
        echo "<script>
                alert('Failed to delete related advises: " . addslashes($conn->error) . "');
                window.history.back();
              </script>";
        $conn->close();
        exit();
    }

    // Step 2: Delete advisor
    $deleteAdvisorSQL = "DELETE FROM advisor WHERE advisorID = '$advisorID'";
    if ($conn->query($deleteAdvisorSQL) === TRUE) {
        echo "<script>
                alert('Advisor with ID $advisorID has been successfully deleted.');
                window.location.href='homepage.php';
              </script>";
    } else {
        echo "<script>
                alert('Failed to delete advisor: " . addslashes($conn->error) . "');
                window.history.back();
              </script>";
    }
} else {
    echo "<script>
            alert('Invalid request.');
            window.history.back();
          </script>";
}

$conn->close();
?>
