<?php // обработчик формы авторизации
session_start();
require_once('functions.php');

$email = $_POST['email'];
$password = $_POST['password'];


function login($email, $password){
    // проверка есть ли в базе емаил
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=my_php;charset=utf8", "root", "");
    // проверка есть ли в базе емаил
    // запрос, с меткой на которую передадим переменную
    $query = "SELECT * FROM `users-dive` WHERE email=:email";
    // нужно подготовить запрос, для безопасной отправки в бд
    $statement = $pdo->prepare($query);
    // в запросе, на метку передаём переменную и выполняем его
    $statement -> execute(['email' => $email]);
    // $user - может содержать запись из таблицы
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    // проверяем пароль
    // хэшировынный пароль из базы
    $hash_password=$user['password'];
    // проверяем соответствует-ли введеный пароль, хешу пароля из бд
    // результат записываем в $verify_password
    $verify_password=password_verify($password, $hash_password);

    // если $user содержит, и $verify_password = true, значит проверка проедена
    if (!empty($user) && $verify_password) {
        $status_login = true;
    } else {
        $status_login = false;
    }

    return $status_login;
}

$status_login = login($email, $password);

if ($status_login) {
    // если проверки пройдены, записывем в сессию массив (данные пользователя)
    $_SESSION['user']=['email'=>$user['email'],'id'=>$user['id']];
    redirect_to ('users.php');
} else {
    //  передаём ошибку
    set_flash_massage("error", "не верный логин или пароль");
    // возращаемся к форме
    redirect_to ('page_login.php');
}
