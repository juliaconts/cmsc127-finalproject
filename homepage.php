<!DOCTYPE html>
<html>
    <head>
        <title>Komsai.Org Database</title>
        <meta charset = "UTF-8">
        <meta name = "author" content = "Contreras-DelRosario-Quindao-Verde">
        <link rel="stylesheet" type="text/css" href="homeDesign.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    </head>

    <body>
        <header>
            <img src="komsai_logo.png" alt="icon" width="60px" height="60px">
		    <h1 id="title">UPV KOMSAI.ORG</h1>
	    </header>
        <hr>

    <!-- top buttons -->
    <div class="top-bar">
        <div class="left-controls">
            <form action=" ">
                <select name="acadyear" id="AY">
                        <option value="24_25">A.Y. 2024-2025</option>
                        <option value="23_24">A.Y. 2023-2024</option>
                        <option value="22_23">A.Y. 2022-2023</option>
                </select>
            </form>
            <form action='addMemberDetails.php' method='post'>
                    <input type='text' style='display: none;' name='studentID' value='".$row["studentID"]."'>
                    <button type='button' onclick='this.form.submit()'>Add Member</button>
            </form>
            <form action='addAdvisorDetails.php' method='post'>
                    <input type='text' style='display: none;' name='advisorID' value='".$row["studentID"]."'>
                    <button type='button' onclick='this.form.submit()'>Add Advisor</button>
            </form>
        </div>

        <div class= "right-controls">
            <form action="">
                <input type="text" placeholder="Search">
             </form>
        </div>
    </div>

        
    <!-- Display of data -->
     <br>

    <!--Advisor display-->
     <div class="container">
        <h2>Advisor</h2>
        
        <div class="dropDowns">
            <form action=" ">
                <select name="sortBy" id="sort">
                        <option value="none" selected disabled hidden>Sort by</option>
                        <option value="roles">Roles</option>
                        <option value="status">Status </option>
                        <option value="add sort">add sort</option>
                        <option value="add sort">add sort</option>
                </select>
            </form>

            <form action=" ">
                <select name="filterBy" id="filter">
                        <option value="none" selected disabled hidden>Filter by</option>
                        <option value="add filter">add filter</option>
                        <option value="add filter">add filter</option>
                        <option value="add filter">add filter</option>
                        <option value="add filter">add filter</option>
                </select>
            </form>
        </div>
    </div>

        <div class="scrollbox">
            <table style="width:100%">
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Academic Year</th>
                    <th>Semester</th>
                    <th>AdvisorID</th>
                    <th></th>
                </tr>
            <?php
                include 'advisor.php';
            ?>
            </table>
        </div>
        <br>

    <!--First Year display-->
        <div class="container">
            <h2>1st Year</h2>
        
            <div class="dropDowns">
                <form action=" ">
                    <select name="sortBy" id="sort">
                            <option value="none" selected disabled hidden>Sort by</option>
                            <option value="roles">Roles</option>
                            <option value="status">Status </option>
                            <option value="add sort">add sort</option>
                            <option value="add sort">add sort</option>
                    </select>
                </form>

                <form action=" ">
                    <select name="filterBy" id="filter">
                            <option value="none" selected disabled hidden>Filter by</option>
                            <option value="add filter">add filter</option>
                            <option value="add filter">add filter</option>
                            <option value="add filter">add filter</option>
                            <option value="add filter">add filter</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="scrollbox">
            <table style="width:100%">
                <tr>
                    <th>Name</th>
                    <th>ID</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>SOA Reqs</th>
                    <th>Payment</th>
                    <th></th>
                </tr>
            <?php
                include 'firstyear.php';
            ?>
            </table>
        </div>
        <br>

    <!--Second Year display-->
        <div class="container">
            <h2>2nd Year</h2>
        
            <div class="dropDowns">
                <form action=" ">
                    <select name="sortBy" id="sort">
                            <option value="none" selected disabled hidden>Sort by</option>
                            <option value="roles">Roles</option>
                            <option value="status">Status </option>
                            <option value="add sort">add sort</option>
                            <option value="add sort">add sort</option>
                    </select>
                </form>

                <form action=" ">
                    <select name="filterBy" id="filter">
                            <option value="none" selected disabled hidden>Filter by</option>
                            <option value="add filter">add filter</option>
                            <option value="add filter">add filter</option>
                            <option value="add filter">add filter</option>
                            <option value="add filter">add filter</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="scrollbox">
            <table style="width:100%">
                <tr>
                    <th>Name</th>
                    <th>ID</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>SOA Reqs</th>
                    <th>Payment</th>
                    <th></th>
                <tr>
            <?php
                include 'secondyear.php';
            ?>
            </table>
        </div>
        <br>

    <!--Third Year display-->
        <div class="container">
            <h2>3rd Year</h2>
            
            <div class="dropDowns">
                <form action=" ">
                    <select name="sortBy" id="sort">
                            <option value="none" selected disabled hidden>Sort by</option>
                            <option value="roles">Roles</option>
                            <option value="status">Status </option>
                            <option value="add sort">add sort</option>
                            <option value="add sort">add sort</option>
                    </select>
                </form>

                <form action=" ">
                    <select name="filterBy" id="filter">
                            <option value="none" selected disabled hidden>Filter by</option>
                            <option value="add filter">add filter</option>
                            <option value="add filter">add filter</option>
                            <option value="add filter">add filter</option>
                            <option value="add filter">add filter</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="scrollbox">
            <table style="width:100%">
               <tr>
                    <th>Name</th>
                    <th>ID</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>SOA Reqs</th>
                    <th>Payment</th>
                    <th></th>
                <tr>
            <?php
                include 'thirdyear.php';
            ?>
            </table>
        </div>
        <br>

    <!--Fourth Year display-->
       <div class="container">
            <h2>4th Year</h2>
            
            <div class="dropDowns">
                <form action=" ">
                    <select name="sortBy" id="sort">
                            <option value="none" selected disabled hidden>Sort by</option>
                            <option value="roles">Roles</option>
                            <option value="status">Status </option>
                            <option value="add sort">add sort</option>
                            <option value="add sort">add sort</option>
                    </select>
                </form>

                <form action=" ">
                    <select name="filterBy" id="filter">
                            <option value="none" selected disabled hidden>Filter by</option>
                            <option value="add filter">add filter</option>
                            <option value="add filter">add filter</option>
                            <option value="add filter">add filter</option>
                            <option value="add filter">add filter</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="scrollbox">
            <table style="width:100%">
                <tr>
                    <th>Name</th>
                    <th>ID</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>SOA Reqs</th>
                    <th>Payment</th>
                    <th></th>
                <tr>
            <?php
                include 'fourthyear.php';
            ?>
            </table>
        </div>
        <br>

    <!--Nth Year display-->
        <div class="container">
            <h2>Nth Year</h2>
            
            <div class="dropDowns">
                <form action=" ">
                    <select name="sortBy" id="sort">
                            <option value="none" selected disabled hidden>Sort by</option>
                            <option value="roles">Roles</option>
                            <option value="status">Status </option>
                            <option value="add sort">add sort</option>
                            <option value="add sort">add sort</option>
                    </select>
                </form>

                <form action=" ">
                    <select name="filterBy" id="filter">
                            <option value="none" selected disabled hidden>Filter by</option>
                            <option value="add filter">add filter</option>
                            <option value="add filter">add filter</option>
                            <option value="add filter">add filter</option>
                            <option value="add filter">add filter</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="scrollbox">
            <table style="width:100%">
                <tr>
                    <th>Name</th>
                    <th>ID</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>SOA Reqs</th>
                    <th>Payment</th>
                    <th></th>
                <tr>
            <?php
                include 'nthyear.php';
            ?>
            </table>
        </div>
        <br>

        <!--Alumni display-->
        <div class="container">
            <h2>Alumni</h2>
            
            <div class="dropDowns">
                <form action=" ">
                    <select name="sortBy" id="sort">
                            <option value="none" selected disabled hidden>Sort by</option>
                            <option value="roles">Roles</option>
                            <option value="status">Status </option>
                            <option value="add sort">add sort</option>
                            <option value="add sort">add sort</option>
                    </select>
                </form>

                <form action=" ">
                    <select name="filterBy" id="filter">
                            <option value="none" selected disabled hidden>Filter by</option>
                            <option value="add filter">add filter</option>
                            <option value="add filter">add filter</option>
                            <option value="add filter">add filter</option>
                            <option value="add filter">add filter</option>
                    </select>
                </form>
            </div>
        </div>
    
        <div class="scrollbox">
            <table style="width:100%">
                <tr>
                    <th>Name</th>
                    <th>AlumniID</th>
                    <th>StudentID</th>
                    <th></th>
                <tr>
            <?php
                include 'alumni.php';
            ?>
            </table>
        </div>
        <br>

    </body>
</html>