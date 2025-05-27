<?php
generateDropdown($conn, "SELECT DISTINCT semester FROM academicyear WHERE acadYear = '$acadYear' ORDER BY semester ASC", $semester, 'semester');
?>