<?php
header('Content-Type: image/jpeg');

$tla           = glob("cap_bg/{*.jpg,*.jpeg}", GLOB_BRACE);
$czcionki      = glob("cap_font/*.ttf");

$znaki         = 'ABCDEFGHIJKLMNPQRSTUWXYZ123456789';

$obrazek_tla   = $tla[array_rand($tla)];
$liczba_znakow = rand(4, 5);

$cap           = imagecreatefromjpeg($obrazek_tla);

$kolor         = imagecolorallocate($cap, 250, 250, 250);
$linie         = imagecolorallocate($cap, 205, 205, 205);

for($x = 1; $x <= 50; $x++)        // powtarzamy 50 razy - rysujemy 50 linii
 imageline(                        // funkcja rysująca linię
  $cap,                            // uchwyt obrazka
  0,                               // współrzędna X początku linii
  rand(-100,imagesy($cap)+100),    // współrzędna Y początku linii
  imagesx($cap),                   // współrzędna X końca linii
  rand(-100,imagesy($cap)+100),    // współrzędna Y końca linii
  $linie                           // kolor linii
 );
$haslo = "";
for($x = 1; $x <= $liczba_znakow; $x++)
{
 $czcionka = $czcionki[array_rand($czcionki)];
 $znak     = $znaki[rand(0, strlen($znaki)-1)];
 $haslo  .= $znak;
	
 $odleglosc_miedzy_znakami = (round(imagesx($cap) / $liczba_znakow+1)-10)*($x-1)+25;
	
 imagettftext(                      // funkcja pisząca tekst
  $cap,                             // uchwyt obrazka
  rand(25, 30),                     // rozmiar czcionki
  rand(-15, 15),                    // naczylenie znaku
  $odleglosc_miedzy_znakami,        // odległość między znakami
  rand(40, 60),                     // położenie względem górnej krawędzi obrazka
  $kolor,
  $czcionka,
  $znak
 );
}

imagejpeg($cap);

session_start();
$_SESSION['captcha'] = $haslo;
?>