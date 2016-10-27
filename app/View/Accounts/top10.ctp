<h1>Top 10</h1>

<?php
echo $this->element('timezoneblock');
?>

<?php
echo $this->Form->create(
	null, 
	array(
		'url' => array('controller' => 'accounts', 'action' => 'top10'), 
		'id' => 'frmTop10'
	)
);
?>
<div style="margin:6px 20px 6px 2px;">
<table>
	<tr>
		<td>
		<div style="float:left;margin:0px 5px 0px 0px;">
		<?php
		echo $this->Form->input('Top10.period',
			array(
				'id' => 'selPeriod',
				'label' => '', 'type' => 'select',
				'options' => $periods,
				'selected' => isset($start)? ($start . ',' . $end) : null,
				'style' => 'width:210px;'
			)
		);
		?>
		</div>
		<div style="float:left;margin:0px 5px 0px 0px;">
		<?php 
		echo $this->Form->input('Top10.selsitenum',
			array(
				'id' => 'selSites',
				'label' => '', 'type' => 'select',
				'options' => array('All sties', 'Only ' . $sites[12] . '&' . $sites[13]),
				'selected' => isset($conds) && $conds['siteids'][0] == 12 && $conds['siteids'][1] == 13 ? 1 : 0,
				'style' => 'width:136px;'
			)
		);
		?>
		</div>
		<div style="float:left;">
		<?php
		echo $this->Form->submit('>>', array('style' => 'width:30px;'));
		?>
		</div>
		</td>
	</tr>
</table>
</div>
<?php
if (!empty($rs)) {
?>
	<table style="font-size:90%;width:100%;">
		<caption style="font-style:italic;">
		The Period (From <?php echo $start; ?> To <?php echo $end; ?>)
		<?php 
		if ($conds['siteids'][0] == 12 && $conds['siteids'][1] == 13) {
			echo sprintf(" [Only for site %s&%s]", $sites[$conds['siteids'][0]], $sites[$conds['siteids'][1]]);
		}
		?>
		</caption>
		<thead>
		<tr>
			<th>Top NO.</th>
			<th>Office</th>
			<?php 
			if (isset($groupby) && $groupby == 0) {
			?>
			<th>Agent</th>
			<?php 
			}
			?>
			<th>Total Sales</th>
		</tr>
		</thead>
		<?php
		$i = 0;
		foreach ($rs as $r) {
			$i++;
		?>
		<tr <?php echo $i <= 3 ? 'style="font-weight:bold;"' : ''; ?>>
			<td align="center"><?php echo $i; ?></td>
			<td align="center"><?php echo $r[0]['sales'] > 0 ? $r['Top10Stats']['officename'] : ''; ?></td>
			<?php 
			if (isset($groupby) && $groupby == 0) {
			?>
			<td align="center"><?php echo $r[0]['sales'] > 0 ? $r['Top10Stats']['username'] : ''; ?></td>
			<?php 
			}
			?>
			<td align="center"><?php echo $r[0]['sales'] > 0 ? $r[0]['sales'] : ''; ?></td>
		</tr>
		<?php
		}
		?>
	</table>
	<div style="display:none">
	<?php echo $this->Form->submit('go', array('id' => 'iptSubmit'));?>
	</div>
<?php
}
echo $this->Form->input('Top10.start', array('type' => 'hidden', 'id' => 'iptStart', 'value' => isset($start) ? $start : 0));
echo $this->Form->input('Top10.end', array('type' => 'hidden', 'id' => 'iptEnd', 'value' => isset($end) ? $end : 0));
echo $this->Form->input('Top10.groupby', array('type' => 'hidden', 'id' => 'iptGroupBy', 'value' => isset($groupby) ? $groupby : 0));
echo $this->Form->end();
?>

<script type="text/javascript">
jQuery("#selPeriod").change(function() {
	__zSetFromTo("selPeriod", "iptStart", "iptEnd");
	var selv = jQuery("#selPeriod").find("option:selected").text();
	if (selv.substring(0, 4) == "[2W]") {
		jQuery("#selSites").hide();
		jQuery("#iptGroupBy").val(1);//group by office
	} else {
		jQuery("#selSites").show();
		jQuery("#iptGroupBy").val(0);//group by agent
	}
	//alert(jQuery("#iptGroupBy").val());//for debug
});
</script>