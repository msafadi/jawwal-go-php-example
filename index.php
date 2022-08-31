<?php
include __DIR__ . '/includes/db.php';
include __DIR__ . '/includes/constants.php';
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
        <div class="d-flex justify-content-between mb-4">
            <h2>Transactions</h2>
            <div>
                <a href="insert.php" class="btn btn-sm btn-outline-primary">Add Transaction</a>
            </div>
        </div>

        <?php if (isset($_GET['success'])) : ?>
            <div class="alert alert-success">
                Action successful!
            </div>
        <?php endif ?>
        

        <table class="table">
            <thead>
                <tr>
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
                $query = 'SELECT * FROM transactions ORDER BY created_at DESC';
                $result = $mysqli->query($query); // return mysqli_result
                
                while ( $row = $result->fetch_assoc() ) :
                ?>
                <tr>
                    <td><?php echo $row['id'] ?></td>
                    <td><?= $row['amount'] ?></td>
                    <td><?= $row['description'] ?></td>
                    <td><?= $tags[$row['tag_id']] ?? '-' ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td><a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-success">Edit</a></td>
                    <td><a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger">Delete</a></td>
                    
                </tr>
                <?php
                endwhile
                ?>
            </tbody>
        </table>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>