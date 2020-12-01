<?php

// on a ici la route quand on tente de se register
// cela nous permet de recupere les message d'erreur login/register
if($page == "process-register" and isset($_POST)) {
    // on vide les message d'erreur de login/register si ils existent
    if(isset($_SESSION['login-error'])) {
        unset($_SESSION['login-error']);
    }
    if(isset($_SESSION['register-error'])) {
        unset($_SESSION['register-error']);
    }
    // on instantie l'utilisateur avec le contenu du formulaire
    $user = new User($_POST['username'], $_POST['password'], $_POST['password_check']);
    $register = $user->register();
    if($register === true) {
        // si toute les verif sont valide on stock l'utilisateur en session
        $_SESSION['user'] = $user;
        header('location: ?page=home');
        exit;
    // sinon, on renvois les message d'erreur
    } else {
        $_SESSION['register-error'] = $register;
    }
}
header("location: index.php");
exit;