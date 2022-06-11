<?php
    require_once('functions.php');
    
    session_start();
    $email = $_POST['email'];
    $password = $_POST['password'];

    // поиск пользователя по email
    $user = get_user_by_email($email);
    
    // если $user содержит, значит такой емаил в бд 
    if (!empty($user)) {
        set_flash_massage("danger", "Этот электронный адрес уже занят другим пользователем!");
        redirect_to ('page_register.php');
        exit;
    }
    
    // далее добавляем пользователя, так как введенного емэйла нет в бд
    add_user ($email, $password);

    set_flash_massage("success", "Регистрация успешна!");

    redirect_to ('page_login.php');
