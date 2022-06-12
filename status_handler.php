<?php
require_once('functions.php');
session_start();

// получить данные
$status = $_POST['status_select'];
$id_user = $_POST['id'];

// установить статус
set_status($status, $id_user);

// перенаправление на главную
set_flash_massage("success", "Статус изменён!");
redirect_to('users.php');
