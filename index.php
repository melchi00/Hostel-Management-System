<?php
include 'functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hostel Allocation Management System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Optional: Add some styling for the body with background image */
        body {
            background: url('https://francecentral1-mediap.svc.ms/transform/thumbnail?provider=spo&farmid=190717&inputFormat=jpg&cs=MDAwMDAwMDAtMDAwMC0wMDAwLTAwMDAtMDAwMDQ4MTcxMGE0fFNQTw&docid=https%3A%2F%2Fmy.microsoftpersonalcontent.com%2F_api%2Fv2.0%2Fdrives%2Fb!LPttJfhGHE-0RLit5DPDnjsMoajUhfhCsyyI2I4VDF1wu8YCOllrSo-OIWcumosR%2Fitems%2F01RLBLH4SX7XVBK3TZLVGYTF3XS46ODRWY%3Ftempauth%3Dv1e.eyJzaXRlaWQiOiIyNTZkZmIyYy00NmY4LTRmMWMtYjQ0NC1iOGFkZTQzM2MzOWUiLCJhcHBpZCI6IjAwMDAwMDAwLTAwMDAtMDAwMC0wMDAwLTAwMDA0ODE3MTBhNCIsImF1ZCI6IjAwMDAwMDAzLTAwMDAtMGZmMS1jZTAwLTAwMDAwMDAwMDAwMC9teS5taWNyb3NvZnRwZXJzb25hbGNvbnRlbnQuY29tQDkxODgwNDBkLTZjNjctNGM1Yi1iMTEyLTM2YTMwNGI2NmRhZCIsImV4cCI6IjE3MjUyNzg0MDAifQ.GXaypUn6-8IAqjuvZ5aw2JHybC72ynYcazQIIFfGsPt7mPpcUgRUFgjmD32-4Rikaf0df1Rkj_HqkjUcUY3IbaaX7rV745fviA6LR6FcsQMhb1SMlyTHwg3HGtQ7irsG1v7rPfkYPQa5hhInZJ4z_Gum_u95ZeDUxxdEq-NRjVQCqZfGyrbVrVC5cBslQGa-GUJORt0IOZXXPq6iSsm6_2nGdmbmUwqRvU8zI3HCtI2NowvY8rxnweB1-Z7V0kU--c420rR1cAo4aagB3vrh5Mv0vs7bPY_beWXyvYf7CrorZHP10HpmhvZMPD6j-tC--yu2cvGYS2U_B_bVBHOZTpaJ6WD1HJoVYcfqkO3sb96r7I0K1DqR1rRRJ85WAqru.XtqBiCrZuScMmDQRt3m1EwBPzaD5iiVMgkTz6lQbBUE%26version%3DPublished&cb=63857356257&encodeFailures=1&width=735&height=489&action=Access') no-repeat center center fixed;
            background-size: cover; /* Ensure the background image covers the whole page */
            
        }

        /* Optional: Adjust container styling to ensure readability */
        .container {
            background:  /* Semi-transparent white background for readability */
            padding: 20px;
            border-radius: 10px;
            
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Welcome to the Hostel Allocation Management System</h1>
        </header>
        <nav>
            <ul>
                <li><a href="admin_login.php">Admin Login</a></li>
                <li><a href="client_login.php">Client Login</a></li>
                <li><a href="client_signup.php">Client Sign Up</a></li>
                <!-- Remove or secure this link after initial setup (admin_signup.php cause I'm the only admin) -->
            </ul>
        </nav>
        <main>
            <section class="homepage">
                <p> <i>Please use the navigation above to log in or sign up.</i><br><br><br><br><br><br>
            <b>ABOUT US</b> <br>
            The Hostel Allocation Management System is a comprehensive software solution designed to streamline and optimize the process of allocating hostel accommodations within educational institutions or near educational institutions. </p>
            </section>
        </main>
        <footer>
            <p>&copy; 2024 Hostel Allocation Management System</p>
        </footer>
    </div>
</body>
</html>
