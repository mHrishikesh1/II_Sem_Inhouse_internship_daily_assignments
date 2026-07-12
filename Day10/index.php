<?php
require_once 'db_connect.php';

$message = "";
$msgClass = "";

if (isset($_GET['delete_id'])) {
    $deleteId = (int)$_GET['delete_id'];
    $deleteQuery = "DELETE FROM students WHERE id = ?";
    $stmt = mysqli_prepare($conn, $deleteQuery);
    mysqli_stmt_bind_param($stmt, "i", $deleteId);
    
    if (mysqli_stmt_execute($stmt)) {
        $message = "Success! The student record has been permanently removed from the system registry.";
        $msgClass = "alert-success";
    } else {
        $message = "Error removing record: " . mysqli_error($conn);
        $msgClass = "alert-danger";
    }
    mysqli_stmt_close($stmt);
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'create') {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $branch = mysqli_real_escape_string($conn, trim($_POST['branch']));

    if (empty($name) || empty($email) || empty($branch)) {
        $message = "Validation Error: All inputs are required before saving entry layers.";
        $msgClass = "alert-danger";
    } else {
        $insertQuery = "INSERT INTO students (student_name, student_email, student_branch) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($stmt, "sss", $name, $email, $branch);
        
        if (mysqli_stmt_execute($stmt)) {
            $message = "Excellent! New student profile successfully archived inside the registry matrix.";
            $msgClass = "alert-success";
        } else {
            $message = "Database processing error: " . mysqli_error($conn);
            $msgClass = "alert-danger";
        }
        mysqli_stmt_close($stmt);
    }
}
$searchName = isset($_GET['search_name']) ? trim($_GET['search_name']) : '';
$searchBranch = isset($_GET['search_branch']) ? trim($_GET['search_branch']) : '';
$selectQuery = "SELECT * FROM students WHERE 1=1";

if ($searchName !== '') {
    $escapedName = mysqli_real_escape_string($conn, $searchName);
    $selectQuery .= " AND student_name LIKE '%$escapedName%'";
}
if ($searchBranch !== '') {
    $escapedBranch = mysqli_real_escape_string($conn, $searchBranch);
    $selectQuery .= " AND student_branch = '$escapedBranch'";
}

$selectQuery .= " ORDER BY id DESC";
$result = mysqli_query($conn, $selectQuery);
$totalRecords = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f5f7fa; }
        .dashboard-header { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white; }
    </style>
</head>
<body>

<div class="dashboard-header py-4 mb-4 shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">
        <h1><i class="fa-solid fa-server me-2"></i>Student Management Portal</h1>
        <span class="badge bg-light text-dark px-3 py-2 fw-bold shadow-sm rounded">
            <i class="fa-solid fa-database text-primary me-1"></i> System Online
        </span>
    </div>
</div>

