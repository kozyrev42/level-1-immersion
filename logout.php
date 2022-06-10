<?php
// скрипт выхода из приложения
session_start();
// очистка сессии
unset($_SESSION['user']);
// возвращаемся на главную
header ('location: page_login.php');
