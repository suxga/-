<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>スケジュールしちゃうあぷり</title>
</head>
<body>
<form method="POST" action="input_process.php">
<p>
  件名：<input type="text" name="subject" size="25">
</p><p>
  日付：<input type="date" name="pdate" size="12">
</p><p>
  時刻：<input type="time" name="ptime" size="10">
</p><p>
  分類：
  <input type="radio" name="cid" value="1">会議
  <input type="radio" name="cid" value="2">外出
  <input type="radio" name="cid" value="3">提出
  <input type="radio" name="cid" value="4">私用
  <input type="radio" name="cid" value="5">その他
</p><p>
  メモ：<input type="text" name="memo" size="50">
</p><p>
  <input type="submit" value="登録">
</p>
</form>
</body>
</html>