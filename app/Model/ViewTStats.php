<?php
class ViewTStats extends AppModel {
	var $name = 'ViewTStats';
}

/*

DROP VIEW `view_t_stats`;

CREATE VIEW `view_t_stats`  AS  
select `t`.`trxtime` AS `trxtime`,`t`.`agentid` AS `agentid`,`t`.`siteid` AS `siteid`,`s`.`sitename` AS `sitename`,`t`.`raws` AS `raws`,`t`.`uniques` AS `uniques`,`t`.`chargebacks` AS `chargebacks`,`t`.`signups` AS `signups`,`t`.`frauds` AS `frauds`,`t`.`sales_type1` AS `sales_type1`,`t`.`sales_type2` AS `sales_type2`,`t`.`sales_type3` AS `sales_type3`,`t`.`sales_type4` AS `sales_type4`,`t`.`sales_type5` AS `sales_type5`,`t`.`sales_type6` AS `sales_type6`,`t`.`sales_type7` AS `sales_type7`,`t`.`sales_type8` AS `sales_type8`,`t`.`sales_type9` AS `sales_type9`,`t`.`sales_type10` AS `sales_type10`,`b`.`companyid` AS `companyid`,`c`.`officename` AS `officename`,`a`.`username` AS `username`,`a`.`username4m` AS `username4m`,`b`.`ag1stname` AS `ag1stname`,`b`.`aglastname` AS `aglastname`,((((((((((`t`.`sales_type1` + `t`.`sales_type2`) + `t`.`sales_type3`) + `t`.`sales_type4`) + `t`.`sales_type5`) + `t`.`sales_type6`) + `t`.`sales_type7`) + `t`.`sales_type8`) + `t`.`sales_type9`) + `t`.`sales_type10`) - `t`.`chargebacks`) AS `net`,((((((((((`t`.`sales_type1` * `t`.`sales_type1_payout`) + (`t`.`sales_type2` * `t`.`sales_type2_payout`)) + (`t`.`sales_type3` * `t`.`sales_type3_payout`)) + (`t`.`sales_type4` * `t`.`sales_type4_payout`)) + (`t`.`sales_type5` * `t`.`sales_type5_payout`)) + (`t`.`sales_type6` * `t`.`sales_type6_payout`)) + (`t`.`sales_type7` * `t`.`sales_type7_payout`)) + (`t`.`sales_type8` * `t`.`sales_type8_payout`)) + (`t`.`sales_type9` * `t`.`sales_type9_payout`)) + (`t`.`sales_type10` * `t`.`sales_type10_payout`)) AS `payouts`,((((((((((`t`.`sales_type1` * `t`.`sales_type1_earning`) + (`t`.`sales_type2` * `t`.`sales_type2_earning`)) + (`t`.`sales_type3` * `t`.`sales_type3_earning`)) + (`t`.`sales_type4` * `t`.`sales_type4_earning`)) + (`t`.`sales_type5` * `t`.`sales_type5_earning`)) + (`t`.`sales_type6` * `t`.`sales_type6_earning`)) + (`t`.`sales_type7` * `t`.`sales_type7_earning`)) + (`t`.`sales_type8` * `t`.`sales_type8_earning`)) + (`t`.`sales_type9` * `t`.`sales_type9_earning`)) + (`t`.`sales_type10` * `t`.`sales_type10_earning`)) AS `earnings`,
(t.sales_type1 + t.sales_type3) as sales_type1_3,
(t.sales_type2 + t.sales_type4) as sales_type2_4,
(t.sales_type1 * t.sales_type1_payout + t.sales_type3 * t.sales_type3_payout) as sales_type1_3_payout,
(t.sales_type2 * t.sales_type2_payout + t.sales_type4 * t.sales_type4_payout) as sales_type2_4_payout,
(t.sales_type1 * t.sales_type1_earning + t.sales_type3 * t.sales_type3_earning) as sales_type1_3_earning,
(t.sales_type2 * t.sales_type2_earning + t.sales_type4 * t.sales_type4_earning) as sales_type2_4_earning,
`t`.`run_id` AS `run_id`,`t`.`group_by` AS `group_by` 
from ((((`t_stats` `t` join `accounts` `a`) join `agents` `b`) join `companies` `c`) join `sites` `s`) where ((`t`.`agentid` = `a`.`id`) and (`a`.`id` = `b`.`id`) and (`b`.`companyid` = `c`.`id`) and (`t`.`siteid` = `s`.`id`)) ;
 */
?>