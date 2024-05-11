<?php
include 'config.php';
session_start();

// Validation et nettoyage des données POST nécessaires
if(!(isset($_POST['id']) &&
    isset($_POST['jour']) &&
    isset($_POST['heure']) &&
    isset($_POST['etat'])
)) {
    header("location:liste_presence.php");
}

$id = $_POST['id'];
$jour = $_POST['jour'];
$heure = $_POST['heure'];
$etat = $_POST['etat'];
$classePrecedente = $_POST['classePrecedente'] ? $_POST['classePrecedente'] : "6TTI";
$jourPrecedent = $_POST['jourPrecedent'] ? $_POST['jourPrecedent'] : "lundi";

// Vérifier la connexion à la base de données
if ($conn->connect_error) {
    die("Échec de connexion : " . $conn->connect_error);
}

// Préparer une requête pour vérifier si l'enregistrement de présence existe déjà
$query = $conn->prepare("SELECT * FROM presences WHERE id_eleve = ? AND jour = ? AND heure = ?");
$query->bind_param("isi", $id, $jour, $heure);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    // L'enregistrement existe, effectuer une mise à jour
    $update = $conn->prepare("UPDATE presences SET etat = ? WHERE id_eleve = ? AND jour = ? AND heure = ?");
    $update->bind_param("sisi", $etat, $id, $jour, $heure);
    if (!$update->execute()) {
        die('Erreur de mise à jour MySQL : ' . htmlspecialchars($update->error));
    }
    $update->close();
} else {
    $jourEscaped = mysqli_real_escape_string($conn, $jour);
    $insert = $conn->prepare("INSERT INTO presences (id_eleve, jour, etat, heure) VALUES (?, '$jourEscaped', ?, ?)");
    $insert->bind_param("iss", $id, $etat, $heure);
    $insert->execute();
}

$query->close();
$conn->close();

// Rediriger vers la liste des présences avec les paramètres de filtre appropriés
header("Location: liste_presence.php?classe=$classePrecedente&jour=$jourPrecedent");
exit;
?>