<?php
require_once 'db_connect.php';

$message = "";
$msgClass = "";
if (!is_dir('uploads')) {
    mkdir('uploads', 0777, true);
}
if (isset($_GET['delete_id'])) {
    $deleteId = (int)$_GET['delete_id'];
    $imgQuery = "SELECT student_photo FROM students WHERE id = ?";
    $stmt = mysqli_prepare($conn, $imgQuery);
    mysqli_stmt_bind_param($stmt, "i", $deleteId);
    mysqli_stmt_execute($stmt);
    $imgResult = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($imgResult)) {
        if ($row['student_photo'] !== 'default-avatar.png' && file_exists($row['student_photo'])) {
            unlink($row['student_photo']);
        }
    }
    mysqli_stmt_close($stmt);
    $deleteQuery = "DELETE FROM students WHERE id = ?";
    $stmt = mysqli_prepare($conn, $deleteQuery);
    mysqli_stmt_bind_param($stmt, "i", $deleteId);
    if (mysqli_stmt_execute($stmt)) {
        $message = "Student record and associated profile photo deleted successfully.";
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
    
    $photoName = 'default-avatar.png';

    if (empty($name) || empty($email) || empty($branch)) {
        $message = "Validation Error: All text input fields are strictly required.";
        $msgClass = "alert-danger";
    } else {
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $targetDir = "uploads/";
            $fileName = time() . "_" . basename($_FILES["photo"]["name"]);
            $targetFilePath = $targetDir . $fileName;
            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

            $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
            if (in_array($fileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
                    $photoName = $targetFilePath;
                } else {
                    $message = "Warning: Failed to move uploaded image file. Using default avatar.";
                }
            } else {
                $message = "Warning: Invalid image format type. Only JPG, JPEG, PNG, & GIF allowed.";
            }
        }

        $insertQuery = "INSERT INTO students (student_name, student_email, student_branch, student_photo) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $branch, $photoName);
        
        if (mysqli_stmt_execute($stmt)) {
            $message = "Success! New student profile archived neatly inside active registries.";
            $msgClass = "alert-success";
        } else {
            $message = "Database Insertion Fault: " . mysqli_error($conn);
            $msgClass = "alert-danger";
        }
        mysqli_stmt_close($stmt);
    }
}

$totalCountQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM students");
$totalData = mysqli_fetch_assoc($totalCountQuery);
$statTotal = $totalData['total'];

$cseCountQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM students WHERE student_branch='Computer Science Engineering'");
$cseData = mysqli_fetch_assoc($cseCountQuery);
$statCse = $cseData['total'];

$otherCountQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM students WHERE student_branch!='Computer Science Engineering'");
$otherData = mysqli_fetch_assoc($otherCountQuery);
$statOther = $otherData['total'];

$searchQuery = isset($_GET['search_query']) ? trim($_GET['search_query']) : '';
$searchBranch = isset($_GET['search_branch']) ? trim($_GET['search_branch']) : '';

$selectQuery = "SELECT * FROM students WHERE 1=1";

if ($searchQuery !== '') {
    $escapedQuery = mysqli_real_escape_string($conn, $searchQuery);
    // Filters match safely by either name string OR email criteria context bounds
    $selectQuery .= " AND (student_name LIKE '%$escapedQuery%' OR student_email LIKE '%$escapedQuery%')";
}
if ($searchBranch !== '') {
    $escapedBranch = mysqli_real_escape_string($conn, $searchBranch);
    $selectQuery .= " AND student_branch = '$escapedBranch'";
}

