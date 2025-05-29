<?php
include 'DBConnector.php';

// Handle POST: Show JS confirm and redirect to GET
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["advisorID"])) {
    $advisorID = $_POST["advisorID"];
    $acadYear = isset($_POST['acadYear']) ? $_POST['acadYear'] : '';
    $semester = isset($_POST['semester']) ? $_POST['semester'] : '';
    $type = isset($_POST['type']) ? $_POST['type'] : '';

    echo "<script>
        var advisorID = encodeURIComponent(" . json_encode($advisorID) . ");
        var acadYear = encodeURIComponent(" . json_encode($acadYear) . ");
        var semester = encodeURIComponent(" . json_encode($semester) . ");
        var typeVal = encodeURIComponent(" . json_encode($type) . ");
        var choice = confirm('Click OK to delete this advisor from the entire database.\\nClick Cancel to delete only this assignment (for the selected semester, academic year, and type).');
        if (choice) {
            window.location.href = 'deleteAdvisor.php?advisorID=' + advisorID + '&type=full&acadYear=' + acadYear + '&semester=' + semester + '&advType=' + typeVal;
        } else {
            window.location.href = 'deleteAdvisor.php?advisorID=' + advisorID + '&type=instance&acadYear=' + acadYear + '&semester=' + semester + '&advType=' + typeVal;
        }
    </script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["type"]) && isset($_GET["advisorID"])) {
    $type = $_GET["type"];
    $advisorID = mysqli_real_escape_string($conn, $_GET["advisorID"]);
    $acadYear = isset($_GET['acadYear']) ? mysqli_real_escape_string($conn, $_GET['acadYear']) : '';
    $semester = isset($_GET['semester']) ? mysqli_real_escape_string($conn, $_GET['semester']) : '';
    $advType = isset($_GET['advType']) ? mysqli_real_escape_string($conn, $_GET['advType']) : '';

    $conn->begin_transaction();

    //if the advisor is to be completely deleted from the database
    if ($type === "full") {
        // delete all advises for this advisor, then the advisor record itself
        $conn->query("DELETE FROM advises WHERE advisorID = '$advisorID'");
        if ($conn->query("DELETE FROM advisor WHERE advisorID = '$advisorID'") === TRUE) {
            $conn->commit();
            echo "<script>
                    alert('Advisor with ID $advisorID has been fully deleted from the database.');
                    window.location.href='homepage.php?acadYear=" . urlencode($acadYear) . "&semester=" . urlencode($semester) . "';
                  </script>";
        } else {
            $conn->rollback();
            echo "<script>
                    alert('Error deleting advisor: " . addslashes($conn->error) . "');
                    window.location.href='homepage.php?acadYear=" . urlencode($acadYear) . "&semester=" . urlencode($semester) . "';
                  </script>";
        }
    //if the advisor is to be deleted only for a specific instance (semester, academic year, and type)
    } elseif ($type === "instance") {
        $conn->query("DELETE FROM advises WHERE advisorID = '$advisorID' AND acadYear = '$acadYear' AND semester = '$semester' AND type = '$advType'");
        $conn->commit();
        echo "<script>
                alert('Advisor assignment for $acadYear, Semester $semester, $advType has been deleted.');
                window.location.href='homepage.php?acadYear=" . urlencode($acadYear) . "&semester=" . urlencode($semester) . "';
              </script>";
    }
    exit();
}

$conn->close();
?>