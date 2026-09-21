<?php
include 'db_connect.php';

$accounts = [
    ['email' => 'alawihaohealth@gmail.com',           'plain' => 'admin123'],
    ['email' => 'mikaelasalazarpimentel09@gmail.com', 'plain' => 'Welcome123'],
    ['email' => 'janner@gmail.com',                   'plain' => 'Welcome123'],
    ['email' => 'kish@gmail.com',                     'plain' => 'Welcome123'],
    ['email' => 'gerry@gmail.com',                    'plain' => 'Welcome123'],
    ['email' => 'riezza@gmail.com',                   'plain' => 'Welcome123'],
];

foreach ($accounts as $acc) {
    $hash = password_hash($acc['plain'], PASSWORD_BCRYPT);
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ? AND password NOT LIKE '\$2y\$%'");
    $stmt->bind_param("ss", $hash, $acc['email']);
    $stmt->execute();
    echo "Updated: " . $acc['email'] . " (affected: " . $stmt->affected_rows . ")<br>";
    $stmt->close();
}

echo "<br><strong>Done. You can now delete this file.</strong>";
echo "<br><br><b>Login credentials:</b><br>";
echo "Super Admin: alawihaohealth@gmail.com / admin123<br>";
echo "Admin: kish@gmail.com / Welcome123<br>";
echo "Admin: riezza@gmail.com / Welcome123<br>";
?>
