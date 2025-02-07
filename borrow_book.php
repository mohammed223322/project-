<?php
session_start();
if (!isset($_SESSION['user_id'])) {
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

// التحقق من وجود book_id في الرابط
if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];
    $user_id = $_SESSION['user_id'];

    // التحقق من وجود الكتاب في قاعدة البيانات
    $sql = "SELECT * FROM books WHERE book_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $book = $stmt->get_result()->fetch_assoc();

    if ($book && $book['available_copies'] > 0) {
        // إضافة الاستعارة في جدول borrowings
        $borrow_date = date("Y-m-d");
        $return_date = date('Y-m-d', strtotime("+7 days")); // تحديد تاريخ الإرجاع بعد 14 يومًا

        $sql = "INSERT INTO borrowings (user_id, book_id, borrow_date, return_date) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiss", $user_id, $book_id, $borrow_date, $return_date);
        $stmt->execute();

        // تحديث عدد النسخ المتاحة في جدول الكتب
        $new_available_copies = $book['available_copies'] - 1;
        $sql = "UPDATE books SET available_copies = ? WHERE book_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $new_available_copies, $book_id);
        $stmt->execute();

        echo "تم استعارة الكتاب بنجاح! يجب عليك إرجاعه في موعد أقصاه: " . $return_date;
    } else {
        echo "عذرًا، الكتاب غير متاح حالياً.";
    }
} else {
    echo "لم يتم تحديد الكتاب.";
}
?>
