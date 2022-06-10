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

// функция логирования
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

//проверка на авторизацию 
function is_not_logged_in() {
    // если не авторизован, возвращаем true
    if (empty($_SESSION['user'])){
        return true;
    }
    else {
        return false;
    }
}

// функция получения всех пользователей
function get_all_users() {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=my_php;charset=utf8", "root", "");
    $query = 'SELECT * FROM `users-dive`';
    $statement = $pdo->query($query);
    $users = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $users;
} 
