<?php
require 'checkSession.php';
if ($_SESSION['role'] != 'EMP') {
    header('location:authForm.php?auth=role');
}

require 'dbconnection.php';
require 'trace.php';
require 'myfunctions.php';

$employee = $_SESSION['user'];
$query = "SELECT * FROM tickets WHERE employee_email='$employee' AND status != 'closed' ORDER BY created_at DESC";
$result = mysqli_query($connection, $query);
trace($query);
$total = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tickets</title>
    <link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
    <div class="page">
        <?php require 'header.php'; ?>
        <div class="card tutorial-card">
            <h2>Quick IT Fix Guides</h2>
            <p class="subtitle">Select a common issue and follow the steps to resolve it.</p>
            <div class="tutorial-select">
                <label for="tutorial-select">Choose a troubleshooting guide</label>
                <select id="tutorial-select" onchange="renderTutorial(this.value)">
                    <option value="internet">Internet connection issue</option>
                    <option value="printer">Printer not working</option>
                    <option value="password">Forgot password</option>
                    <option value="slow">Computer running slow</option>
                </select>
            </div>
            <div id="tutorial-steps" class="tutorial-steps"></div>
        </div>
        <div class="card">
            <h2>My Active Tickets</h2>
            <p class="subtitle">Track the status of your open requests</p>

            <div class="nav-links">
                <a href="submitTicketForm.php" class="btn btn-primary">+ New Ticket</a>
            </div>

            <?php if ($total) { ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Work progress</th>
                        <th>Assigned to</th>
                        <th>Created</th>
                        <th></th>
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
                        echo "<td><span class='status-badge status-{$ticket['status']}'>$statusLabel</span></td>";
                        $workLabel = workStatusLabel($ticket['work_status']);
                        if ($ticket['status'] == 'on_hold') {
                            $workLabel = '—';
                        }
                        echo "<td><span class='work-badge work-{$ticket['work_status']}'>$workLabel</span></td>";
                        echo "<td>" . ($ticket['assigned_to'] ? $ticket['assigned_to'] : '—') . "</td>";
                        echo "<td>{$ticket['created_at']}</td>";
                        echo "<td><a href='showTicket.php?num={$ticket['id']}'>View →</a></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr><th colspan="8">Total: <?php echo $total; ?></th></tr>
                </tfoot>
            </table>
            <?php } else { ?>
            <div class="empty-state">
                <p>No active tickets yet.</p>
                <a href="submitTicketForm.php" class="btn btn-primary">Submit your first ticket</a>
            </div>
            <?php }

            mysqli_close($connection);
            ?>
        </div>
    </div>
    <script>
        const itTutorials = {
            internet: {
                title: 'Internet connection issue',
                steps: [
                    'Check that the network cable is plugged in or that Wi-Fi is enabled.',
                    'Restart your router or disconnect and reconnect to the Wi-Fi network.',
                    'Open a browser and try loading a simple website like example.com.',
                    'If the issue persists, submit a ticket with the network name and any error details.'
                ]
            },
            printer: {
                title: 'Printer not working',
                steps: [
                    'Make sure the printer is powered on and connected to the network or computer.',
                    'Verify there is paper loaded and no paper jams in the printer tray.',
                    'Restart the printer and try printing a test page.',
                    'If needed, submit a ticket with the printer name and any error messages.'
                ]
            },
            password: {
                title: 'Forgot password',
                steps: [
                    'Try logging in with the correct email and current password if you remember it.',
                    'If you cannot log in, use the password reset option or contact IT support.',
                    'Describe the account email and whether you still have access to the registered address.',
                    'Submit a ticket so IT can update your credentials securely.'
                ]
            },
            slow: {
                title: 'Computer running slow',
                steps: [
                    'Close unused applications and browser tabs to free memory.',
                    'Restart your computer to clear temporary files and background tasks.',
                    'Check for pending system updates and install them if available.',
                    'If performance does not improve, submit a ticket describing the slow behavior.'
                ]
            }
        };

        function renderTutorial(key) {
            const tutorial = itTutorials[key] || itTutorials.internet;
            const container = document.getElementById('tutorial-steps');
            container.innerHTML = `
                <div class="tutorial-step">
                    <h3>${tutorial.title}</h3>
                    <ol>${tutorial.steps.map(step => `<li>${step}</li>`).join('')}</ol>
                </div>
            `;
        }

        document.addEventListener('DOMContentLoaded', function () {
            renderTutorial(document.getElementById('tutorial-select').value);
        });
    </script>
</body>
</html>
