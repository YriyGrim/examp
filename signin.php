<?php
session_start();
require_once 'connect.php';

$login = $_POST['login'];
$password = $_POST['password'];

// Проверяем, существует ли пользователь с таким логином и паролем
$check_query = "SELECT * FROM `user` WHERE `login` = '$login'";
$check_result = mysqli_query($connect, $check_query);

if (mysqli_num_rows($check_result) > 0) {
    $user = mysqli_fetch_assoc($check_result);
    if ($password == $user['password']) {
        // Пользователь найден и пароль совпадает, сохраняем данные пользователя в сессии
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['id_role'];
        
        // Перенаправляем пользователя в зависимости от роли
        if ($user['id_role'] == 2) {
            header('Location: admin.php');
            exit();
        } else {
            header('Location: requests.php');
            exit();
        }
    } else {
        $_SESSION['message'] = 'Неверный пароль';
        header('Location: index.php');
        exit();
    }
} else {
    $_SESSION['message'] = 'Пользователь с таким логином не найден';
    header('Location: index.php');
    exit();
}