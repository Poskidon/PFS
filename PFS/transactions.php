<?php
session_start();

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$database = "smartspenddb";

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $database);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

$message = "";

// Traitement de la suppression
if (isset($_POST['supprimer'])) {
    $transactionID = $_POST['transaction_id'];
    $sql_delete = "DELETE FROM transactions WHERE TransactionID = $transactionID";

    if ($conn->query($sql_delete) === TRUE) {
        // Rediriger vers la même page après la suppression réussie
        header("Location: $_SERVER[PHP_SELF]");
        exit();
    } else {
        $message = "<div class='erreur'>Erreur lors de la suppression de la transaction : " . $conn->error . "</div>";
    }
}

// Traitement de la modification
if (isset($_POST['modifier'])) {
    $transactionID = $_POST['transaction_id'];
    // Récupérer les nouvelles valeurs des champs du formulaire
    $nouveauMontant = $_POST['nouveau_montant'];
    $nouvelleDate = $_POST['nouvelle_date'];
    $nouvelleDescription = $_POST['nouvelle_description'];

    $sql_update = "UPDATE transactions SET Montant = $nouveauMontant, Date = '$nouvelleDate', Description = '$nouvelleDescription' WHERE TransactionID = $transactionID";

    if ($conn->query($sql_update) === TRUE) {
        // Rediriger vers la même page après la modification réussie
        header("Location: $_SERVER[PHP_SELF]");
        exit();
    } else {
        $message = "<div class='erreur'>Erreur lors de la modification de la transaction : " . $conn->error . "</div>";
    }
}

// Récupérer les transactions de l'utilisateur connecté
$userID = $_SESSION['user_id'];
$sql_select = "SELECT * FROM transactions WHERE UserID = $userID";
$result = $conn->query($sql_select);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartSpend - Espace Personnel</title>
    <link rel="stylesheet" href="espace_personnel.css">
    <style>
        /* Style du tableau */
        .transactions-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .transactions-table th, .transactions-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .transactions-table th {
            background-color: #f2f2f2;
        }

        .transactions-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .transactions-table tbody tr:hover {
            background-color: #ddd;
        }

        /* Style des boutons */
        .transactions-table button {
            padding: 5px 10px;
            cursor: pointer;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 3px;
        }

        .transactions-table button:hover {
            background-color: #0056b3;
        }

        /* Style du formulaire de modification */
        .modification-form {
            display: none;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin-top: 10px;
        }

        .modification-form label {
            display: block;
            margin-bottom: 10px;
        }

        .modification-form input[type="number"],
        .modification-form input[type="date"],
        .modification-form input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .modification-form button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .modification-form button:hover {
            background-color: #45a049;
        }

        .modification-form.show {
            display: table-row;
        }
    </style>
</head>
<body>
    <header>
        <h1>Espace Personnel - SmartSpend</h1>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="espace_personnel.php">Espace Personnel</a></li>
                <li><a href="#">Objectifs Financiers</a></li>
                <li><a href="#">Alertes</a></li>
                <li><a href="deconnexion.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <!-- Affichage des transactions -->
        <section id="transactions">
            <h2>Mes Transactions</h2>
            <table class="transactions-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Montant</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Boucle pour afficher les transactions -->
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["Date"] . "</td>";
                            echo "<td>" . $row["Montant"] . "</td>";
                            echo "<td>" . $row["Description"] . "</td>";
                            echo "<td>";
                            // Bouton de modification
                            echo "<button class='modifier-btn' data-transaction-id='" . $row["TransactionID"] . "'>Modifier</button>";
                            // Bouton de suppression
                            echo "<form action='' method='post'>";
                            echo "<input type='hidden' name='transaction_id' value='" . $row["TransactionID"] . "'>";
                            echo "<button type='submit' name='supprimer'>Supprimer</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                            // Formulaire de modification
                            echo "<tr class='modification-form' id='form-" . $row["TransactionID"] . "'>";
                            echo "<td colspan='4'>";
                            echo "<form action='' method='post'>";
                            echo "<input type='hidden' name='transaction_id' value='" . $row["TransactionID"] . "'>";
                            echo "<label for='nouveau_montant'>Nouveau Montant :</label>";
                            echo "<input type='number' id='nouveau_montant' name='nouveau_montant' required>";
                            echo "<label for='nouvelle_date'>Nouvelle Date :</label>";
                            echo "<input type='date' id='nouvelle_date' name='nouvelle_date' required>";
                            echo "<label for='nouvelle_description'>Nouvelle Description :</label>";
                            echo "<input type='text' id='nouvelle_description' name='nouvelle_description' required>";
                            echo "<button type='submit' name='modifier'>Modifier</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>Aucune transaction disponible.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>
    <footer>
        <p>© 2024 SmartSpend. Tous droits réservés.</p>
    </footer>
    <script>
        // Script pour afficher le formulaire de modification lors du clic sur le bouton "Modifier"
        document.addEventListener('DOMContentLoaded', function() {
            var modifierBtns = document.querySelectorAll('.modifier-btn');
            modifierBtns.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var transactionID = this.getAttribute('data-transaction-id');
                    var form = document.getElementById('form-' + transactionID);
                    if (form.classList.contains('show')) {
                        form.classList.remove('show');
                    } else {
                        form.classList.add('show');
                    }
                });
            });
        });
    </script>
</body>
</html>


