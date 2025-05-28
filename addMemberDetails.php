<?php include 'DBConnector.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Member</title>
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
        .radio-group {
            display: flex;
            gap: 10px;
            margin-top: 5px;
        }
    </style>
    <script>
        //displaying the almnui ID field based on which button they click
        function toggleAlumniIDField() {
            const memberType = document.querySelector('input[name="memberType"]:checked').value;
            const alumniIDField = document.getElementById('alumniIDGroup');
            alumniIDField.style.display = memberType === 'Alumni' ? 'block' : 'none';
        }
        //for validating inputs
        function validateForm() {
            const studentID = document.querySelector('input[name="studentID"]').value.trim();
            if (studentID === "" || studentID.length !== 9) {
                alert("Student ID is required and must be exactly 9 digits.");
                return false;
            }
            const memberType = document.querySelector('input[name="memberType"]:checked').value;
            if (memberType === "Alumni") {
                const alumniID = document.querySelector('input[name="alumniID"]').value.trim();
                if (alumniID === "") {
                    alert("Alumni ID is required for Alumni members.");
                    return false;
                }
            }
            const firstName = document.querySelector('input[name="firstName"]').value;
            if (firstName == "") {
                alert("First Name is required.");
                return false;
            }
            const lastName = document.querySelector('input[name="lastName"]').value;
            if (lastName == "") {
                alert("Last Name is required.");
                return false;
            }
            const upMail = document.querySelector('input[name="upMail"]').value;
            if (upMail === "" || !upMail.endsWith("@up.edu.ph")) {
                alert("UP Mail is required and must end with @up.edu.ph");
                return false;
            }
            const university = document.querySelector('input[name="university"]').value;
            if (university === "") {
                alert("University is required");
                return false;
            }
            const degreeProgram = document.querySelector('input[name="degreeProgram"]').value;
            if (degreeProgram === "") {
                alert("Degree Program is required");
                return false;
            }
            const homeAddress = document.querySelector('input[name="homeAddress"]').value;
            if (homeAddress === "") {
                alert("Home Address is required");
                return false;
            }
            const birthday = document.querySelector('input[name="birthday"]').value;
            if (birthday === "") {
                alert("Birthday is required.");
                return false;
            }
            const roleID = document.querySelector('select[name="roleID"]').value;
            if (roleID === "") {
                alert("Please select a role.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <form action="addMember.php" method="post" onsubmit="return validateForm();">
        <h2>Add Member</h2>
        <label>Member Type:</label>
        <div class="radio-group">
            <input type="radio" id="student" name="memberType" value="Student" checked onclick="toggleAlumniIDField()">
            <label for="student">Student</label>
            <input type="radio" id="alumni" name="memberType" value="Alumni" onclick="toggleAlumniIDField()">
            <label for="alumni">Alumni</label>
        </div>

        <label for="studentID">Student ID:</label>
        <input type="text" name="studentID" required maxlength="9"><br>

        <div id="alumniIDGroup" style="display:none;">
            <label for="alumniID">Alumni ID:</label>
            <input type="text" name="alumniID" id="alumniID">
        </div>

        <label for="firstName">First Name:</label>
        <input type="text" name="firstName" required><br>

        <label for="middleName">Middle Name:</label>
        <input type="text" name="middleName"><br>

        <label for="lastName">Last Name:</label>
        <input type="text" name="lastName" required><br>

        <label for="upMail">UP Mail:</label>
        <input type="text" name="upMail" required><br>

        <label for="university">University:</label>
        <input type="text" name="university" required><br>

        <label for="degreeProgram">Degree Program:</label>
        <input type="text" name="degreeProgram" required><br>

        <label for="homeAddress">Home Address:</label>
        <input type="text" class="expand" name="homeAddress" required><br>

        <label for="birthday">Birthday:</label>
        <input type="date" name="birthday" required><br>

        <!-- Assigned Table Fields -->
        <label for="status">Status:</label>
        <select class="expand" name="status">
            <option value="">-- Select Status --</option>
            <option value="Regular">Regular</option>
            <option value="Irregular">Irregular</option>
            <option value="Transferee">Transferee</option>
            <option value="Shiftee">Shiftee</option>
            <option value="Alumni">Alumni</option>
        </select><br>

        <label for="roleID">Role:</label>
        <select name="roleID" id="roleID" class="expand" required>
            <option value="">-- Select Role --</option>
            <?php
            $roles = $conn->query("SELECT roleID, role FROM roles ORDER BY roleID ASC");
            while ($role = $roles->fetch_assoc()) {
                echo "<option value='" . htmlspecialchars($role['roleID']) . "'>" . htmlspecialchars($role['role']) . "</option>";
            }
            ?>
        </select><br>

        <label for="yearLevel">Year Level:</label>
        <input type="number" name="yearLevel" min="1" max="5"><br>

        <label for="contactNo">Contact No:</label>
        <input type="text" name="contactNo"><br>

        <label for="presentAddress">Present Address:</label>
        <input type="text" class="expand" name="presentAddress"><br>

        <label for="signature">Signature (URL):</label>
        <input type="text" name="signature"><br>

        <label for="idPicture">ID Picture (URL):</label>
        <input type="text" name="idPicture"><br>

        <label for="form5">Form 5 (URL):</label>
        <input type="text" name="form5"><br>

        <label for="acadYear">Academic Year:</label>
        <select name="acadYear" required>
            <?php
            $res = $conn->query("SELECT DISTINCT acadYear FROM academicyear ORDER BY acadYear DESC");
            while ($row = $res->fetch_assoc()) {
                echo "<option value='" . htmlspecialchars($row['acadYear']) . "'>" . htmlspecialchars($row['acadYear']) . "</option>";
            }
            ?>
        </select><br>

        <label for="semester">Semester:</label>
        <select name="semester" required>
            <option value="1">1</option>
            <option value="2">2</option>
        </select><br>


        <button type="submit">Add Member</button>
    </form>
    <script>
        toggleAlumniIDField();
    </script>
</body>
</html>