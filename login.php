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
        
         include_once 'config/db.php';
         if (!empty($_POST)) {
           $resultsAlerts = array();
           global $db;
           global $req;
         
           /*if(isset($_SESSION['authvend'])){
             header('Location:dashbord-client.php');
               exit();
             
           }*/
       
           if (!empty($_POST) && !empty($_POST['pseudo']) && !empty($_POST['motDePasse'])) {
               $req = $db->prepare("SELECT * FROM users WHERE ( pseudo = :pseudo OR email = :pseudo)");
               $req->execute([':pseudo' =>$_POST['pseudo']]);
               $user = $req->fetch();

               if(password_verify($_POST['motDePasse'], $user->motDePasse)){
                 header('Location:profil.php');
                 exit();
                 
               }else{
                 $resultsAlerts['passwordAlerte'] = "Mot de passe incorrecte";
                 echo '<pre>';
                 echo var_dump($_POST['motDePasse']);
                 echo '</pre>';
               }
           } else {
               $resultsAlerts['connexionform'] = "Identifiant ou mot de passe incorrect";
           }
       }

        ?>
<div class="card">
  <div class="card-header text-center"> Connexion </div>
   <!--Alert -->
   <?php if(!empty($resultsAlerts)): ?>
        <div class="alert alert-danger alert-dismissible text-center" role="alert">
            <?php echo (implode("<br/>", $resultsAlerts)); ?>
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
                <input class="form-control" id="motDePasse" name="motDePasse" type="password" placeholder="Mot de passe" data-sb-validations="required" />
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