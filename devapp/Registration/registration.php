<?php
include ('../config/db_connect.php');
$today = date("Y-m-d");
$errors = [];
$firstName = $lastName = $dob = $email = $password = $username = $number = "";

if (($_SERVER['REQUEST_METHOD']) !== 'POST') {
    header('Location:index.php');
}

//validation checks for firstname input
if (!isset($_POST['firstname']) || empty($_POST['firstname'])) {
    $errors['firstname'] = "Firstname is required";
} else {
    $firstName = $_POST['firstname'];
}

//validation checks for lastname input
if (!isset($_POST['lastname']) || empty($_POST['lastname'])) {
    $errors['lastname'] = "Lastname is required";
} else {
    $lastName = $_POST['lastname'];
}

//validation checks for dob input
if (!isset($_POST['dob']) || empty($_POST['dob'])) {
    $errors['dob'] = "Date of birth is required";
} else if (strtotime($_POST['dob']) > strtotime($today)) {
    $errors['dob'] = "Pick a valid date";
} else {
    $dob = $_POST['dob'];
}

//validation checks for email input
if (!isset($_POST['email']) || empty($_POST['email'])) {
    $errors['email'] = "Email is required";
} else {
    $email = $_POST['email'];
    $stmt = $conn->prepare("SELECT email FROM registration where email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $data = $stmt->fetch();
    if ($data['email'] == $email) {
        $errors['email'] = "Email already exists, use another";
    }
}

//validation checks for password input

if (!isset($_POST['password']) || empty($_POST['password']) || !isset($_POST['confirmpassword']) || empty($_POST['confirmpassword'])) {
    $errors['password'] = "Password is required";
} else if (strlen($_POST['password']) < 8){
    $errors['password'] = "Password must be at least 8 characters";
} else if($_POST['password'] != $_POST['confirmpassword']){
    $errors['password'] = "Passwords need to match";
} else {
    $password = $_POST['password'];
}

//validation checks for username input
if (!isset($_POST['username']) || empty($_POST['username'])) {
    $errors['username'] = "Username is required";
} else if (strlen($_POST['username']) < 8){
    $errors['username'] = "Username must be at least 8 characters";
} else {
    $username = $_POST['username'];
    $stmt = $conn->prepare("SELECT username FROM registration WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $data = $stmt->fetch();
    if ($data['username'] == $username) { // #2
        $errors['username'] = "Username already exists, use another";
    }
}

//validation checks for number input
if (!isset($_POST['number']) || empty($_POST['number'])) {
    $errors['number'] = "Number is required";
} else {
    $number = $_POST['number'];
}
$data = [":firstname" => $firstName, ":lastname" => $lastName, ":dob" => $dob, ":email" => $email, ":password" => password_hash($password, PASSWORD_DEFAULT), ":username" => $username, ":number" => $number];

//entering data into database
if (count($errors) > 0) {
    var_dump($errors);
    die();
} else {
    $sql = 'INSERT INTO registration (firstname, lastname, dob, email, password, username, number) VALUES (:firstname,:lastname,:dob,:email,:password,:username,:number)';
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}
?>