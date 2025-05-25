<?php
include 'DBConnector.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentID = $conn->real_escape_string($_POST['studentID']);
    $firstName = $conn->real_escape_string($_POST['firstName']);
    $middleName = $conn->real_escape_string($_POST['middleName']);
    $lastName = $conn->real_escape_string($_POST['lastName']);
    $status = $conn->real_escape_string($_POST['status']);
    $upMail = $conn->real_escape_string($_POST['upMail']);
    $yearLevel = $conn->real_escape_string($_POST['yearLevel']);
    $university = $conn->real_escape_string($_POST['university']);
    $degreeProgram = $conn->real_escape_string($_POST['degreeProgram']);
    $contactNo = $conn->real_escape_string($_POST['contactNo']);
    $presentAddress = $conn->real_escape_string($_POST['presentAddress']);
    $homeAddress = $conn->real_escape_string($_POST['homeAddress']);
    $birthday = $conn->real_escape_string($_POST['birthday']);
    $signature = $conn->real_escape_string($_POST['signature']);
    $idPicture = $conn->real_escape_string($_POST['idPicture']);
    $form5 = $conn->real_escape_string($_POST['form5']);
    $role = $conn->real_escape_string($_POST['role']); // Get the role from the form

    // Update the member table
    $sql = "UPDATE member SET
            firstName = '$firstName',
            middleName = '$middleName',
            lastName = '$lastName',
            status = '$status',
            upMail = '$upMail',
            yearLevel = '$yearLevel',
            university = '$university',
            degreeProgram = '$degreeProgram',
            contactNo = '$contactNo',
            presentAddress = '$presentAddress',
            homeAddress = '$homeAddress',
            birthday = '$birthday',
            signature = '$signature',
            idPicture = '$idPicture',
            form5 = '$form5'
            WHERE studentID = '$studentID'";

    echo "Member Update SQL: " . $sql . "<br>";

    if ($conn->query($sql) === TRUE) {
        echo "Member record updated successfully.<br>";

        // Update the assigned table.  We need to check if a role is already assigned.
        $checkSql = "SELECT COUNT(*) FROM assigned WHERE studentID = '$studentID'";
        $checkResult = $conn->query($checkSql);
        $count = $checkResult->fetch_row()[0];

        // First, get the roleID from the roles table based on the role name.
        $getRoleIdSql = "SELECT roleID FROM roles WHERE role = '$role'";
        $roleIdResult = $conn->query($getRoleIdSql);

        if ($roleIdResult && $roleIdResult->num_rows > 0) {
            $roleIdRow = $roleIdResult->fetch_assoc();
            $roleId = $roleIdRow['roleID'];

            if ($count > 0) {
                //  Update existing role assignment
                $updateRoleSql = "UPDATE assigned SET roleID = '$roleId' WHERE studentID = '$studentID'";
                echo "Assigned Update SQL: " . $updateRoleSql . "<br>";
                if ($conn->query($updateRoleSql) === TRUE) {
                    echo "Role updated successfully.<br>";
                } else {
                    echo "Error updating role: " . $conn->error . "<br>";
                }
            } else {
                // Insert new role assignment
                $insertRoleSql = "INSERT INTO assigned (studentID, roleID, semester, acadYear) VALUES ('$studentID', '$roleId', 1, '2024-2025')"; //You may need to adjust semester and acadYear
                echo "Assigned Insert SQL: " . $insertRoleSql . "<br>";
                if ($conn->query($insertRoleSql) === TRUE) {
                    echo "Role assigned successfully.<br>";
                } else {
                    echo "Error assigning role: " . $conn->error . "<br>";
                }
            }
        } else {
            echo "Error: Role '$role' not found in the roles table.<br>";
        }

        header("Location: homepage.php");
        exit();
    } else {
        echo "Error updating member record: " . $conn->error;
    }

    $conn->close();
}
?>
