<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal - Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="card-title mb-0 fw-bold">Portal Registration Form</h4>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small">Please fill out all the fields below. Verification scripts will parse metrics on submission.</p>
                    <hr>
                    
                    <form action="process.php" method="POST">
                        
                        <div class="mb-3">
                            <label for="student_name" class="form-label fw-semibold">Full Name</label>
                            <input type="text" class="form-control" name="student_name" id="student_name" placeholder="Enter full name">
                        </div>
                        
                        <div class="mb-3">
                            <label for="student_email" class="form-label fw-semibold">Email Address</label>
                            <input type="text" class="form-control" name="student_email" id="student_email" placeholder="name@domain.com">
                        </div>
                        
                        <div class="mb-3">
                            <label for="student_branch" class="form-label fw-semibold">Branch / Specialization</label>
                            <select class="form-select" name="student_branch" id="student_branch">
                                <option value="">Choose Branch</option>
                                <option value="Computer Science Engineering">Computer Science Engineering</option>
                                <option value="Electronics Engineering">Electronics Engineering</option>
                                <option value="Mechanical Engineering">Mechanical Engineering</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="student_phone" class="form-label fw-semibold">Phone Number</label>
                            <input type="text" class="form-control" name="student_phone" id="student_phone" placeholder="10-digit mobile number">
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100 fw-bold mt-2">Submit Registration</button>
                    </form>
                    
                </div>
            </div>
            <div class="text-center mt-3">
                <a href="id_card.php" class="text-decoration-none small">&larr; View Identity Card Dashboard</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>