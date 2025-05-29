<?php
include 'DBConnector.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['studentID'], $_POST['membershipFeeStatus'], $_POST['akwe2023Status'], $_POST['akwe2024Status'])) {
    // === HANDLE UPDATE ===
    $studentID = $_POST['studentID'];
    $acadYear = $_POST['acadYear'];
    $semester = (int)$_POST['semester'];
    $membershipFeeStatus = (int)$_POST['membershipFeeStatus'];
    $akwe2023Status = (int)$_POST['akwe2023Status'];
    $akwe2024Status = (int)$_POST['akwe2024Status'];

    $fees = [
        'Membership Fee' => $membershipFeeStatus,
        'AKWE 2023' => $akwe2023Status,
        'AKWE 2024' => $akwe2024Status
    ];

    foreach ($fees as $feeName => $status) {
        // First, try to update
        $query = "UPDATE pays p
                  JOIN payment pm ON p.paymentID = pm.paymentID
                  SET p.isPaid = ?
                  WHERE p.studentID = ? AND pm.payment = ? AND p.acadYear = ? AND p.semester = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isssi", $status, $studentID, $feeName, $acadYear, $semester);
        $stmt->execute();

        if ($stmt->affected_rows === 0 && $status === 1) {
            // No row updated and status is paid, so insert new
            $stmt->close();
            $paymentQuery = "SELECT paymentID FROM payment WHERE payment = ?";
            $paymentStmt = $conn->prepare($paymentQuery);
            $paymentStmt->bind_param("s", $feeName);
            $paymentStmt->execute();
            $paymentResult = $paymentStmt->get_result();

            if ($paymentRow = $paymentResult->fetch_assoc()) {
                $paymentID = $paymentRow['paymentID'];

                // Check if record already exists
                $checkQuery = "SELECT COUNT(*) FROM pays WHERE studentID = ? AND paymentID = ? AND acadYear = ? AND semester = ?";
                $checkStmt = $conn->prepare($checkQuery);
                $checkStmt->bind_param("sisi", $studentID, $paymentID, $acadYear, $semester);
                $checkStmt->execute();
                $checkStmt->bind_result($count);
                $checkStmt->fetch();
                $checkStmt->close();

            if ($count == 0) {
                // Safe to insert
                $insertQuery = "INSERT INTO pays (studentID, paymentID, isPaid, acadYear, semester)
                                VALUES (?, ?, ?, ?, ?)";
                $insertStmt = $conn->prepare($insertQuery);
                $insertStmt->bind_param("siisi", $studentID, $paymentID, $status, $acadYear, $semester);
                $insertStmt->execute();
                $insertStmt->close();
            }
        }

        $paymentStmt->close();
        } else {
            $stmt->close();
        }
    }

    $conn->close();

    header("Location: paymentpage.php?acadYear=" . urlencode($acadYear) . "&semester=" . urlencode($semester) . "&updated=true");
    exit;

} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['studentID'])) {
    // === DISPLAY FORM ===
    $acadYear = $_POST['acadYear'];
    $semester = $_POST['semester'];
    $studentID = $_POST['studentID'];

    $sql = "SELECT 
                m.studentID, 
                CONCAT(m.lastName, ', ', m.firstName, ' ', m.middleName) AS Name,
                (SELECT p.isPaid FROM pays p JOIN payment pm ON p.paymentID = pm.paymentID 
                 WHERE p.studentID = m.studentID AND pm.payment = 'Membership Fee' AND p.acadYear = ? AND p.semester = ? LIMIT 1) AS membershipFeeStatus,
                (SELECT p.isPaid FROM pays p JOIN payment pm ON p.paymentID = pm.paymentID 
                 WHERE p.studentID = m.studentID AND pm.payment = 'AKWE 2023' AND p.acadYear = ? AND p.semester = ? LIMIT 1) AS akwe2023Status,
                (SELECT p.isPaid FROM pays p JOIN payment pm ON p.paymentID = pm.paymentID 
                 WHERE p.studentID = m.studentID AND pm.payment = 'AKWE 2024' AND p.acadYear = ? AND p.semester = ? LIMIT 1) AS akwe2024Status
            FROM member m
            WHERE m.studentID = ?
            LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $acadYear, $semester, $acadYear, $semester, $acadYear, $semester, $studentID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Payment Status</title>
    <style>
        body { font-family: "Chakra Petch", sans-serif; background-color: #000C1E; }
        form {
            width: 40%; margin: 3% auto; padding: 20px;
            background-color: #fff; border-radius: 10px;
            border: 10px solid #0049AD; box-shadow: 0 4px 8px rgb(3, 4, 60);
        }
        label { display: block; margin-top: 10px; color: rgb(2, 16, 36); font-weight: bold; }
        .radio-group { margin-left: 15px; }
        button { margin-top: 20px; padding: 10px 20px; width: 100%; border: none; border-radius: 5px; cursor: pointer; }
        button[type="submit"] { background-color: #001E47; color: white; }
        button[type="submit"]:hover { background-color: #0049AD; }
    </style>
</head>
<body>

<?php if (isset($_GET['updated']) && $_GET['updated'] == 'true'): ?>
<script>
    alert("✅ Payment status updated successfully.");
</script>
<?php endif; ?>

    <form method="post">
        <input type="hidden" name="studentID" value="<?= htmlspecialchars($row['studentID']) ?>">
        <input type="hidden" name="acadYear" value="<?= htmlspecialchars($acadYear) ?>">
        <input type="hidden" name="semester" value="<?= htmlspecialchars($semester) ?>">

        <h2>Edit Payment Status</h2>

        <?php foreach (["Membership Fee" => "membershipFeeStatus", "AKWE 2023" => "akwe2023Status", "AKWE 2024" => "akwe2024Status"] as $label => $name): ?>
            <label><?= $label ?>:</label>
            <div class="radio-group">
                <label><input type="radio" name="<?= $name ?>" value="1" <?= $row[$name] == 1 ? "checked" : "" ?>> Paid</label>
                <label><input type="radio" name="<?= $name ?>" value="0" <?= $row[$name] != 1 ? "checked" : "" ?>> Unpaid</label>
            </div>
        <?php endforeach; ?>

        <label>Overall Status:</label>
        <div class="radio-group">
            <label><input type="radio" name="overallStatus" value="COMPLETE"
                <?= ($row['membershipFeeStatus'] && $row['akwe2023Status'] && $row['akwe2024Status']) ? "checked" : "" ?>> COMPLETE</label>
            <label><input type="radio" name="overallStatus" value="INCOMPLETE"
                <?= (!($row['membershipFeeStatus'] && $row['akwe2023Status'] && $row['akwe2024Status'])) ? "checked" : "" ?>> INCOMPLETE</label>
        </div>

        <button type="submit" onclick="return confirm('Are you sure you want to update this information?')">Update</button>
        <button type="button" onclick="window.location.href='paymentpage.php?acadYear=<?= urlencode($acadYear) ?>&semester=<?= urlencode($semester) ?>'">Cancel</button>
    </form>
</body>
</html>
<?php
    } else {
        echo "Student not found.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
