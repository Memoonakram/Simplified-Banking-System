<?php
session_start();
include 'db.php';

// Fetch all users' emails from the database in alphabetical order
$query = "SELECT id, email FROM users ORDER BY email ASC";
$result = $conn->query($query);
$users = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alliance Digital - Users List</title>
    <style>
        /* Original styles preserved */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
                Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        body {
            background-color: #f5f5f5;
        }

        .header {
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .logo {
            font-weight: 600;
            font-size: 1.2rem;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .users-table {
            width: 100%;
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-collapse: collapse;
        }

        .users-table th,
        .users-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .users-table th {
            background-color: #f9fafb;
            font-weight: 500;
            color: #374151;
        }

        .users-table tr:last-child td {
            border-bottom: none;
        }

        .users-table tbody tr:hover {
            background-color: #f9fafb;
        }

        .action-btn {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            background-color: #dc2626;
            color: white;
            border: none;
            cursor: pointer;
        }

        .action-btn:hover {
            background-color: #b91c1c;
        }
    </style>
</head>
<body>
<header class="header">
    <div class="logo">Alliance Digital</div>
    <div class="user-list" style="font-family: 'Arial, sans-serif'; font-size: 1.0rem; font-weight: bold; color: #FF0000;">
        Users List
    </div>
</header>

<div class="container">
    <a href="admin_dashboard.php" class="btn btn-primary" style="margin-bottom: 1rem; display: inline-block; font-size: 1rem; color: white; background: #dc2626; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none;">Go to Admin Dashboard</a>

    <table class="users-table">
        <thead>
            <tr>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td>
                        <a href="admin_dashboard.php?user_id=<?php echo $user['id']; ?>" class="action-btn">View Details</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
