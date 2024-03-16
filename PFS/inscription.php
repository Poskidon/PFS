<?php
$mois =  ["janvier","fevrier","mars","mai","avril","juin","juillet","aout","septembre","ocotbre","novembre","decembre"];
$jourdelasemaine = ["Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi"];
$message = "";
include("form.php");
if(isset($valider)){
    if(!preg_match("#^[a-zA-zéèêïÉÈÊÏ \-]+$#",$nom))
        $message="<div class='erreur'>Nom invalide!</div>";
    if(!preg_match("#^[a-zA-zéèêïÉÈÊÏ \-]+$#",$prenom))
        $message.="<div class='erreur'>Prénom invalide!</div>";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $message.="<div class='erreur'>Email invalide!</div>";
    if($mdp!=$rmdp)
        $message.="<div class='erreur'>Mot de passes non identiques!</div>";
    if($message === ""){
// Connexion à la base de données
$servername = "172.19.0.2";
$username = "root";
$password = "root";
$database = "SmartSpendDB";

$conn = new mysqli($servername, $username, $password, $database);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}
// Date d'inscription actuelle
$date_inscription = date("Y-m-d");
$Fullname = $nom." ".$prenom;
$MotDePasse = md5($mdp);
// Insérer les données dans la base de données avec la date d'inscription actuelle
$sql = "INSERT INTO Utilisateurs (Nom, Email, MotDePasse, DateInscription) VALUES ('$Fullname', '$email', '$MotDePasse', '$date_inscription')";
if ($conn->query($sql) === TRUE) {
    $message ="<div class='ok'>Félicitations, vous êtes maintenant inscrit sur notre site! Vous pouvez vous connecter avec vos identifiants.</div>";
} else {
     $message ="<div class='erreur'>Erreur lors du process d'inscription</div>";
}

// Fermer la connexion à la base de données
$conn->close();
  } 
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Sign Up</title>
		<link rel="stylesheet" href="CSS/inscription.css" />
	</head>
	<body>
		<form name="fo" method="post" action="" method="POST">
			<h1 class="h1title">Inscription
			<span class="date">
				<?php
					echo $jourdelasemaine[date('w')]." ".date('j')." ".$mois[date('n')-1]." ".date('Y');//j->jourdemois
				?>
			</span>
			</h1>

			<div class="label">Nom <span>*</span></div>
			<div class="input">
				<input type="text" name="nom" class="zdt" />
			</div>
			<div class="label">Prénom <span>*</span></div>
			<div class="input">
				<input type="text" name="prenom" class="zdt"  />
			</div>
			<div class="label">Email <span>*</span></div>
			<div class="input">
				<input type="email" name="email" class="zdt"/>
			</div>
			<div class="label">Mot de passe<span>*</span></div>
			<div class="input">
				<input type="password" name="mdp" class="zdt"/>
			</div>	
			<div class="label">Confirmer le mot de passe<span>*</span></div>
			<div class="input">
				<input type="password" name="rmdp" class="zdt"/>
			</div>	
			<div class="input">
				<input type="submit" name="valider" class="zdt submit" value="S'inscrire" />
			</div>
            <?php echo $message;?>
		</form>
	</body>
</html>
