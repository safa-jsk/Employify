<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <title>Employify</title>
</head>

<body>
    <?php include 'includes/js_navbar.php'; ?>
    <?php include 'includes/js_sidebar.php'; ?>

    <main class="container">
        <main class="container-fluid mt-5">
            <div class="row">
                <div class="col-12">
                    <div class="full-width-card">
                        <div class="text-center mb-4">
                            <h4>Search Page</h4>
                        </div>
                        <form action="" method="get">
                            <div class="input-group mb-3">
                                <input type="text" name="search" value="<?php if (isset($_GET['search'])) {
                                                                            echo htmlspecialchars($_GET['search']);
                                                                        } ?>" class="form-control"
                                    placeholder="Search for jobs">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-12">
                    <div class="full-width-card">
                        <table class="table table-bordered text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Application ID</th>
                                    <th>Name</th>
                                    <th>Company</th>
                                    <th>Deadline</th>
                                    <th>Field</th>
                                    <th>Salary</th>
                                    <th>Description</th>
                                    <th>Apply</th>
                                    <th>Bookmark</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                session_start();
                                require_once 'DBconnect.php'; // use $con

                                if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
                                    $search = mysqli_real_escape_string($con, $_GET['search']);
                                    $query = "SELECT * FROM applications WHERE CONCAT(Name, Field, Salary, Description) LIKE '%$search%'";
                                    $query_run = mysqli_query($con, $query);

                                    if (mysqli_num_rows($query_run) > 0) {
                                        while ($items = mysqli_fetch_assoc($query_run)) {
                                            $company_query = "SELECT r.CName FROM recruiter r INNER JOIN applications a ON r.R_id = a.R_id WHERE a.A_id = " . $items['A_id'];
                                            $company_query_run = mysqli_query($con, $company_query);
                                            $company = mysqli_fetch_assoc($company_query_run);
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($items['A_id']); ?></td>
                                    <td><?= htmlspecialchars($items['Name']); ?></td>
                                    <td><?= htmlspecialchars($company['CName']); ?></td>
                                    <td><?= htmlspecialchars($items['Deadline']); ?></td>
                                    <td><?= htmlspecialchars($items['Field']); ?></td>
                                    <td><?= htmlspecialchars($items['Salary']); ?></td>
                                    <td><?= htmlspecialchars($items['Description']); ?></td>
                                    <td><a href="js_apply.php?A_id=<?= htmlspecialchars($items['A_id']); ?>"
                                            class="btn btn-primary" name="apply">Apply</a></td>
                                    <td><a href="js_bookmark.php?A_id=<?= htmlspecialchars($items['A_id']); ?>"
                                            class="btn btn-primary" name="bookmark">Bookmark</a></td>
                                </tr>
                                <?php
                                        }
                                    } else {
                                        ?>
                                <tr>
                                    <td colspan="9">No Record Found</td>
                                </tr>
                                <?php
                                    }
                                } else {
                                    ?>
                                <tr>
                                    <td colspan="9">Please enter a search term.</td>
                                </tr>
                                <?php
                                }
                                mysqli_close($con);
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFyZ6tOrX2UGjUZJhzoeF1hZeGkEe2kC7HrrxQi1RAd+0cFihkc7x9o2p6" crossorigin="anonymous">
        </script>

        <?php
        if (isset($_GET['message']) && !empty($_GET['message'])) {
            $message = htmlspecialchars($_GET['message']);
            echo "<script> alert('$message'); </script>";
        }
        ?>
</body>

</html>