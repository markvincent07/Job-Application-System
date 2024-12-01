<?php  
require_once 'core/models.php'; 
require_once 'core/handleForms.php'; 

if (!isset($_SESSION['username'])) {
	header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Insert Applicant</title>
	<link rel="stylesheet" href="css/insertApplicant.css">
</head>
<body>
	<?php include 'navbar.php'; ?>

	<form action="core/handleForms.php" method="POST">
			<p>
				<label for="firstName">First Name</label> 
				<input type="text" name="first_name">
			</p>
			<p>
				<label for="lastName">Last Name</label> 
				<input type="text" name="last_name">
			</p>
			<p>
				<label for="email">Email</label> 
				<input type="text" name="email">
			</p>
			<p>
				<label for="yearsOfExperience">Years of Experience</label> 
				<input type="number" name="years_of_experience">
			</p>
			<p>
				<label for="specialization">Specialization</label> 
				<input type="text" name="specialization">
			</p>
			<p>
				<label for="favoriteProgrammingLanguage">Favorite Programming Language</label> 
				<input type="text" name="favorite_programming_language">
				<input type="submit" name="insertApplicantBtn" value="Create">
		</p>
	</form>

	<div class="tableClass">
		<table style="width: 100%;" cellpadding="20">
			<tr>
				<th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Years of Experience</th>
                <th>Specialization</th>
                <th>Favorite Programming Language</th>
				<th>Date Added</th>
				<th>Added By</th>
				<th>Last Updated</th>
				<th>Last Updated By</th>
				<th>Action</th>
			</tr>
			<?php if (!isset($_GET['searchBtn'])) { ?>
				<?php $getAllApplicants = getAllApplicants($pdo); ?>
				<?php foreach ($getAllApplicants as $row) { ?>
				<tr>
					<td><?php echo $row['first_name']; ?></td>
					<td><?php echo $row['last_name']; ?></td>
					<td><?php echo $row['email']; ?></td>
					<td><?php echo $row['years_of_experience']; ?></td>
					<td><?php echo $row['specialization']; ?></td>
					<td><?php echo $row['favorite_programming_language']; ?></td>
					<td><?php echo $row['date_added']; ?></td>
					<td><?php echo $row['added_by']; ?></td>
					<td><?php echo $row['last_updated']; ?></td>
					<td><?php echo $row['last_updated_by']; ?></td>
					<td>
						<a href="editApplicant.php?applicants_id=<?php echo $row['applicants_id']; ?>">Update</a>
					</td>
				</tr>
				<?php } ?>
			<?php } else { ?>
				<?php $getAllApplicantsBySearch = getAllApplicantsBySearch($pdo, $_GET['searchQuery']); ?>
				<?php foreach ($getAllApplicantsBySearch as $row) { ?>
				<tr>
					<td><?php echo $row['first_name']; ?></td>
					<td><?php echo $row['last_name']; ?></td>
					<td><?php echo $row['email']; ?></td>
					<td><?php echo $row['years_of_experience']; ?></td>
					<td><?php echo $row['specialization']; ?></td>
					<td><?php echo $row['favorite_programming_language']; ?></td>
					<td><?php echo $row['date_added']; ?></td>
					<td><?php echo $row['added_by']; ?></td>
					<td><?php echo $row['last_updated']; ?></td>
					<td><?php echo $row['last_updated_by']; ?></td>
					<td>
						<a href="editApplicant.php?applicants_id=<?php echo $row['applicants_id']; ?>">Update</a>
					</td>
				</tr>
				<?php } ?>
			<?php } ?>
		</table>
	</div>

</body>
</html>