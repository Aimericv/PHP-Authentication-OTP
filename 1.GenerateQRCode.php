<?php

// Inclusion des dépendances
require_once dirname(__FILE__).'/vendor/autoload.php';
use OTPHP\TOTP;

/***********************
 * Génération d'un secret
 ***********************/
$otp = TOTP::create();

// Génération à la volée
$secret = $otp->getSecret();

// ou utilisation d'un secret déjà généré par nos soins (pour les tests)
$secret = "MFUW2ZLSNFRXGZLDOJSXI===";

echo "The OTP secret is: {$secret}\n";



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

$otpOutput = "{$otp->now()}\n";

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
        <br>
        <div>
            <span>Current OTP: <b><?= $otpOutput; ?></b></span>
        </div>
        <br>
        <div>
            <span>Date: <?= $dateOutput; ?></span>
        </div>
        <br>
        <div>
            <?= $qrCodeOutput; ?>
        </div>

    </body>
</html>
