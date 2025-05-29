<?php
echo "<td align='center'>".
    "<div style='display: flex; gap: 5px;  justify-content: center'>".
        "<form action='editStudent.php' method='post'>".
            "<input type='hidden' name='studentID' value='".htmlspecialchars($row["studentID"])."'>".
            "<input type='hidden' name='roleID' value='".htmlspecialchars($row["roleID"])."'>".
            "<input type='hidden' name='acadYear' value='".htmlspecialchars($row["acadYear"])."'>".
            "<input type='hidden' name='semester' value='".htmlspecialchars($row["semester"])."'>".
                "<button type='button' onclick='this.form.submit()'>Edit</button>".
         "</form>".
        "<form action='deleteMember.php' method='post' onsubmit=\"return confirm('Are you sure you want to delete this member?');\">".
            "<input type='hidden' name='studentID' value='".htmlspecialchars($row['studentID'])."'>".
            "<input type='hidden' name='acadYear' value='".htmlspecialchars($acadYear)."'>".
            "<input type='hidden' name='semester' value='".htmlspecialchars($semester)."'>".
            "<button type='submit'>Delete</button>".
        "</form>".
    "</div>".
"</td>";
?>