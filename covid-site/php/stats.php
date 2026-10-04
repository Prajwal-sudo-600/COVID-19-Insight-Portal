<?php
require_once 'db.php';

try {
    $stmt = $pdo->query("SELECT SUM(total_cases) as total_cases, SUM(total_deaths) as total_deaths, SUM(total_vaccinations) as vaccinations FROM covid_data");
    $row = $stmt->fetch();
    echo json_encode([
        'total_cases' => (int)$row['total_cases'],
        'total_deaths' => (int)$row['total_deaths'],
        'vaccinations' => (int)$row['vaccinations']
    ]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Could not fetch data']);
}
?>
