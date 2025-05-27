<?php
include 'DBConnector.php';

$sql = sprintf("SELECT DISTINCT advisor.advisorID, firstName, middleInitial, lastName, advises.type, advises.acadYear, advises.semester
        FROM advisor
        JOIN advises ON advisor.advisorID = advises.advisorID
        WHERE advises.acadYear = '%s' AND advises.semester = %d
        GROUP BY advisor.advisorID, advises.type",
        mysqli_real_escape_string($conn, $acadYear),
        $semester);

$result = $conn->query($sql);

if ($result->num_rows > 0) {

    while($row = $result->fetch_assoc()) {
        $fullName = $row["lastName"] . ", " . $row["firstName"] . " " . $row["middleInitial"] . ". ";
        echo "<tr>".
                "<td align='left'>".$fullName."</td>".
                "<td align='center'>".$row["type"]."</td>".
                "<td align='center'>".$row["acadYear"]."</td>".
                "<td align='center'>".$row["semester"]."</td>".
                "<td align='center'>".$row['advisorID']."</td>";

        echo "<td align='center'>".
                "<div style='display: flex; gap: 5px;  justify-content: center'>".
                    "<form action='editPerson.php' method='post'>".
                        "<input type='text' style='display: none;' name='advisorID' value='".$row["advisorID"]."'>".
                        "<button type='button' onclick='this.form.submit()'>Edit</button>".
                    "</form>".
                    "<form action='deletePerson.php' method='post' onsubmit=\"return confirm('Are you sure you want to delete this person?');\">".
                        "<input type='text' style='display: none;' name='advisorID' value='".$row["advisorID"]."'>".
                        "<button type='submit'>Delete</button>".
                    "</form>".
                "</td>";
        echo "</tr>";
    }
} 
else {
    echo "0 results";
}
?>