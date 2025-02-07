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

// جلب بيانات المستخدم من قاعدة البيانات
$user_id = $_SESSION['user_id'];
$sql_user = "SELECT * FROM users WHERE user_id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc(); // حفظ بيانات المستخدم
} else {
    die("فشل في جلب بيانات المستخدم.");
}

// جلب جميع الكتب من قاعدة البيانات
$sql = "SELECT * FROM books";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم الإداريين</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <div class="container">
            <h1>المكتبة الإلكترونية</h1>
            <nav>
                <ul>
                    <li><a href="index.php">الرئيسية</a></li>
                    <li><a href="admin_dashboard.php">لوحة تحكم الإداريين</a></li>
                    <li><a href="logout.php">تسجيل الخروج</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="admin-dashboard">
            <div class="container">
                <!-- عرض معلومات المستخدم -->
                <div class="user-info">
                    <?php if (!empty($user['profile_picture'])): ?>
                        <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="صورة المستخدم" class="profile-picture">
                    <?php else: ?>
                        <img src="default-profile.png" alt="الصورة الافتراضية" class="profile-picture">
                    <?php endif; ?>
                    <h2 class="welcome-message">مرحبًا، <?php echo htmlspecialchars($user['name']); ?>!</h2>
                </div>
                <!-- <h2>مرحبًا، <?php echo $_SESSION['user_name']; ?>!</h2> -->
                <p class="description-text">إليك قائمة الكتب في المكتبة:</p>
                <a href="users_list.php" class="btn">إدارة المستخدمين</a>
                <a href="add_book.php" class="btn">إضافة كتاب جديد</a>
                <a href="add_user.php" class="btn">إضافة مستخدم جديد</a>
                <a href="manage_borrowings.php" class="btn">إدارة الاستعارات</a>


                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>إجراءات</th>
                            <th>عدد النسخ</th>
                            <th>المؤلف</th>
                            <th>عنوان الكتاب</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($book = $result->fetch_assoc()) { ?>
                            <tr>
                                <td>
                                    <a href="edit_book.php?book_id=<?php echo $book['book_id']; ?>" class="btn-action edit">تعديل</a>
                                    <a href="delete_book.php?book_id=<?php echo $book['book_id']; ?>" class="btn-action delete">حذف</a>
                                </td>
                                <td><?php echo htmlspecialchars($book['total_copies']); ?></td>
                                <td><?php echo htmlspecialchars($book['author']); ?></td>
                                <td><?php echo htmlspecialchars($book['title']); ?></td>
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