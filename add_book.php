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

// معالجة إضافة الكتاب
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $total_copies = $_POST['total_copies'];
    $cover_image = $_FILES['cover_image']['name'];

    // تحميل صورة الغلاف
    if ($cover_image) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($cover_image);
        move_uploaded_file($_FILES['cover_image']['tmp_name'], $target_file);
    } else {
        $target_file = "";
    }

    // إدخال الكتاب في قاعدة البيانات
    $sql = "INSERT INTO books (title, author, category, total_copies, available_copies, cover_image) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $available_copies = $total_copies; // في البداية كل النسخ متاحة
    $stmt->bind_param("sssiis", $title, $author, $category, $total_copies, $available_copies, $target_file);

    if ($stmt->execute()) {
        echo "تم إضافة الكتاب بنجاح!";
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "حدث خطأ أثناء إضافة الكتاب.";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة كتاب</title>
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
        <section class="add-book-form">
            <div class="containerAdd_book">
                <h2 class="form-title">إضافة كتاب جديد</h2>
                <form action="add_book.php" method="POST" enctype="multipart/form-data" class="form-style">
                    <label for="title">عنوان الكتاب:</label>
                    <input type="text" id="title" name="title" required class="form-input">

                    <label for="author">المؤلف:</label>
                    <input type="text" id="author" name="author" required class="form-input">

                    <label for="category">الفئة:</label>
                    <input type="text" id="category" name="category" required class="form-input">

                    <label for="total_copies">إجمالي النسخ:</label>
                    <input type="number" id="total_copies" name="total_copies" required class="form-input">

                    <label for="cover_image">صورة الغلاف:</label>
                    <input type="file" id="cover_image" name="cover_image" class="form-input">

                    <button type="submit" class="form-button">إضافة الكتاب</button>
                </form>
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