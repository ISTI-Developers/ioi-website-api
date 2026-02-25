<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/config/db.php'; // your existing db.php

// Choose DEV or PROD
$config = getDbConfig('DEV');

// Connect to database
$conn = mysqli_connect(
    $config['host'],
    $config['username'],
    $config['password'],
    $config['database']
);

if (!$conn) {
    die("DB CONNECTION FAILED: " . mysqli_connect_error());
}

// Query the ioi_team_members table
$result = mysqli_query($conn, "SELECT * FROM ioi_team_members");

if (!$result) {
    die("QUERY FAILED: " . mysqli_error($conn));
}

// Display results
echo "<h3>Team Members Table Test</h3>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>team_id</th><th>employee_id</th><th>first_name</th><th>last_name</th><th>position</th><th>quote</th><th>image</th></tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$row['team_id']}</td>
        <td>{$row['employee_id']}</td>
        <td>{$row['first_name']}</td>
        <td>{$row['last_name']}</td>
        <td>{$row['position']}</td>
        <td>{$row['quote']}</td>
        <td>{$row['image']}</td>
    </tr>";
}

echo "</table>";
