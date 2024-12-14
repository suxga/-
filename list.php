<?php
// ベーシック認証の設定。一度キャンセルボタン押さないとキャッシュ消えない。認証方法の変更でUX向上可能。
$valid_users = [
    'tyamamoto' => '123', // 許可するユーザー名とパスワード
    'ksugano' => '456'
];

// ログアウト処理だが、上手く作動せず。本来なら未承認メッセを送信しURLから?logout=1を除くことで完全にログアウトしたい
if (isset($_GET['logout'])) {
    // ログアウト後にキャッシュを完全に無効化
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
header('Location: /basic/sche/list.php'); // クリーンなURL
exit;
 
}



// 認証処理。非認証ユーザorパスワード、またはそのペアかを確認し、該当すればtrue(->echo行)へ進む
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
<p>未来を決めようぜ、<?php echo htmlspecialchars($_SERVER['PHP_AUTH_USER'], ENT_QUOTES, 'UTF-8'); ?>様。</p>
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

<script>
// ランダム言葉を取得する関数（複数対応）
async function fetchRandomWords() {
    const endpoints = ['random_word.php', 'random_word2.php', 'random_word3.php'];
    const ids = ['random-word', 'random-word2', 'random-word3'];

    try {
        for (let i = 0; i < endpoints.length; i++) {
            const response = await fetch(endpoints[i]); // 各APIにリクエスト
            if (!response.ok) {
                throw new Error('HTTPエラー: ' + response.status);
            }
            const data = await response.json(); // JSONデータを取得
            document.getElementById(ids[i]).value = data.word; // 対応するテキストボックスに表示
        }
    } catch (error) {
        console.error('エラーが発生しました:', error);
        alert('言葉を取得できませんでした。');
    }
}
</script>

   <!-- ランダム言葉機能 -->
<h3>あなたにインスピレーションを与える、もしくは時間を奪うボタン</h3>
<button onclick="fetchRandomWords()">押しちゃう</button>
<p><input type="text" id="random-word" readonly>が<input type="text" id="random-word2" readonly><input type="text" id="random-word3" readonly></p>
</body>
</html>