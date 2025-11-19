<?php
/**
 * Configuration de la base de données
 * Utilise exclusivement les variables d'environnement
 */

// En-têtes CORS
$frontend_url = getenv('FRONTEND_URL') ?: 'http://localhost:3000';
header("Access-Control-Allow-Origin: {$frontend_url}");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

// Gérer les requêtes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

/**
 * Récupération des paramètres de connexion depuis les variables d'environnement
 */
$db_host = getenv('MYSQL_HOST') ?: 'db';
$db_name = getenv('MYSQL_DATABASE');
$db_user = getenv('MYSQL_USER');
$db_pass = getenv('MYSQL_PASSWORD');

// Vérifier que toutes les variables sont définies
if (!$db_name || !$db_user || !$db_pass) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Configuration de la base de données manquante'
    ]);
    exit();
}

/**
 * Fonction de connexion à la base de données
 */
function getDbConnection() {
    global $db_host, $db_name, $db_user, $db_pass;
    
    try {
        $dsn = "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        $pdo = new PDO($dsn, $db_user, $db_pass, $options);
        return $pdo;
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Erreur de connexion à la base de données'
        ]);
        exit();
    }
}

/**
 * Fonction pour envoyer une réponse JSON
 */
function sendJsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit();
}

/**
 * Fonction pour valider un email
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Fonction pour nettoyer les données
 */
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?>