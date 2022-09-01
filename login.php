<?php
include __DIR__ . '/includes/db.php';
include __DIR__ . '/includes/functions.php';

start_session();

$error = '';

if ($_POST) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($email && $password) {

        $status = 'active';

        $query = 'SELECT * FROM users WHERE email = ?  AND status = ? LIMIT 1';
        $stmt = $mysqli->prepare($query);
        
        //$stmt->bind_param('ss', $email, $status); // Before 8.1
        $stmt->execute([$email, $status]); // Valid in PHP 8.1
        $result = $stmt->get_result();

        $row = $result->fetch_assoc();
        
        if ($row && password_verify($password, $row['password'])) {
            login($row);
        
            if ($_POST['remember'] ?? false) {
                $token = uniqid(); // built-in function return unique random string
                setcookie('remember_token', $token, time() + 60 * 60 * 24 * 30, '/');

                $query = 'UPDATE users SET remember_token = ? WHERE id = ?';
                $stmt = $mysqli->prepare($query);
                $stmt->execute([$token, $row['id']]);
            }

            header('Location: index.php');
            exit;
        }
    }

    $error = 'Invalid email and password combination!';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wallet</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>

<body>

    <div class="container">

        <h2>Login</h2>

        <?php if ($error) : ?>
        <div class="alert alert-danger">
            <?= $error ?>
        </div>
        <?php endif ?>

        <?php if (isset($_SESSION['flash_messages']['success'])) : ?>
            <div class="alert alert-success">
                <?= $_SESSION['flash_messages']['success'] ?>
                <?php
                unset($_SESSION['flash_messages']['success']);
                ?>
            </div>
        <?php endif ?>

        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
            <div class="row mb-3">
                <label for="" class="col-md-3">Email address</label>
                <div class="col-md-9">
                    <input type="text" class="form-control" name="email">
                </div>
            </div>
            <div class="row mb-3">
                <label for="" class="col-md-3">Password</label>
                <div class="col-md-9">
                    <input type="password" class="form-control" name="password">
                </div>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" value="1" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                    Remeber me.
                </label>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>

        </form>

    </div>
    
</body>
</html>