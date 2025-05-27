<?php
function generateDropdown($conn, $query, $selectedValue, $column) {
    $result = $conn->query($query);
    if ($result->num_rows === 0) {
        echo "<option value='' disabled>No options available</option>";
        return;
    }
    while ($row = $result->fetch_assoc()) {
        $value = $row[$column];
        if ($value == $selectedValue) {
            $selected = 'selected';
        } else {
            $selected = '';
        }
        echo "<option value='$value' $selected>$value</option>";
    }
}
generateDropdown($conn, "SELECT DISTINCT acadYear FROM academicyear ORDER BY acadYear DESC", $acadYear, 'acadYear');
?>