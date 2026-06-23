<?php
require 'checkSession.php';

require 'dbconnection.php';
require 'trace.php';
require 'myfunctions.php';

if ($_SESSION['role'] == 'IT') {
    tryAssignPendingTicket($connection, $_SESSION['user']);
}

$number = $_GET['num'];

$query = "SELECT * FROM tickets WHERE id = $number";
$result = mysqli_query($connection, $query);
trace($query);
$ticket = mysqli_fetch_array($result);

if (!$ticket) {
    echo "Ticket not found";
    exit;
}

if ($_SESSION['role'] == 'EMP' && $ticket['employee_email'] != $_SESSION['user']) {
    header('location:authForm.php?auth=role');
    exit;
}

$statusLabel = $ticket['status'] == 'on_hold' ? 'On Hold' : ucfirst($ticket['status']);
$workLabel = workStatusLabel($ticket['work_status']);
$canUpdate = ($_SESSION['role'] == 'IT' &&
    $ticket['status'] == 'assigned' &&
    $ticket['assigned_to'] == $_SESSION['user']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #<?php echo $ticket['id']; ?></title>
    <link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
    <div class="page">
        <?php require 'header.php'; ?>
        <div class="card">
            <h2>Ticket #<?php echo $ticket['id']; ?></h2>
            <p class="subtitle">
                <span class="status-badge status-<?php echo $ticket['status']; ?>"><?php echo $statusLabel; ?></span>
                <?php if ($ticket['status'] != 'on_hold') { ?>
                    <span class="work-badge work-<?php echo $ticket['work_status']; ?>"><?php echo $workLabel; ?></span>
                <?php } ?>
            </p>

            <?php if (isset($_GET['updated'])) { ?>
                <div class="alert alert-success">Work progress saved.</div>
            <?php } ?>

            <ul class="ticket-details">
                <li><b>Title</b> <span class="value"><?php echo $ticket['title']; ?></span></li>
                <li><b>Category</b> <span class="value"><?php echo $ticket['category']; ?></span></li>
                <li><b>Description</b> <span class="value"><?php echo $ticket['description']; ?></span></li>
                <li><b>Employee</b> <span class="value"><?php echo $ticket['employee_email']; ?></span></li>
                <li><b>Assigned to</b> <span class="value"><?php echo $ticket['assigned_to'] ? $ticket['assigned_to'] : '—'; ?></span></li>
                <li><b>Created at</b> <span class="value"><?php echo $ticket['created_at']; ?></span></li>
                <?php if ($ticket['screenshot']) { ?>
                <li>
                    <b>Screenshot</b>
                    <span class="value">
                        <img class="ticket-screenshot" src="<?php echo $ticket['screenshot']; ?>" alt="Screenshot">
                        <br><a href="<?php echo $ticket['screenshot']; ?>">Download</a>
                    </span>
                </li>
                <?php } ?>
                <?php if ($ticket['it_notes']) { ?>
                <li><b>IT notes</b> <span class="value"><?php echo nl2br($ticket['it_notes']); ?></span></li>
                <?php } ?>
                <?php if ($ticket['status'] == 'closed') { ?>
                <li><b>Closed by</b> <span class="value"><?php echo $ticket['closed_by']; ?></span></li>
                <li><b>Closed at</b> <span class="value"><?php echo $ticket['closed_at']; ?></span></li>
                <?php } ?>
            </ul>

            <?php if ($canUpdate) { ?>
            <div class="it-update-box">
                <h3>Update your work</h3>
                <p class="hint">Tell what you did and set the current progress</p>
                <form action="updateTicket.php?num=<?php echo $ticket['id']; ?>" method="post">
                    <div class="form-group">
                        <label for="work_status" class="required">Work status</label>
                        <select id="work_status" name="work_status" required>
                            <option value="not_started" <?php if ($ticket['work_status'] == 'not_started') echo 'selected'; ?>>Not Started</option>
                            <option value="in_progress" <?php if ($ticket['work_status'] == 'in_progress') echo 'selected'; ?>>In Progress</option>
                            <option value="waiting" <?php if ($ticket['work_status'] == 'waiting') echo 'selected'; ?>>Waiting for User</option>
                            <option value="solved" <?php if ($ticket['work_status'] == 'solved') echo 'selected'; ?>>Solved</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="it_notes" class="required">What did you do?</label>
                        <textarea id="it_notes" name="it_notes" required
                                  placeholder="Example: Restarted the router, updated drivers, waiting for user to test..."><?php echo $ticket['it_notes']; ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Progress</button>
                </form>
            </div>
            <?php } ?>

            <div class="actions">
                <?php if ($canUpdate) { ?>
                    <a href="closeTicket.php?num=<?php echo $ticket['id']; ?>" class="btn btn-danger"
                       onclick="return confirm('Close this ticket?')">Close Ticket</a>
                <?php } ?>
                <?php if ($_SESSION['role'] == 'IT') { ?>
                    <a href="allTickets.php" class="btn btn-secondary">← Back to tickets</a>
                <?php } else { ?>
                    <a href="myTickets.php" class="btn btn-secondary">← Back to my tickets</a>
                <?php } ?>
            </div>

            <?php mysqli_close($connection); ?>
        </div>
    </div>
</body>
</html>
