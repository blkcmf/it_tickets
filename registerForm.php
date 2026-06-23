<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Ticketing - Register</title>
    <link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
    <div class="page">
        <div class="card card-narrow">
           
            <h2>Create Account</h2>
            <p class="subtitle">Register as an employee to submit IT tickets</p>

            <?php
            if (isset($_GET['error'])) {
                echo '<div class="alert alert-error">';
                switch ($_GET['error']) {
                    case 'pass':
                        echo 'Passwords do not match.';
                        break;
                    case 'exists':
                        echo 'This email is already registered.';
                        break;
                    default:
                        echo 'Registration failed. Please try again.';
                }
                echo '</div>';
            }
            ?>

            <form method="post" action="addUser.php">
                <div class="form-group">
                    <label for="email" class="required">Email</label>
                    <input type="email" id="email" name="email" required placeholder="you@company.com">
                </div>

                <div class="form-group">
                    <label for="pass" class="required">Password</label>
                    <input type="password" id="pass" name="pass" required minlength="6" placeholder="Min. 6 characters">
                </div>

                <div class="form-group">
                    <label for="pass2" class="required">Confirm password</label>
                    <input type="password" id="pass2" name="pass2" required minlength="6" placeholder="Repeat password">
                </div>

                <button type="submit" class="btn btn-primary btn-block">Create Account</button>
            </form>

            <div class="auth-footer">
                Already have an account? <a href="authForm.php">Sign in</a>
            </div>
        </div>
    </div>
</body>
</html>
