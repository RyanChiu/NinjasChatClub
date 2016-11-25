<?php 
/**
 * 
 */
include 'zmysqlConn.class.php';
include 'extrakits.inc.php';

$today = date("Y-m-d");
$lastday = date("Y-m-d", strtotime(date('Y-m-d') . " Sunday"));
if (date("w") == 0) {
	$lastday = date("Y-m-d", strtotime($lastday . " + 6 days"));
} else {
	$lastday = date("Y-m-d", strtotime($lastday . " - 1 days"));
}
$weekend = $lastday;
$weekstart = date("Y-m-d", strtotime($lastday . " - 6 days"));

$curbiweek = __getCurBiweek();
$curbiweekse = explode(",", $curbiweek);
$biweekstart = $curbiweekse[0];
$biweekend = $curbiweekse[1];
if ($today < INTOHALFMONTHLYBIWEEKDAY) {
	$biweekstart0 = date("Y-m-d", strtotime($biweekstart . " - 2 weeks"));
	$biweekend0 = date("Y-m-d", strtotime($biweekstart0 . " + 2 weeks - 1 day"));
} else {
	$se = __getPreBiweek($curbiweek);
	$se = explode(",", $se);
	$biweekstart0 = $se[0];
	$biweekend0 = $se[1];
}

$zconn = new zmysqlConn();
/*
 * flag = 0 ~ till today group by agent
 * flag = 1 ~ weekly group by agent 
 * flag = 2 ~ monthly group by agent
 * flag = 3,4 ~ biweekly group by office (3 means this 2 weeks, 4 means the 2 weeks before)
 */

/*
 * normal top10s
 */
$_sql_ = 
	"SELECT %d, '%s', stats.agentid,  accounts.username, agents.ag1stname, companies.officename, 
		sum(sales_number - chargebacks) as sales 
	FROM stats, accounts, agents, companies   
	WHERE %s
		and stats.agentid = accounts.id and agents.id = stats.agentid 
		and agents.companyid = companies.id 
		AND agentid > 0 
		AND companies.id != 98 
	GROUP BY agentid ORDER BY `sales` desc LIMIT 10";
$sql = sprintf($_sql_, 0, $today, "convert(trxtime, date) >= '2016-08-14' and convert(trxtime, date) <= '$today'");
$rs = mysql_query("delete from top10s where flag = 0", $zconn->dblink)
	or die ("Something wrong with: " . mysql_error() . "\n");
$rs = mysql_query("insert into top10s " . $sql, $zconn->dblink)
	or die ("Something wrong with: " . mysql_error() . "\n");
$sql = sprintf($_sql_, 1, $today, "convert(trxtime, date) <= '$weekend' AND convert(trxtime, date) >= '$weekstart'");
$rs = mysql_query("delete from top10s where flag = 1", $zconn->dblink)
	or die ("Something wrong with: " . mysql_error() . "\n");
$rs = mysql_query("insert into top10s " . $sql, $zconn->dblink)
	or die ("Something wrong with: " . mysql_error() . "\n");
/* //disable the monthly group by agent for now
$sql = sprintf($_sql_, 2, $today, "convert(trxtime, date) <= '$monthend' AND convert(trxtime, date) >= '$monthstart'");
$rs = mysql_query("delete from top10s where flag = 2", $zconn->dblink)
	or die ("Something wrong with: " . mysql_error() . "\n");
$rs = mysql_query("insert into top10s " . $sql, $zconn->dblink)
	or die ("Something wrong with: " . mysql_error() . "\n");
*/
$_sql_ = 
	"select %d, '%s', null, null, null, companies.officename, sum(sales_number - chargebacks) as sales
	from stats, accounts, companies
	where %s
		and stats.companyid = accounts.id and accounts.id = companies.id
		and companyid in (4247, 4570, 4571, 4572, 4573, 4574, 4575, 4576, 4577, 4578)
	group by companyid
	order by sales desc
	limit 10";
$sql = sprintf($_sql_, 3, $today, 
	"convert(trxtime, date) <= '$biweekend' AND convert(trxtime, date) >= '$biweekstart'"
);
mysql_query("delete from top10s where flag = 3", $zconn->dblink)
	or die ("Something wrong with: " . mysql_error() . "\n");
mysql_query("insert into top10s " . $sql, $zconn->dblink)
	or die ("Something wrong with: " . mysql_error() . "\n");
