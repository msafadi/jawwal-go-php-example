<?php

use Models\Transaction;

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

        Transaction::create([
            'amount' => $amount,
            'description' => $description,
            'tag_id' => $tag_id,
        ]);

        $_SESSION['flash_messages']['success'] = 'Transaction added!';

        // Redirect
        header('Location: index.php');
        exit;

    }
}

?>

<?php include __DIR__ . '/views/header.php' ?>

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
                        <?php
                        $query = 'SELECT * FROM tags ORDER BY name';
                        $result = $mysqli->query($query);
                        while ($row = $result->fetch_object()) :
                        ?>
                        <option value="<?= $row->id ?>"><?= $row->name ?></option>
                        <?php endwhile ?>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Add Data</button>
        </form>
    </div>

<?php include __DIR__ . '/views/footer.php' ?>
