<?php    
$db = new PDO('mysql:host=localhost;dbname=basic;charset=utf8', 'myusr', '12345');
$stt = $db->prepare('INSERT INTO schedule(uid, subject, pdate, ptime, cid, memo) VALUES(:uid, :subject, :pdate, :ptime, :cid, :memo)');
$user = 'yyamada';
$stt->bindParam(':uid', $user);
$stt->bindParam(':subject', $_POST['subject']);
$stt->bindParam(':pdate', $_POST['pdate']);
$stt->bindParam(':ptime', $_POST['ptime']);
$stt->bindParam(':cid', $_POST['cid']);
$stt->bindParam(':memo',$_POST['memo']);
$stt->execute();
header('Location: http://localhost/basic/sche/list.php');