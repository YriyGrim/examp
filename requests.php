<?php require_once 'header.php'; ?>

<?php
session_start();
require_once 'connect.php';

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Получаем список заявок пользователя
$user_id = $_SESSION['user_id'];
$requests_query = "SELECT r.id, r.booking_datetime, m.name AS master_name, s.name AS status_name
                   FROM `request` r
                   JOIN `master` m ON r.id_master = m.id
                   JOIN `status` s ON r.id_status = s.id
                   WHERE r.id_user = $user_id";
$requests_result = mysqli_query($connect, $requests_query);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мои заявки</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-4">
        <h1>Мои заявки</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Дата и время</th>
                    <th>Мастер</th>
                    <th>Статус</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($request = mysqli_fetch_assoc($requests_result)): ?>
                    <tr>
                        <td><?php echo $request['booking_datetime']; ?></td>
                        <td><?php echo $request['master_name']; ?></td>
                        <td><?php echo $request['status_name']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="newrequest.php" class="btn btn-primary">Новая заявка</a>
    </div>
    <div class="text-center mb-4">
            <img src="images/requests_image.png" alt="Requests Image" class="img-fluid">
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>