<?php
require_once __DIR__ . '/../config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('../login.php');
$email = trim($_POST['email'] ?? ''); $password = (string)($_POST['password'] ?? '');
$stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?'); $stmt->execute([$email]); $user = $stmt->fetch();
if (!$user || !password_verify($password, $user['password'])) { $_SESSION['flash']='Неверный email или пароль.'; redirect('../login.php'); }
$_SESSION['user']=['id'=>(int)$user['id'],'name'=>$user['name'],'email'=>$user['email'],'phone'=>$user['phone'],'address'=>$user['address']];
redirect('../profile.php');
