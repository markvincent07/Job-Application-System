<?php  

require_once 'dbConfig.php';
require_once 'models.php';

if (isset($_POST['insertNewUserBtn'])) {
	$username = trim($_POST['username']);
	$first_name = trim($_POST['first_name']);
	$last_name = trim($_POST['last_name']);
	$password = trim($_POST['password']);
	$confirm_password = trim($_POST['confirm_password']);

	if (!empty($username) && !empty($first_name) && !empty($last_name) && !empty($password) && !empty($confirm_password)) {

		if ($password == $confirm_password) {

			$insertQuery = insertNewUser($pdo, $username, $first_name, $last_name, password_hash($password, PASSWORD_DEFAULT));
			$_SESSION['message'] = $insertQuery['message'];

			if ($insertQuery['status'] == '200') {
				$_SESSION['message'] = $insertQuery['message'];
				$_SESSION['status'] = $insertQuery['status'];
				header("Location: ../login.php");
			}

			else {
				$_SESSION['message'] = $insertQuery['message'];
				$_SESSION['status'] = $insertQuery['status'];
				header("Location: ../register.php");
			}

		}
		else {
			$_SESSION['message'] = "Please make sure both passwords are equal";
			$_SESSION['status'] = '400';
			header("Location: ../register.php");
		}

	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
	}
}

if (isset($_POST['loginUserBtn'])) {
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);

	if (!empty($username) && !empty($password)) {

		$loginQuery = checkIfUserExists($pdo, $username);
		$userIDFromDB = $loginQuery['userInfoArray']['user_id'];
		$usernameFromDB = $loginQuery['userInfoArray']['username'];
		$passwordFromDB = $loginQuery['userInfoArray']['password'];

		if (password_verify($password, $passwordFromDB)) {
			$_SESSION['user_id'] = $userIDFromDB;
			$_SESSION['username'] = $usernameFromDB;
			header("Location: ../index.php");
		}

		else {
			$_SESSION['message'] = "Username/password invalid";
			$_SESSION['status'] = "400";
			header("Location: ../login.php");
		}
	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
	}

}

if (isset($_POST['insertApplicantBtn'])) {
	$first_name = trim($_POST['first_name']);
	$last_name = trim($_POST['last_name']);
	$email = trim($_POST['email']);
    $years_of_experience = trim($_POST['years_of_experience']);
	$specialization = trim($_POST['specialization']);
	$favorite_programming_language = trim($_POST['favorite_programming_language']);

	if (!empty($first_name) && !empty($last_name) && !empty($email) && !empty($years_of_experience) && !empty($specialization) ){
		$insertApplicant = insertApplicant($pdo, $first_name, $last_name, $email, $years_of_experience, $specialization, $favorite_programming_language, $_SESSION['username']);
		$_SESSION['status'] =  $insertApplicant['status']; 
		$_SESSION['message'] =  $insertApplicant['message']; 
		header("Location: ../index.php");
	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../index.php");
	}

}


if (isset($_POST['updateApplicantBtn'])) {

	$first_name = trim($_POST['first_name']);
	$last_name = trim($_POST['last_name']);
	$email = trim($_POST['email']);
    $years_of_experience = trim($_POST['years_of_experience']);
	$specialization = trim($_POST['specialization']);
	$favorite_programming_language = trim($_POST['favorite_programming_language']);
	$date = date('Y-m-d H:i:s');

	if (!empty($first_name) && !empty($last_name) && !empty($email) && !empty($years_of_experience) && !empty($specialization) ){

		$updateApplicant = updateApplicant($pdo, $first_name, $last_name, $email, $years_of_experience, $specialization, $favorite_programming_language, $date, $_SESSION['username'], $_GET['applicants_id']);

		$_SESSION['message'] = $updateApplicant['message'];
		$_SESSION['status'] = $updateApplicant['status'];
		header("Location: ../index.php");
	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
	}

}

if (isset($_POST['deleteApplicantBtn'])) {
	$applicants_id = $_GET['applicants_id'];

	if (!empty($applicants_id)) {
		$deleteApplicant = deleteApplicant($pdo, $applicants_id);
		$_SESSION['message'] = $deleteApplicant['message'];
		$_SESSION['status'] = $deleteApplicant['status'];
		header("Location: ../index.php");
	}
}


if (isset($_GET['logoutUserBtn'])) {
	unset($_SESSION['username']);
	header("Location: ../login.php");
}

?>