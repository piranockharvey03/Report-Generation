<?php
$password = 'harvey123'; // replace with actual password
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Hashed password: " . $hash;
