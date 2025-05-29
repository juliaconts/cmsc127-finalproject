<?php
include 'DBConnector.php';

$acadYear = $_GET['acadYear'] ?? '';
$semester = $_GET['semester'] ?? '';

$sql = "SELECT m.studentID, CONCAT(m.lastName, ', ', m.firstName, ' ', m.middleName) AS Name,
        CASE 
            WHEN EXISTS (
                SELECT 1 FROM pays p
                JOIN payment pm ON p.paymentID = pm.paymentID
                WHERE p.studentID = m.studentID 
                AND pm.payment = 'Membership Fee' 
                AND p.isPaid = 1
                AND p.acadYear = ? 
                AND p.semester = ?
            ) THEN 'Paid' ELSE 'Unpaid' 
        END AS MembershipFee,

        CASE 
            WHEN EXISTS (
                SELECT 1 FROM pays p
                JOIN payment pm ON p.paymentID = pm.paymentID
                WHERE p.studentID = m.studentID 
                AND pm.payment = 'AKWE 2024' 
                AND p.isPaid = 1
                AND p.acadYear = ? 
                AND p.semester = ?
            ) THEN 'Paid' ELSE 'Unpaid' 
        END AS AKWEFee2024,

        CASE 
            WHEN EXISTS (
                SELECT 1 FROM pays p
                JOIN payment pm ON p.paymentID = pm.paymentID
                WHERE p.studentID = m.studentID 
                AND pm.payment = 'AKWE 2023' 
                AND p.isPaid = 1
                AND p.acadYear = ? 
                AND p.semester = ?
            ) THEN 'Paid' ELSE 'Unpaid' 
        END AS AKWEFee2023,

        CASE 
            WHEN 
                EXISTS (
                    SELECT 1 FROM pays p
                    JOIN payment pm ON p.paymentID = pm.paymentID
                    WHERE p.studentID = m.studentID 
                    AND pm.payment = 'Membership Fee' 
                    AND p.isPaid = 1
                    AND p.acadYear = ? 
                    AND p.semester = ?
                ) AND
                EXISTS (
                    SELECT 1 FROM pays p
                    JOIN payment pm ON p.paymentID = pm.paymentID
                    WHERE p.studentID = m.studentID 
                    AND pm.payment = 'AKWE 2024' 
                    AND p.isPaid = 1
                    AND p.acadYear = ? 
                    AND p.semester = ?
                ) AND
                EXISTS (
                    SELECT 1 FROM pays p
                    JOIN payment pm ON p.paymentID = pm.paymentID
                    WHERE p.studentID = m.studentID 
                    AND pm.payment = 'AKWE 2023' 
                    AND p.isPaid = 1
                    AND p.acadYear = ? 
                    AND p.semester = ?
                ) THEN 'COMPLETE' ELSE 'INCOMPLETE'
        END AS Status

FROM member m
JOIN assigned a ON m.studentID = a.studentID
WHERE a.yearLevel = 2 AND a.acadYear = ? AND a.semester = ?
  AND EXISTS (
      SELECT 1 FROM pays p
      WHERE p.studentID = m.studentID AND p.acadYear = ? AND p.semester = ?
  )
ORDER BY Name;";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "sisisisisisissis",  // 15 total: alternating string and int
    $acadYear, $semester, // Membership
    $acadYear, $semester, // AKWE 2024
    $acadYear, $semester, // AKWE 2023
    $acadYear, $semester, // Membership in Status
    $acadYear, $semester, // AKWE 2024 in Status
    $acadYear, $semester, // AKWE 2023 in Status
    $acadYear, $semester, // Filter assigned year
    $acadYear, $semester  // EXISTS: has payment in year
);

$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>".
            "<td align='left'>".$row["Name"]."</td>".
            "<td align='center'>".$row["studentID"]."</td>".
            "<td align='center'>".($row["MembershipFee"] == 'Unpaid' ? "<span style='color:red;'>Unpaid</span>" : "<span style='color:green;'>Paid</span>")."</td>".
            "<td align='center'>".($row["AKWEFee2024"] == 'Unpaid' ? "<span style='color:red;'>Unpaid</span>" : "<span style='color:green;'>Paid</span>")."</td>".
            "<td align='center'>".($row["AKWEFee2023"] == 'Unpaid' ? "<span style='color:red;'>Unpaid</span>" : "<span style='color:green;'>Paid</span>")."</td>".
            "<td align='center'>".($row["Status"] == 'INCOMPLETE' ? "<span style='color:red;'>INCOMPLETE</span>" : "<span style='color:green;'>COMPLETE</span>")."</td>";

        echo "<td align='center'>".
                "<div style='display: flex; gap: 5px;  justify-content: center'>".
                    "<form action='editPayment.php' method='post'>".
                        "<input type='text' style='display: none;' name='studentID' value='".$row["studentID"]."'>".
                         "<input type='hidden' name='acadYear' value='" . htmlspecialchars($acadYear) . "'>".
                         "<input type='hidden' name='semester' value='" . htmlspecialchars($semester) . "'>".
                         "<button type='button' onclick='this.form.submit()'>Edit</button>".
                    "</form>".
                "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7' style='text-align:center;'>No data found for the selected Academic Year and Semester.</td></tr>";
}

$conn->close();
?>

<script>
document.getElementById("AY").addEventListener("change", function () {
    document.getElementById("filterAYSem").submit();
});

document.getElementById("semester").addEventListener("change", function () {
    document.getElementById("filterAYSem").submit();
});
</script>