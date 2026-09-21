<?php
include 'db_connect.php';

// Check users table structure
$result = $conn->query("DESCRIBE users");

echo "<h3>Users Table Structure:</h3>";
echo "<table border='1'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";

while($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['Field'] . "</td>";
    echo "<td>" . $row['Type'] . "</td>";
    echo "<td>" . $row['Null'] . "</td>";
    echo "<td>" . $row['Key'] . "</td>";
    echo "</tr>";
}
echo "</table>";

// Show sample data
$result2 = $conn->query("SELECT * FROM users LIMIT 1");
echo "<h3>Sample Data:</h3>";
if ($result2->num_rows > 0) {
    echo "<table border='1'>";
    $row = $result2->fetch_assoc();
    foreach($row as $key => $value) {
        echo "<tr><td><strong>$key</strong></td><td>$value</td></tr>";
    }
    echo "</table>";
} else {
    echo "No data in users table.";
}
?>
