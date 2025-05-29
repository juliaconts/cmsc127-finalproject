<?php
include_once 'DBConnector.php';

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
        f.form5,
        r.role,
        COUNT(p.paymentID) AS totalPayments,
        SUM(CASE WHEN p.isPaid = 1 THEN 1 ELSE 0 END) AS paidPayments
        FROM assigned a
        INNER JOIN member m ON a.studentID = m.studentID
        INNER JOIN roles r ON a.roleID = r.roleID
        LEFT JOIN form5 f ON m.studentID = f.studentID 
            AND f.acadYear = a.acadYear 
            AND f.semester = a.semester
        LEFT JOIN pays p ON m.studentID = p.studentID 
            AND p.acadYear = a.acadYear 
            AND p.semester = a.semester
        WHERE a.yearLevel = 2 AND a.acadYear = '%s' AND a.semester = %d
        GROUP BY m.studentID, m.firstName, m.lastName, m.middleName, 
                 a.status, m.upMail, a.contactNo, a.presentAddress, 
                 m.homeAddress, m.signature, m.idPicture, f.form5, r.role
        ORDER BY m.lastName ASC",
        mysqli_real_escape_string($conn, $acadYear), $semester);
 
$result =$conn->query($sql);
include 'displayMembers.php';
?>