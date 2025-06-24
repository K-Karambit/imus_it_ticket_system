<?php
// === CONFIGURATION ===
$host = 'localhost';
$dbname = 'db_it_ticket_system_dev';
$username = 'root';
$password = '';
$backupDir = 'backups'; // Make sure this folder is writable
$timestamp = date('Ymd_His');
$backupFile = "{$backupDir}/{$dbname}_backup_{$timestamp}.sql";

// === CREATE BACKUP FOLDER IF NOT EXISTS ===
if (!file_exists($backupDir)) {
    mkdir($backupDir, 0777, true);
}

// === EXECUTE BACKUP ===
$command = "mysqldump --user={$username} --password={$password} --host={$host} {$dbname} > {$backupFile}";

exec($command, $output, $result);

if ($result === 0) {
    echo "✅ Backup successful! File saved as: {$backupFile}";
} else {
    echo "❌ Backup failed. Please check your credentials and permissions.";
}
