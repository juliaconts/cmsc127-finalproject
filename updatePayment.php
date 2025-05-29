<?php
include 'DBConnector.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentID = $_POST['studentID'];
    $acadYear = $_POST['acadYear'];
    $semester = (int)$_POST['semester'];
    $membershipFeeStatus = (int)$_POST['membershipFeeStatus'];
    $akwe2023Status = (int)$_POST['akwe2023Status'];
    $akwe2024Status = (int)$_POST['akwe2024Status'];

    function updateFeeStatus($conn, $studentID, $feeName, $isPaid, $acadYear, $semester) {
        $query = "UPDATE pays p
                  JOIN payment pm ON p.paymentID = pm.paymentID
                  SET p.isPaid = ?
                  WHERE p.studentID = ? AND pm.payment = ? AND p.acadYear = ? AND p.semester = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            die("Prepare failed for $feeName: (" . $conn->errno . ") " . $conn->error);
        }

        // Corrected type: isPaid (int), studentID (string), feeName (string), acadYear (string), semester (int)
        $stmt->bind_param("isssi", $isPaid, $studentID, $feeName, $acadYear, $semester);

        if (!$stmt->execute()) {
            die("Execute failed for $feeName: (" . $stmt->errno . ") " . $stmt->error);
        }

        if ($stmt->affected_rows === 0) {
            echo "No existing record found to update for fee: $feeName<br>";
        } else {
            echo "Successfully updated $feeName.<br>";
        }

        $stmt->close();
    }

    updateFeeStatus($conn, $studentID, 'Membership Fee', $membershipFeeStatus, $acadYear, $semester);
    updateFeeStatus($conn, $studentID, 'AKWE 2023', $akwe2023Status, $acadYear, $semester);
    updateFeeStatus($conn, $studentID, 'AKWE 2024', $akwe2024Status, $acadYear, $semester);

    $conn->close();

    // Redirect after updates succeed (uncomment when ready)
    // header("Location: paymentpage.php?acadYear=" . urlencode($acadYear) . "&semester=" . urlencode($semester));
    // exit;
} else {
    echo "Invalid request.";
}
?>