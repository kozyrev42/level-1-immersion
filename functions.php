<?php
// поиск пользователя по email
function get_user_by_email($email)
{
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
function set_flash_massage($var, $massage)
{
    $_SESSION[$var] = $massage;
}

// функция перенаправление 
function redirect_to($path)
{
    header("location:" . $path);
}

// добавляем пользователя
function add_user($email, $password)
{
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=my_php;charset=utf8", "root", "");
    // шифруем пароль
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    // запрос, с меткой накоторую передадим переменную
    $query = "INSERT INTO `users-dive` (email, password) VALUES (:email, :password)";
    // нужно подготовить запрос, для безопасной отправки в бд
    $statement = $pdo->prepare($query);
    // в запросе, на метки передаём переменные, и выполняем его
    $statement->execute(['email' => $email, 'password' => $hashed_password]);
    // возвращаем id пользователя
    $id_new_user = $pdo->lastInsertId();
    return $id_new_user;
}

// функция логирования
function login($email, $password)
{
    // проверка есть ли в базе емаил
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=my_php;charset=utf8", "root", "");
    // проверка есть ли в базе емаил
    // запрос, с меткой на которую передадим переменную
    $query = "SELECT * FROM `users-dive` WHERE email=:email";
    // нужно подготовить запрос, для безопасной отправки в бд
    $statement = $pdo->prepare($query);
    // в запросе, на метку передаём переменную и выполняем его
    $statement->execute(['email' => $email]);
    // $user - может содержать запись из таблицы
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    // проверяем пароль
    // хэшировынный пароль из базы
    $hash_password = $user['password'];
    // проверяем соответствует-ли введеный пароль, хешу пароля из бд
    // результат записываем в $verify_password
    $verify_password = password_verify($password, $hash_password);

    // если $user содержит, и $verify_password = true, значит проверка проедена
    if (!empty($user) && $verify_password) {
        // если проверки пройдены, записываем в сессию массив (данные пользователя)
        $_SESSION['user'] = ['email' => $user['email'], 'id' => $user['id'], 'role' => $user['role']];
        redirect_to('users.php');
    } else {
        //  передаём ошибку, возвращаемся к форме
        set_flash_massage("error", "не верный логин или пароль");
        redirect_to('page_login.php');
    }
}

//проверка на авторизацию 
function is_not_logged_in()
{
    // если не авторизован, возвращаем true
    if (empty($_SESSION['user'])) {
        return true;
    } else {
        return false;
    }
}

//проверка на админа 
function is_not_admin()
{
    // если не админ, возвращаем true
    if ($_SESSION['user']['role']!=='admin') {
        return true;
    } else {
        return false;
    }
}

// функция получения всех пользователей
function get_all_users()
{
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=my_php;charset=utf8", "root", "");
    $query = 'SELECT * FROM `users-dive`';
    $statement = $pdo->query($query);
    $users = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $users;
}

// обновление общей информации 
function edit_general_info($username, $position, $tel, $address, $id_user)
{
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=my_php;charset=utf8", "root", "");
    $query = "UPDATE `users-dive` SET name=:username, position=:position, tel=:tel, address=:address WHERE id=:id";
    $statement = $pdo->prepare($query);
    $statement->execute(['username' => $username, 'position' => $position, 'tel' => $tel, 'address' => $address, 'id' => $id_user]);
}

// загрузка аватар
function upload_avatar($image_name, $id_user)
{
    // если аватар не загружен, запись пути на дефолтный аватар
    if (empty($image_name)) {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=my_php;charset=utf8", "root", "");
        $query = "UPDATE `users-dive` SET avatar=:avatar WHERE id=:id";
        $statement = $pdo->prepare($query);
        $statement->execute(['avatar' => "avatar.png", 'id' => $id_user]);
    }

    // если аватар загружен
    if (!empty($image_name)) {
        // получим расширение файла
        $extension = pathinfo($image_name)["extension"];
        // формируем уникальное имя файла
        $uniq_image_name = uniqid() . "." . $extension;

        // сохранить картинку в постоянную папку
        // формируем путь сохранения, откуда
        $tmp_name = $_FILES['image']['tmp_name'];
        //куда
        $target = "img/demo/avatars/" . $uniq_image_name;
        // перемещаем в постоянную папку
        move_uploaded_file($tmp_name, $target);

        // записать в базу имени загруженего файла
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=my_php;charset=utf8", "root", "");
        $query = "UPDATE `users-dive` SET avatar=:avatar WHERE id=:id";
        $statement = $pdo->prepare($query);
        $statement->execute(['avatar' => $uniq_image_name, 'id' => $id_user]);
    }
}

// установить статус
function set_status($status, $id_user)
{
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=my_php;charset=utf8", "root", "");
    $query = "UPDATE `users-dive` SET status=:status WHERE id=:id";
    $statement = $pdo->prepare($query);
    $statement->execute(['status' => $status, 'id' => $id_user]);
}

// добавление социальных сетей
function edit_social_links($vk, $teleg, $insta, $id_user)
{
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=my_php;charset=utf8", "root", "");
    $query = "UPDATE `users-dive` SET vk=:vk, teleg=:teleg, insta=:insta WHERE id=:id";
    $statement = $pdo->prepare($query);
    $statement->execute(['vk' => $vk, 'teleg' => $teleg, 'insta' => $insta, 'id' => $id_user]);
}

// поиск пользователя по id
function get_user_by_id($id_user)
{
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=my_php;charset=utf8", "root", "");
    $query = "SELECT * FROM `users-dive` WHERE id=:id";
    $statement = $pdo->prepare($query);
    $statement->execute(['id' => $id_user]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    return $user;
}

// проверка на автора
function is_not_author($logged_id_user, $edit_id_user)
{
    // если НЕ автор возвращаем true
    if ($logged_id_user !== $edit_id_user) {
        return true;
    } else {
        return false;
    }
}