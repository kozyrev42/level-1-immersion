<?php
session_start();
require_once('functions.php');

//проверка на авторизацию 
if (is_not_logged_in()) {
    // если не авторизован, то перенаправление на форму логирования
    redirect_to('page_login.php');
}

// получаем данные редактируемого профиля
$edit_id_user = $_GET['id'];
$edit_user = get_user_by_id($edit_id_user);

// данные авторизованного
$logged_id_user = $_SESSION['user']['id'];

// если НЕ админ, проверить на автора, если не автор, перенаправляем
if (is_not_admin()) {
    if (is_not_author($logged_id_user, $edit_id_user)) {
        set_flash_massage("danger", "Редактировать только свой профиль!");
        redirect_to('users.php');
    }
}

function user_delete($edit_id_user)
{
    // данные авторизованного
    $logged_id_user = $_SESSION['user']['id'];
    // если удаляем себя
    if ($logged_id_user == $edit_id_user) {
        // удаляем аватар
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=my_php;charset=utf8", "root", "");
        $query = "SELECT * FROM `users-dive` WHERE id=:id";
        $statement = $pdo->prepare($query);
        $statement->execute(['id' => $edit_id_user]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        // прописать путь до удаляемого файла
        @unlink("img/demo/avatars/" . $result['avatar']);

        // удаляем запись в таблице
        $query = "DELETE FROM `users-dive` WHERE id=:id";
        $statement = $pdo->prepare($query);
        $statement->execute(['id' => $edit_id_user]);

        // очистка сессии пользователя
        unset($_SESSION['user']);

        // перевод 
        set_flash_massage("danger", "Пользователь Удален!");
        redirect_to('page_register.php');
    }


    // если удаляем Другого
    if ($logged_id_user !== $edit_id_user) {
        // удаляем аватар
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=my_php;charset=utf8", "root", "");
        $query = "SELECT * FROM `users-dive` WHERE id=:id";
        $statement = $pdo->prepare($query);
        $statement->execute(['id' => $edit_id_user]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        // прописать путь до удаляемого файла
        @unlink("img/demo/avatars/" . $result['avatar']);

        // удаляем запись в таблице
        $query = "DELETE FROM `users-dive` WHERE id=:id";
        $statement = $pdo->prepare($query);
        $statement->execute(['id' => $edit_id_user]);

        // перевод 
        set_flash_massage("success", "Пользователь Удален!");
        redirect_to('users.php');
    }
}

user_delete($edit_id_user);
