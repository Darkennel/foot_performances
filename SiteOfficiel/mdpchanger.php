<?php
session_start();

if (isset($_GET['email'])) {
    $email = $_GET['email'];
    $_SESSION['mailsaisi'] = $email;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="Sites.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changement de Mot de Passe</title>
</head>


<body>
    <div class="data-container3">
        <div class="formulaire">
            <form action="traitement_changement_mdp.php" method="post" id="passwordChangeForm">
            <p>Votre adresse-mail : <?php echo isset($_SESSION['mailsaisi']) ? $_SESSION['mailsaisi'] : 'bon'; ?></p><br>

                <input type="hidden" name="email" value="<?php echo isset($_SESSION['mailsaisi']) ? $_SESSION['mailsaisi'] : ''; ?>">
                <label for="code_verification">Code de vérification :</label>
                <input type="text" name="code_verification" required><br>
                <br>
                <label for="nouveau_mdp">Nouveau mot de passe :</label>
                <input type="password" name="nouveau_mdp" required>
                <br>
                <label for="confnouveau_mdp">Confirmer nouveau mot de passe :</label>
                <input type="password" name="confnouveau_mdp" required><br>
                <br>
                <button><input type="submit" id="seconn" value="Valider"></button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function validateField(input, errorMessage, validationFunction) {
                var value = input.val().trim();
                var isValid = validationFunction(value);

                if (isValid || value === '') {
                    input.css('border-color', isValid ? 'green' : 'red');
                    input.next('.error-message').remove();
                    return isValid;
                } else {
                    input.css('border-color', 'red');
                    input.next('.error-message').remove();
                    if (value.length > 0) {
                        input.after('<div class="error-message">' + errorMessage + '</div>');
                    }
                    return false;
                }
            }

            var storedEmail = "<?php echo isset($_SESSION['mailsaisi']) ? $_SESSION['mailsaisi'] : ''; ?>";

            if (storedEmail !== '') {
                $('#emailInput').val(storedEmail);
            }

            $('input[name="code_verification"]').on('input', function() {
                validateField($(this), 'Le code de vérification doit avoir exactement 8 caractères.', function(value) {
                    return value.length === 8;
                });
                validateForm();
            });

            $('input[name="nouveau_mdp"]').on('input', function() {
                validateField($(this), 'Le mot de passe doit contenir au moins 8 caractères (dont un caractere special et un chiffre).', function(value) {
                    var isLowerCaseValid = /[a-z]/.test(value);
                    var isUpperCaseValid = /[A-Z]/.test(value);
                    var isDigitValid = /\d/.test(value);
                    var isSpecialCharValid = /[@$!%*?&,;.:/_]/.test(value);
                    return value.length >= 8 && isLowerCaseValid && isUpperCaseValid && isDigitValid && isSpecialCharValid;
                });
                validateForm();
            });

            $('input[name="confnouveau_mdp"]').on('blur', function() {
                validateField($(this), 'Les mots de passe ne correspondent pas.', function(value) {
                    return value === $('input[name="nouveau_mdp"]').val().trim();
                });
                validateForm();
            });

            function validateForm() {
                var isEmailValid = validateField($('input[name="email"]'), 'Veuillez entrer une adresse e-mail valide.', function(value) {
                    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    return emailRegex.test(value);
                });

                var isPasswordMatch = validateField($('input[name="confnouveau_mdp"]'), 'Les mots de passe ne correspondent pas.', function(value) {
                    return value === $('input[name="nouveau_mdp"]').val().trim();
                });

                var isFormValid = isEmailValid && isPasswordMatch;

                $('input[type="submit"]').prop('disabled', !isFormValid);
            }
        });
    </script>

</body>

</html>