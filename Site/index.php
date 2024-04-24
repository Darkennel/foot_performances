<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="Sites.css">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Performances football</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <header class="header-block">
        <div class="header-content">
            
            <div class="title-and-search">
                <h1>FOOTBALL STAT</h1>
            </div>

        </div>
    </header>


    <div class="data-container5">
        <div class="formulaire">


            <form id = "login" action="connecter.php" method="post" autocomplete="off">
                <p> Identifiant (mail): <input type="text" name="adrmail" value=""></p><br>
                <p> Mot de passe : <input type="password" name="mdpcon" value=""></p><br>
                <p><input type="submit" id="seconn" value="Se connecter"></p><br>
            </form>

            <a href="mdpoublie.php">Mot de passe oublié ?</a>
            <a href="nouveau.php">Créer un compte ?</a>
            <a href="Acceuil.php">Invité ?</a>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            $('#login').submit(function(event) {
                event.preventDefault();

                var emailInput = $('input[name="adrmail"]');
                var passwordInput = $('input[name="mdpcon"]');
                var emailValue = emailInput.val().trim();
                var passwordValue = passwordInput.val().trim();
                var emailFormat = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                $('.error-message').remove();
                emailInput.css('border', '1px solid #ccc');
                passwordInput.css('border', '1px solid #ccc');
                $('#form-error').remove();

                if (emailValue !== '' && passwordValue !== '') {
                    if (emailFormat.test(emailValue)) {
                        var formData = $(this).serialize();
                        console.log(formData);

                        $.ajax({
                            url: 'connecter.php',
                            type: 'POST',
                            data: formData,
                            dataType: 'json',
                            success: function(response) {
                                console.log(response);
                                if (response.valide === true) {
                                    $('body').prepend('<p id="success-message" style="color: white; background: green; position: fixed; top: 100px; left: 47%; transform: translateX(-50%); width: 200px; text-align: center;">Connexion en cours. Vous allez être redirigé.</p>');
                                    setTimeout(function() {
                                        window.location.href = 'Acceuil.php';
                                    }, 1000);
                                } else {
                                    $('body').prepend('<p id="success-message" style="color: white; background: red; position: fixed; top: 100px; left: 47%; transform: translateX(-50%); width: 200px; text-align: center;">Mot de passe incorrect ou email introuvable</p>');
                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.log("AJAX Error:", textStatus, errorThrown);
                            }
                        });
                    } else {
                        emailInput.after('<p class="error-message" style="color: red;">Adresse e-mail incorrecte</p>');
                        emailInput.css('border', '1px solid red');
                    }
                } else {
                    $('body').prepend('<p id="success-message" style="color: white; background: red; position: fixed; top: 100px; left: 47%; transform: translateX(-50%); width: 200px; text-align: center;">Veuillez remplir les champs vides</p>');
                    if (emailValue === '') {
                        emailInput.css('border', '1px solid red');
                    }
                    if (passwordValue === '') {
                        passwordInput.css('border', '1px solid red');
                    }
                }
            });

            $('input[name="adrmail"], input[name="mdpcon"]').on('input', function() {
                $('#success-message').remove();
            });

            $('input[name="adrmail"], input[name="mdpcon"]').on('input', function() {
                $('.error-message').remove();
                $(this).css('border', '1px solid #ccc');
                $('#form-error').remove();
                $('p:contains("Mot de passe incorrect ou email introuvable")').remove();
            });

        });
    </script>




</body>

</html>