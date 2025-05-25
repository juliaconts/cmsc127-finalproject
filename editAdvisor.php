<?php
include 'DBConnector.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $advisorID = $_POST['advisorID'];

    // Modified SQL to fetch from 'advisor' and 'advises' tables
    $sql = "SELECT a.advisorID, a.firstName, a.middleInitial, a.lastName,
                   adv.type, adv.acadYear, adv.semester
            FROM advisor a
            LEFT JOIN advises adv ON a.advisorID = adv.advisorID
            WHERE a.advisorID = '$advisorID'"; // Removed the AND condition

    $result = $conn->query($sql);

    if ($result->num_rows > 0) { // Changed to > 0 to handle multiple advises entries
        // Fetch the advisor's base data.  We'll assume the first row has the advisor's name.
        $advisor_data = null;
        $advises_data = [];
        while ($row = $result->fetch_assoc()) {
            if ($advisor_data == null){
                $advisor_data = $row;
            }
            //Organize the advises data.
            $advises_data[] = [
                'type' => $row['type'],
                'acadYear' => $row['acadYear'],
                'semester' => $row['semester']
            ];
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
            <form action="./updates/updateAdvisor.php" method="post">
                <h2>Edit Advisor</h2>
                <label for="advisorID">Advisor ID:</label>
                <input type="text" name="advisorID" value="<?php echo $advisor_data['advisorID']; ?>" required><br>
                <label for="firstName">First Name:</label>
                <input type="text" name="firstName" value="<?php echo $advisor_data['firstName']; ?>" required><br>
                <label for="middleName">Middle Initial:</label>
                <input type="text" name="middleName" value="<?php echo $advisor_data['middleInitial']; ?>"><br>
                <label for="lastName">Last Name:</label>
                <input type="text" name="lastName" value="<?php echo $advisor_data['lastName']; ?>" required><br>

                <label for="acadYear">Academic Year:</label>
                <select class="expand" name="acadYear">
                    <option value="" disabled>-- Select Academic Year --</option>
                    <?php
                    // Fetch distinct academic years from the database
                    $acadYears = [];
                    foreach($advises_data as $advisory){
                        if (!in_array($advisory['acadYear'], $acadYears)){
                            $acadYears[] = $advisory['acadYear'];
                        }
                    }
                    foreach ($acadYears as $year) {
                        echo "<option value=\"$year\"";
                        if (in_array($year, array_column($advises_data, 'acadYear'))) {
                            echo " selected";  // Select the year if it matches
                        }
                        echo ">$year</option>";
                    }
                    ?></select><br>

                <label>Semester:</label>
                <div class="semester-group">
                    <input type="radio" id="sem1" name="semester[]" value="1" >
                    <label for="sem1">1st Semester</label>
                    <input type="radio" id="sem2" name="semester[]" value="2" >
                    <label for="sem2">2nd Semester</label>
                </div>

                <label for="type">Type:</label>
                <select class="expand" name="type">
                    <option value="" disabled>-- Select Type --</option>
                    <?php
                    $types = ['Advisor', 'Co-Advisor'];
                    foreach($types as $type){
                         echo "<option value=\"$type\"";
                         if (in_array($type, array_column($advises_data, 'type'))) {
                            echo " selected";
                         }
                         echo ">$type</option>";
                    }
                    ?>
                </select><br>
                <button type="submit">Update Advisor</button>
            </form>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const acadYearSelect = document.querySelector('select[name="acadYear"]');
            const sem1Radio = document.getElementById('sem1');
            const sem2Radio = document.getElementById('sem2');

            function updateSemesterRadios() {
                const selectedYear = acadYearSelect.value;
                let hasSem1 = false;
                let hasSem2 = false;
                <?php
                    foreach ($advises_data as $advisory) {
                        echo "if ('" . $advisory['acadYear'] . "' === selectedYear) {";
                        echo "    if (" . $advisory['semester'] . " === 1) { hasSem1 = true; }";
                        echo "    if (" . $advisory['semester'] . " === 2) { hasSem2 = true; }";
                        echo "}";
                    }
                ?>
                sem1Radio.checked = hasSem1;
                sem2Radio.checked = hasSem2;

                //If neither semester is selected, select both.
                if (!hasSem1 && !hasSem2){
                    sem1Radio.checked = true;
                    sem2Radio.checked = true;
                }
            }
            acadYearSelect.addEventListener('change', updateSemesterRadios);
            updateSemesterRadios(); // Initial update on page load
        });
        </script>
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
