<?php
include 'DBConnector.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $advisorID = $_POST['advisorID'];

    $sql = "SELECT a.advisorID, a.firstName, a.middleInitial, a.lastName,
                   adv.type, adv.acadYear, adv.semester
            FROM advisor a
            LEFT JOIN advises adv ON a.advisorID = adv.advisorID
            WHERE a.advisorID = '$advisorID'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $advisor_data = null;
        $advises_data = [];
        while ($row = $result->fetch_assoc()) {
            if ($advisor_data == null) {
                $advisor_data = [
                    'advisorID' => $row['advisorID'],
                    'firstName' => $row['firstName'],
                    'middleInitial' => $row['middleInitial'],
                    'lastName' => $row['lastName']
                ];
            }
            if ($row['acadYear'] && $row['semester']) {
                $advises_data[] = [
                    'type' => $row['type'],
                    'acadYear' => $row['acadYear'],
                    'semester' => $row['semester']
                ];
            }
        }
        // get all academic years for dropdown
        $acadYearOptions = '';
        $acadYearRes = $conn->query("SELECT DISTINCT acadYear FROM academicyear ORDER BY acadYear DESC");
        while ($yr = $acadYearRes->fetch_assoc()) {
            $acadYearOptions .= '<option value="' . htmlspecialchars($yr['acadYear']) . '">' . htmlspecialchars($yr['acadYear']) . '</option>';
        }
?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Edit Advisor</title>
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
                .semester-group {
                    display: flex;
                    gap: 10px;
                    margin-top: 5px;
                }
            </style>
        </head>
        <body>
            <form action="updateAdvisor.php" method="post">
                <h2>Edit Advisor</h2>
                <label for="advisorID">Advisor ID:</label>
                <input type="text" name="advisorID" value="<?php echo $advisor_data['advisorID']; ?>" required><br>
                <label for="firstName">First Name:</label>
                <input type="text" name="firstName" value="<?php echo $advisor_data['firstName']; ?>" required><br>
                <label for="middleName">Middle Initial:</label>
                <input type="text" name="middleName" value="<?php echo $advisor_data['middleInitial']; ?>"><br>
                <label for="lastName">Last Name:</label>
                <input type="text" name="lastName" value="<?php echo $advisor_data['lastName']; ?>" required><br>

                <h3>Advises (per Academic Year & Semester)</h3>
                <table class="advises-table">
                    <tr>
                        <th>Academic Year</th>
                        <th>Semester</th>
                        <th>Type</th>
                        <th>Update?</th>
                    </tr>
                    <?php
                    // show all advises for this advisor
                    foreach ($advises_data as $i => $adv) {
                        echo "<tr>";
                        echo "<td><input type='text' name='adv_acadYear[]' value='" . htmlspecialchars($adv['acadYear']) . "' readonly></td>";
                        echo "<td><input type='number' name='adv_semester[]' value='" . htmlspecialchars($adv['semester']) . "' readonly></td>";
                        echo "<td>
                                <select name='adv_type[]'>
                                    <option value='Advisor' " . ($adv['type'] == 'Advisor' ? 'selected' : '') . ">Advisor</option>
                                    <option value='Co-Advisor' " . ($adv['type'] == 'Co-Advisor' ? 'selected' : '') . ">Co-Advisor</option>
                                </select>
                            </td>";
                        echo "<td><input type='checkbox' name='adv_update[]' value='$i'></td>";
                        echo "</tr>";
                    }
                ?></table><br>
                <h4>Add New Advises Entry</h4>
                <label for="new_acadYear">Academic Year:</label>
                <select name="new_acadYear">
                    <option value="">-- Select Academic Year --</option>
                    <?php echo $acadYearOptions; ?>
                </select>
                <label for="new_semester">Semester:</label>
                <select name="new_semester">
                    <option value="">-- Select Semester --</option>
                    <option value="1">1st Semester</option>
                    <option value="2">2nd Semester</option>
                </select>
                <label for="new_type">Type:</label>
                <select name="new_type">
                    <option value="">-- Select Type --</option>
                    <option value="Advisor">Advisor</option>
                    <option value="Co-Advisor">Co-Advisor</option>
                </select>
                <br>
                <button type="submit">Update Advisor</button>
            </form>
        </body>
        </html>
        <?php
    } else {
        echo "Advisor not found.";
    }
} else {
    echo "Invalid request. Please use the form to edit an advisor.";
}

$conn->close();
?>
