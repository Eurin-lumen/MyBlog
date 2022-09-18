<?php
session_start(); 
$title = "Connexion";
include "includes/_head.php";
include "includes/_nav.php";
// besoin de la base de donnée
require_once "config/db.php";
?>
        <!-- Page Header-->
        <header class="masthead" style="background-image: url('assets/img/login-bg.jpg')">
            <div class="container position-relative px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-md-10 col-lg-8 col-xl-7">
                        <div class="page-heading">
                            <h1>Espace Membres</h1>
                            <span class="subheading">Connectez-vous et commencer votre aventure ici.</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <?php 
        // Traitement de formulaire 

if (isset($_POST['login'])) {
    $errors = [];

    $pseudoLog = htmlspecialchars($_POST['pseudo']);
    $password = sha1($_POST['password']);

    if (!empty($pseudoLog) && !empty($_POST['password'])) {
        $req = $db->prepare("SELECT * FROM users WHERE pseudo = :pseudo OR email = :pseudo  AND motDePasse = :password");
        $req->execute([':pseudo'=> $pseudoLog, ':password' => $password]);
        $user = $req->rowCount();
        if ($user == true) {
            $userinfo = $req->fetch();
            $_SESSION['id'] = $userinfo['id'];
            $_SESSION['pseudo'] = $userinfo['pseudo'];
            $_SESSION['nom'] = $userinfo['nom'];
            $_SESSION['prenoms'] = $userinfo['prenoms'];
            $_SESSION['email'] = $userinfo['email'];
            header("Location:profil.php?id=".$_SESSION['id']);
            exit();
        } else {
            $errors['pass'] = "Identifiants incorrecte";
            echo '<pre>';
            var_dump($user);
            echo '</pre>';
        }
    // var_dump($user);
    } else {
        $errors['pseudoLog'] = " Champs vide : Veuillez complèter tout les champs";
    }
}
        
        
        ?>
<div class="card">
  <div class="card-header text-center"> Connexion </div>
   <!--Alert -->
   <?php if(!empty($errors)): ?>
        <div class="alert alert-danger alert-dismissible text-center" role="alert">
            <?php echo (implode("<br/>", $errors)); ?>
           <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
  <?php endif ?>

    <div class="container px-5 my-5">
        <form id="contactForm" action="" method="POST" data-sb-form-api-token="API_TOKEN">
            <div class="form-floating mb-3">
                <input class="form-control" id="pseudo" name="pseudo" type="text" placeholder="Pseudo ou Email" data-sb-validations="required" />
                <label for="pseudo">Pseudo ou Email</label>
                <div class="invalid-feedback" data-sb-feedback="pseudo:required">Pseudo ou Email est obligatoire.</div>
            </div>

            <div class="form-floating mb-3">
                <input class="form-control" id="motDePasse" name="password" type="password" placeholder="Mot de passe" data-sb-validations="required" />
                <label for="motDePasse">Mot de passe</label>
                <div class="invalid-feedback" data-sb-feedback="motDePasse:required">Votre Mot de passe est obligatoire.</div>
            </div>

            <div class="d-grid">
                <button class="btn btn-primary btn-lg " name="login" id="submitButton" type="submit">Se Connecter</button>
            </div>

            <div class="d-none" id="submitSuccessMessage">
                <div class="text-center mb-3">
                    <div class="fw-bolder">Form submission successful!</div>
                    <p>To activate this form, sign up at</p>
                    <a href="https://startbootstrap.com/solution/contact-forms">https://startbootstrap.com/solution/contact-forms</a>
                </div>
            </div>
            <div class="d-none" id="submitErrorMessage">
                <div class="text-center text-danger mb-3">Error sending message!</div>
            </div>
            <div class="text-center mb-3">
                    <p>C'est votre première fois  ?  <a href="register.php">Inscrivez-vous ici</a> </p> 
            </div>
        </form>
    </div>
    <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
</div>
 <?php include "includes/_footer.php";?>