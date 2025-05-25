<?php
include_once 'DBConnector.php';

// Get academic year and semester from homepage.php (default to latest if not set)
$acadYear = $_GET['acadYear'] ?? $_POST['acadYear'] ?? '';
$semester = $_GET['semester'] ?? $_POST['semester'] ?? '';

if (empty($acadYear) || empty($semester)) {
    // Get latest acadYear and semester from DB as fallback
    $sql_latest = "SELECT acadYear, semester FROM academicyear ORDER BY acadYear DESC, semester DESC LIMIT 1";
    $result_latest = $conn->query($sql_latest);
    if ($result_latest && $result_latest->num_rows > 0) {
        $latest = $result_latest->fetch_assoc();
        $acadYear = $latest['acadYear'];
        $semester = $latest['semester'];
    }
}

// for sorting
include 'sort_config.php';
$sort = $_GET['sort2By'] ?? 'none';
if ($sort === 'pay-reqs' or $sort ==='soa-reqs')    $sortBy = ''; // no SQL ordering, do PHP sorting only
else                                                $sortBy = $allowed[$sort];
// 

// Query for all 2nd year students for the selected acadYear and semester
$sql = "
    SELECT 
        m.studentID,
        m.firstName,
        m.middleName,
        m.lastName,
        a.status,
        m.upMail,
        a.contactNo,
        a.presentAddress,
        m.homeAddress,
        m.signature,
        m.idPicture,
        a.form5,
        r.role,
        COUNT(p.paymentID) AS totalPayments,
        COALESCE(SUM(CASE WHEN p.isPaid = 1 THEN 1 ELSE 0 END), 0) AS paidPayments
    FROM assigned a
    INNER JOIN member m ON a.studentID = m.studentID
    INNER JOIN roles r ON a.roleID = r.roleID
    LEFT JOIN pays p ON m.studentID = p.studentID 
        AND p.acadYear = a.acadYear 
        AND p.semester = a.semester
    WHERE a.yearLevel = 2
      AND a.acadYear = '$acadYear'
      AND a.semester = '$semester'
        GROUP BY m.studentID, m.lastName, m.firstName, m.middleName, r.role, a.status
";

// for sorting non pay or soa reqs
if ($sortBy !== '') {
    $sql .= "  ORDER BY $sortBy";
}

 $result =$conn->query($sql);

if ($result && $result->num_rows > 0) {
    
    while ($row = $result->fetch_assoc()) {

        $recordComplete = (
            !empty($row['upMail']) &&
            !empty($row['contactNo']) &&
            !empty($row['presentAddress']) &&
            !empty($row['homeAddress']) &&
            !empty($row['signature']) &&
            !empty($row['idPicture']) &&
            !empty($row['form5'])
        );

        $paymentComplete = ($row['totalPayments'] > 0 && $row['paidPayments'] == $row['totalPayments']);
        $fullName = $row["lastName"] . ", " . $row["firstName"] . " " . $row["middleName"];

        echo "<tr>".
                "<td align='left'>".$fullName."</td>".
                "<td align='center'>".$row["studentID"]."</td>".
                "<td align='center'>".$row["role"]."</td>".
                "<td align='center'>".$row["status"]."</td>";

        echo "<tr>";
        echo "<td align='left'>" . htmlspecialchars($fullName) . "</td>";
        echo "<td align='center'>" . htmlspecialchars($row["studentID"]) . "</td>";
        echo "<td align='center'>" . htmlspecialchars($row["role"]) . "</td>";
        echo "<td align='center'>" . htmlspecialchars($row["status"]) . "</td>";
        echo "<td align='center' style='color:" . ($recordComplete ? "white" : "red") . ";'>" .
            ($recordComplete ? "Complete" : "Incomplete") . "</td>";
        echo "<td align='center' style='color:" . ($paymentComplete ? "white" : "red") . ";'>" .
            ($paymentComplete ? "Complete" : "Incomplete") . "</td>";
        
            echo "<td align='center'>
                <div style='display: flex; gap: 5px; justify-content: center'>
                    <form action='editStudent.php' method='post' style='display:inline;'>
                        <input type='hidden' name='studentID' value='" . htmlspecialchars($row["studentID"]) . "'>
                        <button type='submit'>Edit</button>
                    </form>
                    <form action='deleteMember.php' method='post' style='display:inline;' onsubmit=\"return confirm('Are you sure you want to delete this person?');\">
                        <input type='hidden' name='studentID' value='" . htmlspecialchars($row["studentID"]) . "'>
                        <button type='submit'>Delete</button>
                    </form>
                </div>
              </td>";
        echo "</tr>";
    }
} else {
    echo "0 results";
}

// $conn->close();
?>