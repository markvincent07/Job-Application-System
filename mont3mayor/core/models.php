<?php  

require_once 'dbConfig.php';

function checkIfUserExists($pdo, $username) {
	$response = array();
	$sql = "SELECT * FROM user_accounts WHERE username = ?";
	$stmt = $pdo->prepare($sql);

	if ($stmt->execute([$username])) {

		$userInfoArray = $stmt->fetch();

		if ($stmt->rowCount() > 0) {
			$response = array(
				"result"=> true,
				"status" => "200",
				"userInfoArray" => $userInfoArray
			);
		}

		else {
			$response = array(
				"result"=> false,
				"status" => "400",
				"message"=> "User doesn't exist from the database"
			);
		}
	}

	return $response;

}

function insertNewUser($pdo, $username, $first_name, $last_name, $password) {
	$response = array();
	$checkIfUserExists = checkIfUserExists($pdo, $username); 

	if (!$checkIfUserExists['result']) {

		$sql = "INSERT INTO user_accounts (username, first_name, last_name, password) 
		VALUES (?,?,?,?)";

		$stmt = $pdo->prepare($sql);

		if ($stmt->execute([$username, $first_name, $last_name, $password])) {
			$response = array(
				"status" => "200",
				"message" => "User successfully inserted!"
			);
		}

		else {
			$response = array(
				"status" => "400",
				"message" => "An error occured with the query!"
			);
		}
	}

	else {
		$response = array(
			"status" => "400",
			"message" => "User already exists!"
		);
	}

	return $response;
}

