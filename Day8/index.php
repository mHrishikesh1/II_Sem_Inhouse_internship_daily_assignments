<?php include 'header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fa-solid fa-pen-to-square me-2"></i>Student Entry Form</h5>
            </div>
            <div class="card-body p-4">
                <p class="text-muted small">Please input all structural details below. Global POST checks will trigger error parsing arrays on processing.</p>
                <hr>

                <form action="process.php" method="POST">
                
                    <div class="mb-3">
                        <label for="student_name" class="form-label fw-semibold">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                            <input type="text" class="form-control" name="student_name" id="student_name" placeholder="John Doe">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="student_email" class="form-label fw-semibold">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" class="form-control" name="student_email" id="student_email" placeholder="john@example.com">
                        </div>
</div>
                    <div class="mb-3">
                        <label for="student_cgpa" class="form-label fw-semibold">CGPA Metric Score</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-chart-simple"></i></span>
                            <input type="number" step="0.01" min="0" max="10" class="form-control" name="student_cgpa" id="student_cgpa" placeholder="0.00 to 10.00">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="student_branch" class="form-label fw-semibold">Branch / Stream</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-code-branch"></i></span>
                            <select class="form-select" name="student_branch" id="student_branch">
                                <option value="">-- Choose Branch --</option>
                                <option value="Computer Science Engineering">Computer Science Engineering</option>
                                <option value="Electronics Engineering">Electronics Engineering</option>
                                <option value="Mechanical Engineering">Mechanical Engineering</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="student_college" class="form-label fw-semibold">College / Campus</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-building-columns"></i></span>
                            <input type="text" class="form-control" name="student_college" id="student_college" placeholder="Enter institution name">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold mt-3">
                        <i class="fa-solid fa-paper-plane me-2"></i>Process Registration
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>