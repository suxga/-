<?php
// ベーシック認証の設定
$valid_users = [
    'tyamamoto' => '123',
    'ksugano' => '456'
];

// ログアウト処理
if (isset($_GET['logout'])) {
    // 認証情報をクリアするために無効な認証を要求
    header('WWW-Authenticate: Basic realm="Schedule Manager"');
    header('HTTP/1.0 401 Unauthorized');
    
    // クエリを削除したクリーンなURLにリダイレクト
    echo "<script>
        const cleanUrl = window.location.origin + window.location.pathname;
        window.location.href = cleanUrl;
    </script>";
    exit;
}

// 認証処理
if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) ||
    !array_key_exists($_SERVER['PHP_AUTH_USER'], $valid_users) ||
    $valid_users[$_SERVER['PHP_AUTH_USER']] !== $valid_users[$_SERVER['PHP_AUTH_USER']]) {
    // 認証ヘッダーを送信して再試行を要求
    header('WWW-Authenticate: Basic realm="Schedule Manager"');
    header('HTTP/1.0 401 Unauthorized');
    echo "このページを見るには認証が必要です。";
    exit;
}

// 認証成功後のスケジュール表示処理
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>スケジュール管理アプリ</title>
</head>
<body>
<p>ようこそ、<?php echo htmlspecialchars($_SERVER['PHP_AUTH_USER'], ENT_QUOTES, 'UTF-8'); ?>さん。</p>
<table border="1">
<tr><th>件名</th><th>分類</th><th>日付</th><th>時刻</th><th>メモ</th><th>削除</th></tr>
<?php    
$db = new PDO('mysql:host=localhost;dbname=basic;charset=utf8', 'myusr', '12345');
$stt = $db->query('SELECT s.pid, s.subject, s.pdate, s.ptime, c.cname, s.memo FROM schedule AS s INNER JOIN category AS c ON s.cid = c.cid ORDER BY s.pdate, s.ptime');
while ($row = $stt->fetch()) {
    print('<tr>');
    print('<td><a href="edit.php?pid='.htmlspecialchars($row['pid'], ENT_QUOTES, 'UTF-8').'">'.htmlspecialchars($row['subject'], ENT_QUOTES, 'UTF-8').'</a></td>');
    print('<td>'.htmlspecialchars($row['cname'], ENT_QUOTES, 'UTF-8').'</td>');
    print('<td>'.htmlspecialchars($row['pdate'], ENT_QUOTES, 'UTF-8').'</td>');
    print('<td>'.htmlspecialchars($row['ptime'], ENT_QUOTES, 'UTF-8').'</td>');
    print('<td>'.htmlspecialchars($row['memo'], ENT_QUOTES, 'UTF-8').'</td>');
    print('<td><form method="POST" action="delete.php">
            <input type="hidden" name="pid" value="'.htmlspecialchars($row['pid'], ENT_QUOTES, 'UTF-8').'">
            <input type="submit" value="削除"></form></td>');
    print('</tr>');
}
?>
</table>
<a href="input.php">新規登録</a>
<form method="GET">
    <button type="submit" name="logout" value="1">ログアウト</button>
</form>
</body>
</html>
