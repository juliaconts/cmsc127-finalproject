<?php
include_once 'DBConnector.php';

$sql = sprintf(
    "SELECT m.studentID,
            m.firstName,
            m.lastName,
            m.middleName,
            a.alumniID
    FROM assigned
    INNER JOIN member m ON assigned.studentID = m.studentID
    INNER JOIN alumni a ON m.studentID = a.studentID
    WHERE assigned.status = 'Alumni' AND assigned.acadYear = '%s' AND assigned.semester = '%s'
    GROUP BY m.studentID, m.firstName, m.lastName, m.middleName, a.alumniID",
        mysqli_real_escape_string($conn, $acadYear),
        mysqli_real_escape_string($conn, $semester));
 
 $result =$conn->query($sql);

if ($result->num_rows > 0) {

    while($row = $result->fetch_assoc()) {
        $fullName = $row["lastName"] . ", " . $row["firstName"] . " " . $row["middleName"];
        echo "<tr>".
                "<td align='left'>".$fullName."</td>".
                "<td align='center'>".$row["alumniID"]."</td>".
                "<td align='center'>".$row["studentID"]."</td>";

        echo "<td align='center'>".
                "<div style='display: flex; gap: 5px;  justify-content: center'>".
                    "<form action='editPerson.php' method='post'>".
                        "<input type='text' style='display: none;' name='alumniID' value='".$row["alumniID"]."'>".
                        "<button type='button' onclick='this.form.submit()'>Edit</button>".
                    "</form>".
                    "<form action='deletePerson.php' method='post' onsubmit=\"return confirm('Are you sure you want to delete this person?');\">".
                        "<input type='text' style='display: none;' name='alumniID' value='".$row["alumniID"]."'>".
                        "<button type='submit'>Delete</button>".
                    "</form>".
                "</td>";
        echo "</tr>";
    }
} 
else {
    echo "0 results";
}

// $conn->close();
?>