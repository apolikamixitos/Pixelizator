<?php

require 'Pixelizator.class.php';

$KeyFile = 'key.txt';
$EncryptedImgFile = 'EncryptedImg.jpg';
$DecryptedImgFile = 'DecryptedImg.jpg';


$ImageFile = 'flowers.jpg';
$Slices = 20;
$MaxRandSwitch = 300;


/*
 * Encryption part
 */
$VisualEn = new Pixelizator($ImageFile, $Slices);
$VisualEn->Encrypt($MaxRandSwitch); //As long as the $MaxRandSwitch increases, the key file size will be larger
$VisualEn->GenerateKey("key.txt");
$VisualEn->SaveImage($EncryptedImgFile);
$VisualEn->Show();
exit;

/*
 * Decryption part
 */
$ImageFile = $EncryptedImgFile;
$VisualDe = new Pixelizator($ImageFile, $Slices);
$VisualDe->LoadKey("key.txt");
$VisualDe->Decrypt();
$VisualDe->SaveImage($DecryptedImgFile);
$VisualDe->Show();
?>
