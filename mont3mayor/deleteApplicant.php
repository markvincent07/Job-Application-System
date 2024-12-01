<?php 
require_once 'core/models.php'; 
require_once 'core/dbConfig.php';
 
if (!isset($_SESSION['username'])) {
	header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Delete Applicant</title>
	<link rel="stylesheet" href="css/deleteApplicant.css">
</head>
<body>
	<h1>Are you sure you want to delete this applicant?</h1>
	<?php $getApplicantByID = getApplicantByID($pdo, $_GET['applicants_id']); ?>
	<div class="container">
    <h2>First Name: <?php echo $getApplicantByID['first_name']; ?></h2>
    <h2>Last Name: <?php echo $getApplicantByID['last_name']; ?></h2>
    <h2>Email: <?php echo $getApplicantByID['email']; ?></h2>
    <h2>Years of Experience: <?php echo $getApplicantByID['years_of_experience']; ?></h2>
    <h2>Specialization: <?php echo $getApplicantByID['specialization']; ?></h2>
    <h2>Favorite Programming Language: <?php echo $getApplicantByID['favorite_programming_language']; ?></h2>

    <div class="deleteBtn">
        <form action="core/handleForms.php?applicants_id=<?php echo $_GET['applicants_id']; ?>" method="POST">
            <input type="submit" name="deleteApplicantBtn" value="Delete">
        </form>
    </div>
</div>
</body>
</html>