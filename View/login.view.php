<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.style.css">
    <link rel="stylesheet" href="form.style.css">
    <title>Home</title>
</head>
<body>

    
    <div class="main-field">
        <fieldset class="connection-field">
    
            <legend>Connection :</legend>
    
            <form action="?page=process-login" method="POST">
    
                <div>
                    <label for="username-connection">Username :</label>
                    <input type="text" name="username" id="username-connection">
                </div>
    
                <div>
                    <label for="password-connection">Password :</label>
                    <input type="password" name="password" id="password-connection">
                </div>
    
                <button type="submit">Login</button>
    
            </form>

            <?php if(isset($_SESSION['login-error'])) echo '<p class="login-error">'.$_SESSION['login-error'].'</p>'; ?>

    
        </fieldset>
    
        <fieldset class="register-field">
    
            <legend>Register :</legend>
    
            <form action="?page=process-register" method="POST">
    
                <div>
                    <label for="username-register">Username :</label>
                    <input type="text" name="username" id="username-register">
                </div>
    
                <div>
                    <label for="password-register">Password :</label>
                    <input type="password" name="password" id="password-register">
                </div>
    
                <div>
                    <label for="password-check-register">Repeat Password :</label>
                    <input type="password" name="password_check" id="password-check-register">
                </div>
    
                <button type="submit">Register</button>
    
            </form>
    
            <?php if(isset($_SESSION['register-error'])) echo '<p class="login-error">'.$_SESSION['register-error'].'</p>'; ?>

        </fieldset>

    
    </div>

</body>
</html>