<?php
include __DIR__ . '/includes/db.php';
include __DIR__ . '/includes/functions.php';

$errors = [];

if ($_POST) {
    if (!isset($_POST['name']) || empty($_POST['name'])) {
        $errors['name'] = 'The name filed is required.';
    }
    if (!isset($_POST['email']) || empty($_POST['email'])) {
        $errors['email'] = 'The email filed is required.';
    }
    if (!isset($_POST['accept'])) {
        $errors['accept'] = 'You must accpet our terms and conditions';
    }
    if (!isset($_POST['password']) || empty($_POST['password'])) {
        $errors['password'] = 'The password filed is required.';
    } else if ($_POST['password'] != $_POST['password_confirm']) {
        $errors['password'] = 'The password don\'t match its confirmation.';
    }
    
    // Validate email is not used before
    $query = 'SELECT * FROM users WHERE email = ?';
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $_POST['email']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $errors['email'] = 'Email already exists!';
    }


    if (!$errors) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $query = 'INSERT INTO users (name, email, password) VALUES (?, ?, ?)';
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('sss', $_POST['name'], $_POST['email'], $password);
        $stmt->execute();

        start_session();
        $_SESSION['flash_messages']['success'] = 'Account created!';

        redirect('login.php');
    }
    
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

        <h2>Register</h2>

        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
            <div class="row mb-3">
                <label for="" class="col-md-3">Name</label>
                <div class="col-md-9">
                    <input type="text" class="form-control" name="name" required>
                    <?php if (isset($errors['name'])) : ?>
                    <p class="text-danger"><?= $errors['name'] ?></p>
                    <?php endif ?>
                </div>
            </div>
            <div class="row mb-3">
                <label for="" class="col-md-3">Email address</label>
                <div class="col-md-9">
                    <input type="email" class="form-control" name="email">
                    <?php if (isset($errors['email'])) : ?>
                    <p class="text-danger"><?= $errors['email'] ?></p>
                    <?php endif ?>
                </div>
            </div>
            <div class="row mb-3">
                <label for="" class="col-md-3">Password</label>
                <div class="col-md-9">
                    <input type="password" class="form-control" name="password">
                    <?php if (isset($errors['password'])) : ?>
                    <p class="text-danger"><?= $errors['password'] ?></p>
                    <?php endif ?>
                </div>
            </div>
            <div class="row mb-3">
                <label for="" class="col-md-3">Confirm Password</label>
                <div class="col-md-9">
                    <input type="password" class="form-control" name="password_confirm">
                </div>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="accept" value="1" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                    I accept terms and conditions.
                </label>
                <?php if (isset($errors['accept'])) : ?>
                <p class="text-danger"><?= $errors['accept'] ?></p>
                <?php endif ?>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>

        </form>

    </div>
    
</body>
</html>