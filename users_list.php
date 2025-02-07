<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: loginHtml.php");
    exit();
}

// الاتصال بقاعدة البيانات
$host = "localhost";
$db_name = "LibraryDB";
$username = "root";
$password = "";
$port = 3307;
$conn = new mysqli($host, $username, $password, $db_name, $port);

if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// جلب جميع المستخدمين من قاعدة البيانات
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المستخدمين</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <div class="container">
            <h1>المكتبة الإلكترونية</h1>
            <nav>
                <ul>
                    <li><a href="admin_dashboard.php">لوحة تحكم الإداريين</a></li>
                    <li><a href="add_user.php">إضافة مستخدم جديد</a></li>
                    <li><a href="logout.php">تسجيل الخروج</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="users-list">
            <div class="container">
                <h2>قائمة المستخدمين</h2>
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>إجراءات</th>
                            <th>نوع المستخدم</th>
                            <th>البريد الإلكتروني</th>
                            <th>اسم المستخدم</th>
                            <th>الصورة الشخصية</th>
                            <th>تاريخ الإنشاء</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = $result->fetch_assoc()) { ?>
                            <tr>
                                <td>
                                    <a href="edit_user.php?user_id=<?php echo $user['user_id']; ?>" class="btn-action edit">تعديل</a>
                                    <a href="delete_user.php?user_id=<?php echo $user['user_id']; ?>" class="btn-action delete">حذف</a>
                                </td>
                                <td><?php echo htmlspecialchars($user['user_type']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['name']); ?></td>
                                <td>
                                    <?php if (!empty($user['profile_picture'])): ?>
                                        <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="صورة المستخدم" class="profile-picture">
                                    <?php else: ?>
                                        <img src="default-profile.png" alt="الصورة الافتراضية" class="profile-picture">
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>جميع الحقوق محفوظة &copy; 2024 المكتبة الإلكترونية</p>
        </div>
    </footer>
</body>

</html>
