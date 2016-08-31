<?php
session_start();
	$komunikat = "";
	function sprawdzEmail($email)
	{
		$wzor = '^([a-zA-Z0-9._\-]+)@([a-zA-Z0-9.\-]+\.[a-zA-Z]{2,4})';
		if (filter_var($email, FILTER_VALIDATE_EMAIL) === false)
			return false;
		else
			return true;
	}
	
	function sendMessage()
    {		
        $name    = addslashes(trim($_POST['name']));
        $phone   = addslashes(trim($_POST['phone']));
        $email   = addslashes(trim($_POST['email']));
        $msg     = addslashes(trim($_POST['msg']));
        
        if (($name != "" && $email != "" && $msg != "" && sprawdzEmail($email)) == 1)
        {
            $message = "Wiadomość ze strony!<br />Imię i nazwisko: ".$name."<br />Telefon: ".$phone."<br />E-mail: ".$email."<br />Wiadomość:<br />".$msg;
 
            $to        = "terapia@psychosfera.wroc.pl"; //terapia@psychosfera.wroc.pl
            $subject   = 'Wiadomość ze strony';
            $headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: Admin <admin@psychosfera.wroc.pl>' . "\r\n";

            $wiadomosc = '<html> 
                      <head> 
					  <meta charset="utf-8"> 
                      <title>Wiadomość e-mail</title> 
                      </head>
                      <body>
                      <p>Wiadomość ze strony!<br />Imię i nazwisko: '.$name.'<br />Telefon: '.$phone.'<br />E-mail: '.$email.'<br />Wiadomość:<br />'.$msg.'</p></body>
                      </html>';
			
			if(mail($to, $subject, $wiadomosc, $headers))
            {
				return 1;
            }
        }
        
        return 0;
    }
	if(isset($_POST['name'])) {
		if($_SESSION['captcha'] != $_POST['cap'] && $_POST['cap']!= ""){ 
			$komunikat = 'Wpisany kod nie jest poprawny.'; 
		} else { 
			if(sendMessage()) $komunikat = "Dziękujemy za Twoją wiadomość.";
			else $komunikat = "Wiadomość nie została wysłana!";			
		}
	} 
?>
<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Psychosfera.pl - profesjonalna grupa terapeutów</title>
        <link rel="stylesheet" type="text/css" href="css/style.css"><script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery.validate.min.js"></script>
        <script>
			function val(x)
			{
				var pole = "#" + x;
				if(x == "email")
				{
					var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
					if(reg.test($(pole).val()) == false)
					{
						$(pole).css( "border", "1px solid red" );
						return false;
					}else{
					$(pole).css( "border", "1px solid green" );
					return true;
					}

				}
				
					if($(pole).val() === "")
					{
						$(pole).css( "border", "1px solid red" );
						return false;
					}
					$(pole).css( "border", "1px solid green" );
					return true;
					
			}
		</script>
    <script>
