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

/*
 * Decryption part
 */

$EncryptedImgFile = 'EncryptedImg.jpg';

$KeyFile = 'key';
$DecryptedImgFile = 'DecryptedImg.jpg';

$VisualDe = new Pixelizator($EncryptedImgFile);
$VisualDe->LoadKey($KeyFile);
$VisualDe->Decrypt();
$VisualDe->SaveImage($DecryptedImgFile);
$VisualDe->Show();
?>
