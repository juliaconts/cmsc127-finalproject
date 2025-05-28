<?php
include 'DBConnector.php';

// Handle POST: Show JS confirm and redirect to GET
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["studentID"])) {
    $studentID = $_POST["studentID"];
    $acadYear = isset($_POST['acadYear']) ? $_POST['acadYear'] : '';
    $semester = isset($_POST['semester']) ? $_POST['semester'] : '';

    echo "<script>
        var studentID = encodeURIComponent(" . json_encode($studentID) . ");
        var acadYear = encodeURIComponent(" . json_encode($acadYear) . ");
        var semester = encodeURIComponent(" . json_encode($semester) . ");
        var choice = confirm('Click OK to delete this student from the entire database.\\nClick Cancel to delete only this instance (for the selected semester and academic year).');
        if (choice) {
            window.location.href = 'deleteMember.php?studentID=' + studentID + '&type=full&acadYear=' + acadYear + '&semester=' + semester;
        } else {
            window.location.href = 'deleteMember.php?studentID=' + studentID + '&type=instance&acadYear=' + acadYear + '&semester=' + semester;
        }
    </script>";
    exit();
}

// Handle GET: Actually perform the deletion
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["type"]) && isset($_GET["studentID"])) {
    $type = $_GET["type"];
    $studentID = mysqli_real_escape_string($conn, $_GET["studentID"]);
    $acadYear = isset($_GET['acadYear']) ? $_GET['acadYear'] : '';
    $semester = isset($_GET['semester']) ? $_GET['semester'] : '';

    $conn->begin_transaction();

    if ($type === "full") {
        $conn->query("DELETE FROM pays WHERE studentID = '$studentID'");
        $conn->query("DELETE FROM assigned WHERE studentID = '$studentID'");
        $conn->query("DELETE FROM form5 WHERE studentID = '$studentID'");
        $conn->query("DELETE FROM student WHERE studentID = '$studentID'");
        $conn->query("DELETE FROM alumni WHERE studentID = '$studentID'");
        if ($conn->query("DELETE FROM member WHERE studentID = '$studentID'") === TRUE) {
            $conn->commit();
            echo "<script>
                    alert('Member with ID $studentID has been fully deleted from the database.');
                    window.location.href='homepage.php?acadYear=" . urlencode($acadYear) . "&semester=" . urlencode($semester) . "';
                  </script>";
        } else {
            $conn->rollback();
            echo "<script>
                    alert('Error deleting member: " . addslashes($conn->error) . "');
                    window.location.href='homepage.php?acadYear=" . urlencode($acadYear) . "&semester=" . urlencode($semester) . "';
                  </script>";
        }
    } elseif ($type === "instance") {
        $conn->query("DELETE FROM pays WHERE studentID = '$studentID' AND acadYear = '$acadYear' AND semester = '$semester'");
        $conn->query("DELETE FROM assigned WHERE studentID = '$studentID' AND acadYear = '$acadYear' AND semester = '$semester'");
        $conn->query("DELETE FROM form5 WHERE studentID = '$studentID' AND acadYear = '$acadYear' AND semester = '$semester'");
        $conn->commit();
        echo "<script>
                alert('Member instance for $acadYear, Semester $semester has been deleted.');
                window.location.href='homepage.php?acadYear=" . urlencode($acadYear) . "&semester=" . urlencode($semester) . "';
              </script>";
    }
    exit();
}

$conn->close();
?>