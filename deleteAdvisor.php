<?php
<?php
include 'DBConnector.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["studentID"])) {
    $studentID = urlencode($_POST["studentID"]);
    $acadYear = isset($_POST['acadYear']) ? urlencode($_POST['acadYear']) : '';
    $semester = isset($_POST['semester']) ? urlencode($_POST['semester']) : '';
    $yearLevel = isset($_POST['yearLevel']) ? urlencode($_POST['yearLevel']) : '';

    // Ask user what to delete (JS prompt)
    echo "<script>
        var choice = confirm('Click OK to delete this student from the entire database.\\nClick Cancel to delete only this instance (for the selected semester, academic year, and year level).');
        if (choice) {
            window.location.href = 'deleteMember.php?studentID=$studentID&type=full&acadYear=$acadYear&semester=$semester&yearLevel=$yearLevel';
        } else {
            window.location.href = 'deleteMember.php?studentID=$studentID&type=instance&acadYear=$acadYear&semester=$semester&yearLevel=$yearLevel';
        }
    </script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["type"]) && isset($_GET["studentID"])) {
    $type = $_GET["type"];
    $studentID = mysqli_real_escape_string($conn, $_GET["studentID"]);
    $acadYear = isset($_GET['acadYear']) ? $_GET['acadYear'] : '';
    $semester = isset($_GET['semester']) ? $_GET['semester'] : '';
    $yearLevel = isset($_GET['yearLevel']) ? intval($_GET['yearLevel']) : '';

    $conn->begin_transaction();

    if ($type === "full") {
        // Delete all related records for this student
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
        // Delete only the assigned/form5/pays for this instance
        $conn->query("DELETE FROM pays WHERE studentID = '$studentID' AND acadYear = '$acadYear' AND semester = '$semester'");
        $conn->query("DELETE FROM assigned WHERE studentID = '$studentID' AND acadYear = '$acadYear' AND semester = '$semester' AND yearLevel = '$yearLevel'");
        $conn->query("DELETE FROM form5 WHERE studentID = '$studentID' AND acadYear = '$acadYear' AND semester = '$semester'");
        $conn->commit();
        echo "<script>
                alert('Member instance for $acadYear, Semester $semester, Year Level $yearLevel has been deleted.');
                window.location.href='homepage.php?acadYear=" . urlencode($acadYear) . "&semester=" . urlencode($semester) . "';
              </script>";
    }
}

$conn->close();
?>