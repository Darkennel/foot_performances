<!DOCTYPE html>
<html class="forminsc">

<?php
session_start();
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $_SESSION['mailsaisi'] = $email;
    // Redirection vers mdpchanger.php
    header("Location: mdpchanger.php");
    exit();
}
?>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Mot de passe oublié</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
        .error-message {
            color: red;
        }
    </style>
</head>

<body>

    <div class="mdpoublie">
        <p>Veuillez entrer votre adresse mail afin de recevoir des indications.</p>
        <form action="mdpchanger.php" id="passwordResetForm" method="POST">
            <label for="email">Adresse e-mail :</label>
            <input type="email" name="email" required><br>
            <br>
            <button type="submit" id="submitButton">Envoyer</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#submitButton').on('click', function(event) {
                event.preventDefault();

                var emailInput = $('input[name="email"]');
                var email = emailInput.val().trim();

                function validateEmail(email) {
                    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    return emailRegex.test(email);
                }

                emailInput.css('border-color', '');
                emailInput.next('.error-message').remove();

                if (!validateEmail(email)) {
                    emailInput.css('border-color', 'red');
                    emailInput.after('<div class="error-message">Veuillez entrer une adresse e-mail valide.</div>');
                } else {
                    $.ajax({
                        url: 'envoyer_email.php',
                        method: 'POST',
                        data: {
                            email: email
                        },
                        dataType: 'json',
                        success: function(response) {
                            console.log('Response:', response);

                            if (response.exists === true) {
                                emailInput.css('border-color', 'green');
                                setTimeout(function() {
                                    window.location.href = 'mdpchanger.php?email=' + encodeURIComponent(email);
                                }, 1000);
                                emailInput.next('.error-message').remove();
                            } else {
                                emailInput.css('border-color', 'red');
                                alert("E-mail introuvable");
                                emailInput.after('<div class="error-message">E-mail introuvable.</div>');
                            }
                        },
                        error: function() {
                            console.log('Une erreur s\'est produite lors de la vérification de l\'adresse e-mail.');
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>