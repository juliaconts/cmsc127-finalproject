<?php
include 'DBConnector.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $advisorID = $_POST['advisorID'];
    $firstName = $_POST['firstName'];
    $middleInitial = $_POST['middleInitial'];
    $lastName = $_POST['lastName'];
    $type = $_POST['type']; // 1 for Advisor, 2 for Co-Advisor
    $acadYear = $_POST['acadYear'];
    $semester = $_POST['semester'];

    // Validate input (important for security and data integrity)
    if (empty($advisorID) || empty($firstName) || empty($lastName) || empty($type) || empty($acadYear) || empty($semester)) {
        echo "All fields are required. Please fill in all the information.";
        $conn->close();
        exit();
    }

    if ($type != 1 && $type != 2) {
        echo "Invalid advisor type.  Please select either Advisor or Co-Advisor.";
        $conn->close();
        exit();
    }

    // Check if advisorID already exists in the advisor table
    $checkSql = "SELECT advisorID FROM advisor WHERE advisorID = '$advisorID'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        echo "Advisor ID already exists. Please enter a unique Advisor ID.";
        $conn->close();
        exit();
    }

    // Begin transaction (for data integrity, to ensure both tables are updated or neither is)
    $conn->begin_transaction();

    // Insert into advisor table
    $insertAdvisorSql = "INSERT INTO advisor (advisorID, firstName, middleInitial, lastName) VALUES ('$advisorID', '$firstName', '$middleInitial', '$lastName')";
    $advisorResult = $conn->query($insertAdvisorSql);

    if ($advisorResult) {
        echo "Advisor record created successfully.<br>";
        echo "<script>
                alert('A member has been successfully added.');
                window.location.href='homepage.php';
              </script>";
        // Insert into advises table
        $typeString = ($type == 1) ? 'Advisor' : 'Co-Advisor';
        $insertAdvisesSql = "INSERT INTO advises (advisorID, type, acadYear, semester) VALUES ('$advisorID', '$typeString', '$acadYear', '$semester')";
        $adviseResult = $conn->query($insertAdvisesSql);

        if ($adviseResult) {
            echo "Advises record created successfully.<br>";
            $conn->commit(); // Commit the transaction
            header("Location: homepage.php"); // Redirect to homepage
            exit();
        } else {
            echo "Error inserting into advises table: " . $conn->error . "<br>";
            $conn->rollback(); // Rollback the transaction
        }
    } else {
        echo "Error inserting into advisor table: " . $conn->error . "<br>";
        $conn->rollback(); // Rollback the transaction
    }

    $conn->close();
}