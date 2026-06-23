<?php
require 'checkSession.php';
if ($_SESSION['role'] != 'IT') {
    header('location:authForm.php?auth=role');
}

require 'dbconnection.php';
require 'trace.php';
require 'myfunctions.php';

tryAssignPendingTicket($connection, $_SESSION['user']);

$query = "SELECT * FROM tickets WHERE status != 'closed' ORDER BY created_at ASC";
$result = mysqli_query($connection, $query);
trace($query);
$total = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Tickets</title>
    <link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
    <div class="page">
        <?php require 'header.php'; ?>
        <div class="card">
            <h2>Active Tickets</h2>
            <p class="subtitle">Manage and resolve employee requests</p>

            <div class="nav-links">
                <a href="ticketHistory.php" class="btn btn-secondary">← History</a>
            </div>

            <?php if ($total) { ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Employee</th>
                        <th>Status</th>
                        <th>Work progress</th>
                        <th>Assigned to</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($ticket = mysqli_fetch_array($result)) {
                        $statusLabel = $ticket['status'] == 'on_hold' ? 'On Hold' : ucfirst($ticket['status']);
                        echo "<tr>";
                        echo "<td>#{$ticket['id']}</td>";
                        echo "<td>{$ticket['title']}</td>";
                        echo "<td>{$ticket['category']}</td>";
                        echo "<td>{$ticket['employee_email']}</td>";
                        echo "<td><span class='status-badge status-{$ticket['status']}'>$statusLabel</span></td>";
                        $workLabel = workStatusLabel($ticket['work_status']);
                        if ($ticket['status'] == 'on_hold') {
                            $workLabel = '—';
                        }
                        echo "<td><span class='work-badge work-{$ticket['work_status']}'>$workLabel</span></td>";
                        echo "<td>" . ($ticket['assigned_to'] ? $ticket['assigned_to'] : '—') . "</td>";
                        echo "<td>{$ticket['created_at']}</td>";
                        echo "<td>";
                        echo "<a href='showTicket.php?num={$ticket['id']}'>View</a>";
                        if ($ticket['status'] == 'assigned' && $ticket['assigned_to'] == $_SESSION['user']) {
                            echo " · <a href='closeTicket.php?num={$ticket['id']}' ";
                            echo "onclick='return confirm(\"Close this ticket?\")'>Close</a>";
                        }
                        echo "</td>";
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
                <p>No active tickets. All clear!</p>
            </div>
            <?php }

            mysqli_close($connection);
            ?>
        </div>
    </div>
</body>
</html>
