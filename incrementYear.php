<?php
include_once 'DBConnector.php';

function main($conn) {
    $academicYears = getAcademicYears($conn);
    $students = getStudents($conn);

    processStudents($conn, $students, $academicYears);
}

function getAcademicYears($conn) {
    $sql = "SELECT acadYear, semester FROM academicyear ORDER BY acadYear ASC, semester ASC";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $academicYears = [];
        while ($data = $result->fetch_assoc()) {
            $academicYears[] = [$data['acadYear'], $data['semester']];
        }
        return $academicYears;
    } else {
        echo("error academic years: " . $conn->error);
        return [];
    }
}

function getStudents($conn) {
    $sql = "SELECT studentID, MIN(acadYear) AS minAcadYear, MIN(semester) AS minSemester
            FROM assigned
            GROUP BY studentID";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $students = [];
        while ($row = $result->fetch_assoc()) {
            $students[$row['studentID']] = [$row['minAcadYear'], $row['minSemester']];
        }
        return $students;
    } else {
        echo("error getting students: " . $conn->error);
        return [];
    }
}

function processStudents($conn, $students, $academicYears) {
    foreach ($students as $studentID => $start) {
        processStudent($conn, $studentID, $start, $academicYears);
    }
}

function processStudent($conn, $studentID, $start, $academicYears) {
    $startAcadYear = $start[0];
    $startSemester = $start[1];

    $startIndex = array_search([$startAcadYear, $startSemester], $academicYears);
    if ($startIndex === false) {
        echo("academic year $startAcadYear-$startSemester not found for student $studentID");
        return;
    }

    //earliest yearLevel
    $earliestYearLevel = getEarliestYearLevel($conn, $studentID);

    // academic year/semester
    for ($i = $startIndex; $i < count($academicYears) - 1; $i++) {
        $prevAcadYear = $academicYears[$i][0];
        $prevSemester = $academicYears[$i][1];
        $newAcadYear = $academicYears[$i + 1][0];
        $newSemester = $academicYears[$i + 1][1];

        processAcademicYear($conn, $studentID, $prevAcadYear, $prevSemester, 
                            $newAcadYear, $newSemester, $earliestYearLevel);
    }
}

function processAcademicYear($conn, $studentID, $prevAcadYear, $prevSemester, 
                            $newAcadYear, $newSemester, $earliestYearLevel) {
    $studentID = mysqli_real_escape_string($conn, $studentID);
    $newAcadYear = mysqli_real_escape_string($conn, $newAcadYear);
    $newSemester = mysqli_real_escape_string($conn, $newSemester);
    $prevAcadYear = mysqli_real_escape_string($conn, $prevAcadYear);
    $prevSemester = mysqli_real_escape_string($conn, $prevSemester);

    //if student is already an alumni
    $sql = sprintf(
        "SELECT COUNT(*) AS count FROM alumni WHERE studentID = '%s'",
        $studentID
    );
    $result = $conn->query($sql);
    if (!$result) {
        echo("error checking alumni status: " . $conn->error);
        return;
    }
    $row = $result->fetch_assoc();
    if ($row['count'] > 0) {
        return;                                      //stop if already an alumni
    }

    //check if already processed
    $sql = sprintf(
        "SELECT COUNT(*) AS count FROM assigned WHERE studentID = '%s' AND acadYear = '%s' AND semester = '%s'",
        $studentID,
        $newAcadYear,
        $newSemester
    );
    $result = $conn->query($sql);
    if (!$result) {
        echo("error checking assigned status: " . $conn->error);
        return;
    }
    $row = $result->fetch_assoc();
    if ($row['count'] > 0) {
        return; //already processed
    }

    //get previous data
    $sql = sprintf(
        "SELECT yearLevel, status, roleID, contactNo, presentAddress
        FROM assigned
        WHERE studentID = '%s' AND acadYear = '%s' AND semester = '%s'
        LIMIT 1",
        $studentID,
        $prevAcadYear,
        $prevSemester
    );
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        $prevData = [
            'yearLevel' => $earliestYearLevel,
            'status' => 'Regular',
            'roleID' => 17,
            'contactNo' => null,
            'presentAddress' => null
        ];
    } else {
        $prevData = $result->fetch_assoc();
    }

    //stop adding data for student if inactive
    $inactiveStatus = ['Dropped', 'LOA', 'Transferred'];

    if (isset($prevData['status']) && in_array($prevData['status'], $inactiveStatus)) {
        return;
    }

    $isNewYear = ($newAcadYear > $prevAcadYear);
    if ($isNewYear) {
        if (!empty($prevData['yearLevel'])) {
            $yearLevel = $prevData['yearLevel'] + 1;
        } else {
            $yearLevel = $earliestYearLevel + 1;
        }
    } else {
        if (!empty($prevData['yearLevel'])) {
            $yearLevel = $prevData['yearLevel'];
        } else {
            $yearLevel = $earliestYearLevel;
        }
    }


    //graduating or active student
    if ($prevData['status'] == 'Graduating' && $isNewYear) {
        $conn->query("START TRANSACTION");
        try {
            insertAlumni($conn, $studentID, $newAcadYear);
            insertAssigned($conn, $studentID, $newAcadYear, $newSemester, $prevData, true, $isNewYear);
            $conn->query("COMMIT");
        } catch (Exception $e) {
            $conn->query("ROLLBACK");
            echo("Transaction failed: graduating student $studentID in $newAcadYear-$newSemester: " . $e->getMessage());
        }
    } else {
        $conn->query("START TRANSACTION");
        try {
            insertAssigned($conn, $studentID, $newAcadYear, $newSemester, $prevData, false, $isNewYear, $yearLevel);
            addForm5($conn, $studentID, $newAcadYear, $newSemester);
            $conn->query("COMMIT");
        } catch (Exception $e) {
            $conn->query("ROLLBACK");
            echo("Insert failed: active student $studentID in $newAcadYear-$newSemester: " . $e->getMessage());
        }
    }
}

