<?php

require_once __DIR__ . "/common/auth.php";

if (isLoggedIn()) {
    header("Location: product_list.php");
    exit;
}

require_once __DIR__ . "/model/user.php";

$username = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || $password === "") {
        $errors[] = "Tên đăng nhập và mật khẩu không được để trống.";
    } elseif (getUserByUsername($username)) {
        $errors[] = "Tên đăng nhập đã tồn tại.";
    } else {
        $success = createUser($username, $password);

        if ($success) {
            header("Location: login.php?registered=1");
            exit;
        }

        $errors[] = "Không thể đăng ký tài khoản. Vui lòng thử lại.";
    }
}

require_once __DIR__ . "/view/header.php";

?>

<link rel="stylesheet" href="assets/auth.css">

<div class="auth-wrapper">

    <div class="auth-card">

        <h2 class="auth-title">Đăng ký tài khoản</h2>

        <p class="auth-subtitle">Tạo tài khoản để bắt đầu quản lý giỏ hàng</p>

<?php if (!empty($errors)): ?>
    <?php foreach ($errors as $error): ?>
        <div class="auth-msg auth-error"><?= htmlspecialchars($error) ?></div>
    <?php endforeach; ?>
<?php endif; ?>

<form method="post" action="register.php" class="auth-form">

    <p>
        <label for="username">Tên đăng nhập:</label><br>
        <input
            type="text"
            id="username"
            name="username"
            maxlength="50"
            value="<?= htmlspecialchars($username) ?>"
        >
    </p>

    <p>
        <label for="password">Mật khẩu:</label><br>
        <span class="password-wrap">
            <input
                type="password"
                id="password"
                name="password"
                style="padding-right: 36px;"
            >
            <button
                type="button"
                id="togglePassword"
                aria-label="Hiển thị hoặc ẩn mật khẩu"
                style="
                    position: absolute;
                    right: 6px;
                    top: 50%;
                    transform: translateY(-50%);
                    border: none;
                    background: transparent;
                    cursor: pointer;
                    font-size: 18px;
                    line-height: 1;
                    padding: 0;
                "
            >👁</button>
        </span>
        <script>
            (function () {
                var btn = document.getElementById("togglePassword");
                var input = document.getElementById("password");

                btn.onclick = function () {
                    if (input.type === "password") {
                        input.type = "text";
                        btn.textContent = "\uD83D\uDE48"; // 🙈
                    } else {
                        input.type = "password";
                        btn.textContent = "\uD83D\uDC41"; // 👁
                    }
                };
            })();
        </script>
    </p>

    <button type="submit" class="btn-submit">Đăng ký</button>

    <p class="auth-footer">
        Đã có tài khoản?
        <a href="login.php" class="auth-link">Đăng nhập</a>
    </p>

</form>

    </div>

</div>

<?php

require_once __DIR__ . "/view/footer.php";

?>