$sql = sprintf($_sql_, 4, $today, 
	"convert(trxtime, date) <= '$biweekend0' AND convert(trxtime, date) >= '$biweekstart0' 
		and officename in (select officename from top10s where flag = 3 and `date` = '$today') "
);
mysql_query("delete from top10s where flag = 4", $zconn->dblink)
	or die ("Something wrong with: " . mysql_error() . "\n");
mysql_query("insert into top10s " . $sql, $zconn->dblink)
	or die ("Something wrong with: " . mysql_error() . "\n");
	

echo "top10s (all & weekly & biweekly) generated.(" . date("Y-m-d H:i:s") . ")\n";

/*
 * top10s for NTCP&SXUP
 */
$_sql_ = 
	"SELECT %d, '%s', s.agentid, a.username, g.ag1stname, c.officename,
		sum(if(typeid = (SELECT id FROM types WHERE siteid = 12 order by id limit 0, 1), sales_number, 0)) 
			+ sum(if(typeid = (SELECT id FROM types WHERE siteid =13 order by id limit 0, 1), sales_number, 0)) 
			+sum(if(typeid = (SELECT id FROM types WHERE siteid = 12 order by id limit 2, 1), sales_number, 0)) 
			+ sum(if(typeid = (SELECT id FROM types WHERE siteid =13 order by id limit 2, 1), sales_number, 0)) 
			as sales_trial,
		sum(if(typeid = (SELECT id FROM types WHERE siteid = 12 order by id limit 1, 1), sales_number, 0)) 
			+ sum(if(typeid = (SELECT id FROM types WHERE siteid =13 order by id limit 1, 1), sales_number, 0)) 
			+ sum(if(typeid = (SELECT id FROM types WHERE siteid = 12 order by id limit 3, 1), sales_number, 0)) 
			+ sum(if(typeid = (SELECT id FROM types WHERE siteid =13 order by id limit 3, 1), sales_number, 0)) 
			as sales_bonus,
		sum(if(typeid = (SELECT id FROM types WHERE siteid = 12 order by id limit 0, 1), sales_number, 0)) 
			+ sum(if(typeid = (SELECT id FROM types WHERE siteid =13 order by id limit 0, 1), sales_number, 0)) 
			+ sum(if(typeid = (SELECT id FROM types WHERE siteid = 12 order by id limit 2, 1), sales_number, 0)) 
			+ sum(if(typeid = (SELECT id FROM types WHERE siteid =13 order by id limit 2, 1), sales_number, 0)) 
			+ sum(if(typeid = (SELECT id FROM types WHERE siteid = 12 order by id limit 1, 1), sales_number, 0)) 
			+ sum(if(typeid = (SELECT id FROM types WHERE siteid =13 order by id limit 1, 1), sales_number, 0)) 
			+ sum(if(typeid = (SELECT id FROM types WHERE siteid = 12 order by id limit 3, 1), sales_number, 0)) 
			+ sum(if(typeid = (SELECT id FROM types WHERE siteid =13 order by id limit 3, 1), sales_number, 0)) 
			as sales
	from stats s, companies c, agents g, accounts a
	where %s and s.siteid in (12, 13) and s.companyid != 98 
		and (s.agentid = g.id and g.id = a.id) and g.companyid = c.id and s.agentid > 0
	group by s.agentid
	order by sales desc 
	limit 10";
$sql = sprintf($_sql_, 0, $today, "convert(trxtime, date) >= '2016-08-14' and convert(trxtime, date) <= '$today'");
$rs = mysql_query("delete from trbo_top10s where flag = 0", $zconn->dblink)
	or die ("Something wrong with: " . mysql_error() . "\n");
$rs = mysql_query("insert into trbo_top10s " . $sql, $zconn->dblink)
	or die ("Something wrong with: " . mysql_error() . "\n");
$sql = sprintf($_sql_, 1, $today, "convert(trxtime, date) <= '$weekend' AND convert(trxtime, date) >= '$weekstart'");
$rs = mysql_query("delete from trbo_top10s where flag = 1", $zconn->dblink)
	or die ("Something wrong with: " . mysql_error() . "\n");
$rs = mysql_query("insert into trbo_top10s " . $sql, $zconn->dblink)
	or die ("Something wrong with: " . mysql_error() . "\n");
echo "new top10s (all, only for SXUP&NTCP) generated.(" . date("Y-m-d H:i:s") . ")\n";
?>
