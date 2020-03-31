<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.3.2/css/uikit.min.css" />
    <title>.htaccess secure folder</title>
</head>

<body>

    <div class="uk-container uk-margin-medium-bottom">
        <h3 class="uk-heading-divider uk-text-primary">Step 1: pwgen.php</h3>
        <ul>
            <li>Create a new file <b>pwgen.php</b> with <a href="https://gist.github.com/pverhaert/bf75f97ef185f1b823cfbb7a11e40fcd" target="_blank">this Gist</a>
                as content.
            </li>
            <li>Upload the file <b>pwgen.php</b> to the server in the folder to be protected.<br>
                E.g: <b>https://yourdomain.com/secret/</b> is your protected folder.
            </li>
        </ul>

        <h3 class="uk-heading-divider uk-text-primary">Step 2: .htaccess file</h3>
        <ul>
            <li>Create a local <b>.htaccess</b> file.</li>
            <li>Open the the password generator in a browser.<br>
                E.g: <b>https://yourdomain.com/secret/pwgen.php</b></li>
            <li>Place the code below your local <b>.htaccess</b> file.</li>
            <li>Don't upload yet!</li>
        </ul>
        <?php
    $dirName = $_SERVER['SCRIPT_FILENAME'];
    $dirName = str_replace("\\", "/", $dirName);
    $dirName = dirname($dirName) . '/password.txt';
    if (substr($dirName, 0, 1) != "/") {
        $dirName = '"' . $dirName . '"';
    }
    ?>
        <pre><code>
AuthName "Login"
AuthType Basic
AuthUserFile <?php echo $dirName ?>

require valid-user

&lt;Files password.txt&gt;
order deny,allow
deny from all
&lt;/Files&gt;
    </code></pre>

        <h3 class="uk-heading-divider uk-text-primary">Stap 3: hash password</h3>
        <ul>
            <li>Use the form below to generate a hashed <b>username:password</b> combination.</li>
        </ul>
        <form method="post">
            <div class="uk-margin">
                <div class="uk-inline">
                    <span class="uk-form-icon" uk-icon="icon: user"></span>
                    <input class="uk-input" type="text" placeholder="username" name="user" value="<?php echo((isset($_POST["user"])) ? $_POST["user"] : "") ?>">
                </div>
            </div>
            <div class="uk-margin">
                <div class="uk-inline">
                    <span class="uk-form-icon" uk-icon="icon: lock"></span>
                    <input class="uk-input" type="password" placeholder="password" name="pw" value="<?php echo((isset($_POST["pw"])) ? $_POST["pw"] : "") ?>">
                </div>
            </div>
            <p class="uk-margin">
                <button class="uk-button uk-button-primary" type="submit">Generate username:password</button>
            </p>
        </form>

        <?php
    if (isset($_POST[user]) && ($_POST[user] != "") && isset($_POST[pw]) && ($_POST[pw] != "")) {
        $cost = 12;     // a higher $cost is safer but slower to generate
        $user = $_POST[user];
        $pw = $_POST[pw];
        $hash = password_hash($pw, PASSWORD_BCRYPT, ['cost' => $cost]);
        if ($hash === false) {
            echo 'Bcrypt hashing not supported.';
        }
        ?>
        <h3 class="uk-heading-divider uk-text-danger" id="step4">Step 4: password.txt</h3>
        <ul>
            <li>Create a local <b>password.txt</b> file.</li>
            <li>Place the generated <b>username:password</b> combination (blue box below) in <b>password.txt</b>.<br>
                Place each <b>username:password</b> combination on a <b>separate line</b> in <b>password.txt</b>!
            </li>
        </ul>
        <div class="uk-alert-primary" uk-alert>
            <p><b><?php echo "$user:$hash" ?></b></p>
        </div>

        <h3 class="uk-heading-divider uk-text-danger" id="step4">Step 4: upload .htaccess and password.txt</h3>
        <ul>
            <li>Upload <b>password.text</b> and <b>.htaccess</b> to the folder to be protected:
                <ul>
                    <li><b>https://yourdomain.com/secret/.htaccess</b></li>
                    <li><b>https://yourdomain.com/secret/password.txt</b></li>
                    <li>https://yourdomain.com/secret/pwgen.php</li>
                </ul>
            </li>
            <li>You can check your login by reloading this page.</li>
        </ul>

        <script>
            window.location.href = "#step4";

        </script>
        <?php } ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.3.2/js/uikit.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.3.2/js/uikit-icons.js"></script>

</body>

</html>