$(document).ready(function (){
    $("#z_8_i").hide();
	$("#z_8_p").css('height', '75%');
	$("#z_8_p").html('<span style="font-size: 28px; border-bottom: solid 1px white; position: relative;top: -2px;"">' + $("#z_8_p").data('name') + '<br></span><p style="text-align: justify; font-size: 15px;margin-top: 5px;font-weight:lighter;">'+$("#z_8_p").data('opis') +'</p>');
  				
			$("#logo").click(function (){
                    $('html, body').animate({
                        scrollTop: 0
                    }, 1000);
            });
            $("#nn_o").click(function (){
                    $('html, body').animate({
                        scrollTop: $("#o_nas").offset().top-88
                    }, 1000);
            });
			$("#nn_u").click(function (){
                    $('html, body').animate({
                        scrollTop: $("#uslugi").offset().top-88
                    }, 1000);
            });
			$("#nn_z").click(function (){
                    $('html, body').animate({
                        scrollTop: $("#zespol").offset().top-88
                    }, 1000);
            });
			$("#nn_c").click(function (){
                    $('html, body').animate({
                        scrollTop: $("#cennik").offset().top-88
                    }, 1000);
            });
			$("#nn_k").click(function (){
                    $('html, body').animate({
                        scrollTop: $("#kontakt").offset().top-88
                    }, 1000);
            });
			$("#nn_o").mouseenter(function (){
                    $("#n_o").attr('src','img/nav_o_nas_zaz.png');
					$(this).css( "color", "#ea8123" );
            });
			$("#nn_o").mouseleave(function (){
					$("#n_o").attr('src','img/nav_o_nas.png');
					$(this).css( "color", "#313336" );
            });
			$("#nn_u").mouseenter(function (){
                    $("#n_u").attr('src','img/nav_uslugi_zaz.png');
					$(this).css( "color", "#ea8123" );
            });
			$("#nn_u").mouseleave(function (){
					$("#n_u").attr('src','img/nav_uslugi.png');
					$(this).css( "color", "#313336" );
            });
			$("#nn_z").mouseenter(function (){
                    $("#n_z").attr('src','img/nav_zespol_zaz.png');
					$(this).css( "color", "#ea8123" );
            });
			$("#nn_z").mouseleave(function (){
					$("#n_z").attr('src','img/nav_zespol.png');
					$(this).css( "color", "#313336" );
            });
			$("#nn_c").mouseenter(function (){
                    $("#n_c").attr('src','img/nav_cennik_zaz.png');
					$(this).css( "color", "#ea8123" );
            });
			$("#nn_c").mouseleave(function (){
					$("#n_c").attr('src','img/nav_cennik.png');
					$(this).css( "color", "#313336" );
            });
			$("#nn_k").mouseenter(function (){
                    $("#n_k").attr('src','img/nav_kontakt_zaz.png');
					$(this).css( "color", "#ea8123" );
            });
			$("#nn_k").mouseleave(function (){
					$("#n_k").attr('src','img/nav_kontakt.png');
					$(this).css( "color", "#313336" );
            });
			$( "#z_1" ).hover(
  				function() {
    				$("#z_1_i").hide();
					$("#z_1_p").css('height', '100%');
					$("#z_1_p").html('<span style="font-size: 28px; border-bottom: solid 1px white;position: relative;top: -2px;">' + $("#z_1_p").data('name') + '<br></span><p style="text-align: justify; font-size: 15px;margin-top: 5px;font-weight:lighter;">'+$("#z_1_p").data('opis') +'</p>');
  				}, function() {
    				$("#z_1_i").show();
					$("#z_1_p").css('height', '57px');
					$("#z_1_p").html($("#z_1_p").data('name'));
  				}
			);
			$( "#z_2" ).hover(
  				function() {
    				$("#z_2_i").hide();
					$("#z_2_p").css('height', '100%');
					$("#z_2_p").html('<span style="font-size: 28px; border-bottom: solid 1px white; position: relative;top: -2px;"">' + $("#z_2_p").data('name') + '<br></span><p style="text-align: justify; font-size: 15px;margin-top: 5px;font-weight:lighter;">'+$("#z_2_p").data('opis') +'</p>');
  				}, function() {
    				$("#z_2_i").show();
					$("#z_2_p").css('height', '47px');
					$("#z_2_p").html($("#z_2_p").data('name'));
  				}
			);
			$( "#z_3" ).hover(
  				function() {
    				$("#z_3_i").hide();
					$("#z_3_p").css('height', '100%');
					$("#z_3_p").html('<span style="font-size: 28px; border-bottom: solid 1px white; position: relative;top: -2px;"">' + $("#z_3_p").data('name') + '<br></span><p style="text-align: justify; font-size: 15px;margin-top: 5px;font-weight:lighter;">'+$("#z_3_p").data('opis') +'</p>');
  				}, function() {
    				$("#z_3_i").show();
					$("#z_3_p").css('height', '47px');
					$("#z_3_p").html($("#z_3_p").data('name'));
  				}
			);
			$( "#z_4" ).hover(
  				function() {
    				$("#z_4_i").hide();
					$("#z_4_p").css('height', '100%');
					$("#z_4_p").html('<span style="font-size: 28px; border-bottom: solid 1px white; position: relative;top: -2px;"">' + $("#z_4_p").data('name') + '<br></span><p style="text-align: justify; font-size: 15px;margin-top: 5px;font-weight:lighter;">'+$("#z_4_p").data('opis') +'</p>');
  				}, function() {
    				$("#z_4_i").show();
					$("#z_4_p").css('height', '47px');
					$("#z_4_p").html($("#z_4_p").data('name'));
  				}
			);
			$( "#z_5" ).hover(
  				function() {
    				$("#z_5_i").hide();
					$("#z_5_p").css('height', '100%');
					$("#z_5_p").html('<span style="font-size: 28px; border-bottom: solid 1px white; position: relative;top: -2px;"">' + $("#z_5_p").data('name') + '<br></span><p style="text-align: justify; font-size: 15px;margin-top: 5px;font-weight:lighter;">'+$("#z_5_p").data('opis') +'</p>');
  				}, function() {
    				$("#z_5_i").show();
					$("#z_5_p").css('height', '47px');
					$("#z_5_p").html($("#z_5_p").data('name'));
  				}
			);
			$( "#z_6" ).hover(
  				function() {
    				$("#z_6_i").hide();
					$("#z_6_p").css('height', '100%');
					$("#z_6_p").html('<span style="font-size: 28px; border-bottom: solid 1px white; position: relative;top: -2px;"">' + $("#z_6_p").data('name') + '<br></span><p style="text-align: justify; font-size: 15px;margin-top: 5px;font-weight:lighter;">'+$("#z_6_p").data('opis') +'</p>');
  				}, function() {
    				$("#z_6_i").show();
					$("#z_6_p").css('height', '47px');
					$("#z_6_p").html($("#z_6_p").data('name'));
  				}
			);
			$( "#z_7" ).hover(
  				function() {
    				$("#z_7_i").hide();
					$("#z_7_p").css('height', '100%');
					$("#z_7_p").html('<span style="font-size: 28px; border-bottom: solid 1px white; position: relative;top: -2px;"">' + $("#z_7_p").data('name') + '<br></span><p style="text-align: justify; font-size: 15px;margin-top: 5px;font-weight:lighter;">'+$("#z_7_p").data('opis') +'</p>');
  				}, function() {
    				$("#z_7_i").show();
					$("#z_7_p").css('height', '47px');
					$("#z_7_p").html($("#z_7_p").data('name'));
  				}
			);
			/*$( "#z_8" ).hover(
  				function() {
    				$("#z_8_i").hide();
					$("#z_8_p").css('height', '100%');
					$("#z_8_p").html('<span style="font-size: 28px; border-bottom: solid 1px white; position: relative;top: -2px;"">' + $("#z_8_p").data('name') + '<br></span><p style="text-align: justify; font-size: 15px;margin-top: 5px;font-weight:lighter;">'+$("#z_8_p").data('opis') +'</p>');
  				}, function() {
    				$("#z_8_i").show();
					$("#z_8_p").css('height', '47px');
					$("#z_8_p").html($("#z_8_p").data('name'));
  				}
			);*/
        });
    </script>
    <script>
			function wiecej(x)
			{
				var a = "#" + x;
				var el = $(a).parent(),
    				curHeight = el.height(),
    				autoHeight = el.css('height', 'auto').height();
				if(curHeight == 100){
					$(a).attr('src','img/gora.png');
					$(a).parent().height(curHeight).animate({height: autoHeight}, 1000);
				}else{
					$(a).attr('src','img/dol.png');
					$(a).parent().animate({height: 100}, 1000);
				}
			}
			
			function zaz(x){
				var a = "#" + x;
				$(a).css('background','white');
				$(a).css('color','#ea8123');
				$(a).children("p").html($(a).children("p").data('name') + '<br><span style="font-size: 18px; color: #313336;">czytaj więcej>></span>');
			}
			function odz(x){
				var a = "#" + x;
				$(a).css('background','none');
				$(a).css('color','white');
				$(a).children("p").html($(a).children("p").data('name'));
			}
			function rozwin(x){
				var a = "#" + x;
				$("#u_roz").children("h1").text($(a).children("p").data('name_roz'));
				$("#u_roz").children("p").html($(a).children("p").data('roz'));
				$("#u_roz").show("slow");
				$("#uslugi").hide("slow");
				$("#uslugi_kiedy").hide("slow");
				$('html, body').animate({
                        scrollTop: $("#uslugi").offset().top-88
                    }, 1000);
			}
			function schowaj(){
				$("#u_roz").hide("slow");
				$("#uslugi").show("slow");
				$("#uslugi_kiedy").show("slow");
			}
			
			
	</script>
	</head>
	<body>
		<div id="top">
			<nav id="MENU">
            	<img id="logo" src="img/logo.png"/>
                <table>
                	<tr>
                    	<td id="nn_o"><img src="img/nav_o_nas.png" alt="O nas" id="n_o"><br>O nas</td>
                        <td id="nn_u"><img src="img/nav_uslugi.png" alt="usługi" id="n_u"><br>Usługi</td>
                        <td id="nn_z"><img src="img/nav_zespol.png"  alt="zepol"  id="n_z"><br>Zespół</td>
                        <td id="nn_c"><img src="img/nav_cennik.png" alt="cennik" id="n_c"><br>Cennik</td>
                        <td id="nn_k"><img src="img/nav_kontakt.png" alt="kontakt"  id="n_k"><br>Kontakt</td>
                    </tr>
                </table>
			</nav>
			<section id="header">
                <div><u>&emsp;Godziny otwarcia&emsp;</u><br><span style="font-weight:lighter;">Pn. - Pt&emsp;12.00-21.00</span></div>
                <p id="p_h">Jesteśmy po to, by Was wspierać...</p>
            </section>
            <section id="o_nas">
            	<p class="p_h p">&emsp;O NAS&emsp;</p>
                <p id="opis_nas">Jesteśmy grupą terapeutów z wieloletnim doświadczeniem działającą w następujących obszarach:</p>
				<table>
                	<tr>
                    	<td>
                        	<img src= "img/a.png">
                            <p>PROFILAKTYKA</p>
                            <ul>
                            	<br>
                            	<li>działania na rzecz zapobiegania niepowodzeniom szkolnym</li>
                                <li>wspieranie umiejętności komunikacji interpersonalnej</li>
                                <li>rozwiązywanie konﬂiktów bez przemocy</li>
                           	</ul>
                        </td>
                        <td>
                        	<img src= "img/c.png">
                            <p>WSPOMAGANIE ROZWOJU</p>
                            <ul><br>
                            	<li>wzmacnianie potencjału rozwojowego</li>
                                <li>odkrywanie możliwości twórczych</li>
                                <li>kształtowanie umiejętności radzenia sobie ze stresem</li>
                                <li>przeciwdziałanie nieprawidłowościom rozwojowym u dzieci</li>
                           	</ul>
                        </td>
                        <td>
                        	<img src= "img/b.png">
                            <p>RÓŻNEGO RODZAJU<br>FORMY TERAPII</p>
                            <ul>
                            	<li>terapia psychologiczna</li>
                                <li>terapia pedagogiczna</li>
                                <li>terapia biofeedback</li>
                                <li>terapia reha-com</li>
                                <li>terapia integracji sensorycznej</li>
                           	</ul>
                        </td>
                    </tr>
                </table>
            </section>
            <section id="u_roz">
            	<h1></h1>
              	<img src="img/exit.png" onClick="schowaj()">
                <p></p>
            </section>
            <section id="uslugi">
   			  	<img src="img/pik_uslugi.png">
                <p class="p_h g">&emsp;USŁUGI&emsp;</p>
                <p class="o">CO OFERUJEMY?</p>
                <table class="tab">
                	<tr>  
                    	<td id="o_1" onMouseOver="zaz(this.id)" onMouseOut="odz(this.id)" onClick="rozwin(this.id)">
                        	<img src="img/u_1.png">
                            <p data-name="Biofeedback" data-name_roz="Biofeedback" data-roz="Jako jedni z nielicznych pracujemy na Systemie Infiniti, który oferuje wiele innowacyjnych rozwiązań dla biofeedbacku i neurofeedbacku nie spotykanych u innych producentów m.in. wyniki treningów i badań są porównywane z normatywną bazą danych.<br><br><strong>Trening Biofeedback / NEUROFEEDBACK</strong><br>Biologiczne sprzężenie zwrotne, czyli informacja zwrotna dotycząca aktualnego funkcjonowania organizmu. Istnieje wiele odmian treningu biofeedback, w zależności od kanału informacji fizjologicznej (biologicznej informacji wykorzystywanej do ćwiczenia):<br>- mięśnie – powierzchniowe EMG – elektromiografia,<br>stosowane do nauki relaksacji i kontroli reakcji na stres, a także jako pomoc w uświadomieniu napięcia mięśni z obszaru głowy, szyi oraz pleców. Może też służyć jako narzędzie treningu kontroli wybranych grup mięśniowych,<br>- przewodność skóry<br>pomaga rozwinąć świadomość wyuczonych odpowiedzi na stres, a także nauczyć podstawowych umiejętności samoregulacyjnych,<br>- temperatura<br>trening mający na celu zwiększenie temperatury obwodowej oraz kontrolowania nieświadomych reakcji w odpowiedzi na stres,<br>- oddychanie<br>trening oddechu brzusznego i (opcjonalnie) oddechu w odcinku piersiowym. Wolny, głęboki oddech pomaga w relaksacji i można go stosować do obniżenia częstotliwości tętna,<br>- HRV<br>zmienność rytmu serca – ekrany treningowe monitorują oddech i rytm serca z użyciem czujników BVP oraz EKG i mogą być przydatne do treningu oddechowego (RSA) lub też rozszerzyć swoje działanie o zwiększanie możliwości adaptacyjnych systemu sercowo-naczyniowego przez zwiększanie jego zmienności,<br><br>Biofeedback jest metodą terapii opartej na technice komputerowej, umożliwiającej trening w celu poprawienia efektywności działania i uzyskania kontroli nad procesami fizjologicznymi zachodzącymi w naszym organiźmie, zwykle niedostępnymi dla naszej świadomości. Osoba trenująca uczy się rozpoznawać informacje płynące z ciała i jednocześnie moderuje funkcje ciała.<br><br><strong>Zasada działania:</strong><br>Istotą metody jest wykorzystywanie efektu biologicznego sprzężenia zwrotnego do modyfikowania procesu fizjologicznego i nadawania mu pożądanego kierunku. Uzyskanie bezpośrednich informacji zwrotnych dotyczących parametru tego procesu, otwiera proces uczenia się zależności pomiędzy naszym ciałem i umysłem a jakością pracy. Możliwa jest świadoma kontrola poszczególnych reakcji fizjologicznych, takich jak: praca serca, ciśnienie krwi, rytm oddychania, napięcie mięśniowe itp. Dzięki treningowi można nauczyć się panowania nad poszczególnymi funkcjami organizmu, kierowanymi przez autonomiczny układ nerwowy. Samoświadomość czynności życiowych pozwala na lepsze ich kontrolowanie oraz wykorzystanie potencjału, którym dysponujemy.<br><br><strong>Czym jest trening EEG Biofeedback?</strong><br>Aparatura jest wzbogaconym o opcję treningową i przystawkę do sprzężenia zwrotnego aparatem EEG. Składa się z dwóch systemów EEG, dwóch monitorów dla terapeuty i dla pacjenta oraz głowicy EEG z elektrodami. Terapeuta zakłada trenowanemu na głowę dwie lub więcej elektrod oraz dwie elektrody uszne. Poprzez elektrody aktywność mózgu jest odczytywana i poddawana komputerowej analizie. Pasma częstotliwości fal mózgowych są prezentowane liczbowo i graficznie na monitorze terapeuty. Natomiast trenujący obserwuje te dane na swoim monitorze w postaci gry. Trenujący widzi czynność bioelektryczną swojego mózgu pod postacią samochodu, samolotu, bądź piłki, którymi kieruje (bez klawiatury) poprzez odpowiednią koncentrację umysłu na wykonywanym zadaniu. Trenujący otrzymuje ciągłą informację o swoim aktualnym stanie (np. koncentracji, relaksu) w postaci osiągniętego wyniku w zadaniu. Terapeuta stymuluje pożądane i hamuje niepożądane pasma fal mózgowych, zależnie od klinicznych objawów i wzorców EEG, ocenionego przed i w czasie treningu. Podczas treningu terapeuta dostraja pożądane parametry fal mózgowych pacjenta, stymulując powstawanie nowych korzystnych wzorców lub hamując te niewłaściwe.<br><br><strong>Cel treningu:</strong><br>Ogólnym celem jest poprawa funkcjonowania, harmonizacja procesów fizjologicznych, poprzez polepszenie czynności bioelektrycznej mózgu wraz z ukierunkowaniem i wzmocnieniem koncentracji uwagi,  hamowania stanów nadmiernego pobudzenia, stanów nadmiernego hamowania lub obu równocześnie.<br><br><strong>Wskazania:</strong><br>Metoda polecana polecane jest wszystkim, niezależnie od wieku i stanu zdrowia. Szczególne wskazania:<br>- Trudności szkolne<br>- Zespół nadpobudliwości psychoruchowej (ADD, ADHD)<br>- Zaburzenia emocjonalne (lękowe, nerwice)<br>- Zaburzenia zachowania (agresja)<br>- Zaburzenia snu<br>- Zaburzenia ze spektrum autyzmu (Autyzm, Zespół Aspergera)<br>- Zaburzenia mowy (jąkanie, opóźniony rozwój mowy)<br><br><strong>Efekty:</strong><br>- Ogólne polepszenie funkcjonowania intelektualnego<br>- Dostarczanie energii do wydajnego działania<br>- Polepszenie koncentracji<br>- Doskonalenie umiejętności komunikacyjnych<br>- Kształtowanie postawy ciała, koordynacji ruchowej, orientacji przestrzennej przez zmysł równowagi<br>- Poprawa stanów emocjonalnych, redukcja stresu, rozwój kreatywności, wzrost pewności siebie, pobudzenie motywacji, niwelacja stanów lękowych oraz zwiększenie samokontroli emocjonalnej<br><br><strong>Jak długo:</strong><br>Zależnie od specyfiki problemu, jednak minimum 10 spotkań w odstępach cotygodniowych.">Biofeedback</p>
                       	</td>
                        <td id="o_2" onMouseOver="zaz(this.id)" onMouseOut="odz(this.id)" onClick="rozwin(this.id)">
                        	<img src="img/u_2.png">
                            <p  data-name="Reha-com" data-name_roz="Reha-com" data-roz='Zaburzenia procesów poznawczych są częstą konsekwencją urazów mózgowych, tak więc pojawiła się potrzeba wspomagającej terapii dla pacjentów nimi dotkniętych. Postęp w technologii komputerowej umożliwił powstanie programów do ich rehabilitacji. Trudności z utrzymaniem i skupieniem uwagi, uczeniem się, pamięcią, nieadekwatną reakcją na bodźce i z wieloma innymi funkcjami mózgowymi w różnym stopniu poddają się leczeniu. Rehabilitacja procesów poznawczych skupia się przede wszystkim na zminimalizowaniu ograniczeń powstałych w wyniku uszkodzeń mózgu. Celem komputerowej procedury treningowej jest doprowadzenie do znaczących, pozytywnych zmian w sprawności procesów poznawczych pacjentów. Głównym kryterium sukcesu jest osobista ocena pacjenta dotycząca zmiany jego jakości życia.<br><br>RehaCom pozwala na ćwiczenie rozmaitych obszarów poznawczych przy pomocy określonych procedur. Pacjent rozpoczyna ćwiczenia
