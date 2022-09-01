<?php
session_start();

if (isset($_POST['name'])) {
    $_SESSION['name'] = $_POST['name'];

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

$name = $_SESSION['name'] ?? '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <?php if ($name) : ?>
    
        <h2>Hello <?= $name ?>! Your IP address is <?= $_SERVER['REMOTE_ADDR'] ?></h2>
    
    <?php else : ?>

        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
            Hello, waht's your name?
            <input type="text" name="name">
            <button type="submit">Send</button>
        </form>

    <?php endif ?>

</body>
</html>