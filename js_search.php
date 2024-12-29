<?php
session_start();
?>

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
                            <h4>Search Page</h4>
                        </div>
                        <form action="" method="get">
                            <div class="input-group mb-3">
                                <input type="text" name="search"
                                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                                    class="form-control" placeholder="Search for jobs">
                                <button type="submit" class="search-button">Search</button>
                            </div>
                        </form>

                        <!-- Advanced Search -->
                        <button type="button" class="advanced_search_collapsible">Advanced Search</button>
                        <div class="advanced_search_content">
                            <form action="" method="get">

                                <div class="input-group">
                                    <input type="text" name="name"
                                        value="<?php echo isset($_GET['name']) ? htmlspecialchars($_GET['name']) : ''; ?>"
                                        class="form-control" placeholder="Search job by name">
                                    <button type="submit" class="search-button">Search</button>
                                </div>

                                <div class="input-group">
                                    <input type="text" name="company"
                                        value="<?php echo isset($_GET['company']) ? htmlspecialchars($_GET['company']) : ''; ?>"
                                        class="form-control" placeholder="Search job by company">
                                    <button type="submit" class="search-button">Search</button>
                                </div>

                                <div class="input-group">
                                    <input type="text" name="field"
                                        value="<?php echo isset($_GET['field']) ? htmlspecialchars($_GET['field']) : ''; ?>"
                                        class="form-control" placeholder="Search job by field">
                                    <button type="submit" class="search-button">Search</button>
                                </div>

                                <div class="input-group">
                                    <input type="int" name="salary"
                                        value="<?php echo isset($_GET['salary']) ? htmlspecialchars($_GET['salary']) : ''; ?>"
                                        class="form-control" placeholder="Search job by minimum salary">
                                    <button type="submit" class="search-button">Search</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Advanced Search Script -->
            <script>
                var coll = document.getElementsByClassName("advanced_search_collapsible");
                var i;

                for (i = 0; i < coll.length; i++) {
                    coll[i].addEventListener("click", function() {
                        this.classList.toggle("active");
                        var content = this.nextElementSibling;
                        if (content.style.maxHeight) {
                            content.style.maxHeight = null;
                        } else {
                            content.style.maxHeight = content.scrollHeight + "px";
                        }
                    });
                }
            </script>

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

                                $search = isset($_GET['search']) && !empty(trim($_GET['search'])) ? trim($_GET['search']) : null;
                                $name = isset($_GET['name']) && !empty(trim($_GET['name'])) ? trim($_GET['name']) : null;
                                $company = isset($_GET['company']) && !empty(trim($_GET['company'])) ? trim($_GET['company']) : null;
                                $field = isset($_GET['field']) && !empty(trim($_GET['field'])) ? trim($_GET['field']) : null;
                                $salary = isset($_GET['salary']) && !empty(trim($_GET['salary'])) ? trim($_GET['salary']) : null;

                                // Prepare query
                                $query = "SELECT a.*, r.CName FROM applications a INNER JOIN recruiter r ON a.R_id = r.R_id WHERE 1=1";
                                $params = [];
                                $types = "";

                                if ($search) {
                                    $query .= " AND CONCAT(a.Name, a.Field, a.Salary, a.Description) LIKE ?";
                                    $params[] = "%$search%";
                                    $types .= "s";
                                }
                                if ($name) {
                                    $query .= " AND a.Name LIKE ?";
                                    $params[] = "%$name%";
                                    $types .= "s";
                                }
                                if ($company) {
                                    $query .= " AND r.CName LIKE ?";
                                    $params[] = "%$company%";
                                    $types .= "s";
                                }
                                if ($field) {
                                    $query .= " AND a.Field LIKE ?";
                                    $params[] = "%$field%";
                                    $types .= "s";
                                }
                                if ($salary) {
                                    $query .= " AND a.Salary >= ?";
                                    $params[] = $salary;
                                    $types .= "i";
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