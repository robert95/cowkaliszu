<?php
function normalMenu()
{
	echo '<table>
			<tr>
				<td onclick="window.location.href = "addevent_1.php";"><img src="img/p_plus.png" alt="Dodaj wydarzenie"/> <a href="addevent_1.php">Dodaj wydarzenie</a></td>
				<td onclick="window.location.href = "zapisanewydarzenie.php";"><img src="img/p_saved.png" alt="Kategorie"/> <a href="zapisanewydarzenie.php">Zapisane wydarzenie</a></td>
				<td onclick="window.location.href = "settings.php";"><img src="img/p_setting.png" alt="Kategorie"/> <a href="settings.php">Ustawienia konta</a></td>
				<td onclick="window.location.href = "logout.php";"><img src="img/p_logout.png" alt="Kategorie"/> <a href="logout.php">Wyloguj</a></td>
			</tr>
		</table>';
}

function adminMenu()
{
	echo '<table>
			<tr>
				<td onclick="window.location.href = "dodajkategorie.php";"><img src="img/p_plus.png" alt="Dodaj kategorie"/> <a href="dodajkategorie.php">Dodaj kategorię</a></td>
				<td onclick="window.location.href = "addevent_1.php";"><img src="img/p_plus.png" alt="Dodaj wydarzenie"/> <a href="addevent_1.php">Dodaj wydarzenie</a></td>
				<td onclick="window.location.href = "zapisanewydarzenie.php";"><img src="img/p_saved.png" alt="Kategorie"/> <a href="zapisanewydarzenie.php">Zapisane wydarzenie</a></td>
				<td onclick="window.location.href = "settings.php";"><img src="img/p_setting.png" alt="Kategorie"/> <a href="settings.php">Ustawienia konta</a></td>
				<td onclick="window.location.href = "logout.php";"><img src="img/p_logout.png" alt="Kategorie"/> <a href="logout.php">Wyloguj</a></td>
			</tr>
		</table>';
}
	
?>