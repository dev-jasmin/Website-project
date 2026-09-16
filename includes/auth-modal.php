<?php
$returnTo = $_SERVER['REQUEST_URI'] ?? '/index.php';
$message = $_GET['message'] ?? '';
$status = $_GET['status'] ?? '';
$showFormStatus = $status === 'error' && $message !== '';
?>

<div class="modal-overlay" id="authModalOverlay" hidden>
    <div class="auth-card" role="dialog" aria-modal="true" aria-labelledby="authModalTitle">
        <button class="modal-close" id="authModalClose" type="button" aria-label="Close">&times;</button>

        <div class="logo">
            <img src="images/logo/logo-cream white.png" alt="Ember">
        </div>

        <div class="auth-switch" role="tablist" aria-label="Account access">
            <button id="tabLogin" type="button" class="is-active" role="tab">Log in</button>
            <button id="tabSignup" type="button" role="tab">Sign up</button>
        </div>

        <section class="auth-form" id="loginPanel">
            <h2 id="authModalTitle">Log in</h2>
            <p class="auth-intro">Return to your saved scents, rewards, and Ember account.</p>
            <?php if ($showFormStatus): ?>
                <p class="form-status-error"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>
            <form action="login/login.php" method="post" novalidate>
                <input type="hidden" name="return_to" class="return-to-field" value="<?= htmlspecialchars($returnTo, ENT_QUOTES, 'UTF-8') ?>">
                <div class="field-group">
                    <label for="loginUsername">Username or email</label>
                    <input id="loginUsername" name="username_or_email" type="text" required>
                </div>
                <div class="field-group">
                    <label for="loginPassword">Password</label>
                    <div class="password-field">
                        <input id="loginPassword" name="password" type="password" required>
                        <button type="button" class="show-password" data-target="loginPassword">Show</button>
                    </div>
                </div>
                <button class="auth-button" type="submit" name="log-in">Log in</button>
            </form>
        </section>

        <section class="auth-form hidden" id="signupPanel">
            <h2>Sign up</h2>
            <p class="auth-intro">Create your Ember account and keep close to your favorite scents.</p>
            <form action="register/register.php" method="post" novalidate>
                <input type="hidden" name="return_to" class="return-to-field" value="<?= htmlspecialchars($returnTo, ENT_QUOTES, 'UTF-8') ?>">
                <div class="field-group">
                    <label for="signupUsername">Username</label>
                    <input id="signupUsername" name="username" type="text" required>
                </div>
                <div class="field-group">
                    <label for="signupEmail">Email address</label>
                    <input id="signupEmail" name="email" type="email" required>
                </div>
                <div class="field-group">
                    <label for="signupPassword">Password</label>
                    <div class="password-field">
                        <input id="signupPassword" name="password" type="password" minlength="8" required>
                        <button type="button" class="show-password" data-target="signupPassword">Show</button>
                    </div>
                </div>
                <div class="field-group">
                    <label for="confirmPassword">Confirm password</label>
                    <div class="password-field">
                        <input id="confirmPassword" name="confirm_password" type="password" minlength="8" required>
                        <button type="button" class="show-password" data-target="confirmPassword">Show</button>
                    </div>
                </div>
                <button class="auth-button" type="submit" name="sign-up">Create account</button>
            </form>
        </section>
    </div>
</div>
