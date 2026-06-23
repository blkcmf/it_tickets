<?php
require 'checkSession.php';
if ($_SESSION['role'] != 'IT') {
    header('location:authForm.php?auth=role');
}

require 'dbconnection.php';
require 'trace.php';
require 'myfunctions.php';

$query = "SELECT * FROM tickets WHERE status='closed' ORDER BY closed_at DESC";
$result = mysqli_query($connection, $query);
trace($query);
$total = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket History</title>
    <link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
    <div class="page">
        <?php require 'header.php'; ?>
        <div class="card">
            <h2>Ticket History</h2>
            <p class="subtitle">All resolved tickets with closing details</p>

            <div class="nav-links">
                <a href="allTickets.php" class="btn btn-secondary">← Active Tickets</a>
            </div>

            <?php if ($total) { ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Employee</th>
                        <th>Work done</th>
                        <th>Closed by</th>
                        <th>Created</th>
                        <th>Closed at</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($ticket = mysqli_fetch_array($result)) {
                        echo "<tr>";
                        echo "<td>#{$ticket['id']}</td>";
                        echo "<td>{$ticket['title']}</td>";
                        echo "<td>{$ticket['category']}</td>";
                        echo "<td>{$ticket['employee_email']}</td>";
                        echo "<td><span class='work-badge work-{$ticket['work_status']}'>";
                        echo workStatusLabel($ticket['work_status']);
                        echo "</span></td>";
                        echo "<td>{$ticket['closed_by']}</td>";
                        echo "<td>{$ticket['created_at']}</td>";
                        echo "<td>{$ticket['closed_at']}</td>";
                        echo "<td><a href='showTicket.php?num={$ticket['id']}'>View →</a></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr><th colspan="9">Total: <?php echo $total; ?></th></tr>
                </tfoot>
            </table>
            <?php } else { ?>
            <div class="empty-state">
                <p>No closed tickets yet.</p>
            </div>
            <?php }

            mysqli_close($connection);
            ?>
        </div>
    </div>
</body>
</html>
