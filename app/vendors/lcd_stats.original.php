<?php
include 'zmysqlConn.class.php';
include 'extrakits.inc.php';

if (($argc - 1) != 1) {//if there is 1 parameter and it must mean a date like '2010-04-01,12:34:56'
	exit("Only 1 parameter needed like '2010-05-01,12:34:56'.\n");
}

/*
 * the following line will make the whole script exit if date string format is wrong
 */
$date_l = $argv[1];
$dates = explode(",", $date_l);
$date = $dates[0];

/*get the abbreviation of the site*/
$abbr = __stats_get_abbr($argv[0]);

/*find out the typeids and siteid from db by "xxxc" which is the abbreviation of the site*/
$typeids = array();
$siteid = null;
$zconn = new zmysqlConn;
__stats_get_types_site($typeids, $siteid, $abbr, $zconn->dblink);
if (empty($siteid)) {
	exit(sprintf("The site with abbreviation \"%s\" does not exist.\n", $abbr));
}
if (count($typeids) != 1) {
	exit(sprintf("The site with abbreviation \"%s\" should have 1 type at least.\n", $abbr));
}
/*get all the campaign mappings of the site*/
$sql = sprintf(
	"select a.id AS id,a.siteid AS siteid,a.agentid AS agentid, d.companyid As companyid, 
		a.campaignid AS campaignid,a.flag AS flag,b.hostname AS hostname,b.abbr AS abbr,
		b.sitename AS sitename,c.username AS username 
	from agent_site_mappings a, sites b, accounts c, agents d, companies e
	where ((a.siteid = b.id) and (a.agentid = c.id) and (a.agentid = d.id) and (d.companyid = e.id)) 
		and siteid = %d", 
	$siteid
);
$rs = mysql_query($sql, $zconn->dblink)
	or die ("Something wrong with: " . mysql_error());
$agents = array();
while ($row = mysql_fetch_assoc($rs)) {
	$agents += array($row['campaignid'] => array('agentid' => $row['agentid'], 'companyid' => $row['companyid']));
}
if (empty($agents)) {
	exit(sprintf("The site with abbreviation \"%s\" does not have any campaign ids asigned for agents.\n", $abbr));
}

/*
 * start of the block that given by loadedcash.com
 */
/*
$aid = 'YOUR LOADEDCASH AFFILIATE ID HERE';
$username = 'YOUR LOADEDCASH USERNAME HERE';
-$password = 'YOUR LOADEDCASH PASSWORD HERE';
$apiToken = 'YOUR LOADEDCASH API TOKEN FOR ramelita HERE';
*/
$aid = '54330';
$username = 'ramelita';
//-$password = 'PDD54321A';
//$apiToken = 'aRpUdhqNWfPUtB60fYcIQWavJ8z3d6g';
$apiToken = 'oxnT7VrHzuAcuhBnUpt4YOUzPiHafSR3';

$key_d_t = gmdate("Y-m-d H:i:s"); // Greenwich Mean Date Time
//$key = md5($username . $password . $key_d_t);
$token = md5($username . $apiToken . $key_d_t);

$start_date = $date;//'2011-02-13';
$end_date = $date;//'2011-02-15';

$url = //'http://www.loadedcash.com/api.php?response_type=xml&json={"key":"' .
	'http://www.loadedcash.com/api.php?response_type=xml&json={"token":"' .
	//$key . '","key_d_t":"' . urlencode($key_d_t) .
	$token . '","key_d_t":"' . urlencode($key_d_t) .
	'","c":"affiliateStats","a":"trafficStats","params":{"aid":"' . $aid .
	'","start_date":"' . $start_date . '","end_date":"' . $end_date . '"}}';
/*
 * end of the block that given by loadedcash.com
 */
//echo "\n$url\n$date_l\n";//debug

/*
 * the following 3 lines are given by loadedcash.com
 */
//$response = file_get_contents($url);
//var_dump($response);
//$xml = simplexml_load_string($response);
//exit();//for debug

/*
 * and we change and optimize the above 3 lines as the following block goes
 */
$retimes = 0;
$toptimes = 2;
$response = file_get_contents($url);
while ($response === false) {
	$retimes++;
	sleep(35);
	$response = file_get_contents($url);
	if ($retimes == $toptimes) break;
}
if ($response === false) {
	$mailinfo = 
		__phpmail("agents.maintainer@gmail.com",
			"LCD (on NCC) STATS GETTING ERROR, REPORT WITH DATE: " . date('Y-m-d H:i:s') . "(retried " . $retimes . " times)",
			"<b>FROM WEB02</b><br><b>--ERROR REPORT</b><br>"
		);
	exit(sprintf("Failed to read stats data.(%s)(%d times)\n", $mailinfo, $retimes));
}
//echo "var_dump\n";//for debug
//var_dump($response);//for debug
//echo "var_dump\n";//for debug
$xml = simplexml_load_string($response);

