<?php

class pluginPasswordProtect extends Plugin {

    // Initialize plugin
    public function init() {
        // Define database fields for plugin settings
        $this->dbFields = array(
            'masterPassword' => '',
            'sessionTimeout' => 3600,
            'enableMaster' => false
        );
    }

    // Admin panel form for plugin settings
    public function form() {
        global $L;
        
        $html = '<div class="alert alert-primary" role="alert">';
        $html .= 'To protect a page, add a custom field called <strong>pagePassword</strong> to your page with the desired password.';
        $html .= '</div>';

        // Enable master password checkbox
        $html .= '<div class="form-group">';
        $html .= '<label>Enable Master Password</label>';
        $html .= '<select name="enableMaster" class="form-control">';
        $html .= '<option value="false"'.($this->getValue('enableMaster') === false ? ' selected' : '').'>No</option>';
        $html .= '<option value="true"'.($this->getValue('enableMaster') === true ? ' selected' : '').'>Yes</option>';
        $html .= '</select>';
        $html .= '<small class="form-text text-muted">Allow a master password that works for all protected pages</small>';
        $html .= '</div>';

        // Master password field
        $html .= '<div class="form-group">';
        $html .= '<label>Master Password</label>';
        $html .= '<input type="password" name="masterPassword" class="form-control" value="'.$this->getValue('masterPassword').'" placeholder="Leave empty to disable">';
        $html .= '<small class="form-text text-muted">This password will unlock any protected page</small>';
        $html .= '</div>';

        // Session timeout
        $html .= '<div class="form-group">';
        $html .= '<label>Session Timeout (seconds)</label>';
        $html .= '<input type="number" name="sessionTimeout" class="form-control" value="'.$this->getValue('sessionTimeout').'" min="60">';
        $html .= '<small class="form-text text-muted">How long users stay logged in after entering correct password (default: 3600 = 1 hour)</small>';
        $html .= '</div>';

        return $html;
    }

    // Hook that executes before site content loads
    public function beforeSiteLoad() {
        global $page, $url, $WHERE_AM_I;

        // Only check password on individual pages, not home or other sections
        if ($WHERE_AM_I !== 'page') {
            return;
        }

        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Get the page password from custom field
        $pagePassword = $page->custom('pagePassword');

        // If no password is set for this page, allow access
        if (empty($pagePassword)) {
            return;
        }

        // Get page slug for session tracking
        $pageSlug = $page->slug();

        // Check if password was submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['page_password'])) {
            $submittedPassword = $_POST['page_password'];
            $masterPassword = $this->getValue('masterPassword');
            $enableMaster = $this->getValue('enableMaster');

            // Verify password (check both page password and master password)
            $passwordCorrect = ($submittedPassword === $pagePassword);
            
            if ($enableMaster && !empty($masterPassword)) {
                $passwordCorrect = $passwordCorrect || ($submittedPassword === $masterPassword);
            }

            if ($passwordCorrect) {
                // Store authentication in session
                $_SESSION['protected_page_' . $pageSlug] = time();
                
                // Redirect to same page to prevent form resubmission
                header('Location: ' . $page->permalink());
                exit;
            } else {
                // Set error flag
                $_SESSION['password_error_' . $pageSlug] = true;
            }
        }

        // Check if user is authenticated for this page
        $sessionKey = 'protected_page_' . $pageSlug;
        $sessionTimeout = $this->getValue('sessionTimeout');

        if (isset($_SESSION[$sessionKey])) {
            $loginTime = $_SESSION[$sessionKey];
            
            // Check if session has expired
            if ((time() - $loginTime) < $sessionTimeout) {
                // User is authenticated and session is valid
                return;
            } else {
                // Session expired, clear it
                unset($_SESSION[$sessionKey]);
            }
        }

        // User is not authenticated, show password form
        $this->showPasswordForm($pageSlug);
        exit;
    }

    // Display password protection form
    private function showPasswordForm($pageSlug) {
        global $page, $site, $L;

        $errorMessage = '';
        if (isset($_SESSION['password_error_' . $pageSlug])) {
            $errorMessage = '<div class="alert alert-danger">' . $L->get('incorrect-password') . '</div>';
            unset($_SESSION['password_error_' . $pageSlug]);
        }

        // HTML structure matching Bludit's design system
        $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . $page->title() . ' - ' . $site->title() . '</title>
    <style>
        :root {
            --color-background: rgba(252, 252, 249, 1);
            --color-surface: rgba(255, 255, 253, 1);
            --color-text: rgba(19, 52, 59, 1);
            --color-primary: rgba(33, 128, 141, 1);
            --color-primary-hover: rgba(29, 116, 128, 1);
            --color-border: rgba(94, 82, 64, 0.2);
            --color-error: rgba(192, 21, 47, 1);
            --radius-base: 8px;
            --space-16: 16px;
        }
        
        * { box-sizing: border-box; }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: var(--color-background);
            color: var(--color-text);
            margin: 0;
            padding: var(--space-16);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        
        .password-container {
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-base);
            padding: 32px;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.04);
        }
        
        h1 {
            margin: 0 0 8px 0;
            font-size: 24px;
            font-weight: 600;
        }
        
        p {
            margin: 0 0 24px 0;
            color: rgba(98, 108, 113, 1);
        }
        
        .alert {
            padding: 12px;
            border-radius: var(--radius-base);
            margin-bottom: 16px;
        }
        
        .alert-danger {
            background: rgba(192, 21, 47, 0.1);
            color: var(--color-error);
            border: 1px solid rgba(192, 21, 47, 0.2);
        }
        
        .form-group {
            margin-bottom: 16px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 14px;
        }
        
        input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            font-size: 14px;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-base);
            background: var(--color-surface);
            color: var(--color-text);
        }
        
        input[type="password"]:focus {
            outline: 2px solid var(--color-primary);
            border-color: var(--color-primary);
        }
        
        button {
            width: 100%;
            padding: 10px 16px;
            background: var(--color-primary);
            color: white;
            border: none;
            border-radius: var(--radius-base);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        button:hover {
            background: var(--color-primary-hover);
        }
        
        .lock-icon {
            text-align: center;
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <div class="password-container">
        <div class="lock-icon">🔒</div>
        <h1>' . $L->get('password-required') . '</h1>
        <p>' . $L->get('enter-password') . '</p>
        ' . $errorMessage . '
        <form method="post" action="">
            <div class="form-group">
                <label for="page_password">Password</label>
                <input type="password" id="page_password" name="page_password" 
                       placeholder="' . $L->get('password-placeholder') . '" 
                       required autofocus>
            </div>
            <button type="submit">' . $L->get('submit') . '</button>
        </form>
    </div>
</body>
</html>';

        echo $html;
    }

    // Add custom CSS to admin panel
    public function adminHead() {
        $html = '<style>
            .password-protect-info {
                background: #e3f2fd;
                padding: 12px;
                border-radius: 4px;
                margin: 10px 0;
            }
        </style>';
        return $html;
    }
}
