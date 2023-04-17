<?php
$pdo = new PDO ('mysql:host=localhost;port=3306;charset=utf8;dbname=tom', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);