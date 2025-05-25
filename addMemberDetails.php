
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
    <form action="addMember.php" method="post" onsubmit="return validateForm();">
    <?php ?>
    <script>
        function toggleAlumniIDField() {
            const memberType = document.querySelector('input[name="memberType"]:checked').value;
            const alumniIDField = document.getElementById('alumniIDGroup');
            alumniIDField.style.display = memberType === 'Alumni' ? 'block' : 'none';
        }

        function validateForm() {
            // check if Student ID is filled and at least 8 digits
            const studentID = document.querySelector('input[name="studentID"]').value.trim();
            if (studentID === "") {
                alert("Student ID is required.");
                return false;
            }
            
            if (studentID.length < 9) {
                alert("Student ID must exactly be 9 characters.");
                return false;
            }

            // if alumni is selected, alumniID must also be filled
            const memberType = document.querySelector('input[name="memberType"]:checked').value;
            if (memberType === "Alumni") {
                const alumniID = document.querySelector('input[name="alumniID"]').value.trim();
                if (alumniID === "") {
                    alert("Alumni ID is required for Alumni members.");
                    return false;
                }
            }

            // check if status is selected
            const status = document.querySelector('select[name="status"]').value;
            if (status === "None") {
                alert("Please select a status.");
                return false;
            }
            
            const firstName = document.querySelector('input[name="firstName"]').value;
            if (firstName == "") {
                alert("Please fill out this field");
                return false;
            }

            const middleName = document.querySelector('input[name="middleName"]').value;
            if (middleName == "") {
                alert("Please fill out this field");
                return false;
            }

            const lastName = document.querySelector('input[name="lastName"]').value;
            if (lastName == "") {
                alert("Please fill out this field");
                return false;
            }

            const upMail = document.querySelector('input[name="upMail"]').value;
            if (upMail === "") {
                alert("UP Mail is required.");
                return false;
            }

            if (!upMail.endsWith("@up.edu.ph")) {
                alert("UP Mail must end with @up.edu.ph");
                return false;
            }

            const yearLevel = document.querySelector('input[name="yearLevel"]').value;
            if (yearLevel =""){
                alert("Please fill out this field");
            }

            const university = document.querySelector('input[name="university"]').value;
            if (university =""){
                alert("University is required");
            }

            const degreeProgram = document.querySelector('input[name="degreeProgram"]').value;
            if (degreeProgram =""){
                alert("Degree Program is required");
            }

            const contactNo = document.querySelector('input[name="contactNo"]').value;
            if (contactNo === "") {
                alert("Contact No is required.");
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
    <form action="addMember.php" method="post">
        <h2>Add Member</h2>
        <label>Member Type:</label>
        <div class="radio-group">
            <input type="radio" id="student" name="memberType" value="Student" checked onclick="toggleAlumniIDField()">
            <label for="student">Student</label>
            <input type="radio" id="alumni" name="memberType" value="Alumni" onclick="toggleAlumniIDField()">
            <label for="alumni">Alumni</label>
        </div>

        <label for="studentID">Student ID:</label>
        <input type="text" name="studentID" required><br>

        <label for="alumniID" id="alumniIDGroup" style="display:none;">Alumni ID:
            <input type="text" name="alumniID" id="alumniID">
        </label><br>

        <label for="firstName">First Name:</label>
        <input type="text" name="firstName" required><br>

        <label for="middleName">Middle Name:</label>
        <input type="text" name="middleName"><br>

        <label for="lastName">Last Name:</label>
        <input type="text" name="lastName" required><br>

        <label for="status">Status:</label>
        <select class="expand" name="status">
                <option value="None" >-- Select Status --</option>
                <option value="Regular">Regular</option>
                <option value="Irregular">Irregular</option>
                <option value="Transferee">Transferee</option>
                <option value="Shiftee">Shiftee</option>
                <option value="Alumni">Alumni</option>
        </select><br>

        <label for="roleID">Role:</label>
        <select name="roleID" id="roleID" class="expand">
            <option value="">-- Select Role --</option>
            <option value="1">Member</option>
            <option value="2">President</option>
            <option value="3">Vice President for Internal Affairs</option>
            <option value="4">Vice President for External Affairs</option>
            <option value="5">Secretary</option>
            <option value="6">Treasurer</option>
            <option value="7">Auditor</option>
            <option value="8">Business Manager</option>
            <option value="9">PIO</option>
            <option value="10">Batch Representative</option>
            <option value="11">Documentation Committee Member</option>
            <option value="12">Finance Committee Member</option>
            <option value="13">Logistics Committee Member</option>
            <option value="14">Publications Committee Member</option>
            <option value="15">Website Committee Member</option>
            <option value="16">Public Relations Committee Member</option>
            <option value="17">Education and Research Committee Member</option>
        </select><br>

        <label for="upMail">UP Mail:</label>
        <input type="text" name="upMail"><br>

        <label for="yearLevel">Year Level:</label>
        <input type="number" name="yearLevel"><br>

        <label for="university">University:</label>
        <input type="text" name="university"><br>

        <label for="degreeProgram">Degree Program:</label>
        <input type="text" name="degreeProgram"><br>

        <label for="contactNo">Contact No:</label>
        <input type="text" name="contactNo"><br>

        <label for="presentAddress">Present Address:</label>
        <input type="text" class="expand" name="presentAddress"><br>

        <label for="homeAddress">Home Address:</label>
        <input type="text" class="expand" name="homeAddress"><br>

        <label for="birthday">Birthday:</label>
        <input type="date" name="birthday"><br>

        <label for="signature">Signature:</label>
        <input type="text" name="signature"><br>

        <label for="idPicture">ID Picture:</label>
        <input type="text" name="idPicture">

        <label for="form5">Form 5: </label>
        <input type="text" name="form5">

        <button type="submit">Add Member</button>
    </form>
    <script>
        toggleAlumniIDField();
    </script>
</body>
</html>