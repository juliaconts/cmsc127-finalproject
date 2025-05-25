<?php
include 'DBConnector.php';

// for sorting
include 'sort_adv_config.php';
$sort = $_GET['sortAdvBy'] ?? 'none';
$sortAdvBy = $allowed[$sort] ?? 'acadYear DESC, semester ASC, type ASC';
//

$sql = "SELECT DISTINCT advisor.advisorID, firstName, middleInitial, lastName, advises.type, advises.acadYear, advises.semester
        FROM advisor
        JOIN advises ON advisor.advisorID = advises.advisorID
        GROUP BY advisor.advisorID, advises.acadYear, advises.semester, advises.type
        ORDER BY $sortAdvBy";

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
                    "<form action='editAdvisor.php' method='post'>".
                        "<input type='text' style='display: none;' name='advisorID' value='".$row["advisorID"]."'>".
                        "<button type='button' onclick='this.form.submit()'>Edit</button>".
                    "</form>".
                    //make sure to send the proper details for deleting (sem, acadyear, type, id)
                    "<form action='deleteAdvisor.php' method='post' onsubmit=\"return confirm('Are you sure you want to delete this person?');\">".
                        "<input type='hidden' name='advisorID' value='".$row["advisorID"]."'>".
                        "<input type='hidden' name='acadYear' value='".$row["acadYear"]."'>".
                        "<input type='hidden' name='semester' value='".$row["semester"]."'>".
                        "<input type='hidden' name='type' value='".$row["type"]."'>".
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