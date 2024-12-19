<?php
$db = new PDO('mysql:host=localhost;dbname=basic;charset=utf8', 'myusr', '12345');
$stt = $db->prepare('DELETE FROM schedule WHERE pid=:pid');
$stt->bindParam(':pid', $_POST['pid']);
$stt->execute();
header('Location: http://localhost/basic/sche/list.php');