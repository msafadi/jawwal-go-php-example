<?php
include __DIR__ . '/includes/db.php';
include __DIR__ . '/includes/functions.php';
include __DIR__ . '/includes/constants.php';

if (!is_authenticated()) {
    redirect('login.php');
}

if ($_POST) {
    $amount = $_POST['amount'] ?? 0;
    $description = $_POST['description'] ?? '';
    $tag_id = $_POST['tag_id'] ?? null;

    if ($amount && $description) {

        $query = 'INSERT INTO transactions (amount, description, tag_id, user_id, created_at)
            VALUES (?, ?, ?, ?, ?)';

        $user_id = 1;
        $time = date('Y-m-d H:i:s'); // 2022-08-31 14:01:00

        $stmt = $mysqli->prepare($query); // mysqli_stmt

        $stmt->bind_param('dsiis', $amount, $description, $tag_id, $user_id, $time);

        $stmt->execute();

        // Redirect
        header('Location: index.php');
        exit;

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
        <h1>My Wallet</h1>
        <hr>
        <h2 class="mb-4">Insert Transaction</h2>

        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
            <div class="row mb-3">
                <label for="amount" class="col-sm-2 col-form-label">Amount</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" id="amount" name="amount">
                </div>
            </div>
            <div class="row mb-3">
                <label for="description" class="col-sm-2 col-form-label">Description</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="description" name="description"></textarea>
                </div>
            </div>
            <div class="row mb-3">
                <label for="tag_id" class="col-sm-2 col-form-label">Tag</label>
                <div class="col-sm-10">
                    <select class="form-select" id="tag_id" name="tag_id">
                        <option></option>
                        <?php foreach ($tags as $key => $value) : ?>
                        <option value="<?= $key ?>"><?= $value ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Add Data</button>
        </form>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>