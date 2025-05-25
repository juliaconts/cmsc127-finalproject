<?php
include_once 'DBConnector.php';

// for sorting
include 'sort_config.php';
$sort = $_GET['sortNthBy'] ?? 'none';
if ($sort === 'pay-reqs' or $sort ==='soa-reqs')    $sortBy = ''; // no SQL ordering, do PHP sorting only
else                                                $sortBy = $allowed[$sort];
// 

$sql = sprintf(
    "SELECT m.studentID,
        m.firstName,
        m.lastName,
        m.middleName,
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
        SUM(CASE WHEN p.isPaid = 1 THEN 1 ELSE 0 END) AS paidPayments
        FROM assigned a
        INNER JOIN member m ON a.studentID = m.studentID
        INNER JOIN roles r ON a.roleID = r.roleID
        LEFT JOIN pays p ON m.studentID = p.studentID 
            AND p.acadYear = a.acadYear 
            AND p.semester = a.semester
        WHERE a.yearLevel > 4 AND a.acadYear = '%s' AND a.semester = '%s'
        GROUP BY m.studentID, m.lastName, m.firstName, m.middleName, r.role, a.status",
mysqli_real_escape_string($conn, $acadYear),
        mysqli_real_escape_string($conn, $semester));
 
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

        echo "<td align='center' style='color:" . ($recordComplete ? "white" : "red") . "'>" .
            ($recordComplete ? "Complete" : "Incomplete") . "</td>";

        echo "<td align='center' style='color:" . ($paymentComplete ? "white" : "red") . "'>" .
            ($paymentComplete ? "Complete" : "Incomplete") . "</td>";


        echo "<td align='center'>".
                "<div style='display: flex; gap: 5px;  justify-content: center'>".
                    "<form action='editStudent.php' method='post'>".
                        "<input type='text' style='display: none;' name='studentID' value='".$row["studentID"]."'>".
                        "<button type='button' onclick='this.form.submit()'>Edit</button>".
                    "</form>".
                    "<form action='deleteMember.php' method='post' onsubmit=\"return confirm('Are you sure you want to delete this person?');\">".
                        "<input type='text' style='display: none;' name='studentID' value='".$row["studentID"]."'>".
                        "<button type='submit'>Delete</button>".
                    "</form>".
                "</td>";
        echo "</tr>";
    }
} else {
    echo "0 results";
    echo "<tr>".
            "<td align='center'>--</td>".
            "<td align='center'>--</td>".
            "<td align='center'>--</td>".
            "<td align='center'>--</td>".
            "<td align='center'>--</td>".
            "<td align='center'>--</td>".
        "</tr>";
}

// $conn->close();
?>
