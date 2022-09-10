<?php 
$title = "Contact";
include "includes/_head.php";
include "includes/_nav.php";
// Base de donnée requis
require_once 'config/db.php';
?>
        <!-- Page Header-->
        <header class="masthead" style="background-image: url('assets/img/contact-bg.jpg')">
            <div class="container position-relative px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-md-10 col-lg-8 col-xl-7">
                        <div class="page-heading">
                            <h1>Contactez-moi</h1>
                            <span class="subheading">Avez-vous des questions ? Je vous donne la réponse.</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Traitement  du formulaire -->
        <?php if(isset($_POST['contactForm'])){
            $errors = [];
            global $nom;
            $nom = htmlspecialchars($_POST['nom']);
            $email = htmlspecialchars($_POST['email']);
            $phone = htmlspecialchars($_POST['phone']);
            $message = htmlentities($_POST['message']);

            // nom 
            if(strlen($nom)<= 3){
                $errors['nom'] = "Entrer un nom valide (> 3 caractères)";
            }

            // email

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $errors ['email'] = "Entrer un email valide";
            }

            // phone
            if( strlen($phone)<5){
                $errors['phone'] = "Entrer un numéro de téléphone valide";
            }

            // messsage 
            if(strlen($message)<10){
                $errors ['message'] = "Votre message est trop court";
            }

            if(empty($errors)){
                $req=$db->prepare("INSERT INTO message (nom,email, phone, message) VALUES(?, ?, ?, ?)");
                $req->execute([$nom, $email, $phone, $message]);
                echo <<<html
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <p class="text-center">Message envoyé avec succès! </p>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>;
html;
            }else{
                $errors['contactForm'] = "Vous n'avez pas correctement remplis le formulaire";
            }


        
        }
        
        
        
        ?>

        <!-- Main Content-->
        <main class="mb-4">
            <div class="container px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-md-10 col-lg-8 col-xl-7">
                        <p>Remplissez le formulaire suivant et laissez-moi votre message</p>
                        <div class="my-5">
                            <!--Alert -->
                            <?php if(!empty($errors)): ?>
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                        <?php echo (implode("<br/>",$errors)); ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif ?>
             
                            <!-- * * * * * * * * * * * * * * *-->
                            <!-- * * SB Forms Contact Form * *-->
                            <!-- * * * * * * * * * * * * * * *-->
                            <!-- This form is pre-integrated with SB Forms.-->
                            <!-- To make this form functional, sign up at-->
                            <!-- https://startbootstrap.com/solution/contact-forms-->
                            <!-- to get an API token!-->
                            <form id="contactForm" action="" method="POST" data-sb-form-api-token="API_TOKEN">
                                <div class="form-floating">
                                    <input class="form-control" id="name" type="text" name="nom" placeholder="Entrer votre nom..." data-sb-validations="required" />
                                    <label for="name">Nom</label>
                                    <div class="invalid-feedback" data-sb-feedback="name:required">Le Nom est requis.</div>
                                </div>
                                <div class="form-floating">
                                    <input class="form-control" id="email" type="email" name="email" placeholder="Entrer votre email..." data-sb-validations="required,email" />
                                    <label for="email">Adresse Email</label>
                                    <div class="invalid-feedback" data-sb-feedback="email:required">L'email est requis.</div>
                                    <div class="invalid-feedback" data-sb-feedback="email:email">L'email n'est pas valide.</div>
                                </div>
                                <div class="form-floating">
                                    <input class="form-control" id="phone" type="tel" name="phone" placeholder="Entrer votre numéro de téléphone ..." data-sb-validations="required" />
                                    <label for="phone">Numéro de téléphone</label>
                                    <div class="invalid-feedback" data-sb-feedback="phone:required">Le numéro de téléphone est requis.</div>
                                </div>
                                <div class="form-floating">
                                    <textarea class="form-control" id="message" name="message" placeholder="Entrer votre message ici ..." style="height: 12rem" data-sb-validations="required"></textarea>
                                    <label for="message">Message</label>
                                    <div class="invalid-feedback" data-sb-feedback="message:required">Le message est requis.</div>
                                </div>
                                <br />
                                <!-- Submit success message-->
                                <!---->
                                <!-- This is what your users will see when the form-->
                                <!-- has successfully submitted-->
                                <div class="d-none" id="submitSuccessMessage">
                                    <div class="text-center mb-3">
                                        <div class="fw-bolder">Form submission successful!</div>
                                        To activate this form, sign up at
                                        <br />
                                        <a href="https://startbootstrap.com/solution/contact-forms">https://startbootstrap.com/solution/contact-forms</a>
                                    </div>
                                </div>
                                <!-- Submit error message-->
                                <!---->
                                <!-- This is what your users will see when there is-->
                                <!-- an error submitting the form-->
                                <div class="d-none" id="submitErrorMessage"><div class="text-center text-danger mb-3">Error sending message!</div></div>
                                <!-- Submit Button-->
                                <button class="btn btn-primary text-uppercase " name="contactForm" id="submitButton" type="submit">Envoyer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include "includes/_footer.php"; ?>
