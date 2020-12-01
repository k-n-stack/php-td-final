<?php

// on a ici la route quand on tente de se connecter
// cela nous permet de recupere les message d'erreur login/register
if($page == "process-login" and isset($_POST)) {
    // on vide les message d'erreur de login/register si ils existent
    if(isset($_SESSION['login-error'])) {
        unset($_SESSION['login-error']);
    }
    if(isset($_SESSION['register-error'])) {
        unset($_SESSION['register-error']);
    }
    // on instantie l'utilisateur avec le contenu du formulaire
    $user = new User($_POST['username'], $_POST['password']);
    $check = $user->login();
    // si la connection passe on met l'utilisateur en session (connection reussie) puis on renvois sur la page "home"
    if($check === true) {
        $_SESSION['user'] = $user;
        header('location: ?page=home');
        exit;
    // sinon, on renvois les message d'erreur
    } else {
        $_SESSION['login-error'] = $check;
    }
}

header('location: index.php');