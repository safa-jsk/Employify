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

    <div class="search-container">
        <div class="row">
            <div class="col-12">
                <div class="search-card">
                    <div class="search-card-body">
                        <div class="text-center mb-4">
                            <h4>Search Your Desired Job</h4>
                        </div>
                        <form action="" method="get">
                            <div class="input-group mb-3">
                                <select name="criteria" class="advanced-search-form-select">
                                    <option value="all" selected>All</option>
                                    <option value="name">Name</option>
                                    <option value="company">Company</option>
                                    <option value="field">Field</option>
                                    <option value="salary">Salary</option>
                                </select>
                                <input type="text" name="search"
                                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                                    class="form-control" placeholder="Search for jobs">
                                <button type="submit" class="search-button">Search</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
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
                                require_once 'DBconnect.php'; // use $con

                                $criteria = isset($_GET['criteria']) ? $_GET['criteria'] : 'all';
                                $search = isset($_GET['search']) && !empty(trim($_GET['search'])) ? trim($_GET['search']) : null;

                                // Prepare query
                                $query = "SELECT a.*, r.CName FROM applications a INNER JOIN recruiter r ON a.R_id = r.R_id WHERE 1=1";
                                $params = [];
                                $types = "";

                                if ($criteria !== 'all' && $search) {
                                    switch ($criteria) {
                                        case 'name':
                                            $query .= " AND a.Name LIKE ?";
                                            $params[] = "%$search%";
                                            $types .= "s";
                                            break;
                                        case 'company':
                                            $query .= " AND r.CName LIKE ?";
                                            $params[] = "%$search%";
                                            $types .= "s";
                                            break;
                                        case 'field':
                                            $query .= " AND a.Field LIKE ?";
                                            $params[] = "%$search%";
                                            $types .= "s";
                                            break;
                                        case 'salary':
                                            $query .= " AND a.Salary >= ?";
                                            $params[] = $search;
                                            $types .= "i";
                                            break;
                                    }
                                } else if ($search) {
                                    $query .= " AND CONCAT(a.Name, a.Field, a.Salary, a.Description) LIKE ?";
                                    $params[] = "%$search%";
                                    $types .= "s";
                                }

                                $stmt = $con->prepare($query);
                                if (!empty($params)) {
                                    $stmt->bind_param($types, ...$params);
                                }

                                $stmt->execute();
                                $result = $stmt->get_result();

                                if ($result->num_rows > 0) {
                                    while ($items = $result->fetch_assoc()) {
                                ?>
                                        <tr>
                                            <td><?= htmlspecialchars($items['A_id']); ?></td>
                                            <td><?= htmlspecialchars($items['Name']); ?></td>
                                            <td><?= htmlspecialchars($items['CName']); ?></td>
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
                                $stmt->close();
                                $con->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

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