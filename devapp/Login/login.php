<?php
include ('../config/db_connect.php');
session_start();
$errors = [];

if (($_SERVER['REQUEST_METHOD']) !== 'POST') {
    header('Location:index.php');
}

if (!isset($_POST['username']) || empty($_POST['username'])) {
    $errors['username'] = "Username is required";
} else {
    $username = $_POST['username'];
}

if (!isset($_POST['password']) || empty($_POST['password'])) {
    $errors['password'] = "Password is required";
}
else {
    $password = $_POST['password'];
}

$query = $conn->prepare("SELECT username,password FROM registration WHERE username = :username");
$query->bindParam(":username", $username);
$query->execute();

$result = $query->fetch(PDO::FETCH_ASSOC);

if (!$result) {
    $errors['login'] = "Username/Password combinations not found t";
} else {
    echo password_hash($password, PASSWORD_DEFAULT) . '<br/>' . $result['password'] .'<br>';
    if (password_verify($password, $result['password'])) {
        $_SESSION['username'] = $result['username'];
        header("location:../welcome/index.php");
        echo "success";
    } else {
        $errors['login'] = "Username/Password combinations not found";
    }
}


if (count($errors) > 0){
    var_dump($errors);
    die();
}

?>
