<?php
include 'DBConnector.php';
$acadYearOptions = '';
$sql = "SELECT DISTINCT acadYear FROM academicyear ORDER BY acadYear DESC";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $acadYearOptions .= '<option value="' . htmlspecialchars($row['acadYear']) . '">' . htmlspecialchars($row['acadYear']) . '</option>';
}
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Advisor</title>
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
    <script>
    // Show input for new academic year if "Other" is selected
    document.addEventListener('DOMContentLoaded', function() {
        const acadYearSelect = document.getElementById('acadYearSelect');
        const newAcadYearInput = document.getElementById('newAcadYearInput');
        if (acadYearSelect) {
            acadYearSelect.addEventListener('change', function() {
                if (this.value === 'other') {
                    newAcadYearInput.style.display = 'block';
                    newAcadYearInput.required = true;
                } else {
                    newAcadYearInput.style.display = 'none';
                    newAcadYearInput.required = false;
                }
            });
        }
    });
    </script>
</head>
<body>
    <form action="addAdvisor.php" method="post">
        <h2>Add Advisor</h2>
        <table>
            <tr>
                <td class="tlabel">Advisor ID</td>
                <td><input type="number" name="advisorID" required></td>
            </tr>
            <tr>
                <td class="tlabel">First Name</td>
                <td><input type="text" name="firstName" required></td>
            </tr>
            <tr>
                <td class="tlabel">Middle Initial</td>
                <td><input type="text" name="middleInitial"></td>
            </tr>
            <tr>
                <td class="tlabel">Last Name</td>
                <td><input type="text" name="lastName" required></td>
            </tr>
            <tr>
                <td class="tlabel">Type</td>
                <td>
                    <input type="radio" name="type" value="1" required>Advisor<br>
                    <input type="radio" name="type" value="2">Co-Advisor<br>
                </td>
            </tr>
            <tr>
                <td class="tlabel">Academic Year</td>
                <td>
                    <select name="acadYear" id="acadYearSelect" required>
                        <option value="">--Select Academic Year--</option>
                        <?php echo $acadYearOptions; ?>
                        <option value="other">Other (Add new year)</option>
                    </select>
                    <input type="text" name="newAcadYear" id="newAcadYearInput" placeholder="e.g. 2025-2026" style="display:none; margin-top:5px;">
                </td>
            </tr>
        </table>
        <button type="submit">Add Advisor</button>
    </form>
</body>
</html>