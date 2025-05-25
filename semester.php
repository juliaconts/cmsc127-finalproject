<?php
generateDropdown($conn, "SELECT DISTINCT semester FROM academicyear ORDER BY semester ASC", $semester, 'semester');
?>