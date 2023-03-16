<?php

// Inclusion des dépendances
require_once dirname(__FILE__).'/vendor/autoload.php';
use OTPHP\TOTP;

/***********************
 * Génération d'un secret
***********************/
$otp = TOTP::create();
$secret = $otp->getSecret();


// Utilisation d'un secret déjà généré
// $secret = "XVEZ4O2QCL2FYNIAJCIUNDJAKTAWL45S6SCXKJJEBGZNFN7BB4XFOKA3LNX4U3ERMSLSK4AUMKRVWU3YTYXMBK3WFDQQHPVT745CGMA";
$secret = "MFUW2ZLSNFRXGZLDOJSXI===";

$secretOutput = "The OTP secret is: {$secret}\n";


/***********************
 * Création du TOTP avec des informations précises
 ***********************/
$otp = TOTP::create(
    $secret,                   // secret utilisé (généré plus haut)
    30,                 // période de validité
    'sha256',           // Algorithme utilisé
    6                   // 6 digits
);
$otp->setLabel('Aimeric OTP'); // The label
$otp->setIssuer('Lycée Fenelon');
$otp->setParameter('image', 'https://avatars.githubusercontent.com/u/1199051?v=4'); // FreeOTP can display image

$otpOutput = "The current OTP is: {$otp->now()}\n";

/***********************
 * Affichage du temps pour information
 ***********************/
// Définition de la zone de temps
date_default_timezone_set('Europe/Paris');
$maintenant = time() ;

// Affichage de maintenant
$dateOutput = date('Y-m-d H:i:s',$maintenant);


/***********************
 * Génération du QrCode
 ***********************/
// Note: You must set label before generating the QR code
$grCodeUri = $otp->getQrCodeUri(
    'https://api.qrserver.com/v1/create-qr-code/?data=[DATA]&size=300x300&ecc=M',
    '[DATA]'
);
$qrCodeOutput = "<img src='{$grCodeUri}'>";



/***********************
 * Fonction de vérification du formulaire
 ***********************/
// Fonction qui renvoie true si login et mot de passe sont corrects
function checkLoginPassword($login, $password)
{
    if ($login=='aimeric' && $password=='aimericmdp') return true;
    return false;
}

// Vérifie la valeur OTP
function checkOTP($otp_form): bool
{
    global $otp;

    return $otp->verify($otp_form);
}

$formOutput = '';
// Traitement du formulaire de login:
if (!empty($_POST['login']))
{
    if ( checkLoginPassword($_POST['login'], $_POST['password'] ) && checkOTP( $_POST['otp'] ) )
        $formOutput = "Login OK !";
    else
        $formOutput = "Echec login";
}
?>

<!DOCTYPE HTML>
<html>
    <head>
        <title>OTP QR CODE</title>
    </head>
    <body>
        <h1>QR Code</h1>
        <div>
            <span>Secret: <?= $secretOutput; ?></span>
        </div>

        <div>
            <span>Current OTP: <?= $otpOutput; ?></span>
        </div>

        <div>
            <span>Date: <?= $dateOutput; ?></span>
        </div>

        <div>
            <?= $qrCodeOutput; ?>
        </div>


        <form method="POST">
            Login: <input type="text" name="login"><br>
            Mot de passe: <input type="password" name="password"><br>
            OTP: <input type="password" name="otp"><br>
            <input type="submit" value="Login"><br>
        </form>
        <hr>
        <h2>Retour du formulaire <br><br> <b><?= $formOutput; ?></b></h2>

    </body>
</html>
