<?php
<<<<<<< HEAD
/**
 * connexion.php - Connexion à la base de données MySQL via PDO
 * Base de données : admission_medecine
 */
=======
$host = "localhost";
$dbname = "admission_medecine";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
>>>>>>> tache-formulaire

define('DB_HOST', 'localhost');
define('DB_NAME', 'admission_medecine');
define('DB_USER', 'root');       // ← Modifier selon votre configuration
define('DB_PASS', '');           // ← Modifier selon votre configuration
define('DB_CHARSET', 'utf8mb4');

function getConnexion(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die('<div style="font-family:sans-serif;color:#c0392b;padding:20px;">
                    <h2>⚠️ Erreur de connexion à la base de données</h2>
                    <p>' . htmlspecialchars($e->getMessage()) . '</p>
                    <p>Vérifiez les paramètres dans <code>connexion.php</code> et assurez-vous que MySQL est démarré.</p>
                 </div>');
        }
    }
    return $pdo;
}
?>
