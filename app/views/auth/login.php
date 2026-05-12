<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập – HPC Rèn Luyện</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body class="login-page">

<div class="login-wrapper">
    <div class="login-box">
        <div class="login-logo">
            <h2>🏫 HPC</h2>
            <p>Hệ thống Đánh giá Điểm Rèn Luyện</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Tài khoản</label>
                <input type="text" id="username" name="username"
                       placeholder="Nhập tài khoản"
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                       required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password"
                       placeholder="Nhập mật khẩu" required>
            </div>

            <button type="submit" class="btn-login">Đăng nhập</button>
        </form>
    </div>
</div>

</body>
</html>
