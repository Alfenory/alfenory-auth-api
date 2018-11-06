<?php

$config = array();
$config["db"] = array();
$config["db"]["name"] = "iao";
$config["db"]["username"] = "iao";
$config["db"]["password"] = "iao";
$config["db"]["host"] = "localhost";

$config["modules"] = array();
$config["logger"] = false;
$config["api_url_path"] = "api";
$config["extended_privileg_user_object"] = array(); //label, doctrine object entity path

$config["email"] = array();
$config["email"]["address"] = "TODO";
$config["email"]["name"] = "TODO";
$config["email"]["smtp_host"] = "TODO";
$config["email"]["smtp_port"] = "TODO";
$config["email"]["smtp_ssl"] = false;
$config["email"]["username"] = "TODO";
$config["email"]["password"] = "TODO";
$config["email"]["content"] = array();
$config["email"]["content"]["header"] = "<div><div style='max-width: 600px'><h4>Header</h4></div><div style='max-width: 600px'>";
$config["email"]["content"]["footer"] = "</div><div style='max-width: 600px'><h5>Footer</h5></div></div>";

$config["email"]["content"]["confirmation"] = "
<p><b>{salutation]</b></p>
<p><b>Link um das Passwort für den Login zu setzen.</p>
<p><a href='{url}'>{url}</a></p>
<p>Bitte vergeben Sie zuerst ein individuelles Passwort und bestätigen Sie dieses. Danach werden Sie gebeten einen Benutzernamen und Ihr neues Passwort für die Anmeldung einzugeben.</p>
<p>Ihr Benutzername lautet: <b>{username}</b></p>
<p>Nach der ersten Anmeldung haben Sie die Möglichkeit über die „Passwort vergessen“ Funktion Ihr Passwort jederzeit zu ändern.</p>
";

$config["email"]["content"]["resetpassword"] = "
<p><b>Neues Passwort setzen für Ihr System Plattform!</b><br/><br/>Um ein neues Passwort für die Plattform zu setzen, müssen Sie innerhalb von 24 h den folgenden Link im Browser öffnen.</p>
<p><a href='{url}'>{url}</a></p>
";


$config["url"] = "http://localhost:4200/";
$config["email"]["content"]["confirmationsubject"] = "Bestätigung";