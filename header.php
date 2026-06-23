<header class="top-bar">
    <div class="user-info">
        Bonjour <strong><?php echo $_SESSION['user']; ?></strong>
        <span class="role-badge <?php echo $_SESSION['role'] == 'IT' ? 'it' : ''; ?>">
            <?php echo $_SESSION['role'] == 'IT' ? 'IT Staff' : 'Employee'; ?>
        </span>
    </div>
    <a href="disconnect.php" class="btn btn-secondary">Deconnexion</a>
</header>