od tych najprostszych. Wymagania rosną w miarę postępów. Ponieważ istnieje wiele rodzajów zaburzeń funkcji poznawczych, skuteczny pakiet ćwiczeń jest na tyle uniwersalny, aby pomagać w rehabilitacji pacjentów zarówno z prostymi jak i złożonymi zaburzeniami. Opisana struktura umożliwia:<br>• Dobór procedur do konkretnych zaburzeń poznawczych<br>• Dobór zestawów procedur do określonego proﬁlu zaburzeń<br>• Elastyczność struktury treningu (ilość zadań w trakcie sesji, zmiany poziomu trudności itp.)<br><br>RehaCom jest idealnym instrumentem wspomagającym poprawę uwagi, pamięci i innych funkcji. Poprzez indywidualną adaptację do możliwości pacjenta, system dokładnie odpowiada jego potrzebom, dostarczając zadań na takim poziomie, który jest w danym momencie wymagany. To z
kolei w optymalny sposób umożliwia poprawę funkcji mózgowych. RehaCom oferuje wiele zróżnicowanych zadań i dostarcza pacjentowi odpowiedniej motywacji., Dopasowana informacja zwrotna pozwala na zapoczątkowanie procesu uczenia się i pomaga w dopasowaniu strategii do rozwiązywanych zadań. Przyjazny dla użytkownika panel sterowania i dopracowany interfejs graﬁczny zapewniają
komfort pracy i przyczyniają się do sukcesu w prowadzonej terapii. Liczne badania kliniczne potwierdzają skuteczność systemu RehaCom. Jest on z powodzeniem stosowany od ponad 20 lat w wielu klinikach neurologicznych w Europie i poza nią.<br><br>Treningi metodą RehaCom są wskazane dla dzieci i dorosłych, u których występują zaburzenia funkcji poznawczych spowodowanych ogniskowym lub uogólnionym uszkodzeniem mózgu:<br><br>- zaburzenia pamięci słownej i bezsłownej,<br>- zaburzenia uwagi i koncentracji,<br>- zaburzenia logicznego myślenia,<br>- zaburzenia percepcji i kojarzenia,<br>- zaburzenia pamięci i rozpoznawania twarzy,<br>- zaburzenia czasu reakcji,<br>- zaburzenia koordynacji wzrokowo-ruchowej,<br>- zaburzenia funkcji planowania,<br>- zaburzenia funkcji poznawczych po udarze i wylewie.<br><br><span style="font-size: 24px; font-weight:bolder;">Polecany dla dzieci z ADD, ADHD i trudnościami szkolnymi.</span>'>Reha-com</p>
                       	</td>
                        <td id="o_3" onMouseOver="zaz(this.id)" onMouseOut="odz(this.id)" onClick="rozwin(this.id)">
                        	<img src="img/u_3.png">
                            <p data-name="Terapia Pedagogiczna" data-name_roz="Terapia Pedagogiczna" data-roz="<strong>Terapia pedagogiczna </strong>to sposoby i metody pracy z dzieckiem mające na celu usprawnienie funkcji percepcyjno-motorycznych a w konsekwencji nabywanie podstawowych umiejętności szkolnych: czytania, pisania, liczenia. Metody pracy dobierane są przez terapeutę w zależności od założonego głównego celu w terapii, wieku i umiejętności dziecka. Jedną z podstawowych zasad jest stopniowanie trudności. Zadania stawiane przed dzieckiem powinny być odpowiednio trudne, tak by poradziło sobie z nimi i jednocześnie miało przyjemność z wysiłku. Planowanie małych celów i ich stopniowa realizacja w naturalny sposób pozytywnie wpływa na świadomość dziecka własnych umiejętności i jego motywację do dalszej pracy. Aby podejmowane działania przynosiły oczekiwane rezultaty powinny być regularnie i systematycznie wykonywane. Dlatego w terapii peadgogicznej ważną rolę odgrywają zajęcia z terapeutą oraz ukierunkowana codzienna praca w domu.<br>W terapii pedagogicznej koryguje się opóźnione funkcje, zaburzone a bazuje na tych dobrze rozwiniętych. Głównymi obszarami pracy są funkcje słuchowe, wzrokowe, motoryczne. W zakresie umiejętności fonologicznych rozwija się umiejętność przeprowadzania analizy i syntezy sylabowej, głoskowej wyrazu, różnicowania fonemów, bezpośredniej pamięci słuchowej. Przy doskonaleniu percepcji wzrokowej stymuluje się bezpośrednią pamięć wzrokową, umiejętności dostrzegania różnic i podobieństw między podobnymi wzorami, wyobraźnię przestrzenną. W pracy korekcyjno-kompensacyjnej pobudza się koordynację wzrokowo-ruchową, która istotnie wpływa na przyswajanie umiejętności pisania i czytania. Niejednokrotnie celem jest zwiększenie sprawności grafomotorycznej, umiejętnosci wykonywania płynnych ruchów, utrwalania prawidłowych kierunków kreślenia linii (z góry ku dołowi, od lewej do prawej). Na rynku jest wiele pomocy dydaktycznych, które uatrakcyjniają aktywizację poszczególnych sfer rozwoju. Są to przeróżne gry dydaktyczne, zeszyty ćwiczeń, materiały do odsłuchiwania na nośnikach dźwięku czy edukacyjne gry komputerowe. Dzięki wielości i różnorodności materiałów terapeuta ma możliwość dobrać pomoce do indywidualnych potrzeb dziecka i uczynić proces wytężonego wysiłku ciekawą przygodą.<br>Zajęcia terapii pedagogicznej mogą odbywać się w małych grupach oraz indywidualnie. Zajęcia korekcyjno-kompensacyjne prowadzone w szkołach są jedną z najbardziej znanych form terapii pedagogicznej.">Terapia Pedagogiczna</p>
                       	</td>
                        <td id="o_4" onMouseOver="zaz(this.id)" onMouseOut="odz(this.id)" onClick="rozwin(this.id)">
                        	<img src="img/u_4.png">
                            <p data-name="Terapia Psychologiczna" data-name_roz="Terapia Systemowa" data-roz="Terapia systemowa oparta jest na nurcie opisującym człowieka jako istotę przynależną zwykle do kilku systemów społeczno – kulturowych. Uznaje się w niej, że najpełniejsze zrozumienie trudności człowieka możliwe jest jedynie w kontekście, w którym żyje człowiek, z uwzględnieniem jego osobistych przeżyć. Uwzględnia procesy i przemiany zachodzące w systemach społecznych, głównie tych najistotniejszych dla klienta czyli systemach rodzinnych.<br>Inspiracją dla tego sposobu myślenia jest wiele obszarów życia i dziedzin nauki: od filozofii przez biologię, psychologię, socjologię, po nauki matematyczne i informatyczne.<br><br><strong>Cel:</strong><br>Terapeuta jest moderatorem spotkania, zadaje pytania, zaprasza do wspólnej pracy przy użyciu konstelacji przestrzennych osób, przedmiotów, rysunków, gier. Dopytuje też o relacje między klientem a innymi osobami, przeformułowuje znaczenia, do których klient się przywiązał, używa metafor, wydobywa zasoby — mocne strony i umiejętności klienta, zachęca do eksperymentów. Terapeuta i klienci (rodziny, pary lub osoby indywidualne) poszukują wspólnie źródła trudności oraz dążą do wypracowania najlepszych, a zarazem możliwych do wdrożenia rozwiązań. Charakterystyczne jest też zlecanie zadań domowych związanych z obserwacją lub możliwością doświadczenia przez klienta użytecznych dla jego procesu terapeutycznego zmian. Wszystko po to, by doprowadzić do zmiany, która będzie użyteczna dla klienta, zdejmie z niego cierpienie, doda mu poczucie wpływu na siebie i swoje życie.<br><br><strong>Wskazania:</strong><br>Terapia systemowa jest z powodzeniem stosowana w terapii indywidualnej, terapii par czy grupowej, gdyż będąc propozycją nowego sposobu myślenia, wydaje się nie mieć ograniczeń zastosowania. Jej skuteczność jest również znana w takich obszarach oddziaływań psychoterapeutycznych jak terapia chorych psychicznie, chorych przewlekle, pacjentów z objawami psychosomatycznymi, z zaburzeniami jedzenia (bulimia, anoreksja), stanami lękowymi, uzależnieniem, osób w żałobie czy kryzysie<br><br><strong>Jak długo:</strong><br>Terapia systemowa jest z założenia terapią krótkoterminową, przy uwzględnieniu specyfiki problemu klienta może trwać od kilku spotkań do około roku, półtora. Sesje terapeutyczne odbywają się raz na 2–3 tygodnie, w miarę postępowania procesu terapeutycznego odstępy między spotkaniami mogą być jeszcze dłuższe. Terapia odbywa się w gabinecie psychoterapeutycznym zapewniającym klientowi poczucie bezpieczeństwa i możliwość dystansowania się od problemu, wprowadzania różnic w swoje dotychczasowe myślenie.">Terapia Psychologiczna</p>
                       	</td>
                    </tr>
                    <tr>
                    	<td style="border: none;"></td>
                        <td id="o_5" onMouseOver="zaz(this.id)" onMouseOut="odz(this.id)" onClick="rozwin(this.id)">
                        	<img src="img/u_5.png">
                            <p  data-name="Integracja<br>sensoryczna" data-name_roz="Integracja sensoryczna" style="top: -30px;" data-roz="<strong>Czym jest integracja sensoryczne?</strong><br>Integracja sensoryczna jest procesem, dzięki któremu mózg otrzymując informacje ze wszystkich zmysłów: wzroku, słuchu, węchu, dotyku, równowagi i propriocepcji (czucie własnego ciała), dokonuje ich rozpoznania, segregacji, interpretacji oraz integruje je z wcześniejszymi doświadczeniami. Na tej podstawie mózg tworzy odpowiednią do sytuacji reakcję, zwaną adaptacyjną, czyli adekwatnie i efektywnie reaguje na wymogi otoczenia. To właśnie dzięki temu procesowi nasze napięcie mięśniowe jest prawidłowe, potrafimy skoordynować ruchy, potrafimy nauczyć się nowych aktywności ruchowych, a będąc w głośnym pomieszczeniu potrafimy skupić się na danym zadaniu. Wszystkie zmysły u dziecka zaczynają kształtować się już w okresie płodowym, a rozwój procesów integracji sensorycznej najintensywniej przebiega w wieku przedszkolnym<br><br><strong>Co to jest terapia integracji sensorycznej?</strong><br>Terapia integracji sensorycznej (SI) ma na celu nauczenie dziecka adekwatnego reagowania na bodźce dopływające zarówno ze świata zewnętrznego jak i z ciała. Określana jest mianem „naukowej zabawy”, bowiem poprzez zabawę – przyjemną i interesującą dla dziecka – dokonuje się integracja bodźców zmysłowych oraz doświadczeń płynących do ośrodkowego układu nerwowego. Terapia SI nie jest uczeniem konkretnych umiejętności (np. jazdy na rowerze, pisania, czytania), ale usprawnianiem pracy systemów sensorycznych i procesów układu nerwowego, które są bazą do rozwoju tych umiejętności. Dzięki odpowiednio dobranym ćwiczeniom dziecko może poprawić sprawność motoryczną, koordynację ruchów, uwagę i koncentrację, zwiększyć świadomość własnego ciała, a także poprawić funkcjonowanie społeczne i emocjonalne.<br>Terapia integracji sensorycznej odbywa się w specjalnie wyposażonej sali terapeutycznej. Najważniejszą jej częścią jest sprzęt podwieszany, służący do stymulowania bazowych systemów sensorycznych – przedsionkowego i proprioceptywnego. Aby terapia była efektywna dziecko powinno uczestniczyć w zajęciach regularnie, przynajmniej raz w tygodniu.<br><br><strong>Na czym polega diagnoza SI?</strong><br>Żeby stwierdzić zaburzenie integracji sensorycznej należy przeprowadzić diagnozę. Diagnozę taką może przeprowadzić jedynie wykwalifikowany terapeuta SI. Jednak pewne objawy zaburzeń są możliwe do rozpoznania przez rodziców i nauczycieli. Mogą to być:<br>- zaburzenia napięcia mięśniowego,<br>- szybka męczliwość,<br>- niezgrabność ruchowa,<br>- słaba koordynacja ruchowa,<br>- choroba lokomocyjna,<br>- nieprawidłowe posługiwanie się sztućcami, nożyczkami, itp.,<br>- trudności w czytaniu i pisaniu,<br>- zaburzenia mowy,<br>- trudności w koncentracji uwagi,<br>- nadruchliwość ruchowa bądź unikanie zabaw ruchowych,<br>- wycofywanie się z kontaktów z rówieśnikami bądź zachowania agresywne wobec nich,<br>- trudności z wysłuchiwaniem poleceń,<br>- problemy z nauką jazdy na rowerze,<br>- unikanie zabaw na huśtawce, karuzeli lub nadmierne poszukiwanie takich zabaw,<br>- nadwrażliwość na światło lub dźwięki,<br>- unikanie zabaw plasteliną, malowania palcami,<br>- unikanie dotykania niektórych faktur, np. piasku, trawy,<br>- trudności w próbowaniu nowych potraw,<br>- trudności w założeniu nowych ubrań.<br>Pojedynczy objaw nie musi być wskazaniem do diagnozy, lecz współwystępowanie kilku z nich powinno zostać skonsultowane z terapeutą SI.<br><br>Pełna diagnoza procesów przetwarzania sensorycznego składa się z kilku spotkań podczas których przeprowadzane są:<br>- szczegółowy wywiad z rodzicami dotyczący rozwoju dziecka w okresie płodowym, okołoporodowym i po urodzeniu, czasu osiągania przez dziecko „kamieni milowych” rozwoju ruchowego, poznawczego i mowy, stanu zdrowia dziecka,<br>- próby obserwacji klinicznej badające lateralizację, odruchy, napięcie mięśniowe, pracę gałek ocznych, poziom pobudzenia układu przedsionkowego,<br>- wystandaryzowane Testy Południowokalifornijskie oceniające naśladownictwo ruchowe, planowanie ruchu, różnicowanie dotykowe, percepcję wzrokową, koordynację wzrokowo-ruchową, równowagę.<br>Ważna podczas diagnozy jest także obserwacja dziecka w czasie jego swobodnej i zaplanowanej aktywności.<br>Na podstawie przeprowadzonych badań i obserwacji zostają opracowane indywidualne plany terapii.<br><br><strong>Dla kogo terapia SI?</strong><br>Terapia SI jest prowadzona dla dzieci:<br>- z ADHD,<br>- z autyzmem,<br>- z mózgowym porażeniem dziecięcym,<br>- z zespołem Downa,<br>- ze specyficznymi trudnościami w nauce (dysleksja, dysgrafia)<br>- z nadmierną lub zbyt mała wrażliwościąna bodźce dotykowe, wzrokowe, słuchowe lub ruchowe,<br>- ze zbyt wysokim lub zbyt niskim poziomem aktywności ruchowej,<br>- z zaburzeniami napięcia mięśniowego (osłabione lub wzmożone),<br>- ze słabą koordynacją ruchową,<br>- z trudnościami w nauce, w opanowaniu umiejętności czytania i pisania,<br>- z trudnościami z koncentracją uwagi,<br>- z opóźnionym rozwojem mowy,<br>- wycofujących się z kontaktów społecznych,
