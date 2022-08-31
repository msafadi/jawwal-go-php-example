<?php
include __DIR__ . '/includes/db.php';
include __DIR__ . '/includes/constants.php';

$id = (int) $_GET['id'] ?? 0;
if (!$id) {
    header('Location: index.php');
    exit;
}

$clean_id = $mysqli->real_escape_string($id);
$query = "SELECT * FROM transactions WHERE id = '$clean_id'"; // safe
$result = $mysqli->query($query);
$data = $result->fetch_assoc();

if (!$data) {
    header('Location: index.php');
    exit;
}

if ($_POST) {
    $amount = $_POST['amount'] ?? 0;
    $description = $_POST['description'] ?? '';
    $tag_id = $_POST['tag_id'] ?? null;

    if ($amount && $description) {
        
        $query = 'UPDATE transactions SET
            amount = ?,
            description = ?,
            tag_id = ?
            WHERE id = ?
        ';

        $stmt = $mysqli->prepare($query); // mysqli_stmt

        $stmt->bind_param('dsii', $amount, $description, $tag_id, $id);

        $stmt->execute();

        // Redirect
        header('Location: index.php?success=1');
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
        <h2 class="mb-4">Edit Transaction</h2>

        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . "?id={$id}" ?>" method="post">
            <div class="row mb-3">
                <label for="amount" class="col-sm-2 col-form-label">Amount</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" id="amount" name="amount" value="<?= htmlspecialchars($data['amount']) ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="description" class="col-sm-2 col-form-label">Description</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="description" name="description"><?= htmlspecialchars($data['description']) ?></textarea>
                </div>
            </div>
            <div class="row mb-3">
                <label for="tag_id" class="col-sm-2 col-form-label">Tag</label>
                <div class="col-sm-10">
                    <select class="form-select" id="tag_id" name="tag_id">
                        <option></option>
                        <?php foreach ($tags as $key => $value) : ?>
                        <option value="<?= $key ?>" <?= $key == $data['tag_id'] ? 'selected' : '' ?> ><?= $value ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update Data</button>
        </form>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>