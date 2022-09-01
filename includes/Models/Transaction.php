<?php

namespace Models;

class Transaction
{
    public static $db;

    public static function fetch()
    {
        $query = 'SELECT transactions.*, tags.name as tag_name
            FROM transactions 
            LEFT JOIN tags ON tags.id = transactions.tag_id
            ORDER BY created_at DESC';

        $result = self::$db->query($query); // return mysqli_result
        return $result;
    }

    public static function create($data)
    {
        $query = 'INSERT INTO transactions (amount, description, tag_id, user_id, created_at)
            VALUES (?, ?, ?, ?, ?)';

        $user_id = $_SESSION['user']['id'] ?? 0;
        $time = date('Y-m-d H:i:s'); // 2022-08-31 14:01:00

        $stmt = self::$db->prepare($query); // mysqli_stmt

        $stmt->bind_param('dsiis', $data['amount'], $data['description'], $data['tag_id'], $user_id, $time);

        $stmt->execute();
    }

    public static function delete($id)
    {
        $query = 'DELETE FROM transactions WHERE id = ?';

        $stmt = self::$db->prepare($query); // mysqli_stmt

        $stmt->bind_param('i', $id);

        $stmt->execute();
    }
}