<br>- mających trudności z czynnościami samoobsługowymi, z planowaniem ruchu.">Integracja<br>sensoryczna</p>
                       	</td>
                        <td id="o_6" onMouseOver="zaz(this.id)" onMouseOut="odz(this.id)" onClick="rozwin(this.id)">
                        	<img src="img/u_6.png">
                            <p  data-name="Diagnoza<br>psychologiczno-<br>pedagogiczna" data-name_roz="Diagnoza psychologiczno-pedagogiczna" style="top: -30px;" data-roz="- diagnoza możliwości intelektualnych (IQ)<br>- diagnoza trudności szkolnych<br>- diagnoza gotowości szkolnej<br>- diagnoza psychologiczna dla osób ubiegających się o pozwolenie na broń, sędziów, komorników, kuratorów, pracowników ochrony, osób: wyrabiających, nabywających, przechowujących i zajmujących się obrotem materiałami wybuchowymi,">Diagnoza<br>psychologiczno-<br>pedagogiczna</p>
                       	</td>
                        <td style="border: none;"></td>
                    </tr>
                </table>
            </section>
            <section id="uslugi_kiedy">
            	<p class="o">KIEDY INTERWENIUJEMY?</p>
                <br>
            	<table class="tab" style="width: 75%; margin-bottom: 50px;">
                	<tr>
                    	<td>
                        	<img src="img/u_7.png">
                            <p>Trudności w szkole</p>
                        </td>
                        <td>
                        	<img src="img/u_8.png">
                            <p>Trudności<br>w koncetracji uwagi</p>
                        </td>
                        <td>
                        	<img src="img/u_9.png">
                            <p>Trudności z pamięcią</p>
                        </td>
                    </tr>
                    <tr>
                    	<td>
                        	<img src="img/u_10.png">
                            <p>Radzenie sobie<br>ze stresem</p>
                        </td>
                        <td>
                        	<img src="img/u_11.png">
                            <p>Zakłócenia integracji<br>sensorycznej</p>
                        </td>
                        <td>
                        	<img src="img/u_12.png">
                            <p>Rozwijanie potencjału</p>
                        </td>
                    </tr>
                </table>
            </section>
            <section id="zespol">
            	<img src="img/pik_z.png" style="border:none;"><br>
                <p class="zes p_h p">&emsp;ZESPÓŁ&emsp;</p>
            	<table class="tab" cellspacing="10px">
                	<tr>
                    	<td id="z_1"> 
                        	<img src="img/z_1.png" id="z_1_i">
                            <p id="z_1_p" data-opis="psycholog uprawniony do badań osób ubiegających się o zezwolenie na broń, sędziów, komorników, kuratorów, pracowników ochrony, osób: wyrabiających, nabywających, przechowujących i zajmujących się obrotem materiałami wybuchowymi, psycholog uprawniony do badań kierowców, operatorów maszyn i urządzeń, logopeda, terapeuta II stopnia metody biofeedback, terapeuta metody Warnkego, diagnosta trudności szkolnych." data-name="Anna Stasiak-Przybylska">Anna Stasiak-Przybylska</p>
                       	</td>
                        <td id="z_2">
                        	<img src="img/z_2.png" id="z_2_i">
                            <p class="jedno" id="z_2_p" data-opis="pedagog specjalny, specjalista wczesnej interwencji i wspomagania rozwoju małego dziecka, terapeuta integracji sensorycznej." data-name="Malec Zbigniew">Malec Zbigniew</p>
                       	<td id="z_3">
                        	<img src="img/z_3.png" id="z_3_i">
                            <p class="jedno" id="z_3_p" data-opis="psycholog, terapeuta II stopnia metody biofeedback i rehacom, doradca systemowy rodzin pracujący pod stałą superwizją certyfikowanych superwizorów Wielkopolskiego Towarzystwa Terapii Systemowej specjalizujący się w terapii systemowej rodzin oraz w zakresie diagnozy problemów rozwojowych, trudności  szkolnych." data-name="Sylwia Grześlak">Sylwia Grześlak</p>
                       	</td>
						<td id="z_4">
                        	<img src="img/z_4.png" id="z_4_i">
                            <p class="jedno" id="z_4_p" data-opis="pedagog, certyfikowany terapeuta EEG Biofeedback i rehacom, psychoterapeuta systemowy w trakcie certyfikacji (członek nadzwyczajny WTTS Poznań), doradca zawodowy, diagnosta przyczyn niepowodzeń szkolnych i trudności wychowawczych ." data-name="Dorota Urbańczyk">Dorota Urbańczyk</p>
                       	</td>
                    </tr>
					<tr>
                    	<td id="z_5"> 
                        	<img src="img/z_5.png" id="z_5_i">
                            <p class="jedno" id="z_5_p" data-opis="pedagog specjalny, certyfikowana terapeutka integracji sensorycznej. Ukończyła pedagogikę o specjalizacji rewalidacja na Uniwersytecie Wrocławskim. Absolwentka studiów podyplomowych z zakresu pedagogiki resocjalizacyjnej oraz terapii pedagogicznej. Pracowała z dziećmi i młodzieżą ze środowisk zagrożonych patologią społeczną prowadząc zajęcia profilaktyczne z elementami socjoterapii. Od kilku lat pracuje jako wychowawczyni i nauczycielka-terapeutka z dziećmi i młodzieżą z głębszym stopniem niepełnosprawności intelektualnej i sprzężonymi zaburzeniami." data-name="Dorota Cierniak">Dorota Cierniak</p>
                       	</td>
                        <td id="z_6">
                        	<img src="img/z_7.png" id="z_6_i">
                            <p class="jedno" id="z_6_p" data-opis="Psycholog z dyplomem Uniwersytetu Wrocławskiego. Od 2006 roku psycholog w Szkole Podstawowej z oddziałami integracyjnymi we Wrocławiu. Terapeuta  biofeedback I stopnia. Posiada certyfikat I stopnia Wielkopolskiego Towarzystwa Terapii Systemowej oraz Praktyka NLP. Trener w programie edukacyjnym Destination Imagination (DI) zorientowanym na kreatywne rozwiązywanie problemów. Ukończony kurs z Metody ruchu rozwijającego wg Weroniki Sherbone. Ukończony warsztat z Kinezjologii edukacyjnej. Specjalizuje się w pracy z dziećmi nadpobudliwymi w tym z dziećmi z ADHD. W latach 2009/2010 asystent w projekcie „Wprowadzenie nowej metody przeciwdziałania objawom ADHD – Asystent ucznia  z ADHD we wrocławskiej szkole." data-name="Marta Suchta">Marta Suchta</p>
                       	<td id="z_7">
                        	<img src="img/z_6.png" id="z_7_i">
                            <p class="jedno" id="z_7_p" data-opis="pedagog, tyflopedag, pracuje w poradni psychologiczno-pedagogicznej, zajmuje się diagnozą rozwoju dzieci w wieku przedszkolnym, diagnozą przyczyn trudności szkolnych uczniów szkół podstawowych, w tym dysleksji, oraz diagnozą pedagogiczną dzieci słabowidzących i niewidomych, słabosłyszących i niesłyszących, prowadzi indywidualne zajęcia wspierające rozwój, w tym nauce czytania i pisania, myślenia matematycznego, prowadziła warsztaty dla dzieci z ryzyka dysleksji, z twórczego myślenia i in." data-name="Agnieszka Krzywaźnia ">Agnieszka Krzywaźnia </p>
                       	</td>
						<td id="z_8">
                        	<img src="img/z_8.png" id="z_8_i" style="display: none;">
                            <p class="jedno" id="z_8_p" data-opis="tytuł magistra psychologii uzyskała w 2010 roku na Uniwersytecie Erazma w Rotterdamie, Holandia. Od 2011 roku zajmuje się diagnozą, poradnictwem w zakresie psychologii rozwojowej oraz terapią dzieci w wieku od 0 do 7 roku życia. Dodatkowo, od 2014 roku zajmuje się terapią zaburzeń integracji sensorycznej." data-name="Maria Koszucka">Maria Koszucka</p>
							<p class="jedno" style=" border-top: solid 1px white; height: 24%;"><span style="font-size: 28px; border-bottom: solid 1px white;">Jolanta Dolata</span></p>
						</td>
                    </tr>
            	</table>
            </section>
            <section id="cennik">
            	<img src="img/pik_c.png">
                <p class="p_h">&emsp;CENNIK&emsp;</p>
                <br><br>
                <div  class="b">
                	<p>Biofeedback</p>
                    <img src="img/dol.png" id="bio" onClick="wiecej(this.id);">
                    <table>
                    	<tr>
                        	<th style="border-left:none !important; border-top: none !important; width: 55%; ">Rodzaj usługi</th>
                            <th style="border-top: none !important; width: 25%;">Czas trwania</th>
                            <th style="border-right:none!important;border-top: none !important;">Cena</th>
                        </tr>
                        <tr>
                        	<td style="border-left:none !important;">EEG BIOFEEDBACK – konsultacja wstępna</td>
                            <td>do 30 min</td>
                            <td style="border-right:none !important;">0 zł</td>
                        </tr>
                        <tr>
                        	<td style="border-left:none !important;">EEG BIOFEEDBACK – diagnoza</td>
                            <td>30-60 min</td>
                            <td style="border-right:none !important;">120 zł</td>
                        </tr>
                        <tr>
                        	<td style="border-left:none !important;">Terapia EEG BIOFEEDBACK</td>
                            <td>30-60 min</td>
                            <td style="border-right:none !important;">60 zł</td>
                        </tr>
                        <tr>
                        	<td style="border-left:none !important;">Trening EEG BIOFEEDBACK – pakiet 10 sesji</td>
                            <td>-</td>
                            <td style="border-right:none !important;">540 zł</td>
                        </tr>
                        <tr>
                        	<td style="border-left:none !important;">Trening EEG BIOFEEDBACK – pakiet 15 sesji</td>
                            <td>-</td>
                            <td style="border-right:none !important;">750 zł</td>
                        </tr>
                    </table>
                </div>
                <div>
                	<p>Reha-com</p>
                    <img src="img/dol.png"  id="reha" onClick="wiecej(this.id);">
                    <table>
                    	<tr>
                        	<th style="border-left:none; width: 55%;">Rodzaj usługi</th>
                            <th style="width: 25%;">Czas trwania</th>
                            <th style="border-right:none;">Cena</th>
                        </tr>
                        <tr>
                        	<td style="color:white; border-left:none;">REHA-COM – konsultacja wstępna</td>
                            <td>do 30 min</td>
                            <td style="border-right:none;">0 zł</td>
                        </tr>
                        <tr>
                        	<td style="color:white; border-left:none;">Terapia REHA-COM</td>
                            <td>30-60 min</td>
                            <td style="border-right:none;">60 zł</td>
                        </tr>
                        <tr>
                        	<td style="color:white; border-left:none;">Trening REHA-COM – pakiet 10 sesji</td>
                            <td>-</td>
                            <td style="border-right:none;">540 zł</td>
                        </tr>
                        <tr>
                        	<td style="color:white; border-left:none;">Trening REHA-COM – pakiet 15 sesji</td>
                            <td>-</td>
                            <td style="border-right:none;">750 zł</td>
                        </tr>
                    </table>
                </div>
                <div class="b">
                	<p>Terapia pedagogiczna</p>
                    <img src="img/dol.png"  id="ter" onClick="wiecej(this.id);">
                    <table>
                    	<tr>
                        	<th style="border-left:none !important; border-top: none !important;">Rodzaj usługi</th>
                            <th style="border-top: none !important; width: 15%;">Forma usługi</th>
                            <th style="border-top: none !important; width: 25%;">Czas trwania</th>
                            <th style="border-right:none!important;border-top: none !important;  width: 20%;">Cena</th>
                        </tr>
                        <tr>
                        	<td style="border-left:none !important;">Diagnoza pedagogiczna</td>
                            <td>-</td>
                            <td>-</td>
                            <td style="border-right:none !important;">120 zł</td>
                        </tr>
                        <tr>
                        	<td rowspan="2" style="height: 40px; border-left:none !important;">Terapia pedagogiczna (reedukacja)</td>
                            <td rowspan="2" style="height: 40px; ">-</td>
                            <td style="height: 40px; ">45 min</td>
                            <td rowspan="2" style="height: 40px; border-right:none !important;">120 zł</td>
                        </tr>
                        <tr>
                        	<td style="height: 40px; ">60 min</td>
                        </tr>
                        <tr>
                        	<td rowspan="2" style="height: 40px; border-left:none !important;">Diagnoza trudności szkolnych i dysleksji</td>
                            <td style="height: 40px; ">diagnoza</td>
                            <td rowspan="2" style="height: 40px; ">-</td>
                            <td style="height: 40px; border-right:none !important;">290 zł</td>
                        </tr>
                        <tr>
                        	<td style="height: 40px; ">omówienia</td>
                            <td style="height: 40px; border-right:none !important;">110 zł</td>
                        </tr>
                        <tr>
                        	<td rowspan="2" style="height: 40px; border-left:none !important;">Diagnoza gotowości szkolnej</td>
                            <td style="height: 40px; ">diagnoza</td>
                            <td rowspan="2" style="height: 40px; ">-</td>
                            <td style="height: 40px; border-right:none !important;">290 zł</td>
                        </tr>
                        <tr>
                        	<td style="height: 40px; ">omówienia</td>
                            <td style="height: 40px; border-right:none !important;">110 zł</td>
                        </tr>
                    </table>
                </div>
                <div>
                	<p>Integracja sensoryczna</p>
                    <img src="img/dol.png"  id="sen" onClick="wiecej(this.id);">
                    <table>
                    	<tr>
                        	<th style="border-left:none; width: 55%;">Rodzaj usługi</th>
                            <th style="width: 25%;">Czas trwania</th>
                            <th style="border-right:none;">Cena</th>
                        </tr>
                        <tr>
                        	<td style="color:white; border-left:none;">Terapia Integracji Sensorycznej (SI)</td>
                            <td>45 min</td>
                            <td style="border-right:none;">80 zł</td>
                        </tr>
						<tr>
                        	<td style="color:white; border-left:none;">Diagnoza rozwoju SI</td>
                            <td>3 spotkania</td>
                            <td style="border-right:none;">200 zł</td>
                        </tr>
                    </table>
                </div>
                <div class="b">
                	<p>Badania psychologiczne</p>
                    <img src="img/dol.png"  id="inne" onClick="wiecej(this.id);">
                    <table>
                    	<tr>
                        	<th style="border-left:none !important; border-top: none !important; width: 70%;">Rodzaj usługi</th>
                            <th style="border-right:none!important;border-top: none !important;">Cena</th>
                        </tr>
                        <tr>
                        	<td style="border-left:none !important;">Psychologiczne badanie osób ubiegających się o pozwolenie na broń</td>
                            <td style="border-right:none !important;">200 (z VAT *)</td>
                        </tr>
                        <tr>
                        	<td style="border-left:none !important;">Badanie psychologiczne osób posiadających już zezwolenie na broń</td>
                            <td style="border-right:none !important;">200 (z VAT *)</td>
                        </tr>
                        <tr>
                        	<td style="border-left:none !important;">Badania Psychologiczne dla osób ubiegających się o licencje detektywistyczną</td>
                            <td style="border-right:none !important;">150 PLN</td>
                        </tr>
                        <tr>
                        	<td style="border-left:none !important;">Badania Psychologiczne – nabywanie i przechowywanie materiałów wybuchowych przeznaczonych do użytku cywilnego</td>
                            <td style="border-right:none !important;">150 PLN</td>
                        </tr>
                        <tr>
                        	<td style="border-left:none !important;">Badanie Psychologiczne – Kierowanie działalnością gospodarczą lub zatrudnienie przy wyrobie ładunków wybuchowych</td>
                            <td style="border-right:none !important;">150 PLN</td>
                        </tr>
                        <tr>
                        	<td style="border-left:none !important;">Badanie psychologiczne Sędziego/Kuratora</td>
                            <td style="border-right:none !important;">180 PLN</td>
                        </tr>
                        <tr>
                        	<td style="border-left:none !important;">Badania Pracowników Ochrony badania wstępne</td>
                            <td style="border-right:none !important;">180 PLN</td>
                        </tr>
                        <tr>
                        	<td style="border-left:none !important;">Badania Pracowników Ochrony
