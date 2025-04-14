<!DOCTYPE html>
<html>

<head>
    <title>Login Kasir</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <style>
    body {
        background-color: #f5f5f5;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }

    .login-box {
        background: white;
        padding: 30px;
        border-radius: 10px;
        width: 100%;
        max-width: 400px;
        box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
    }
    </style>
</head>

<body>
    <div class="login-box">
        <h4 class="text-center">Login Kasir</h4>
        <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
        <?php endif; ?>
        <form method="post" action="<?= base_url('auth/login_kasir') ?>">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required autofocus>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Masuk</button>
        </form>
    </div>
</body>

</html>