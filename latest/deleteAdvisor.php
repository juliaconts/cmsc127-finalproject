<?php
include 'DBConnector.php';

var_dump($_POST); // See what is actually being sent
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['advisorID'], $_POST['acadYear'], $_POST['semester'], $_POST['type'])
) {
    $advisorID = trim(mysqli_real_escape_string($conn, $_POST['advisorID']));
    $acadYear = trim(mysqli_real_escape_string($conn, $_POST['acadYear']));
    $semester = trim(mysqli_real_escape_string($conn, $_POST['semester']));
    $type = trim(mysqli_real_escape_string($conn, $_POST['type']));

    // delete w/ advisorID, acadYear, semester and type
    $deleteAdvisesSQL = "DELETE FROM advises 
        WHERE advisorID = '$advisorID' 
          AND acadYear = '$acadYear' 
          AND semester = '$semester'
          AND type = '$type'";

    if ($conn->query($deleteAdvisesSQL)) {
        echo "<script>
                alert('Advisor assignment for $acadYear, Semester $semester, $type has been successfully deleted.');
                window.location.href='homepage.php';
              </script>";
    } else {
        echo "<script>
                alert('Failed to delete advisor assignment: " . addslashes($conn->error) . "');
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