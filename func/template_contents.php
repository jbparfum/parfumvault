<?php
/**
 * @file template_contents.php
 * @brief Generates dynamic content for the login page based on conditions.
 * 
 * This file contains a function that generates HTML content for the login page.
 * It checks various conditions such as user registration, password reset, and email confirmation,
 * and returns the appropriate HTML content to be injected into the template.
 */
/**
 * Generate dynamic content based on conditions
 *
 * @param mysqli $conn Database connection
 * @return string HTML content to be injected into the template
 */
function generateContent($conn) {
    global $product, $system_settings;

    // Load language file
    $langFile = __ROOT__ . '/pvTemplates/lang/en.php';
    if (file_exists($langFile)) {
        $lang = include($langFile);
    } else {
        $lang = []; // Fallback to an empty array if the language file is missing
    }

    // Show registration form if no users exist
    if ($conn->query("SELECT id FROM users LIMIT 1")->num_rows == 0) {
        return <<<HTML
<!-- Registration HTML -->
 <div class="row">

<div class="col-lg-6 d-none d-lg-block bg-register-image"></div>
    <div class="col-lg-6">
        <div class="p-5">
            <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">{$lang['register_user_title']}</h1>
            </div>
            <div id="msg"></div>
            <div class="user" id="reg_form">
                <hr>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="fullName" placeholder="{$lang['full_name_placeholder']}">
                    <label for="fullName">{$lang['full_name_label']}</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" placeholder="{$lang['email_placeholder']}">
                    <label for="email">{$lang['email_label']}</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control password-input" id="password" placeholder="{$lang['password_placeholder']}">
                    <label for="password">{$lang['password_label']}</label>
                    <i class="toggle-password fa fa-eye"></i>
                </div>
                <button class="btn btn-primary btn-user btn-block" id="registerSubmit">
                    {$lang['register_button']}
                </button>
            </div>
        </div>
    </div>
</div>
HTML;
    }

    // Default to login form
    $registerLink = '';
    if ($system_settings['USER_selfRegister'] == '1') {
        $registerLink = <<<HTML
        <div class="text-center">
            <a class="small" href="/register.php">Create an Account!</a>
        </div>
        HTML;
    }
    
    // Forgot password block
    $forgotPassBlock = '';
    $forgotPassBlock = <<<HTML
    <hr />
    <div class="text-center">
        <a class="small" href="#" data-bs-toggle="modal" data-bs-target="#forgot_pass">{$lang['forgot_password_link']}</a>
    </div>
    HTML;
    

    
    // Final HTML output
    return <<<HTML
    <div class="row">
        <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
        <div class="col-lg-6">
            <div class="p-5">
                <div class="text-center">
                    <h1 class="h4 mb-4">{$lang['sign_in_title']}</h1>
                </div>
                <div id="msg"></div>
                <div class="user" id="login_form">
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="login_email" placeholder="{$lang['email_placeholder']}">
                        <label for="login_email">{$lang['email_label']}</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="login_pass" placeholder="{$lang['password_placeholder']}">
                        <label for="login_pass">{$lang['password_label']}</label>
                    </div>
                    <div class="form-group"></div>
                    <button class="btn btn-primary btn-user btn-block" id="login_btn">
                        {$lang['sign_in_button']}
                    </button>
                </div>
                {$forgotPassBlock}
                {$registerBlock}
            </div>
        </div>
    </div>
    
    <!-- Forgot Password Modal -->
    <div class="modal fade" id="forgot_pass" data-bs-backdrop="static" tabindex="-1" aria-labelledby="forgot_pass_label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forgot_pass_label">Password Reset</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>To reset your password, please run the following command in your PV container:</p>
                    <code>reset_pass.sh</code>
                    <p class="mt-2">This script will guide you through the password reset process.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    HTML;
    
}
?>
