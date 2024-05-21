<?php
session_start();
require_once 'header.php';

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Получаем список мастеров
$masters_query = "SELECT * FROM `master`";
$masters_result = mysqli_query($connect, $masters_query);

// Обработка отправки формы создания заявки
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $master_id = $_POST['master'];
    $booking_datetime = $_POST['booking_datetime'];

    // Проверяем, что выбранное время соответствует рабочему времени
    $booking_time = date('H:i', strtotime($booking_datetime));
    if ($booking_time < '08:00' || $booking_time >= '18:00') {
        $error_message = 'Выбранное время должно быть между 8:00 и 18:00';
    } else {
        // Вставляем новую заявку в базу данных
        $insert_query = "INSERT INTO `request` (`id_user`, `id_master`, `id_status`, `booking_datetime`) VALUES ($user_id, $master_id, 1, '$booking_datetime')";
        $insert_result = mysqli_query($connect, $insert_query);

        if ($insert_result) {
            header('Location: requests.php');
            exit();
        } else {
            $error_message = 'Ошибка создания заявки. Пожалуйста, попробуйте еще раз.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Новая заявка</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-4">
        <h1>Новая заявка</h1>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label for="master" class="form-label">Мастер</label>
                <select class="form-select" id="master" name="master" required>
                    <option value="">Выберите мастера</option>
                    <?php while ($master = mysqli_fetch_assoc($masters_result)): ?>
                        <option value="<?php echo $master['id']; ?>"><?php echo $master['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="booking_datetime" class="form-label">Дата и время</label>
                <input type="datetime-local" class="form-control" id="booking_datetime" name="booking_datetime" required>
            </div>
            <button type="submit" class="btn btn-primary">Создать заявку</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>