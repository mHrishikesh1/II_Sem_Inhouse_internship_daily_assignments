<?php
$errors = array();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['student_name']) ? trim($_POST['student_name']) : '';
    $email = isset($_POST['student_email']) ? trim($_POST['student_email']) : '';
    $branch = isset($_POST['student_branch']) ? trim($_POST['student_branch']) : '';
    $phone = isset($_POST['student_phone']) ? trim($_POST['student_phone']) : '';
    if (empty($name)) {
        $errors[] = "Name field cannot be left completely empty.";
    }
    if (empty($email)) {
        $errors[] = "Email field is required and cannot be left blank.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address (e.g., name@domain.com).";
    }
    if (empty($branch)) {
        $errors[] = "Please select an academic branch specialization from the list.";
    }
    if (empty($phone)) {
        $errors[] = "Phone number field cannot be left completely empty.";
    } else {
        if (!is_numeric($phone)) {
            $errors[] = "Phone verification failed: Input must contain numbers only, characters are invalid.";
        }
        if (strlen($phone) !== 10) {
            $errors[] = "Phone verification failed: Number must be exactly 10 digits long (Current length: " . strlen($phone) . ").";
        }
    }

} else {
    header("Location: register.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Process Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <?php
            if (!empty($errors)): 
            ?>
                <div class="alert alert-danger shadow-sm" role="alert">
                    <h4 class="alert-heading fw-bold mb-2">Registration Processing Halted!</h4>
                    <p class="mb-3 small">Please address the tracking anomalies highlighted below before updating records:</p>
                    <hr>
                    <ul class="mb-0 ps-3">
                        <?php foreach ($errors as $currentError): ?>
                            <li class="mb-1"><?php echo htmlspecialchars($currentError); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div class="text-center mt-4">
                    <button onclick="history.back()" class="btn btn-primary px-4 fw-bold">Return and Fix Errors</button>
                </div>

            <?php 
            else: 
            ?>
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-success">Registration Received</h2>
                    <h4 class="text-secondary text-capitalize">Welcome, <?php echo htmlspecialchars($name); ?>!</h4>
                </div>
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="mb-0 fw-bold">Verified Profile Records Summary</h5>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-4">The processing routine successfully validated your structural data variables. Output details are logged below:</p>
                        
                        <table class="table table-striped table-bordered mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" style="width: 35%;">Data Metric Field</th>
                                    <th scope="col">Captured Variable Output</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-bold">Student Name</td>
                                    <td><?php echo htmlspecialchars($name); ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Email Address</td>
                                    <td class="text-lowercase"><?php echo htmlspecialchars($email); ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Academic Branch</td>
                                    <td><?php echo htmlspecialchars($branch); ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Phone Number</td>
                                    <td><?php echo htmlspecialchars($phone); ?></td>
                                </tr>
                            </tbody>
                        </table>
                        
                    </div>
                    <div class="card-footer bg-light text-end py-2">
                        <small class="text-muted">Processed via backend metrics securely.</small>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="register.php" class="btn btn-outline-secondary px-4 fw-semibold">Register Another Student</a>
                </div>

            <?php endif; ?>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>