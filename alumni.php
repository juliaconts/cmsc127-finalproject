<?php
include 'DBConnector.php';

$yearGraduated = $_GET['yearGraduated'] ?? '';

// alumni sorting
include 'sort_alum_config.php';
$sort = $_GET['sortAlumBy'] ?? 'none';
$sortBy = $allowed[$sort] ?? 'yearGraduated DESC, lastName ASC, firstName ASC'; // default to latest graduated

// Build base SQL
$sql = "SELECT DISTINCT m.studentID,
               m.firstName,
               m.middleName,
               m.lastName,
               a.alumniID,
               a.yearGraduated
        FROM member m
        INNER JOIN alumni a ON m.studentID = a.studentID";

// Add filter only if yearGraduated is set
if (!empty($yearGraduated)) {
    $sql .= " WHERE a.yearGraduated = ?";
}

// for sorting
$sql .= " ORDER BY $sortBy";

// Prepare statement
$stmt = $conn->prepare($sql);

// Bind param if year filter is set
if (!empty($yearGraduated)) {
    $stmt->bind_param("s", $yearGraduated);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $fullName = "{$row['lastName']}, {$row['firstName']} {$row['middleName']}";
        echo "<tr>" .
                "<td align='left'>" . $fullName . "</td>" .
                "<td align='center'>" . $row["alumniID"] . "</td>" .
                "<td align='center'>" . $row["studentID"] . "</td>" .
                "<td align='center'>" . $row["yearGraduated"] . "</td>";

        echo "<td align='center'>".
                    "<form action='deleteMember.php' method='post' onsubmit=\"return confirm('Are you sure you want to delete this person?');\">".
                        "<input type='text' style='display: none;' name='studentID' value='".$row["studentID"]."'>".
                        "<button type='submit'>Delete</button>".
                    "</form>".
                "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5' style='text-align:center;'>No alumni found for the selected graduation year.</td></tr>";
}

$stmt->close();
$conn->close();
?>