posiadających już licencję pracownika ochrony</td>
                            <td style="border-right:none !important;">150 PLN</td>
                        </tr>
                    </table>
                </div>
            </section>
            <section id="kontakt">
            	<img src="img/pik_k.png"><br>
                <p class="n">&emsp;Kontakt&emsp;</p>
                <p>50-524 Wrocław | ul Ciepła 9-11/1e<br>71 780 69 92<br><span style="color: #ea8123">email: biuro@psychosfera.wroc.pl</span><br>Spytaj o szczegóły terapii:</p>
                <form action="#kontakt" method="post" name="kontakt" id="f_kontakt" onsubmit="return (val('name') && val('phone') && val('email')&& val('msg') );">
                	<table>
                    	<tr>
                        	<td colspan="2">
                            	 <input name="name" type="text" id="name" placeholder="Imie i nazwisko" onblur="val(this.id)">
                            </td>
                        </tr>
                        <tr>
                        	<td>
                            	<input name="phone" type="text" id="phone" placeholder="Telefon" onblur="val(this.id)">	
                            </td>
                            <td>
                            	<input name="email" type="text" id="email" placeholder="E-mail" onblur="val(this.id)">
                            </td>
                        </tr>
                        <tr>
                        	<td colspan="2">
                            	 <textarea name="msg" cols="" rows="" id="msg" placeholder="Treść"onblur="val(this.id)"></textarea>
                            </td>
                        </tr>
						<tr>
                        	<td><span style="font-size: 22px;">Wpisz kod z obrazka&emsp;</span><img src="cap.php" alt="Captcha" /></td>
							<td><input name="cap" type="text" id="cap" onblur="val(this.id)" style="vertical-align: middle; margin-top: 15px;" placeholder="Kod"></td>
                        </tr> 
                        <tr>
                        	<td colspan="2"><input name="submit" type="submit" value="wyślij" class="btn" >&emsp;&emsp;&emsp;<span style="font-size: 22px;"><?php echo $komunikat ?></span></td>
                        </tr>                                               
                    </table>
                    </form>
           	</section>            
			<footer id="STOPKA">
           		<a href="http://www.pinkelephant.pl/">Projekt i realizacja: Agencja Reklamowa Pink Elephant
                <img src="img/pink.png"></a>
            </footer>
	</div>
</body>
</html>