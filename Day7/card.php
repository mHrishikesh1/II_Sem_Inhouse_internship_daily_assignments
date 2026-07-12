<?php
$studentName = "Hrishikesh Medhi"; 
$currentDate = date("Y-m-d"); 
$favLanguage = "C++"; 
$collegeName = "SKIT"; 
$branchName = "Computer Science Engineering"; 
$yearOfStudy = "2st Year (Undergraduate)"; 
$shortBio = "Dedicated to mastering programming, engineering core subjects, and building scalable business systems."; 
$profilePhoto = "https://via.placeholder.com/150";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .id-card {
            max-width: 450px;
            border-top: 5px solid #0d6efd;
            border-radius: 10px;
        }
        .profile-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border: 3px solid #0d6efd;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <h2 class="text-center mb-4">Academic Profile Presentation</h2>
    

    <div class="card id-card mx-auto shadow-sm">
        <div class="card-body text-center">
            <img src="<?php echo $profilePhoto; ?>" alt="Profile Picture" class="rounded-circle profile-img mb-3">
            <h3 class="card-title fw-bold mb-1"><?php echo $studentName; ?></h3>
            <p class="text-muted mb-3"><?php echo $branchName; ?> | <?php echo $yearOfStudy; ?></p>
            
            <ul class="list-group list-group-flush text-start mb-3">
                <li class="list-group-item"><strong>Institution:</strong> <?php echo $collegeName; ?></li>
                <li class="list-group-item"><strong>Favorite Language:</strong> <?php echo $favLanguage; ?></li>
                <li class="list-group-item"><strong>System Date:</strong> <?php echo $currentDate; ?></li>
            </ul>
            
            <div class="bg-light p-3 rounded border">
                <h6 class="fw-bold text-start mb-1">Biography</h6>
                <p class="card-text text-muted small text-start mb-0"><?php echo $shortBio; ?></p>
            </div>
            
        </div>
        <div class="card-footer text-center bg-dark text-white py-2">
            <small>Student Identification System</small>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>