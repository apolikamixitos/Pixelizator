<?php

////////////////////////////////////////////////////////////////////////////////
///
///Coded By : Ayoub DARDORY (Apolikamixitos)
///Email : AYOUBUTO@Gmail.com
///Description : Visual encryption for image files
///Follow me : http://www.twitter.com/Apolikamixitos
///GitHub: http://github.com/apolikamixitos
//
//////////////////////////////////////////////////////////////////////////////// 

require 'Pixelizator.class.php';

$KeyFile = 'key';
$EncryptedImgFile = 'EncryptedImg.jpg';

$ImageFile = 'flowers.jpg';
$Slices = 10;
$MaxRandSwitch = 300;


/*
 * Encryption part
 */
$VisualEn = new Pixelizator($ImageFile, $Slices,true);
$VisualEn->Encrypt($MaxRandSwitch); //As long as the $MaxRandSwitch increases, the key file size will become bigger
$VisualEn->GenerateKey($KeyFile);
$VisualEn->SaveImage($EncryptedImgFile);
$VisualEn->Show();
exit;
?>