<div class="container">
    <?php if (!empty($message)): ?>
        <div class="alert <?php echo $msgClass; ?> alert-dismissible fade show shadow-sm" role="alert">
            <i class="fa-solid fa-circle-info me-2"></i><?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <div class="card shadow-sm mb-4">
        <div class="card-body bg-white rounded">
            <form method="GET" action="index.php" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fw-bold small text-uppercase text-muted"><i class="fa-solid fa-magnifying-glass me-1"></i>Search by Student Name</label>
                    <input type="text" name="search_name" class="form-control" placeholder="Type student name..." value="<?php echo htmlspecialchars($searchName); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small text-uppercase text-muted"><i class="fa-solid fa-filter me-1"></i>Filter by Branch</label>
                    <select name="search_branch" class="form-select">
                        <option value="">-- View All Branches --</option>
                        <option value="Computer Science Engineering" <?php if($searchBranch == 'Computer Science Engineering') echo 'selected'; ?>>Computer Science Engineering</option>
                        <option value="Electronics Engineering" <?php if($searchBranch == 'Electronics Engineering') echo 'selected'; ?>>Electronics Engineering</option>
                        <option value="Mechanical Engineering" <?php if($searchBranch == 'Mechanical Engineering') echo 'selected'; ?>>Mechanical Engineering</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="fa-solid fa-sliders me-1"></i>Apply Filters</button>
                    <a href="index.php" class="btn btn-outline-secondary w-50 fw-semibold"><i class="fa-solid fa-arrow-rotate-left"></i></a>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="card-title mb-0 fw-bold"><i class="fa-solid fa-circle-plus me-2 text-success"></i>Enroll New Entry</h5>
                </div>
                <div class="card-body p-4">
                    <form id="enrollmentForm" method="POST" action="index.php" onsubmit="return validateEnrollmentForm()">
                        <input type="hidden" name="action" value="create">
                        
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Full Name</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="E.g., Priya Nair">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email Domain Address</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="priya@example.com">
                        </div>
                        <div class="mb-3">
                            <label for="branch" class="form-label fw-semibold">Branch Specialization</label>
                            <select class="form-select" name="branch" id="branch">
                                <option value="">-- Select Stream --</option>
                                <option value="Computer Science Engineering">Computer Science Engineering</option>
                                <option value="Electronics Engineering">Electronics Engineering</option>
                                <option value="Mechanical Engineering">Mechanical Engineering</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100 fw-bold py-2 mt-2">
                            <i class="fa-solid fa-floppy-disk me-2"></i>Commit Record Entry
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-list-columns me-2 text-primary"></i>Active Registry Records</h5>
                    <!-- Bonus Feature: Live Record Count Banner -->
                    <span class="badge bg-primary px-3 py-2 rounded-pill fw-bold">
                        <i class="fa-solid fa-user-graduate me-1"></i>Total Tracked: <?php echo $totalRecords; ?>
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="table-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-3">ID</th>
                                    <th>Student Context Name</th>
                                    <th>Email Link</th>
                                    <th>Branch Specialty</th>
                                    <th class="text-center pe-3">Record Management Action Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($totalRecords > 0): ?>
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td class="fw-bold ps-3 text-muted"><?php echo $row['id']; ?></td>
                                            <td class="fw-bold text-dark"><?php echo htmlspecialchars($row['student_name']); ?></td>
                                            <td class="text-lowercase text-muted"><?php echo htmlspecialchars($row['student_email']); ?></td>
                                            <td><span class="badge bg-light text-dark border px-2 py-1"><?php echo htmlspecialchars($row['student_branch']); ?></span></td>
                                            <td class="text-center pe-3">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="update.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-warning fw-semibold px-3">
                                                        <i class="fa-solid fa-marker me-1"></i>Edit
                                                    </a>
                                        
                                                    <a href="index.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-outline-danger fw-semibold px-3" onclick="return confirmDeletePrompt('<?php echo htmlspecialchars(addslashes($row['student_name'])); ?>')">
                                                        <i class="fa-solid fa-trash-can me-1"></i>Delete
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-5 fs-5">
                                            <i class="fa-solid fa-folder-open d-block mb-2 text-light-subtle fs-1"></i>No student entries match selection boundaries.
                                        </td>
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

<footer class="bg-dark text-white text-center py-3 mt-5">
    <small class="text-muted">&copy; 2026 Portal Matrix Engine Dashboard. System Sandbox V1.0</small>
</footer>
<script>
function validateEnrollmentForm() {
    var name = document.getElementById('name').value.trim();
    var email = document.getElementById('email').value.trim();
    var branch = document.getElementById('branch').value;

    if (name === "" || email === "" || branch === "") {
        alert("Validation Warning: Form processing rejected. All key variables fields must be populated.");
        return false;
    }
    return true;
}

function confirmDeletePrompt(studentName) {
    return confirm("Security Confirmation Call:\n\nAre you absolutely sure you want to completely drop the record files for '" + studentName + "' from active server matrices?");
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>