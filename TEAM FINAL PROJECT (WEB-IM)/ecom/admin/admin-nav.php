<nav id="admin-nav">
    <div class="admin-panel-label">
        <span>ADMIN PANEL</span>
    </div>
    <div class="admin-nav-btn">
        <span class="material-symbols-outlined notification-bell">notifications</span>
        <div class="account-name">
            <div><?php echo $_SESSION['admin_name'] ?></div> 
            <div>Admin</div>
        </div>
        <span class="material-symbols-outlined admin-account-logo" onclick="toggleMenu()">manage_accounts</span>
        <div id="account-box">
            <ul>
                <li>
                    <p>Email : <span><?php echo $_SESSION['admin_email'] ?></span></p>
                </li>
                <li> <a href="../logout.php" class="logout-btn">logout</a></li>
            </ul>
        </div>
    </div>
</nav>