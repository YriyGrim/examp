<?php
// Параметры подключения к базе данных
$host = 'localhost';
$database = 'v4';
$username = 'root';
$password = '';

// Создаем соединение с базой данных
$connect = new mysqli($host, $username, $password, $database);

// Проверяем соединение
if ($connect->connect_error) {
    die("Ошибка подключения к базе данных: " . $connect->connect_error);
}