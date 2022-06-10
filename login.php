<?php // обработчик формы авторизации
session_start();
require_once('functions.php');

$email = $_POST['email'];
$password = $_POST['password'];

// вызов функции логирования
$status_login = login($email, $password);

// обрабатываем результат логирования
if ($status_login) {
    // если проверки пройдены, записывем в сессию массив (данные пользователя)
    $user = get_user_by_email($email);
    $_SESSION['user']=['email'=>$user['email'],'id'=>$user['id'],'role'=>$user['role']];
    redirect_to ('users.php');
} else {
    //  передаём ошибку
    set_flash_massage("error", "не верный логин или пароль");
    // возращаемся к форме
    redirect_to ('page_login.php');
}
