<?php
require_once('config.php');
try {
  $pdo = new PDO(DSN, DB_USER, DB_PASS);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->exec("create table if not exists userDeta(
      id int not null auto_increment primary key,
      email varchar(255) unique,
      password varchar(255) ,
      created timestamp not null default current_timestamp
    )");
} catch (Exception $e) {
  echo $e->getMessage() . PHP_EOL;
}

if (!$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
  echo '入力された値が不正です。';
  return false;
}

if (preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i', $_POST['password'])) {
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
} else {
  echo 'パスワードは半角英数字をそれぞれ1文字以上含んだ8文字以上で設定してください。';
  return false;
}

try {
  $stmt = $pdo->prepare("insert into userDeta(email, password) value(?, ?)");
  $stmt->execute([$email, $password]);
  echo '登録完了';
} catch (\Exception $e) {
  echo '登録済みのメールアドレスです。';
}