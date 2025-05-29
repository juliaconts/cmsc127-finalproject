<?php
echo "<td align='center'>".
    "<div style='display: flex; gap: 5px;  justify-content: center'>".
        "<form action='editPerson.php' method='post'>".
            "<input type='text' style='display: none;' name='studentID' value='".$row["studentID"]."'>".
                "<button type='button' onclick='this.form.submit()'>Edit</button>".
         "</form>".
         "<form action='deletePerson.php' method='post' onsubmit=\"return confirm('Are you sure you want to delete this person?');\">".
           "<input type='text' style='display: none;' name='studentID' value='".$row["studentID"]."'>".
                "<button type='submit'>Delete</button>".
         "</form>".
    "</div>".
"</td>";
?>