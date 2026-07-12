<?php
require_once 'db_connect.php';

$message = "";
$msgClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $branch = mysqli_real_escape_string($conn, trim($_POST['branch']));

    if (empty($name) || empty($email) || empty($branch)) {
        $message = "Please fill in all mandatory fields before submitting.";
        $msgClass = "alert-danger";
    } else {
        $query = "INSERT INTO students (student_name, student_email, student_branch) VALUES ('$name', '$email', '$branch')";
        
        if (mysqli_query($conn, $query)) {
            $message = "Student record has been successfully added to MySQL database!";
            $msgClass = "alert-success";
        } else {
            $message = "Database execution error: " . mysqli_error($conn);
            $msgClass = "alert-danger";
        }
    }
}

$selectQuery = "SELECT * FROM students ORDER BY id DESC";
$result = mysqli_query($conn, $selectQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <span class="navbar-brand mb-0 h1">EduManagement System</span>
    </div>
</nav>

<div class="container">
    <?php if (!empty($message)): ?>
        <div class="alert <?php echo $msgClass; ?> alert-dismissible fade show" role="alert">
            <strong>Notice:</strong> <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Add New Student</h5>
                </div>
                <div class="card-body">
                    <form action="index.php" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="John Doe">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="john@example.com">
                        </div>
                        <div class="mb-3">
                            <label for="branch" class="form-label">Branch / Department</label>
                            <select class="form-select" name="branch" id="branch">
                                <option value="">-- Choose Specialization --</option>
                                <option value="Computer Science Engineering">Computer Science Engineering</option>
                                <option value="Electronics Engineering">Electronics Engineering</option>
                                <option value="Mechanical Engineering">Mechanical Engineering</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100 fw-bold">Save Student Record</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="card-title mb-0">Live Student Records Table</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-secondary">
                                <tr>
                                    <th scope="col" class="ps-3">ID</th>
                                    <th scope="col">Student Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Branch</th>
                                    <th scope="col" class="pe-3">Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if (mysqli_num_rows($result) > 0): 
                                    while($row = mysqli_fetch_assoc($result)): 
                                ?>
                                    <tr>
                                        <td class="fw-bold ps-3"><?php echo $row['id']; ?></td>
                                        <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['student_email']); ?></td>
                                        <td><?php echo htmlspecialchars($row['student_branch']); ?></td>
                                        <td class="text-muted small pe-3"><?php echo $row['created_at']; ?></td>
                                    </tr>
                                <?php 
                                    endwhile; 
                                else: 
                                ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No student profiles found inside database records table.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>