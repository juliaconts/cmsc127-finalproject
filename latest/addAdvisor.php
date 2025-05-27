<?php
include 'DBConnector.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $advisorID = $_POST['advisorID'];
    $firstName = $_POST['firstName'];
    $middleInitial = $_POST['middleInitial'];
    $lastName = $_POST['lastName'];
    $type = $_POST['type']; // 1 for Advisor, 2 for Co-Advisor

    // handle academic year selection or new year
    if (isset($_POST['acadYear']) && $_POST['acadYear'] !== 'other') {
        $acadYear = trim($_POST['acadYear']);
    } elseif (isset($_POST['newAcadYear']) && $_POST['newAcadYear'] !== '') {
        $acadYear = trim($_POST['newAcadYear']);
    } else {
        $acadYear = '';
    }

    // input validation
    if (empty($advisorID) || empty($firstName) || empty($lastName) || empty($type) || empty($acadYear)) {
        echo "<script>alert('All fields are required. Please fill in all the information.'); window.history.back();</script>";
        $conn->close();
        exit();
    }

    // input validation for type
    if ($type != 1 && $type != 2) {
        echo "<script>alert('Invalid advisor type. Please select either Advisor or Co-Advisor.'); window.history.back();</script>";
        $conn->close();
        exit();
    }

    // check if advisorID already exists in the advisor table
    $checkSql = "SELECT advisorID FROM advisor WHERE advisorID = '$advisorID'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        echo "<script>alert('Advisor ID already exists. Please enter a unique Advisor ID.'); window.history.back();</script>";
        $conn->close();
        exit();
    }

    $conn->begin_transaction();

    // insert into advisor table
    $insertAdvisorSql = "INSERT INTO advisor (advisorID, firstName, middleInitial, lastName) VALUES ('$advisorID', '$firstName', '$middleInitial', '$lastName')";
    $advisorResult = $conn->query($insertAdvisorSql);

    if ($advisorResult) {
        $typeString = ($type == 1) ? 'Advisor' : 'Co-Advisor';

        // Ensure the academic year exists for both semesters in academicyear table
        $checkAcadYear1 = $conn->query("SELECT * FROM academicyear WHERE acadYear = '$acadYear' AND semester = 1");
        $checkAcadYear2 = $conn->query("SELECT * FROM academicyear WHERE acadYear = '$acadYear' AND semester = 2");
        if ($checkAcadYear1->num_rows == 0) {
            $conn->query("INSERT INTO academicyear (acadYear, semester) VALUES ('$acadYear', 1)");
        }
        if ($checkAcadYear2->num_rows == 0) {
            $conn->query("INSERT INTO academicyear (acadYear, semester) VALUES ('$acadYear', 2)");
        }

        // insert for both semesters (1 and 2) for the selected academic year
        $insertAdvisesSql1 = "INSERT INTO advises (advisorID, type, acadYear, semester) VALUES ('$advisorID', '$typeString', '$acadYear', 1)";
        $insertAdvisesSql2 = "INSERT INTO advises (advisorID, type, acadYear, semester) VALUES ('$advisorID', '$typeString', '$acadYear', 2)";
        $adviseResult1 = $conn->query($insertAdvisesSql1);
        $adviseResult2 = $conn->query($insertAdvisesSql2);

        if ($adviseResult1 && $adviseResult2) {
            $conn->commit();
            echo "<script>
                    alert('Advisor has been successfully added for both semesters.');
                    window.location.href='homepage.php';
                  </script>";
            exit();
        } else {
            echo "<script>alert('Error inserting into advises table: " . addslashes($conn->error) . "'); window.history.back();</script>";
            $conn->rollback();
        }
    } else {
        echo "<script>alert('Error inserting into advisor table: " . addslashes($conn->error) . "'); window.history.back();</script>";
        $conn->rollback();
    }

    $conn->close();
}
?>