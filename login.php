<?php // обработчик формы авторизации
session_start();
require_once('functions.php');

$email = $_POST['email'];
$password = $_POST['password'];

// вызов функции логирования
login($email, $password);
