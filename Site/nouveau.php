<!DOCTYPE html>
<html class = "acceuil">

<head>
    <title>Nouveau</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <h3> Veuillez créer un compte en respectant vos données. </h3>
    
    <form action="clientenr.php" id="registrationForm" method="post" autocomplete="off">
        <p> Nom : <input type="text" name="n" value="<?php echo isset($_SESSION['n']) ? $_SESSION['n'] : ''; ?>" required><br></p>
        <p> Prénom : <input type="text" name="p" value="<?php echo isset($_SESSION['p']) ? $_SESSION['p'] : ''; ?>" required><br></p>
        <p> Adresse : <input type="text" name="adr" value="<?php echo isset($_SESSION['adr']) ? $_SESSION['adr'] : ''; ?>" required><br></p>
        <p> Téléphone : <input type="text" name="num" value="<?php echo isset($_SESSION['num']) ? $_SESSION['num'] : ''; ?>" required><br></p>
        <p> Mail : <input type="text" name="mail" value="<?php echo isset($_SESSION['mail']) ? $_SESSION['mail'] : ''; ?>" required><br></p>
        <p> Mot de passe : <input type="password" name="mdp1" maxlength="12" required><br></p>
        <p> Confirmer votre mot de passe : <input type="password" name="mdp2" maxlength="12" required><br></p>
        <button type="submit" id="submitButton">Valider</button><br>
        <a href="index.php">Retour à l'accueil</a>
    </form>

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


            $('input[name="n"], input[name="p"], input[name="adr"], input[name="num"], input[name="mail"], input[name="mdp1"], input[name="mdp2"]').css('border-color', 'red');

            $('input[name="n"], input[name="p"], input[name="adr"], input[name="num"], input[name="mail"], input[name="mdp1"], input[name="mdp2"]').on('input', function() {
                validateForm();
            });


            function validateForm() {
                var isNumValid = validateField($('input[name="num"]'), 'Veuillez entrer un numéro de téléphone valide.', function(value) {
                    var phoneRegex = /^[0-9]{10}$/;
                    return phoneRegex.test(value);
                });

                var isMdp1Valid = validateField($('input[name="mdp1"]'), 'Le mot de passe doit contenir au moins 8 caractères, une lettre minuscule et majuscule, un chiffre, un caractere speciaux.', function(value) {
                    var isLowerCaseValid = /[a-z]/.test(value);
                    var isUpperCaseValid = /[A-Z]/.test(value);
                    var isDigitValid = /\d/.test(value);
                    var isSpecialCharValid = /[@$!%*?&,;.:/_]/.test(value);
                    return value.length >= 8 && isLowerCaseValid && isUpperCaseValid && isDigitValid && isSpecialCharValid;
                });

                var isMdp2Valid = validateField($('input[name="mdp2"]'), 'Les mots de passe ne correspondent pas.', function(value) {
                    return value === $('input[name="mdp1"]').val().trim();
                });

                var isFormValid = isNumValid && isMdp1Valid && isMdp2Valid && $('input[name="n"]').val().trim() !== '' && $('input[name="p"]').val().trim() !== '' && $('input[name="adr"]').val().trim() !== '' && $('input[name="num"]').val().trim() !== '';

                $('input[type="submit"]').prop('disabled', !isFormValid);
            }

            $('input[name="n"], input[name="p"], input[name="adr"]').on('blur', function() {
                validateField($(this), 'Ce champ est requis.', function(value) {
                    return value.trim() !== '';
                });
                validateForm();
            });

            $('input[name="num"]').on('input', function() {
                validateField($(this), 'Veuillez entrer un numéro de téléphone valide!', function(value) {
                    var phoneRegex = /^[0-9]{10}$/;
                    return value.trim() !== '' || phoneRegex.test(value);
                });
                validateForm();
            });

            $('input[name="mail"]').on('blur', function() {
                var emailInput = $(this);
                var email = emailInput.val().trim();

                if (!validateEmail(email)) {
                    if (emailInput.next('.error-message').length === 0) {
                        emailInput.css('border-color', 'red');
                        emailInput.after('<div class="error-message">Veuillez entrer une adresse e-mail valide.</div>');
                    }
                } else {
                    emailInput.css('border-color', '');

                    $.ajax({
                        url: 'verifieremail.php',
                        method: 'POST',
                        data: {
                            mail: email
                        },
                        dataType: 'json',
                        success: function(response) {
                            console.log('Response:', response);

                            if (response.exists === true) {
                                emailInput.css('border-color', 'red');
                                emailInput.next('.error-message').remove();
                                if (emailInput.next('.error-message').length === 0) {
                                    emailInput.after('<div class="error-message">Cette adresse e-mail existe déjà.</div>');
                                }
                            } else {
                                emailInput.css('border-color', 'green');
                                emailInput.next('.error-message').remove();
                            }
                        },
                    });
                }
            });


            $('input[name="mdp1"]').on('input', function() {
                validateField($(this), 'Le mot de passe doit contenir au moins 8 caractères.', function(value) {
                    var isLowerCaseValid = /[a-z]/.test(value);
                    var isUpperCaseValid = /[A-Z]/.test(value);
                    var isDigitValid = /\d/.test(value);
                    var isSpecialCharValid = /[@$!%*?&,;.:/_]/.test(value);
                    return value.length >= 8 && isLowerCaseValid && isUpperCaseValid && isDigitValid && isSpecialCharValid;
                });
                validateForm();
            });

            $('input[name="mdp2"]').on('blur', function() {
                validateField($(this), 'Les mots de passe ne correspondent pas.', function(value) {
                    return value === $('input[name="mdp1"]').val().trim();
                });
                validateForm();
            });






            $('#submitButton').on('click', function(event) {

                event.preventDefault();

                var isNumValid = validateField($('input[name="num"]'), 'Veuillez entrer un numéro de téléphone valide.', function(value) {
                    var phoneRegex = /^[0-9]{10}$/;
                    return phoneRegex.test(value);
                });

                var isMdp1Valid = validateField($('input[name="mdp1"]'), 'Le mot de passe doit contenir au moins 8 caractères.', function(value) {
                    var isLowerCaseValid = /[a-z]/.test(value);
                    var isUpperCaseValid = /[A-Z]/.test(value);
                    var isDigitValid = /\d/.test(value);
                    var isSpecialCharValid = /[@$!%*?&,;.:/_]/.test(value);
                    return value.length >= 8 && isLowerCaseValid && isUpperCaseValid && isDigitValid && isSpecialCharValid;
                });

                var isMdp2Valid = validateField($('input[name="mdp2"]'), 'Les mots de passe ne correspondent pas.', function(value) {
                    return value === $('input[name="mdp1"]').val().trim();
                });

                var isFormValid = isNumValid && isMdp1Valid && isMdp2Valid;

                if (!isFormValid) {
                    if (!$('input[name="n"]').val().trim() || !$('input[name="p"]').val().trim() || !$('input[name="adr"]').val().trim() || !$('input[name="mail"]').val().trim() || !$('input[name="mdp1"]').val().trim() || !$('input[name="mdp2"]').val().trim() || !$('input[name="num"]').val().trim()) {
                        alert("Remplissez les champs vides");
                    } else {
                        alert("Vérifiez vos données");
                    }
                } else {
                    var formData = $(this).closest('form').serialize();
                    console.log(formData);

                    $.ajax({
                        url: 'clientenr.php',
                        method: 'POST',
                        data: formData,
                        dataType: 'json',
                        success: function(response) {
                            console.log(response);
                            if (response.enregistrementReussi === true) {
                                $('body').prepend('<p id="success-message" style="color: white; background: green; position: fixed; top: 20px; left: 50%; transform: translateX(-50%); width: 60%; text-align: center;">Succès de la création du compte. Connectez-vous.</p>');
                                setTimeout(function() {
                                    window.location.href = 'acceuilConnecte.php';
                                }, 3000);
                            } else {
                                $('body').prepend('<p id="success-message" style="color: white; background: red; position: fixed; top: 20px; left: 50%; transform: translateX(-50%); width: 60%; text-align: center;">La création a echoue. Veuillez réesayer</p>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                            $('body').append('<p style="color: red;">Une erreur s\'est produite. Veuillez réessayer.</p>');
                        }
                    });
                }
            });



            function validateEmail(email) {
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            $(' input[name="n"], input[name="p"], input[name="adr"], input[name="mail"]').on('input', function() {
                validateField($(this), 'Ce champ est requis.', function(value) {
                    return value.trim() !== '';
                });
                removeSuccessMessage();
                validateForm();
            });

            $('input[name="num"], input[name="mdp1"], input[name="mdp2"]').on('input', function() {
                removeSuccessMessage();
                validateForm();
            });

            function removeSuccessMessage() {
                $('#success-message').remove();
            }

        });
    </script>




</body>

</html>