function getAllUsers($pdo) {
	$sql = "SELECT * FROM user_accounts";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();

	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getAllApplicants($pdo) {
	$sql = "SELECT * FROM applicants";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();

	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getAllApplicantsBySearch($pdo, $search_query) {
	$sql = "SELECT * FROM applicants WHERE 
			CONCAT(first_name,last_name,
			email,years_of_experience,
			specialization,favorite_programming_language,
			date_added,added_by,
			last_updated) 
			LIKE ?";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute(["%".$search_query."%"]);
	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getApplicantByID($pdo, $applicants_id) {
	$sql = "SELECT * from applicants WHERE applicants_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$applicants_id]);

	if ($executeQuery) {
		return $stmt->fetch();
	}
}

function insertAnActivityLog($pdo, $operation, $applicants_id, $first_name, $last_name, $email, $years_of_experience, $specialization, $favorite_programming_language,
							 $username) {

	$sql = "INSERT INTO activity_logs (operation, applicants_id, first_name, last_name, email, years_of_experience, specialization, favorite_programming_language, username) 
	VALUES(?,?,?,?,?,?,?,?,?)";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$operation, $applicants_id, $first_name, $last_name, $email, $years_of_experience, $specialization, $favorite_programming_language, $username]);

	if ($executeQuery) {
		return true;
	}

}

function getAllActivityLogs($pdo) {
	$sql = "SELECT * FROM activity_logs 
			ORDER BY date_added DESC";
	$stmt = $pdo->prepare($sql);
	if ($stmt->execute()) {
		return $stmt->fetchAll();
	}
}

function insertApplicant($pdo, $first_name, $last_name, $email, $years_of_experience, $specialization, $favorite_programming_language, $added_by) {
	$response = array();
	$sql = "INSERT INTO applicants (first_name,
				last_name,
				email,
				years_of_experience,
				specialization,
				favorite_programming_language, added_by) VALUES(?,?,?,?,?,?,?)";
	$stmt = $pdo->prepare($sql);
	$insertApplicant = $stmt->execute([$first_name, $last_name, $email, $years_of_experience, $specialization, $favorite_programming_language, $added_by]);

	if ($insertApplicant) {
		$findInsertedItemSQL = "SELECT * FROM applicants ORDER BY date_added DESC LIMIT 1";
		$stmtfindInsertedItemSQL = $pdo->prepare($findInsertedItemSQL);
		$stmtfindInsertedItemSQL->execute();
		$getApplicantID = $stmtfindInsertedItemSQL->fetch();

		$insertAnActivityLog = insertAnActivityLog($pdo, "INSERT", $getApplicantID['applicants_id'], 
			$getApplicantID['first_name'], $getApplicantID['last_name'], 
			$getApplicantID['email'], $getApplicantID['years_of_experience'], $getApplicantID['specialization'], $getApplicantID['favorite_programming_language'], 
			$_SESSION['username']);

		if ($insertAnActivityLog) {
			$response = array(
				"status" =>"200",
				"message"=>"Applicant added successfully!"
			);
		}

		else {
			$response = array(
				"status" =>"400",
				"message"=>"Insertion of activity log failed!"
			);
		}
		
	}

	else {
		$response = array(
			"status" =>"400",
			"message"=>"Insertion of data failed!"
		);

	}

	return $response;
}

function updateApplicant($pdo, $first_name, $last_name, $email, $years_of_experience, $specialization, $favorite_programming_language, 
	$last_updated, $last_updated_by, $applicants_id) {

	$response = array();
	$sql = "UPDATE applicants
			SET first_name = ?,
				last_name = ?,
				email = ?,
				years_of_experience = ?,
				specialization = ?,
				favorite_programming_language = ?,
				last_updated = ?, 
				last_updated_by = ? 
			WHERE applicants_id = ?
			";
	$stmt = $pdo->prepare($sql);
	$updateApplicant = $stmt->execute([$first_name, $last_name, $email, $years_of_experience, $specialization, $favorite_programming_language, 
	$last_updated, $last_updated_by, $applicants_id]);

	if ($updateApplicant) {

		$findInsertedItemSQL = "SELECT * FROM applicants WHERE applicants_id = ?";
		$stmtfindInsertedItemSQL = $pdo->prepare($findInsertedItemSQL);
		$stmtfindInsertedItemSQL->execute([$applicants_id]);
		$getApplicantID = $stmtfindInsertedItemSQL->fetch(); 

		$insertAnActivityLog = insertAnActivityLog($pdo, "UPDATE", $getApplicantID['applicants_id'], 
			$getApplicantID['first_name'], $getApplicantID['last_name'], 
			$getApplicantID['email'], $getApplicantID['years_of_experience'], $getApplicantID['specialization'], $getApplicantID['favorite_programming_language'], $_SESSION['username']);

		if ($insertAnActivityLog) {

			$response = array(
				"status" =>"200",
				"message"=>"Updated the applicant successfully!"
			);
		}

		else {
			$response = array(
				"status" =>"400",
				"message"=>"Insertion of activity log failed!"
			);
		}

	}

	else {
		$response = array(
			"status" =>"400",
			"message"=>"An error has occured with the query!"
		);
	}

	return $response;

}

function deleteApplicant($pdo, $applicants_id) {
	$response = array();
	$sql = "SELECT * FROM applicants WHERE applicants_id = ?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$applicants_id]);
	$getApplicantByID = $stmt->fetch();

	$insertAnActivityLog = insertAnActivityLog($pdo, "DELETE", $getApplicantByID['applicants_id'], 
		$getApplicantByID['first_name'], $getApplicantByID['last_name'], 
		$getApplicantByID['email'], $getApplicantByID['years_of_experience'], $getApplicantByID['specialization'], $getApplicantByID['favorite_programming_language'],  $_SESSION['username']);

	if ($insertAnActivityLog) {
		$deleteSql = "DELETE FROM applicants WHERE applicants_id = ?";
		$deleteStmt = $pdo->prepare($deleteSql);
		$deleteQuery = $deleteStmt->execute([$applicants_id]);

		if ($deleteQuery) {
			$response = array(
				"status" =>"200",
				"message"=>"Deleted the applicant successfully!"
			);
		}
		else {
			$response = array(
				"status" =>"400",
				"message"=>"Insertion of activity log failed!"
			);
		}
	}
	else {
		$response = array(
			"status" =>"400",
			"message"=>"An error has occured with the query!"
		);
	}

	return $response;
}



function deleteUser($pdo, $id) {
	$sql = "DELETE FROM applicants 
			WHERE id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$id]);

	if ($executeQuery) {
		$response = array(
			"status" => "200",
			"message" => "User successfully deleted!"
		);
	}
	else {
		$response = array(
			"status" => "400",
			"message" => "An error occured with the query!"
		);
	}
	
return $response;
}




?>