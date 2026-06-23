<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Ticketing - Login</title>
    <link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
    <div class="page">
        <div class="card card-narrow">
            
            <h2>IT Ticketing</h2>
            <p class="subtitle">Report network, system & IT problems</p>

            <?php
            if (isset($_GET['auth'])) {
                $alertClass = 'alert-info';
                $message = '';
                switch ($_GET['auth']) {
                    case 'false':
                        $alertClass = 'alert-error';
                        $message = 'Please check your email and password.';
                        break;
                    case 'nonAuth':
                        $message = 'You must sign in first.';
                        break;
                    case 'again':
                        $message = 'You have been logged out. Sign in again.';
                        break;
                    case 'role':
                        $alertClass = 'alert-error';
                        $message = "You don't have access to this page.";
                        break;
                    case 'registered':
                        $alertClass = 'alert-success';
                        $message = 'Account created! You can now sign in.';
                        break;
                }
                if ($message) {
                    echo "<div class='alert $alertClass'>$message</div>";
                }
            }
            ?>

            <form method="post" action="chechAuth.php">
                <div class="form-group">
                    <label for="login" class="required">Email</label>
                    <input type="email" id="login" name="login" required placeholder="you@test.com">
                </div>

                <div class="form-group">
                    <label for="pass" class="required">Password</label>
                    <input type="password" id="pass" name="pass" required placeholder="password">
                </div>

                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </form>

            <div class="auth-footer">
                Don't have an account? <a href="registerForm.php">Register</a>
                <p class="hint" style="margin-top:12px;">
                    Need help? Call us at <a href="tel:++2126789012">+2126789012</a> or email <a href="mailto:support@test.com">support@test.com</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
