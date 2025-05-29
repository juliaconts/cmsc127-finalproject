<?php
if ($result && $result->num_rows > 0) {

    // check if soa-reqs sorting is needed
    $sort = $_GET['sort1By'] ?? $_GET['sort2By'] ?? $_GET['sort3By'] ?? $_GET['sort4By'] ?? $_GET['sortNthBy'] ?? 'none';
    if ($sort === 'soa-reqs') {
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            // recordComplete from displayMemberInfo
            $missingRequirements = [];
            if (empty($row['upMail'])) $missingRequirements[] = "UP Mail";
            if (empty($row['contactNo'])) $missingRequirements[] = "Contact No";
            if (empty($row['presentAddress'])) $missingRequirements[] = "Present Address";
            if (empty($row['homeAddress'])) $missingRequirements[] = "Home Address";
            if (empty($row['signature'])) $missingRequirements[] = "Signature";
            if (empty($row['idPicture'])) $missingRequirements[] = "ID Picture";
            if (empty($row['form5'])) $missingRequirements[] = "Form 5";
            $row['recordComplete'] = empty($missingRequirements);
            $row['missingCount'] = count($missingRequirements);
            $rows[] = $row;
        }
        // sort incomplete w/ most missing first, then incomplete desc, then complete
        usort($rows, function($a, $b) {
            if ($a['missingCount'] !== $b['missingCount']) {
                return $b['missingCount'] <=> $a['missingCount'];
            }
            return $a['recordComplete'] <=> $b['recordComplete'];
        });
        foreach ($rows as $row) {
            include 'displayMemberInfo.php';
        }
    } 
    //default
    else {
        while ($row = $result->fetch_assoc()) {
            include 'displayMemberInfo.php';
        }
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
