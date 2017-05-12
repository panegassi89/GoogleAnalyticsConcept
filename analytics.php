<?php

if (isset($_GET['username'])) {
	
	$user = 'root';
	$pass = '';
	$local = '127.0.0.1';
	$db = 'analytics';

	$conn = mysql_connect($local, $user, $pass) or die(mysql_error());
	mysql_select_db($db, $conn) or die(mysql_error());


	$q = "SELECT * FROM hits WHERE username = '".$_GET['username']."' ORDER BY data DESC";
	$result = mysql_query($q);

	$dataServer = date('Y-m-d H:i:s');
	
	if (mysql_num_rows($result) > 0) {
		
		$dataDB = mysql_result($result, 0, 'data');
		
		$ss = explode(':', $dataServer);
		$sb = explode(':', $dataDB);

		if ((int)$ss[2] - (int) $sb[2] > 10) { 
			$q = "INSERT INTO hits VALUES(null, '".$_GET['username']."', 0, 1, 1, '".$dataServer."')"; 
			$insert = mysql_query($q);
		} else {
			$q = "INSERT INTO hits VALUES(null, '".$_GET['username']."', 0, 0, 1, '".$dataServer."')"; 
			$insert = mysql_query($q);
		}
	
	} else {
		
		$q = "INSERT INTO hits VALUES(null, '".$_GET['username']."', 1, 1, 1, '".$dataServer."')"; 
		$insert = mysql_query($q);
		
	}
	

}
?>



<?php

	$u = "SELECT username, SUM(pageviews) as upageview, SUM(session) as usession, SUM(user) as uuser FROM hits WHERE username = '".$_GET['username']."' ORDER BY data DESC";
	$user = mysql_query($u);
	
	if (mysql_num_rows($user) > 0) {
		
?>

Username: <?php echo mysql_result($user, 0, 'username'); ?><br />
User: <?php echo mysql_result($user, 0, 'uuser'); ?><br />
Sessions: <?php echo mysql_result($user, 0, 'usession'); ?><br />
Pageviews:<?php echo mysql_result($user, 0, 'upageview'); ?><br /><br />

<?php } ?>


<?php 
$u = "SELECT SUM(pageviews) as upageview, SUM(session) as usession, SUM(user) as uuser FROM hits ORDER BY data DESC";
$alluser = mysql_query($u);

if (mysql_num_rows($alluser) > 0) {
?>


<hr />

User: <?php echo mysql_result($alluser, 0, 'uuser'); ?><br />
Sessions: <?php echo $psession = mysql_result($alluser, 0, 'usession'); ?><br />
Pageviews:<?php echo $ppageview = mysql_result($alluser, 0, 'upageview'); ?><br />


Pages / Session: <?php echo number_format($ppageview/$psession, 0); ?>

<?php } ?>
