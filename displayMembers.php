<?php
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        include 'displayMemberInfo.php';
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
?>
