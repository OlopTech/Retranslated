<?php
session_start();

// Variable globale pour stocker les traductions
$translations = null;

// Fonction pour définir la langue
function setLanguage($lang) {
    $_SESSION['lang'] = $lang;
}

// Fonction pour obtenir la langue actuelle
function getLanguage() {
    if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'fr'])) {
        setLanguage($_GET['lang']);
        header("Location: " . strtok($_SERVER['REQUEST_URI'], '?')); // Supprime les paramètres GET
        exit;
    }
    return isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';
}

// Fonction pour charger les traductions depuis le fichier JSON
function loadTranslations() {
    global $translations;
    $lang = getLanguage();
    $jsonFile = basename($_SERVER['PHP_SELF']) . ".$lang.json";
    if (file_exists($jsonFile)) {
        $translations = json_decode(file_get_contents($jsonFile), true);
    } else {
        // Charger les traductions en anglais par défaut
        $jsonFile = basename($_SERVER['PHP_SELF']) . ".en.json";
        if (file_exists($jsonFile)) {
            $translations = json_decode(file_get_contents($jsonFile), true);
        } else {
            $translations = [];
        }
    }
}

// Fonction pour traduire le texte
function translate($key) {
    global $translations;
    if ($translations === null) {
        loadTranslations();
    }
    return isset($translations[$key]) ? $translations[$key] : $key;
}
?>
