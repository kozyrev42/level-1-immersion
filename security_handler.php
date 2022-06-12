<?php
require_once('functions.php');
session_start();

// данные редактируемого пользователя
$id_user = $_POST['id'];
$current_email_edit_user = $_POST['current_email'];
$email = $_POST['new_email'];
$password = $_POST['password'];

// функция изменения пароля
function edit_password($password, $id_user)
{
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=my_php;charset=utf8", "root", "");
    $query = "UPDATE `users-dive` SET password=:password WHERE id=:id";
    $statement = $pdo->prepare($query);
    $statement->execute(['password' => $hashed_password, 'id' => $id_user]);
}

// если действующий пароль == новому
if ($current_email_edit_user == $email) {
    // просто изменяем пароль
    edit_password($password, $id_user);
    set_flash_massage("success", "Пароль успешно обновлен!");
    redirect_to("page_profile.php?id=$id_user");
}

// если действующий пароль !== новому
if ($current_email_edit_user !== $email) {
    // поиск пользователя по email
    $user = get_user_by_email($email);
    // если $user содержит, значит емаил есть в бд 
    if (!empty($user)) {
        set_flash_massage("danger", "Электронный адрес уже занят другим пользователем!");
        redirect_to('security.php');
        exit;
    }

    // редактируем емаил и пароля, так как введенного емэйла нет в бд
    function edit_email_password($email, $password, $id_user)
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=my_php;charset=utf8", "root", "");
        $query = "UPDATE `users-dive` SET email=:email, password=:password WHERE id=:id";
        $statement = $pdo->prepare($query);
        $statement->execute(['email' => $email,'password' => $hashed_password, 'id' => $id_user]);
    }

    edit_email_password($email, $password, $id_user);
    
    set_flash_massage("success", "Электронный адрес и пароль успешно изменены!");
    redirect_to("page_profile.php?id=$id_user");
}
