<?php
require 'checkSession.php';
if ($_SESSION['role'] != 'EMP') {
    header('location:authForm.php?auth=role');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Ticket</title>
    <link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
    <div class="page">
        <?php require 'header.php'; ?>
        <div class="card">
            <h2>Submit IT Ticket</h2>
            <p class="subtitle">Describe your problem and attach a screenshot if you can</p>

            <div class="nav-links">
                <a href="myTickets.php" class="btn btn-secondary">← My Tickets</a>
            </div>

            <form action="addTicket.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title" class="required">Problem title</label>
                    <input type="text" id="title" name="title" required maxlength="150"
                           placeholder="Short description of the problem">
                </div>

                <div class="form-group">
                    <label for="category" class="required">Category</label>
                    <select id="category" name="category" required>
                        <option value="">Select a category</option>
                        <option value="Network">Network</option>
                        <option value="System">System</option>
                        <option value="Software">Software</option>
                        <option value="Hardware">Hardware</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description" class="required">Description</label>
                    <textarea id="description" name="description" required
                              placeholder="Explain the problem in detail"></textarea>
                </div>

                <div class="form-group">
                    <label for="screenshot">Screenshot (optional)</label>
                    <input type="file" id="screenshot" name="screenshot"
                           accept="image/png, image/jpeg, image/jpg">
                    <div class="hint">
                        PNG, JPG, JPEG — max 500KB
                        <?php
                        if (isset($_GET['error'])) {
                            switch ($_GET['error']) {
                                case 'typephoto':
                                    echo ' — Type photo not allowed';
                                    break;
                                case 'sizefile':
                                    echo ' — File too large (max 500KB)';
                                    break;
                            }
                        }
                        ?>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Submit Ticket</button>
            </form>
        </div>
    </div>
</body>
</html>
