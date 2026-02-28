-- ============================================================
--  Script SQL - Système d'Admission Faculté de Médecine
--  Université Cheikh Anta Diop (UCAD)
-- ============================================================

-- 1. Créer la base de données
CREATE DATABASE IF NOT EXISTS admission_medecine
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- 2. Sélectionner la base
USE admission_medecine;

-- 3. Créer la table etudiants
CREATE TABLE IF NOT EXISTS etudiants (
    id          INT           NOT NULL AUTO_INCREMENT  COMMENT 'Identifiant unique',
    nom         VARCHAR(100)  NOT NULL                 COMMENT 'Nom de l\'étudiant',
    prenom      VARCHAR(100)  NOT NULL                 COMMENT 'Prénom de l\'étudiant',
    age         INT           NOT NULL                 COMMENT 'Âge de l\'étudiant',
    note_math   FLOAT         NOT NULL                 COMMENT 'Note en Mathématiques (0-20)',
    coef_math   INT           NOT NULL DEFAULT 3       COMMENT 'Coefficient Mathématiques',
    note_pc     FLOAT         NOT NULL                 COMMENT 'Note en Physique-Chimie (0-20)',
    coef_pc     INT           NOT NULL DEFAULT 2       COMMENT 'Coefficient Physique-Chimie',
    note_svt    FLOAT         NOT NULL                 COMMENT 'Note en SVT (0-20)',
    coef_svt    INT           NOT NULL DEFAULT 3       COMMENT 'Coefficient SVT',
    moyenne     FLOAT         NOT NULL                 COMMENT 'Moyenne pondérée calculée',
    admissible  VARCHAR(20)   NOT NULL                 COMMENT 'Oui ou Non',
    created_at  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date d\'enregistrement',
    PRIMARY KEY (id),
    INDEX idx_nom      (nom),
    INDEX idx_admissible (admissible),
    INDEX idx_created  (created_at)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Table des étudiants candidats à la Faculté de Médecine';

-- 4. Données de test (optionnel - à commenter en production)
INSERT INTO etudiants (nom, prenom, age, note_math, coef_math, note_pc, coef_pc, note_svt, coef_svt, moyenne, admissible)
VALUES
  ('Diallo',   'Aminata',  19, 14.5, 3, 15.0, 2, 16.0, 3, 15.06, 'Oui'),
  ('Ndiaye',   'Moussa',   21, 10.0, 3,  9.5, 2, 11.0, 3, 10.19, 'Non'),
  ('Fall',     'Fatou',    18, 17.0, 3, 16.5, 2, 18.0, 3, 17.25, 'Oui'),
  ('Sow',      'Ibrahima', 23, 13.5, 3, 14.0, 2, 12.5, 3, 13.25, 'Non'),
  ('Sarr',     'Mariama',  20, 12.0, 3, 11.5, 2, 13.0, 3, 12.25, 'Non'),
  ('Ba',       'Ousmane',  17, 15.5, 3, 14.0, 2, 16.5, 3, 15.5,  'Oui');

-- ============================================================
-- Formule de la moyenne pondérée :
--   moyenne = (note_math * coef_math + note_pc * coef_pc + note_svt * coef_svt)
--              / (coef_math + coef_pc + coef_svt)
--
-- Critères d'admissibilité :
--   • Moyenne > 12/20  (strictement supérieure)
--   • Âge < 22 ans     (strictement inférieur)
-- ============================================================