<?php
include 'DBConnector.php';

$sql = "SELECT m.studentID,
        m.firstName,
        m.lastName,
        m.middleName,
        m.status,
        m.upMail,
        m.contactNo,
        m.presentAddress,
        m.homeAddress,
        m.signature,
        m.idPicture,
        m.form5,
        r.role,
        COUNT(p.paymentID) AS totalPayments,
        SUM(CASE WHEN p.isPaid = 1 THEN 1 ELSE 0 END) AS paidPayments
        FROM member m
        LEFT JOIN pays p ON m.studentID = p.studentID
        LEFT JOIN assigned a ON m.studentID = a.studentID
        LEFT JOIN roles r ON a.roleID = r.roleID
        WHERE m.yearLevel = 1
        GROUP BY m.studentID";

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

        echo "<td align='center'" . ($recordComplete ? "green" : "red") . "'>" .
            ($recordComplete ? "Complete" : "Incomplete") . "</td>";

        echo "<td align='center'" . ($paymentComplete ? "green" : "red") . "'>" .
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

$conn->close();
?>