$selectQuery .= " ORDER BY id DESC";
$result = mysqli_query($conn, $selectQuery);
$filteredRecords = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Student Management System</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .navbar-brand-gradient { font-weight: 800; color: #ffffff !important; }
        .stat-card { border: none; border-radius: 12px; transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-3px); }
        .table-profile-img { width: 45px; height: 45px; object-fit: cover; border-radius: 50%; border: 2px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .fallback-avatar { width: 45px; height: 45px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; background-color: #e9ecef; color: #6c757d; font-size: 1.2rem; border: 2px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm py-3">
    <div class="container">
        <a class="navbar-brand navbar-brand-gradient" href="index.php">
            <i class="fa-solid fa-graduation-cap text-info me-2"></i>STUDENT MANAGEMENT PORTAL
        </a>
        <div class="text-white small fw-semibold">System Dashboard Matrix v2.0</div>
    </div>
</nav>

<div class="container my-4">

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card stat-card bg-primary text-white shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center p-4">
                    <div>
                        <h6 class="text-uppercase opacity-75 small fw-bold mb-1">Total Enrolled</h6>
                        <h2 class="display-6 fw-bold mb-0"><?php echo $statTotal; ?></h2>
                    </div>
                    <i class="fa-solid fa-users fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card bg-success text-white shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center p-4">
                    <div>
                        <h6 class="text-uppercase opacity-75 small fw-bold mb-1">CSE Department</h6>
                        <h2 class="display-6 fw-bold mb-0"><?php echo $statCse; ?></h2>
                    </div>
                    <i class="fa-solid fa-laptop-code fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card bg-info text-white shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center p-4">
                    <div>
                        <h6 class="text-uppercase opacity-75 small fw-bold mb-1">Other Engineering Branches</h6>
                        <h2 class="display-6 fw-bold mb-0"><?php echo $statOther; ?></h2>
                    </div>
                    <i class="fa-solid fa-gears fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <?php if (!empty($message)): ?>
        <div class="alert <?php echo $msgClass; ?> alert-dismissible fade show shadow-sm" role="alert">
            <i class="fa-solid fa-circle-info me-2"></i><?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body bg-white rounded">
            <form method="GET" action="index.php" class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label class="form-label fw-bold small text-muted"><i class="fa-solid fa-magnifying-glass me-1"></i>Search Student Name or Email</label>
                    <input type="text" name="search_query" class="form-control" placeholder="Search keywords..." value="<?php echo htmlspecialchars($searchQuery); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small text-muted"><i class="fa-solid fa-filter me-1"></i>Branch Category</label>
                    <select name="search_branch" class="form-select">
                        <option value="">-- View All Streams --</option>
                        <option value="Computer Science Engineering" <?php if($searchBranch == 'Computer Science Engineering') echo 'selected'; ?>>Computer Science Engineering</option>
                        <option value="Electronics Engineering" <?php if($searchBranch == 'Electronics Engineering') echo 'selected'; ?>>Electronics Engineering</option>
                        <option value="Mechanical Engineering" <?php if($searchBranch == 'Mechanical Engineering') echo 'selected'; ?>>Mechanical Engineering</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark w-100 fw-bold py-2"><i class="fa-solid fa-filter me-2"></i>Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="card-title mb-0 fw-bold"><i class="fa-solid fa-user-plus text-info me-2"></i>Add Student Profile</h5>
                </div>
                <div class="card-body p-4 bg-white">
                    <form id="studentEnrollmentForm" method="POST" action="index.php" enctype="multipart/form-data" onsubmit="return validateClientInputFields()">
                        <input type="hidden" name="action" value="create">
                        
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Full Name</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="John Doe">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email Domain</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="john@example.com">
                        </div>
                        <div class="mb-3">
                            <label for="branch" class="form-label fw-semibold">Stream Specialization</label>
                            <select class="form-select" name="branch" id="branch">
                                <option value="">-- Select Department --</option>
                                <option value="Computer Science Engineering">Computer Science Engineering</option>
                                <option value="Electronics Engineering">Electronics Engineering</option>
                                <option value="Mechanical Engineering">Mechanical Engineering</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="photo" class="form-label fw-semibold">Upload Profile Photo</label>
                            <input type="file" class="form-control" name="photo" id="photo" accept="image/*">
                            <div class="form-text text-muted small">Supports safe web imagery files (JPG, PNG, GIF).</div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2 mt-2">
                            <i class="fa-solid fa-cloud-arrow-up me-2"></i>Save Student File
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-address-book text-muted me-2"></i>System Registry Datatable</h5>
                    <span class="badge bg-secondary px-3 py-2 fw-semibold">Results Found: <?php echo $filteredRecords; ?></span>
                </div>
                <div class="card-body p-0 bg-white">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-3">Avatar</th>
                                    <th>Student Description</th>
                                    <th>Academic Specialty</th>
                                    <th class="text-center pe-3">Record Maintenance Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($filteredRecords > 0): ?>
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                        
                                            <td class="ps-3">
                                                <?php if ($row['student_photo'] !== 'default-avatar.png' && file_exists($row['student_photo'])): ?>
                                                    <img src="<?php echo htmlspecialchars($row['student_photo']); ?>" alt="Student Photo" class="table-profile-img">
                                                <?php else: ?>
                                                    <div class="fallback-avatar"><i class="fa-solid fa-user"></i></div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($row['student_name']); ?></div>
                                                <small class="text-muted d-block text-lowercase"><?php echo htmlspecialchars($row['student_email']); ?></small>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border px-2 py-1"><?php echo htmlspecialchars($row['student_branch']); ?></span>
                                            </td>
                                            <td class="text-center pe-3">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="update.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-secondary px-3">
                                                        <i class="fa-solid fa-pen-to-square text-warning me-1"></i>Edit
                                                    </a>
                                                    <a href="index.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-outline-secondary px-3" onclick="return confirmDeletePrompt('<?php echo htmlspecialchars(addslashes($row['student_name'])); ?>')">
                                                        <i class="fa-solid fa-trash text-danger me-1"></i>Drop
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-5">
                                            <i class="fa-regular fa-folder-closed d-block mb-2 text-light-subtle fs-1"></i>No student files found matching search bounds.
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

<footer class="bg-dark text-white text-center py-4 mt-5">
    <div class="container small text-muted">
        <p class="mb-1">&copy; 2026 Student Registry Core Infrastructure Dashboard.</p>
        <span>Demo Evaluation System Layer &bull; Project Review Checked</span>
    </div>
</footer>

<script>
function validateClientInputFields() {
    var name = document.getElementById('name').value.trim();
    var email = document.getElementById('email').value.trim();
    var branch = document.getElementById('branch').value;

    if (name === "" || email === "" || branch === "") {
        alert("System Notice: Submission aborted. Ensure Name, Email, and Branch layers are completed.");
        return false;
    }
    return true;
}

function confirmDeletePrompt(targetName) {
    return confirm("Security Check Call:\n\nAre you sure you want to completely erase the record file history for '" + targetName + "' from active local registries?");
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>