<?php
/**
 * API REST pour la gestion des contacts
 * Endpoints : GET (liste), POST (création)
 */

require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        getContacts();
        break;
    
    case 'POST':
        createContact();
        break;
    
    default:
        sendJsonResponse([
            'success' => false,
            'message' => 'Méthode non autorisée'
        ], 405);
        break;
}

/**
 * Récupérer tous les contacts
 */
function getContacts() {
    try {
        $pdo = getDbConnection();
        
        $stmt = $pdo->query("
            SELECT id, nom, prenom, email, commentaire, date_creation 
            FROM contacts 
            ORDER BY date_creation DESC
        ");
        
        $contacts = $stmt->fetchAll();
        
        sendJsonResponse([
            'success' => true,
            'data' => $contacts,
            'count' => count($contacts)
        ]);
        
    } catch (Exception $e) {
        sendJsonResponse([
            'success' => false,
            'message' => 'Erreur lors de la récupération des contacts'
        ], 500);
    }
}

/**
 * Créer un nouveau contact
 */
function createContact() {
    try {
        // Récupérer les données JSON
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Validation des champs requis (email optionnel)
        if (!isset($input['nom']) || !isset($input['prenom']) || !isset($input['commentaire'])) {
            sendJsonResponse([
                'success' => false,
                'message' => 'Les champs nom, prénom et commentaire sont requis'
            ], 400);
        }

        // Nettoyer les données
        $nom = sanitizeInput($input['nom']);
        $prenom = sanitizeInput($input['prenom']);
        $commentaire = sanitizeInput($input['commentaire']);

        // Email est optionnel
        $email = null;
        if (isset($input['email']) && !empty($input['email'])) {
            $email = sanitizeInput($input['email']);
        }

        // Validation supplémentaire
        if (empty($nom) || empty($prenom) || empty($commentaire)) {
            sendJsonResponse([
                'success' => false,
                'message' => 'Les champs nom, prénom et commentaire doivent être remplis'
            ], 400);
        }
        
        // Insérer dans la base de données
        $pdo = getDbConnection();
        
        $stmt = $pdo->prepare("
            INSERT INTO contacts (nom, prenom, email, commentaire) 
            VALUES (:nom, :prenom, :email, :commentaire)
        ");
        
        $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':commentaire' => $commentaire
        ]);
        
        $contactId = $pdo->lastInsertId();
        
        sendJsonResponse([
            'success' => true,
            'message' => 'Contact créé avec succès',
            'data' => [
                'id' => $contactId,
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'commentaire' => $commentaire
            ]
        ], 201);
        
    } catch (Exception $e) {
        sendJsonResponse([
            'success' => false,
            'message' => 'Erreur lors de la création du contact'
        ], 500);
    }
}
?>