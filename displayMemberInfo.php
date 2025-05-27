<?php
    $missingRequirements = [];

    if (empty($row['upMail'])) $missingRequirements[] = "UP Mail";
    if (empty($row['contactNo'])) $missingRequirements[] = "Contact No";
    if (empty($row['presentAddress'])) $missingRequirements[] = "Present Address";
    if (empty($row['homeAddress'])) $missingRequirements[] = "Home Address";
    if (empty($row['signature'])) $missingRequirements[] = "Signature";
    if (empty($row['idPicture'])) $missingRequirements[] = "ID Picture";
    if (empty($row['form5'])) $missingRequirements[] = "Form 5";

    $recordComplete = empty($missingRequirements);

        // $paymentComplete = ($row['totalPayments'] > 0 && $row['paidPayments'] == $row['totalPayments']);
    $fullName = $row["lastName"] . ", " . $row["firstName"] . " " . $row["middleName"];

    echo "<tr>".
        "<td align='left'>".$fullName."</td>".
        "<td align='center'>".$row["studentID"]."</td>".
        "<td align='center'>".$row["role"]."</td>".
        "<td align='center'>".$row["status"]."</td>";

    echo "<td align='center' style='color:" . ($recordComplete ? "green" : "red") . ";'>";
        if (!$recordComplete) {
         echo "Incomplete";
        } else {
            echo "Complete";
        }
        echo "</td>";

    echo "<td align='center'>";
        if (!$recordComplete) {
            echo implode(", ", $missingRequirements);
        } else {
        echo "";
        }
    echo "</td>";

    include 'buttonEditDelete.php';
    echo "</tr>";
?>