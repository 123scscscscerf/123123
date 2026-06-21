<?php
require_once __DIR__ . '/../config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('../registration.php');
$name = trim($_POST['name'] ?? ''); $email = trim($_POST['email'] ?? ''); $password = (string)($_POST['password'] ?? '');
if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 4) { $_SESSION['flash']='Заполните поля корректно.'; redirect('../registration.php'); }
$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?'); $stmt->execute([$email]);
if ($stmt->fetch()) { $_SESSION['flash']='Пользователь уже существует.'; redirect('../registration.php'); }
$stmt = $pdo->prepare('INSERT INTO users (name,email,password) VALUES (?,?,?)');
$stmt->execute([$name,$email,password_hash($password,PASSWORD_DEFAULT)]);
$_SESSION['user']=['id'=>(int)$pdo->lastInsertId(),'name'=>$name,'email'=>$email];
redirect('../profile.php');