if ($xml === false) {
	exit(sprintf("\nFailed to parse stats data.\n"));
}

$i = $j = $m = $f = 0;
foreach ($xml->children() as $item) {
	$attr = $item->attributes();
	
	/*
	echo 'node id ' . $attr['id'] . " => "
		. "date: " . $item->date 
		. ", campaign_label: " . $item->campaign_label //which is actually the campaign id
		. ", campaign_name: " . $item->campaign_name //which is only the alias name for campaign id
		. ", uniques: " . $item->uniques
		. ", frees: " . $item->frees
		. ", signups: " . $item->signups
		. ", reversals: " . $item->reversals
		. "\nfor debug\n";
	continue;//for debug
	*/
	
	/**
	 * slice the campaign id from $item->campaign_label
	 */
	$campaignlabel = "" . $item->campaign_label;
	$prefix = "a" . $aid . "_";
	$prefix_x = $prefix . "-";
	if (empty($campaignlabel)) continue;
	if (!(strpos($campaignlabel, $prefix_x) === false)) {
		continue;
	}
	if (strpos($campaignlabel, $prefix) === false) {
		$f++;
		continue;
	}
	$campaignid = substr($campaignlabel, strlen($prefix));
	//echo $campaignid . "\n"; continue; //for debug
	
	if (in_array($campaignid, array_keys($agents))) {
		//echo $campaignid . "," . $agents[$campaignid] . ";\n"; continue;//for debug
		/*
		 * try to put stats data into db
		 * 0.see if there is any frauds data except 0 or null, if there is, remember it and save it back in step 2
		 * 1.delete the data already exist
		 * 2.insert the new data
		 */
		$frauds = 0;
		$conditions = sprintf('convert(trxtime, date) = "%s" and siteid = %d'
			. ' and typeid = %d and agentid = %d and companyid = %d and campaignid = "%s"',
			$date, $siteid, $typeids[0], $agents[$campaignid]['agentid'], $agents[$campaignid]['companyid'], $campaignid);
		$sql = 'select * from stats where ' . $conditions;
		$result = mysql_query($sql, $zconn->dblink)
			or die ("Something wrong with: " . mysql_error());
		if (mysql_num_rows($result) != 0) {
			if (mysql_num_rows($result) != 1) {
				echo ("It should be only 1 row data by day.(" . mysql_num_rows($result) . ")\n");
			}
			$row = mysql_fetch_assoc($result);
			$frauds = empty($row['frauds']) ? 0 : $row['frauds'];
			
			$sql = 'delete from stats where ' . $conditions;
			mysql_query($sql, $zconn->dblink)
				or die ("Something wrong with: " . mysql_error());
			$_m = $m;
			$m += mysql_affected_rows();
			if (($m - $_m) != 1) {
				echo (($m - $_m) . " row(s) deleted!\n");
			}
		}
		
		$sql = sprintf(
			'insert into stats'
			. ' (agentid, companyid, campaignid, siteid, typeid, raws, uniques, chargebacks, signups, frauds, sales_number, trxtime)'
			. ' values (%d, %d, "%s", %d, %d, 0, %d, %d, %d, %d, %d, "%s")',
			$agents[$campaignid]['agentid'], $agents[$campaignid]['companyid'], $campaignid, $siteid, $typeids[0],
			$item->uniques, $item->reversals, $item->frees, $frauds, $item->signups,
			$date
		);
		//echo $sql . "\n"; continue;//for debug
		mysql_query($sql, $zconn->dblink)
			or die ("Something wrong with: " . mysql_error());
		$_j = $j;
		$j += mysql_affected_rows();
		if (($j - $_j) != 1) {
			echo (($j - $_j) . " row(s) inserted!\n");
		}
		$i++;
	}
}
if ($i == 0) {
	echo "No stats data exist by now.\n";
}
echo $m . " row(s) deleted...$f...\n";
echo $j . "(/" . $i . ") row(s) inserted.\n";
echo "retried " . $retimes . " time(s).\n";
echo "Just got the stats data from the remote server at '" . $date_l . "'.\n";
?>
