<?php
require_once('functions.php');
session_start();

$id_user = $_POST['id'];
$username = $_POST['username'];
$position = $_POST['position'];
$tel = $_POST['tel'];
$address = $_POST['address'];

// обновление общей информации 
edit_general_info($username, $position, $tel, $address, $id_user);

// сообщение
set_flash_massage("success", "Профиль успешно обновлен!");

// перенаправить на профиль редактированного пользователя
redirect_to("page_profile.php?id=$id_user");
