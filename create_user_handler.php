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
$id_new_user = add_user($email, $password);

// обновление общей информации 
edit_general_info($username, $position, $tel, $address, $id_new_user);

// загрузка аватар
upload_avatar($image_name, $id_new_user);

// установить статус
set_status($status, $id_new_user);

// добавление социальных сетей
edit_social_links($vk, $teleg, $insta, $id_new_user);

// сообщение
set_flash_massage("success", "Пользователь добавлен!");

redirect_to('users.php');
