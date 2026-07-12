<?php
require_once 'functions.php';

$errors = array();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $name = isset($_POST['student_name']) ? trim($_POST['student_name']) : '';
    $email = isset($_POST['student_email']) ? trim($_POST['student_email']) : '';
    $cgpa = isset($_POST['student_cgpa']) ? trim($_POST['student_cgpa']) : '';
    $branch = isset($_POST['student_branch']) ? trim($_POST['student_branch']) : '';
    $college = isset($_POST['student_college']) ? trim($_POST['student_college']) : '';

    if (empty($name))   $errors[] = "Full Name variable is required.";
    if (empty($email))  $errors[] = "Email Address configuration path cannot be left empty.";
    if ($cgpa === "")   $errors[] = "CGPA score context must be provided.";
    if (empty($branch)) $errors[] = "Academic Branch designation must be explicitly selected.";
    if (empty($college))$errors[] = "College/Institution entry field is required.";

} else {
    header("Location: index.php");
    exit();
}
include 'header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">

        <?php 
        if (!empty($errors)): 
        ?>
            <div class="alert alert-danger shadow-sm border-start border-5 border-danger" role="alert">
                <h4 class="alert-heading fw-bold"><i class="fa-solid fa-triangle-exclamation me-2"></i>Sprint Validation Failed</h4>
                <p class="small">Every standard variable layer must be captured safely. Please check missing fields:</p>
                <hr>
                <ul class="mb-0 ps-3">
                    <?php foreach ($errors as $currentError): ?>
                        <li class="mb-1"><?php echo htmlspecialchars($currentError); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <div class="text-center mt-4">
                <button onclick="history.back()" class="btn btn-secondary fw-semibold"><i class="fa-solid fa-arrow-left me-2"></i>Return and Edit Form</button>
            </div>

        <?php 
        else: 
            $gradeMetric = calculateGrade((float)$cgpa);
        ?>
            
            <div class="text-center mb-4 animate-fade-in">
                <div class="display-6 fw-bold text-success"><i class="fa-solid fa-circle-check me-2"></i>Registration Confirmed!</div>
                <h4 class="text-muted mt-2">Welcome aboard, <span class="text-primary text-capitalize"><?php echo htmlspecialchars($name); ?></span>!</h4>
            </div>
            <div class="card shadow border-0 gradient-card">
                <div class="card-body p-4">
                    
                    <div class="row align-items-center">
                        <div class="col-sm-3 text-center mb-3 mb-sm-0 border-end border-light-subtle">
                            <div class="profile-avatar mb-2">
                                <i class="fa-solid fa-circle-user"></i>
                            </div>
                            <span class="badge bg-secondary px-3 py-2 rounded-pill">Verified Student</span>
                        </div>

                        <div class="col-sm-9 ps-sm-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h4 class="card-title fw-bold text-dark mb-0"><?php echo htmlspecialchars($name); ?></h4>
                                <small class="text-muted fw-semibold bg-white px-2 py-1 rounded shadow-sm border">
                                    <i class="fa-regular fa-calendar-days me-1"></i><?php echo date("Y-m-d"); ?>
                                </small>
        </div>
                            <div class="alert alert-<?php echo $gradeMetric['color']; ?> py-2 px-3 small border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
                                <i class="fa-solid fa-award me-2 fs-5"></i>
                                <div>
                                    Academic Evaluation Standing: <strong><?php echo $gradeMetric['status']; ?></strong> (Score: <?php echo htmlspecialchars($cgpa); ?>)
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <span class="text-muted d-block small">Registered Email</span>
                                    <strong class="text-dark text-break"><?php echo htmlspecialchars($email); ?></strong>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block small">Stream Branch</span>
                                    <strong class="text-dark"><?php echo htmlspecialchars($branch); ?></strong>
                                </div>
                                <div class="col-12">
                                    <span class="text-muted d-block small">Enrolled Institution Campus</span>
                                    <strong class="text-dark"><i class="fa-solid fa-university me-1 text-muted small"></i><?php echo htmlspecialchars($college); ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-footer bg-white border-top border-light-subtle text-end py-3">
                    <a href="index.php" class="btn btn-outline-primary btn-sm fw-semibold"><i class="fa-solid fa-rotate-left me-1"></i>New Entry</a>
                </div>
            </div>

        <?php endif; ?>

    </div>
</div>

<?php 
include 'footer.php'; 
?>