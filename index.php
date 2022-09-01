<?php

use Models\Transaction;

include __DIR__ . '/includes/db.php';
include __DIR__ . '/includes/functions.php';
include __DIR__ . '/includes/constants.php';

if (!Auth::check()) {
    redirect('login.php');
}

$action = $_POST['action'] ?? '';
$ids = $_POST['id'] ?? [];

switch ($action) {
    // Bulk delete
    case 'delete':
        // $query = 'DELETE FROM transactions WHERE id = ?';
        // $stmt = $mysqli->prepare($query);
        // $stmt->bind_param('i', $id);

        // foreach ($ids as $id) {
        //     $stmt->execute();
        // }

        $ids = array_map('intval', $ids); // Ensure that all array items is integers (convert string to int)
        $query = 'DELETE FROM transactions WHERE id IN (' . implode(', ', $ids) .')';
        $stmt = $mysqli->prepare($query);
        $stmt->execute();

        $_SESSION['flash_messages']['success'] = 'Transactions deleted!';

        redirect($_SERVER['PHP_SELF']);
        break;
}


?>

<?php include __DIR__ . '/views/header.php' ?>

        <div class="d-flex justify-content-between mb-4">
            <h2>Transactions</h2>
            <div>
                <a href="insert.php" class="btn btn-sm btn-outline-primary">Add Transaction</a>
            </div>
        </div>

        <?php if (isset($_SESSION['flash_messages']['success'])) : ?>
            <div class="alert alert-success">
                <?= $_SESSION['flash_messages']['success'] ?>
                <?php
                unset($_SESSION['flash_messages']['success']);
                ?>
            </div>
        <?php endif ?>
        
        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
            <div class="mb-3 d-flex">
                <select name="action" class="form-select me-2">
                    <option value="">Select Action</option>
                    <option value="delete">Bulk Delete</option>
                </select>
                <button type="submit" class="btn btn-dark">Do action on selected items</button>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th>ID</th>
                        <th>Amount</th>
                        <th>Description</th>
                        <th>Tag</th>
                        <th>Time</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = Transaction::fetch();
                    
                    while ( $row = $result->fetch_assoc() ) :
                    ?>
                    <tr>
                        <td><input type="checkbox" name="id[]" value="<?= $row['id'] ?>"></td>
                        <td><?php echo $row['id'] ?></td>
                        <td><?= $row['amount'] ?></td>
                        <td><?= $row['description'] ?></td>
                        <td><?= $row['tag_name'] ?></td>
                        <td><?= $row['created_at'] ?></td>
                        <td><a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-success">Edit</a></td>
                        <td><a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger">Delete</a></td>
                        
                    </tr>
                    <?php
                    endwhile
                    ?>
                </tbody>
            </table>
        </form>
    </div>

<?php include __DIR__. '/views/footer.php' ?>