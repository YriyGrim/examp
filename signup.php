<?php
session_start();
require_once 'connect.php';

$full_name = $_POST['full_name'];
$phone = $_POST['phone'];
$login = $_POST['login'];
$password = $_POST['password'];
$password_confirm = $_POST['password_confirm'];

if ($password === $password_confirm) {

    // Проверяем, существует ли пользователь с таким логином
    $check_query = "SELECT * FROM `user` WHERE `login` = '$login'";
    $check_result = mysqli_query($connect, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $_SESSION['message'] = 'Пользователь с таким логином уже существует';
        header('Location: registration.php');
        exit();
    }

    // Вставляем нового пользователя в базу данных
    $insert_query = "INSERT INTO `user` (`id_role`, `login`, `password`, `full_name`, `phone`) VALUES (1, '$login', '$password', '$full_name', '$phone')";
    $insert_result = mysqli_query($connect, $insert_query);

    if ($insert_result) {
        $_SESSION['success_message'] = 'Регистрация прошла успешно';
        header('Location: index.php');
        exit();
    } else {
        $_SESSION['message'] = 'Ошибка регистрации. Пожалуйста, попробуйте еще раз.';
        header('Location: registration.php');
        exit();
    }
} else {
    $_SESSION['message'] = 'Пароли не совпадают';
    header('Location: registration.php');
    exit();
}