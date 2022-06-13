<?php
require_once('functions.php');

session_start();
$email = $_POST['email'];
$password = $_POST['password'];

$username = $_POST['username'];
$position = $_POST['position'];
$tel = $_POST['tel'];
$address = $_POST['address'];

$image_name = $_FILES['image']['name'];
$tmp_name = $_FILES['image']['tmp_name'];

$status = $_POST['status_select'];

$vk = $_POST['vk'];
$teleg = $_POST['teleg'];
$insta = $_POST['insta'];

// поиск пользователя по email
$user = get_user_by_email($email);

// если $user содержит, значит такой емаил в бд 
if (!empty($user)) {
    set_flash_massage("danger", "Электронный адрес уже занят другим пользователем!");
    redirect_to('create_user.php');
    exit;
}

// далее добавляем пользователя, так как введенного емэйла нет в бд
$id_user = add_user($email, $password);

// обновление общей информации 
edit_general_info($username, $position, $tel, $address, $id_user);

// загрузка аватар
// загрузка аватар
function upload_avatar($image_name, $id_user)
{
    // если аватар не загружен, запись пути на дефолтный аватар
    /* if (empty($image_name)) {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=my_php;charset=utf8", "root", "");
        $query = "UPDATE `users-dive` SET avatar=:avatar WHERE id=:id";
        $statement = $pdo->prepare($query);
        $statement->execute(['avatar' => "avatar_default.png", 'id' => $id_user]);
    } */

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
upload_avatar($image_name, $id_user);

// установить статус
set_status($status, $id_user);

// добавление социальных сетей
edit_social_links($vk, $teleg, $insta, $id_user);

// сообщение
set_flash_massage("success", "Пользователь добавлен!");

redirect_to('users.php');
