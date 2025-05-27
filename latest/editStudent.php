<?php
include 'DBConnector.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentID = $_POST['studentID'];

    $sql = "SELECT m.studentID, m.firstName, m.middleName, m.lastName, 
                   a.status, m.upMail, a.yearLevel, m.university, m.degreeProgram, 
                   a.contactNo, a.presentAddress, m.homeAddress, m.birthday, 
                   m.signature, m.idPicture, a.form5, r.role AS role
            FROM member m
            LEFT JOIN student s ON m.studentID = s.studentID
            LEFT JOIN assigned a ON m.studentID = a.studentID
            LEFT JOIN roles r ON a.roleID = r.roleID
            WHERE m.studentID = '$studentID'
            LIMIT 1";

    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Edit Member</title>
            <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
            <style>
                body {
                    font-family: "Chakra Petch", sans-serif;
                    background-repeat: no-repeat;
                    background-size: cover;
                    background-color: #000C1E;
                }

                form {
                    width: 40%;
                    margin-top: 3%;
                    margin-bottom: 3%;
                    margin-left: auto;
                    margin-right: auto;
                    padding: 20px;
                    background-color: #fff;
                    border-radius: 10px;
                    border: 10px solid #0049AD;
                    box-shadow: 0 4px 8px rgb(3, 4, 60);
                }

                label {
                    display: block;
                    margin-top: 10px;
                    color: rgb(2, 16, 36);
                }

                input[type="text"],
                input[type="number"],
                input[type="date"] {
                    width: 100%;
                    padding: 8px 10px;
                    margin-top: 5px;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                    box-sizing: border-box;
                }

                select {
                    padding: 10px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    font-size: 16px;
                }

                .expand {
                    width: 100%;
                    box-sizing: border-box;

                }

                button[type="submit"] {
                    background-color: #001E47;
                    color: white;
                    padding: 10px 20px;
                    margin-top: 20px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    width: 100%;
                }

                button[type="submit"]:hover {
                    background-color: #0049AD;
                }
            </style>
        </head>
        <body>
            <form action="updateStudent.php" method="post">
                <h2>Edit Member</h2>
                <input type="hidden" name="studentID" value="<?php echo $row['studentID']; ?>">
                <label for="firstName">First Name:</label>
                <input type="text" name="firstName" value="<?php echo $row['firstName']; ?>" required><br>
                <label for="middleName">Middle Name:</label>
                <input type="text" name="middleName" value="<?php echo $row['middleName']; ?>"><br>
                <label for="lastName">Last Name:</label>
                <input type="text" name="lastName" value="<?php echo $row['lastName']; ?>" required><br>
                <label for="status">Status:</label>
                <select class="expand" name="status">
                    <option value="" disabled>-- Select Status --</option>
                    <option value="Regular" <?php if ($row['status'] == 'Regular') echo 'selected'; ?>>Regular</option>
                    <option value="Irregular" <?php if ($row['status'] == 'Irregular') echo 'selected'; ?>>Irregular</option>
                    <option value="Transferee" <?php if ($row['status'] == 'Transferee') echo 'selected'; ?>>Transferee</option>
                    <option value="Shiftee" <?php if ($row['status'] == 'Shiftee') echo 'selected'; ?>>Shiftee</option>
                    <option value="Alumni" <?php if ($row['status'] == 'Alumni') echo 'selected'; ?>>Alumni</option>
                </select><br>
                <label for="role">Role:</label>
                <select class="expand" name="role">
                    <option value="">-- Select Role --</option>
                    <?php
                    // Dynamically load roles from the database
                    $rolesResult = $conn->query("SELECT role FROM roles");
                    while ($roleRow = $rolesResult->fetch_assoc()) {
                        $selected = ($row['role'] == $roleRow['role']) ? 'selected' : '';
                        echo "<option value=\"" . htmlspecialchars($roleRow['role']) . "\" $selected>" . htmlspecialchars($roleRow['role']) . "</option>";
                    }
                    ?>
                </select><br>
                <label for="upMail">UP Mail:</label>
                <input type="text" name="upMail" value="<?php echo $row['upMail']; ?>"><br>
                <label for="yearLevel">Year Level:</label>
                <input type="number" name="yearLevel" value="<?php echo $row['yearLevel']; ?>"><br>
                <label for="university">University:</label>
                <input type="text" name="university" value="<?php echo $row['university']; ?>"><br>
                <label for="degreeProgram">Degree Program:</label>
                <input type="text" name="degreeProgram" value="<?php echo $row['degreeProgram']; ?>"><br>
                <label for="contactNo">Contact No:</label>
                <input type="text" name="contactNo" value="<?php echo $row['contactNo']; ?>"><br>
                <label for="presentAddress">Present Address:</label>
                <input type="text" class="expand" name="presentAddress" value="<?php echo $row['presentAddress']; ?>"><br>
                <label for="homeAddress">Home Address:</label>
                <input type="text" class="expand" name="homeAddress" value="<?php echo $row['homeAddress']; ?>"><br>
                <label for="birthday">Birthday:</label>
                <input type="date" name="birthday" value="<?php echo $row['birthday']; ?>"><br>
                <label for="signature">Signature:</label>
                <input type="text" name="signature" value="<?php echo $row['signature']; ?>">
                <label for="idPicture">ID Picture:</label>
                <input type="text" name="idPicture" value="<?php echo $row['idPicture']; ?>">
                <label for="form5">Form 5: </label>
                <input type="text" name="form5" value="<?php echo $row['form5']; ?>">
                <button type="submit">Update Member</button>
            </form>
        </body>
        </html>
        <?php
    } else {
        echo "Member not found.";
    }
} else {
    echo "Invalid request. Please use the form to edit a member.";
}

$conn->close();
?>
