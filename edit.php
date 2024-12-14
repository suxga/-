<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // データ更新処理
    $db = new PDO('mysql:host=localhost;dbname=basic;charset=utf8', 'myusr', '12345');
    $stmt = $db->prepare('UPDATE schedule SET subject = ?, pdate = ?, ptime = ?, memo = ? WHERE pid = ?');
    $stmt->execute([
        $_POST['subject'], 
        $_POST['pdate'], 
        $_POST['ptime'], 
        $_POST['memo'], 
        $_POST['pid']
    ]);
    header('Location: list.php'); // 編集後に一覧ページに戻る
    exit;
} else {
    // 編集用データ取得
    $db = new PDO('mysql:host=localhost;dbname=basic;charset=utf8', 'myusr', '12345');
    $stmt = $db->prepare('SELECT * FROM schedule WHERE pid = ?');
    $stmt->execute([$_GET['pid']]);
    $row = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>スケジュール編集なのだ</title>
</head>
<body>
<h1>スケジュール編集なのだ</h1>
<form method="POST">
    <input type="hidden" name="pid" value="<?php echo htmlspecialchars($row['pid'], ENT_QUOTES, 'UTF-8'); ?>">
    <label>件名: <input type="text" name="subject" value="<?php echo htmlspecialchars($row['subject'], ENT_QUOTES, 'UTF-8'); ?>"></label><br>
    <label>日付: <input type="date" name="pdate" value="<?php echo htmlspecialchars($row['pdate'], ENT_QUOTES, 'UTF-8'); ?>"></label><br>
    <label>時刻: <input type="time" name="ptime" value="<?php echo htmlspecialchars($row['ptime'], ENT_QUOTES, 'UTF-8'); ?>"></label><br>
    <label>メモ: <textarea name="memo"><?php echo htmlspecialchars($row['memo'], ENT_QUOTES, 'UTF-8'); ?></textarea></label><br>
    <input type="submit" value="更新">
</form>
<a href="list.php">戻る</a>
</body>
</html>
