<?php 
/**
 * this is a driver that will insert a daily totals group by office or office&type etc., one by one.
 * must have 2 parameters(day, bywhat<0 by office, 1 by office&type, etc.>) will be taken, 
 * would be like this: 2016-09-01 0 (for a day group by office), or 2016-01-00 0 (for a month group by office).
 */

include 'zmysqlConn.class.php';
include 'extrakits.inc.php';

$zconn = new zmysqlConn();

$day = date("Y-m-d");
$bywhat = 0; 
if (($argc - 1) == 2) {
	$day = $argv[1];
	$bywhat = $argv[2];
} else {
	exit("It must take 2 parameters, please try again.\n");
}

function byoffice($comid, $day, &$dbconn) {
	$sql = "insert into daily_stats (day, companyid, total)
		select '$day', companyid, sum(sales_number)
		from stats
		where CONVERT(trxtime, date) = '$day' and companyid = $comid
		group by companyid
		ON DUPLICATE KEY update total = (
			select sum(sales_number) 
			from stats 
			where CONVERT(trxtime, date) = '$day' and companyid = $comid
		)
	";
	
	mysql_query($sql, $dbconn->dblink)
		or die ("Something wrong with: " . mysql_error());
}

$ymd = explode("-", $day);
$start = $end = $day;
if ($ymd[2] == "00") {
	$start = date("Y-m-d", strtotime($ymd[0] . "-" . $ymd[1] . "-01"));
	$end = date("Y-m-d", strtotime($start . ' + 1 months - 1 days'));
}
$day = $start;
while ($day <= $end) {
	switch ($bywhat) {
		case 0:
			echo "do it group by office.\n";
			$rs = mysql_query('select id from companies', $zconn->dblink)
				or die ("Something wrong with: ". mysql_error());
			while ($r = mysql_fetch_array($rs, MYSQL_NUM)) {
				echo "company id: " . $r[0] . ", day $day\n";
				byoffice($r[0], $day, $zconn);
			}
			break;
		default:
			echo 'did nothing.\n';
			break;
	}
	$day = date("Y-m-d", strtotime($day . " + 1 days"));
}
?>