<?php
// поиск пользователя по email
function get_user_by_email($email) {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=my_php;charset=utf8", "root", "");
    // запрос, с меткой на которую передадим переменную
    $query = "SELECT * FROM `users-dive` WHERE email=:email";
    // нужно подготовить запрос, для безопасной отправки в бд
    $statement = $pdo->prepare($query);
    // в запросе, на метку передаём переменную и выполняем его
    $statement->execute(['email' => $email]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    return $user;
}

// в функции формируем сообщение, записываем в переменную сессии
function set_flash_massage($var, $massage){
    $_SESSION[$var] = $massage;
}

// функция перенаправление 
function redirect_to ($path) {
    header ("location:" . $path);
}

// добавляем пользователя
function add_user ($email, $password ) {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=my_php;charset=utf8", "root", "");
    // шифруем пароль
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    // запрос, с меткой накоторую передадим переменную
    $query = "INSERT INTO `users-dive` (email, password) VALUES (:email, :password)";
    // нужно подготовить запрос, для безопасной отправки в бд
    $statement = $pdo->prepare($query);
    // в запросе, на метки передаём переменные, и выполняем его
    $statement->execute(['email' => $email, 'password' => $hashed_password]);
}
