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
        f.form5,
        r.role,
        a.roleID,
        a.acadYear,
        a.semester,
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
        WHERE a.yearLevel > 4 AND a.acadYear = '%s' AND a.semester = %d
        GROUP BY m.studentID, m.lastName, m.firstName, m.middleName, 
                 a.status, m.upMail, a.contactNo, a.presentAddress, 
                 m.homeAddress, m.signature, m.idPicture, f.form5, r.role, a.roleID, a.acadYear, a.semester",
mysqli_real_escape_string($conn, $acadYear), $semester);
 
// for sorting non pay or soa reqs
if ($sortBy !== '') {
    $sql .= "  ORDER BY $sortBy";
}

 $result =$conn->query($sql);

include 'displayMembers.php';
?>
