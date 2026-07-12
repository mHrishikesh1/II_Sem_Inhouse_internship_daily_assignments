<?php
require_once 'db_connect.php';

$message = "";
$msgClass = "";
$studentData = null;
if (isset($_GET['id'])) {
    $studentId = (int)$_GET['id'];
    $fetchQuery = "SELECT * FROM students WHERE id = ?";
    $stmt = mysqli_prepare($conn, $fetchQuery);
    mysqli_stmt_bind_param($stmt, "i", $studentId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $studentData = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$studentData) {
        die("System Exception Error: The target row record query pointer returned null data.");
    }
} else {
    header("Location: index.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update') {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $branch = mysqli_real_escape_string($conn, trim($_POST['branch']));

    if (empty($name) || empty($email) || empty($branch)) {
        $message = "Validation Error: Blank entries are rejected during update runs.";
        $msgClass = "alert-danger";
    } else {
        $updateQuery = "UPDATE students SET student_name = ?, student_email = ?, student_branch = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $branch, $studentId);
        
        if (mysqli_stmt_execute($stmt)) {
            $message = "Success Alert: Structural adjustments processed. Data configurations updated successfully.";
            $msgClass = "alert-success";
            
            $studentData['student_name'] = $name;
            $studentData['student_email'] = $email;
            $studentData['student_branch'] = $branch;
        } else {
            $message = "Database adjustment runtime fault: " . mysqli_error($conn);
            $msgClass = "alert-danger";
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Student Records File Context</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <?php if (!empty($message)): ?>
                <div class="alert <?php echo $msgClass; ?> shadow-sm mb-4" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i><?php echo $message; ?>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning text-dark py-3">
                    <h5 class="card-title mb-0 fw-bold"><i class="fa-solid fa-user-pen me-2"></i>Edit Student Entry Parameters: ID #<?php echo $studentData['id']; ?></h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small">Modify record components as needed. Hit save changes to process execution arrays.</p>
                    <hr>

                    <form method="POST" action="update.php?id=<?php echo $studentData['id']; ?>">
                        <input type="hidden" name="action" value="update">

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Student Identity Name</label>
                            <input type="text" class="form-control" name="name" id="name" value="<?php echo htmlspecialchars($studentData['student_name']); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email Directory Endpoint</label>
                            <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($studentData['student_email']); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="branch" class="form-label fw-semibold">Branch Specialty Mapping</label>
                            <select class="form-select" name="branch" id="branch">
                                <option value="Computer Science Engineering" <?php if($studentData['student_branch'] == 'Computer Science Engineering') echo 'selected'; ?>>Computer Science Engineering</option>
                                <option value="Electronics Engineering" <?php if($studentData['student_branch'] == 'Electronics Engineering') echo 'selected'; ?>>Electronics Engineering</option>
                                <option value="Mechanical Engineering" <?php if($studentData['student_branch'] == 'Mechanical Engineering') echo 'selected'; ?>>Mechanical Engineering</option>
                            </select>
                        </div>

                        <div class="d-flex gap-2 pt-2">
                            <button type="submit" class="btn btn-warning flex-grow-1 fw-bold"><i class="fa-solid fa-rotate me-2"></i>Apply Structural Changes</button>
                            <a href="index.php" class="btn btn-secondary px-4"><i class="fa-solid fa-house"></i> Dashboard</a>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>