<?php

require_once __DIR__ . "/common/auth.php";

if (isLoggedIn()) {
    header("Location: product_list.php");
    exit;
}

require_once __DIR__ . "/model/user.php";

$username = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || $password === "") {
        $error = "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.";
    } else {
        $user = getUserByUsername($username);

        if ($user && password_verify($password, $user["password"])) {
            session_regenerate_id(true);
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];

            header("Location: product_list.php");
            exit;
        }

        $error = "Tên đăng nhập hoặc mật khẩu không chính xác.";
    }
}

$registered = isset($_GET["registered"]) && $_GET["registered"] === "1";

require_once __DIR__ . "/view/header.php";

?>

<link rel="stylesheet" href="assets/auth.css">

<div class="auth-wrapper">

    <div class="auth-card">

        <h2 class="auth-title">Đăng nhập</h2>

        <p class="auth-subtitle">Vui lòng đăng nhập để tiếp tục</p>

<?php if ($registered): ?>
    <div class="auth-msg auth-success">Đăng ký thành công. Vui lòng đăng nhập.</div>
<?php endif; ?>

<?php if ($error !== ""): ?>
    <div class="auth-msg auth-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post" action="login.php" class="auth-form">

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

    <button type="submit" class="btn-submit">Đăng nhập</button>

    <p class="auth-footer">
        Chưa có tài khoản?
        <a href="register.php" class="auth-link">Đăng ký</a>
    </p>

</form>

    </div>

</div>

<?php

require_once __DIR__ . "/view/footer.php";

?>