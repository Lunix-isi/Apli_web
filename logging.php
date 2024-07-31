<?php
// Chemin vers le répertoire des logs
$logDir = __DIR__ . '/logs/';

// Fonction pour obtenir le nom du fichier de log pour aujourd'hui
function getLogFile() {
    global $logDir;
    $date = date('Y-m-d'); // Format de la date pour le fichier
    return $logDir . "logfile_$date.log";
}

// Fonction pour écrire dans le fichier de log
function logMessage($message) {
    $logFile = getLogFile();
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] $message\n";
    
    // Vérifier si le fichier existe et le créer si nécessaire
    if (!file_exists($logFile)) {
        $handle = fopen($logFile, 'w');
        fclose($handle);
    }
    
    file_put_contents($logFile, $logEntry, FILE_APPEND);
}

?>
