<?php

use Models\Transaction;

include __DIR__ . '/includes/db.php';
include __DIR__ . '/includes/functions.php';

if (!is_authenticated()) {
    redirect('login.php');
}


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

if (isset($_POST['confirmed']) && $_POST['confirmed'] == 'yes') {
    Transaction::delete($id);

    $_SESSION['flash_messages']['success'] = 'Transaction deleted!';

    // Redirect
    header('Location: index.php?success=1');
    exit;
}

?>
<?php include __DIR__ . '/views/header.php' ?>

    <div class="container">
        <h1>My Wallet</h1>
        <hr>
        <h2 class="mb-4">Delete Transaction</h2>

        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . "?id={$id}" ?>" method="post">
            <h3>Are you sure you want to delete transaction (#<?= $id ?>)?</h3>
            <button type="submit" class="btn btn-danger" name="confirmed" value="yes">Yes! Delete</button>
            <a href="index.php" class="btn btn-dark">No</a>
        </form>
    </div>

<?php include __DIR__ . '/views/footer.php' ?>
