<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>تسجيل الدخول</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        .error-message {
            color: red;
            font-size: 0.9em;
            margin-top: -5px;
            margin-bottom: 10px;
        }
        .error-input {
            border-color: red;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>المكتبة الإلكترونية</h1>
            <nav>
                <ul>
                    <li><a href="index.php">الرئيسية</a></li>
                    <li><a href="loginHtml.php">تسجيل الدخول</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <section class="login-form">
            <div class="container">
                <h2 class="login-title">تسجيل الدخول</h2>
                <!-- عرض رسالة الخطأ -->
                <?php
                if (isset($_SESSION['login_error'])) {
                    echo "<p class='error-message'>" . $_SESSION['login_error'] . "</p>";
                    unset($_SESSION['login_error']); // حذف رسالة الخطأ بعد العرض
                }
                ?>
                <form id="loginForm" action="login.php" method="POST" class="login-form-container">
                    <label for="email" class="form-label">البريد الإلكتروني:</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="أدخل بريدك الإلكتروني"
                        value="<?php echo isset($_SESSION['login_email']) ? htmlspecialchars($_SESSION['login_email']) : ''; ?>"
                        required
                        class="form-input <?php echo isset($_SESSION['login_email_error']) ? 'error-input' : ''; ?>" />
                    <div class="error-message"><?php echo $_SESSION['login_email_error'] ?? ''; ?></div>
                    <?php unset($_SESSION['login_email']); unset($_SESSION['login_email_error']); ?>

                    <label for="password" class="form-label">كلمة المرور:</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="أدخل كلمة المرور"
                        required
                        class="form-input <?php echo isset($_SESSION['login_password_error']) ? 'error-input' : ''; ?>" />
                    <div class="error-message"><?php echo $_SESSION['login_password_error'] ?? ''; ?></div>
                    <?php unset($_SESSION['login_password_error']); ?>

                    <button type="submit" class="submit-button">تسجيل الدخول</button>
                </form>
                <p class="no-account">
                    ليس لديك حساب؟ <a href="register.html" class="register-link">إنشاء حساب جديد</a>
                </p>
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
