-- Initialisation de la base de données

CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) DEFAULT NULL,
    commentaire TEXT NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_date (date_creation)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insérer quelques données de test (optionnel)
INSERT INTO contacts (nom, prenom, email, commentaire) VALUES
('Dupont', 'Jean', 'jean.dupont@example.com', 'Ceci est un commentaire de test'),
('Martin', 'Marie', 'marie.martin@example.com', 'Merci pour votre application'),
('Bernard', 'Pierre', 'pierre.bernard@example.com', 'Test de fonctionnalité');