function getEarliestYearLevel($conn, $studentID) {
    $studentID = mysqli_real_escape_string($conn, $studentID);

    $sql = sprintf(
        "SELECT yearLevel FROM assigned 
        WHERE studentID = '%s' AND yearLevel IS NOT NULL 
        ORDER BY acadYear ASC, semester ASC LIMIT 1",
        $studentID
    );
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $data = $result->fetch_assoc();
        return $data['yearLevel'];
    }
    return 1;
}

function insertAlumni($conn, $studentID, $acadYear) {
    $studentID = mysqli_real_escape_string($conn, $studentID);
    $acadYear = mysqli_real_escape_string($conn, $acadYear);

    $sql = sprintf(
        "INSERT INTO alumni (studentID, yearGraduated) VALUES ('%s', '%s')",
        $studentID,
        $acadYear
    );
    $result = $conn->query($sql);
    if (!$result) {
        echo("error inserting to alumni: " . $conn->error);
    }
}

function insertAssigned($conn, $studentID, $acadYear, $semester, $prevData, $isGraduating, $isNewYear = false, $yearLevel = null) {
    $studentID = mysqli_real_escape_string($conn, $studentID);
    $acadYear = mysqli_real_escape_string($conn, $acadYear);
    $semester = mysqli_real_escape_string($conn, $semester);

    //roleID
    if ($isNewYear) {
        $roleID = 17;
    } else {
        if (isset($prevData['roleID']) && !empty($prevData['roleID'])) {
        $roleID = mysqli_real_escape_string($conn, $prevData['roleID']);
    } else {
        $roleID = mysqli_real_escape_string($conn, 17);
    }

    }

    //status and yearLevel
    if ($isGraduating) {
        $status = mysqli_real_escape_string($conn, 'Alumni');
        $newYearLevel = 'NULL';
    } else {
        if (isset($prevData['status']) && !empty($prevData['status'])) {
            $status = mysqli_real_escape_string($conn, $prevData['status']);
        } else {
            $status = mysqli_real_escape_string($conn, 'Regular');
        }

        if (isset($yearLevel)) {
            $newYearLevel = $yearLevel;
        } else {
            if (isset($prevData['yearLevel'])) {
                $newYearLevel = $prevData['yearLevel'];
        } else {
            $newYearLevel = 1;
        }
    }
    }
    
    //contactNo
    if (isset($prevData['contactNo']) && !empty($prevData['contactNo'])) {
    $contactNo = "'" . mysqli_real_escape_string($conn, $prevData['contactNo']) . "'";
    } else {
        $contactNo = 'NULL';
    }

    if (isset($prevData['presentAddress']) && !empty($prevData['presentAddress'])) {
        $presentAddress = "'" . mysqli_real_escape_string($conn, $prevData['presentAddress']) . "'";
    } else {
        $presentAddress = 'NULL';
    }

    $sql = sprintf(
        "INSERT INTO assigned (acadYear, semester, roleID, studentID, status, yearLevel, contactNo, presentAddress)
        VALUES ('%s', '%s', '%s', '%s', '%s', %s, %s, %s)",
        $acadYear,
        $semester,
        $roleID,
        $studentID,
        $status,
        $newYearLevel,
        $contactNo,
        $presentAddress
    );
    $result = $conn->query($sql);
    if (!$result) {
        echo("error inserting to assigned: " . $conn->error);
    }
}

function addForm5($conn, $studentID, $newAcadYear, $newSemester) {
    $studentID = mysqli_real_escape_string($conn, $studentID);
    $newAcadYear = mysqli_real_escape_string($conn, $newAcadYear);
    $newSemester = mysqli_real_escape_string($conn, $newSemester);

    $sql = sprintf(
        "INSERT INTO form5 (acadYear, semester, studentID, form5) VALUES ('%s', '%s', '%s', NULL)",
        $newAcadYear,
        $newSemester,
        $studentID
    );
    $result = $conn->query($sql);
    if (!$result) {
        echo("error inserting to form5: " . $conn->error);
    }
}

main($conn);
?>
