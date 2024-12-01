<nav>
    <div class="welcome-message">Hello there! Welcome, <?php echo $_SESSION['username']?></div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="insertApplicant.php">Add New Applicant</a>
        <a href="allusers.php">All Users</a>
        <a href="activitylogs.php">Activity Logs</a>
        <a href="core/handleForms.php?logoutUserBtn=1">Logout</a>
    </div>
</nav>
