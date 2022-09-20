<?php 
$title = "Inscription";
include "includes/_head.php";
include "includes/_nav.php";
// connection database 
require_once "config/db.php";
?>
        <!-- Page Header-->
        <header class="masthead" style="background-image: url('assets/img/login-bg.jpg')">
            <div class="container position-relative px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-md-10 col-lg-8 col-xl-7">
                        <div class="page-heading">
                            <h1>Espace Membres</h1>
                            <span class="subheading">Restez au courant de l'actualialité de mon blog en créant un compte.</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <?php 
        // Traitement du formulaire d'inscription
        if(isset($_POST['register'])){
            $errors = [];
            $pseudo = htmlspecialchars($_POST['pseudo']);
            $nom = htmlspecialchars($_POST['nom']);
            $prenoms = htmlspecialchars($_POST['prenoms']);
            $email = htmlspecialchars($_POST['email']);

            // pseudo 

            if(strlen($pseudo) < 3){
                $errors['pseudo'] = "Pseudo trop court (Min 3 caractères)";
            }else{
                $req=$db->prepare("SELECT id FROM users WHERE pseudo = ? ");
                $req->execute([$pseudo]);
                $user = $req->fetch();
                if($user){
                  $errors['username'] = "Votre pseudo existe déjà !";
                }
            }

            // nom
            
            if(empty($nom)){
                $errors['nom'] = "Votre nom n'est pas valide"; 
            }

            // prenoms

            if(empty($prenoms)){
                $errors['prenoms'] = "Votre prenoms n'est pas valide"; 
            }
            // email

            if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
                $errors['email'] = "Votre adresse email n'est pas valide";
            }else{
                $req = $db->prepare("SELECT id FROM users WHERE email = ? ");
                $req->execute([$email]);
                $user = $req->fetch();
                if($user){
                $errors['email'] = "Votre email existe déjà !";
                }
            }

            // mot de passe 

            if(empty($_POST['motDePasse'])){
                $errors['motDePasse'] = "votre mot de passe n'est pas valide";
            }elseif( $_POST['motDePasse'] != $_POST['confirmerMotDePasse']){
                $errors['motDePasse'] = "Les mots de passe ne correspondent pas.";
            }
            
            // Verification et insertion dans la base de donnée
         
            if(empty($errors)){
                $req = $db->prepare("INSERT INTO users (pseudo, nom, prenoms, email, motDePasse) VALUES (?, ?, ?, ?, ?)");
                $password = password_hash($_POST['motDePasse'], PASSWORD_BCRYPT);
                //$password = sha1($_POST['password']);
                $req->execute([ $pseudo,
                                $nom, 
                                $prenoms, 
                                $email,
                                $password]);


                header('Location:login.php');
                exit();
            }else{
                $errors['registerForm'] = "Vous n'avez pas bien remplis le formulaire"; 
            }




        }
        
        ?>
<div class="card">
  <div class="card-header text-center"> Inscription </div>
            <!--Alert -->
            <?php if(isset($errors)): ?>
                <div class="alert alert-danger alert-dismissible text-center" role="alert">
                        <?php echo (implode("<br/>",$errors)); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif ?>

    <div class="container px-5 my-5">
        <form id="contactForm" action="" method="POST" data-sb-form-api-token="API_TOKEN">
            
            <div class="form-floating mb-3">
                <input class="form-control" id="pseudo" name="pseudo" type="text" placeholder="Pseudo" data-sb-validations="required" />
                <label for="pseudo">Pseudo</label>
                <div class="invalid-feedback" data-sb-feedback="pseudo:required">Votre Pseudo est obligatoire.</div>
            </div>
            <div class="form-floating mb-3">
                <input class="form-control" id="nom" name="nom" type="text" placeholder="Nom" data-sb-validations="required" />
                <label for="nom">Nom</label>
                <div class="invalid-feedback" data-sb-feedback="nom:required">Votre Nom est obligatoire.</div>
            </div>
            <div class="form-floating mb-3">
                <input class="form-control" id="prenoms" name="prenoms" type="text" placeholder="Prénoms" data-sb-validations="required" />
                <label for="prenoms">Prénoms</label>
                <div class="invalid-feedback" data-sb-feedback="prenoms:required">Votre Prénom(s) est obligatoire.</div>
            </div>
            <div class="form-floating mb-3">
                <input class="form-control" id="adresseEmail" name="email" type="email" placeholder="Adresse Email" data-sb-validations="required,email" />
                <label for="adresseEmail">Adresse Email</label>
                <div class="invalid-feedback" data-sb-feedback="adresseEmail:required">Adresse Email est obligatoire.</div>
                <div class="invalid-feedback" data-sb-feedback="adresseEmail:email">Adresse Email n'est pas valide.</div>
            </div>
            <div class="form-floating mb-3">
                <input class="form-control" id="motDePasse" name="motDePasse" type="password" placeholder="Mot de passe" data-sb-validations="required" />
                <label for="motDePasse">Mot de passe</label>
                <div class="invalid-feedback" data-sb-feedback="motDePasse:required">Mot de passe est obligatoire.</div>
            </div>
            <div class="form-floating mb-3">
                <input class="form-control" id="confirmerMotDePasse" name="confirmerMotDePasse" type="password" placeholder="Confirmer mot de passe" data-sb-validations="required" />
                <label for="confirmerMotDePasse">Confirmer mot de passe</label>
                <div class="invalid-feedback" data-sb-feedback="confirmerMotDePasse:required">Confirmer mot de passe est obligatoire.</div>
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
        
            <div class="d-grid">
                <button class="btn btn-primary btn-lg " name="register" id="submitButton" type="submit">S'inscrire</button>
            </div>
            <br>
            <div class="text-center mb-3">
                    <p>Avez-vous déja un compte ?  <a href="login.php">Connectez-vous ici</a> </p> 
            </div>
        </form>
    </div>
    <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
</div>

<?php include "includes/_footer.php"; ?>

