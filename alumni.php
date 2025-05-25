<?php
include 'DBConnector.php';

$sql = "SELECT member.firstName, member.middleName, member.lastName, member.studentID, alumni.alumniID
        FROM member
        JOIN alumni ON member.studentID = alumni.studentID";

$result = $conn->query($sql);

if ($result->num_rows > 0) {

    while($row = $result->fetch_assoc()) {
        $fullName = $row["lastName"] . ", " . $row["firstName"] . " " . $row["middleName"];
        echo "<tr>".
                "<td align='left'>".$fullName."</td>".
                "<td align='center'>".$row["alumniID"]."</td>".
                "<td align='center'>".$row["studentID"]."</td>";

        echo "<td align='center'>".
                "<div style='display: flex; gap: 5px;  justify-content: center'>".
                    "<form action='editAlumni.php' method='post'>".
                        "<input type='text' style='display: none;' name='alumniID' value='".$row["alumniID"]."'>".
                        "<button type='button' onclick='this.form.submit()'>Edit</button>".
                    "</form>".
                    "<form action='deleteAlumni.php' method='post' onsubmit=\"return confirm('Are you sure you want to delete this person?');\">".
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

$conn->close();
?>