<?php
// データベース接続設定
$db = new PDO('mysql:host=localhost;dbname=basic;charset=utf8', 'myusr', '12345');

// ランダムに1件取得するクエリ
$query = $db->query('SELECT word FROM words2 ORDER BY RAND() LIMIT 1');
$result = $query->fetch(PDO::FETCH_ASSOC);

// JSON形式で返す
header('Content-Type: application/json');
echo json_encode($result);
exit;
?>
