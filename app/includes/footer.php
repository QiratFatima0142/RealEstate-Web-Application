</main>
<footer class="footer">
    <div class="container">
        <div class="footer__cols">
            <div>
                <h4><?= e(APP_NAME) ?></h4>
                <p class="muted" style="color:#9ca3af;font-size:0.9rem;">
                    <?= e(APP_TAGLINE) ?>. Track purchases, manage sales, and close deals confidently.
                </p>
            </div>
            <div>
                <h4>Explore</h4>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="properties.php">Properties</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>
            <div>
                <h4>Account</h4>
                <ul>
                    <?php if (is_logged_in()): ?>
                        <li><a href="dashboard.php">Dashboard</a></li>
                        <li><a href="actions/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Sign In</a></li>
                        <li><a href="signup.php">Register</a></li>
                        <li><a href="forgot_password.php">Forgot password</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div>
                <h4>Legal</h4>
                <ul>
                    <li><a href="privacy.php">Privacy</a></li>
                    <li><a href="terms.php">Terms</a></li>
                </ul>
            </div>
        </div>
        <div class="footer__bottom">
            <span>&copy; <?= date('Y') ?> <?= e(APP_NAME) ?>. All rights reserved.</span>
            <span>Built for Web Application Development, University of Central Punjab.</span>
        </div>
    </div>
</footer>
<script src="assets/js/app.js" defer></script>
</body>
</html>
