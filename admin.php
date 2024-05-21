<?php require_once 'header.php'; ?>

<?php
session_start();
require_once 'connect.php';

// Проверяем, авторизован ли пользователь как администратор
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 2) {
    header('Location: index.php');
    exit();
}

// Получаем параметры сортировки из GET-запроса
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'booking_datetime';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC';

// Получаем список всех заявок с сортировкой
$requests_query = "SELECT r.id, u.full_name, u.phone, r.booking_datetime, m.name AS master_name, s.name AS status_name
                   FROM `request` r
                   JOIN `user` u ON r.id_user = u.id
                   JOIN `master` m ON r.id_master = m.id
                   JOIN `status` s ON r.id_status = s.id
                   ORDER BY $sort_by $sort_order";
$requests_result = mysqli_query($connect, $requests_query);






// Обработка изменения статуса заявки
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = $_POST['request_id'];
    $status_id = $_POST['status'];

    $update_query = "UPDATE `request` SET `id_status` = $status_id WHERE `id` = $request_id";
    $update_result = mysqli_query($connect, $update_query);

    if ($update_result) {
        // Обновляем результаты запроса $requests_query после изменения статуса
        $requests_query = "SELECT r.id, u.full_name, u.phone, r.booking_datetime, m.name AS master_name, s.name AS status_name
                           FROM `request` r
                           JOIN `user` u ON r.id_user = u.id
                           JOIN `master` m ON r.id_master = m.id
                           JOIN `status` s ON r.id_status = s.id";
        $requests_result = mysqli_query($connect, $requests_query);
    } else {
        $error_message = 'Ошибка изменения статуса заявки. Пожалуйста, попробуйте еще раз.';
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель администратора</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .action-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1>Панель администратора</h1>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <table class="table">
            <thead>
                <tr>
                    <th>
                        <a href="?sort_by=full_name&sort_order=<?php echo $sort_order == 'ASC' ? 'DESC' : 'ASC'; ?>">ФИО</a>
                    </th>
                    <th>Телефон</th>
                    <th>
                        <a href="?sort_by=booking_datetime&sort_order=<?php echo $sort_order == 'ASC' ? 'DESC' : 'ASC'; ?>">Дата и время</a>
                    </th>
                    <th>Мастер</th>
                    <th>Статус</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($request = mysqli_fetch_assoc($requests_result)): ?>
                    <tr>
                        <td><?php echo $request['full_name']; ?></td>
                        <td><?php echo $request['phone']; ?></td>
                        <td><?php echo $request['booking_datetime']; ?></td>
                        <td><?php echo $request['master_name']; ?></td>
                        <td><?php echo $request['status_name']; ?></td>
                        <td>
                            <form method="post" class="action-group">
                                <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                <select name="status" class="form-select">
                                    <?php
                                    $statuses_query = "SELECT * FROM `status`";
                                    $statuses_result = mysqli_query($connect, $statuses_query);
                                    while ($status = mysqli_fetch_assoc($statuses_result)):
                                    ?>
                                        <option value="<?php echo $status['id']; ?>" <?php if ($status['id'] == $request['id_status']) echo 'selected'; ?>><?php echo $status['name']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                                <button type="submit" class="btn btn-primary">Изменить</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="text-center mb-4">
            <img src="images/admin_image.png" alt="Admin Image" class="img-fluid">
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>