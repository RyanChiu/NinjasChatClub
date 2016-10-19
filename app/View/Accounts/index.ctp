<?php
//echo print_r($rs, true);
$userinfo = $this->Session->read('Auth.User.Account');
?>
<div>
<div style="float:left;"><h1>Home</h1></div>
<div style="float:left;margin-left:10px;">
<?php
if ($userinfo['role'] == 0) {
	echo $this->Html->link(
		/*$this->Html->image('archive.jpg',
			array('border' => 0, 'width' => 25, 'height' => 25, 'alt' => 'Archive this bulletin.')
		),// . */'<font size="1">(Archive)</font>',
		array('controller' => 'accounts', 'action' => 'index', 'id' => -1),
		array('escape'=> false),
		'Are you sure you wish to archive this bulletin?'
	);
}
?>
</div>
<div style="float:left;margin:0px 0px 5px 60px;;font-size:10px;">
<?php
if (!empty($archdata)) {
	$i = 0;
	echo '| ';
	foreach ($archdata as $arch) {
		echo $this->Html->link(
			$arch['Bulletin']['archdate'],
			array('controller' => 'accounts', 'action' => 'index', 'id' => $arch['Bulletin']['id']),
			array('escape' => false),
			false
		);
		echo ' | ';
		$i++;
		if ($i > 1) break;
	}
	$more = '';
	if ($i <= count($archdata) - 1) {
		$more = '<a href="#" id="linkMore">(' . (count($archdata) - $i) . ') more...</a>';
	}
	echo $more;
	//echo $this->Html->image('archive_tip.jpg', array('border' => 0, 'width' => 70, 'height' => 23));
?>
	<div id="divMore" style="margin:3px 2px 3px 0px;display:none;">
<?php
	/*list all the rest archives*/
	$k = 0; $step = 4;
	for ($j = $i; $j < count($archdata); $j++) {
		echo ($k % $step == 0) ? ' | ' : '';
		echo $this->Html->link(
			$archdata[$j]['Bulletin']['archdate'],
			array('controller' => 'accounts', 'action' => 'index', 'id' => $archdata[$j]['Bulletin']['id']),
			array('escape' => false),
			false
		);
		$k++;
		echo ($k % $step == 0 && $k != count($archdata)) ? ' | <br/>' : ' | ';
	}
?>
	</div>
<?php
}
?>
	<script type="text/javascript">
	jQuery("#linkMore").click(
		function() {
			var txtMore = jQuery(this).text();
			if (txtMore.indexOf("more") != -1) {
				txtMore = txtMore.substring(0, txtMore.indexOf(")") + 1) + " less..."; 
			} else {
				txtMore = txtMore.substring(0, txtMore.indexOf(")") + 1) + " more...";
			}
			jQuery(this).text(txtMore);
			jQuery("#divMore").toggle("normal");
		}
	);
	</script>
</div>
</div>

<br/>

<?php
echo $this->element('timezoneblock');
?>

<br/>
<table style="width:100%">
<!-- <tr class="odd"> -->
<tr>
	<td>
	<div style="margin:5px 20px 5px 20px;">
	<?php
	//echo $this->Html->image('iconTopnotes.png');
	//echo '<b><font size="3">News</font></b>';
	echo /*'<br/>' . */$topnotes;
	?>
	<div style="height:6px"></div>
	</div>
	</td>
</tr>
<?php
if (!empty($notes)) {
?>
<tr>
	<td>
	<div style="margin:5px 20px 5px 20px;">
	<?php
	//echo $this->Html->image('iconAttention.png');
	echo '<br/>' . $notes . '<br/><div style="height:6px"></div>';
	?>
	</div>
	</td>
</tr>
<?php 
}
?>
</table>

<!-- show the top selling list -->
<br/>
<table style="width:100%">
<caption><font size="5" color="#bb2222">Best sellers</font></caption>
<tr>
	<td colspan=2>
	<div style="float: right;">
		<?php
		if ($userinfo['role'] == 0) {
			echo $this->Html->link('<font size="1">Choose another pay period...</font>',
				array('controller' => 'accounts', 'action' => 'top10'),
				array('escape' => false),
				false
			);
		}
		?>
		</div>
	</td>
</tr>
<tr>
	<td width="50%">
		<table style="width:100%" style="font-size:90%;">
		<caption style="font-style:italic;">
		<font style="font-weight:bold;color:red;">WEEKLY TOP 10 AGENTS</font> (From <?php echo $weekstart; ?> To <?php echo $weekend; ?>)		
		</caption>
		<thead>
		<tr>
			<th>Rank</th>
			<th>Office</th>
			<th>Agent</th>
			<th>Sales</th>
		</tr>
		</thead>
		<?php
		$i = 0;
		foreach ($weekrs as $r) {
			$i++;
		?>
		<tr>
			<td align="center"><?php echo $i; ?></td>
			<td align="center"><?php echo $r['Top10']['sales'] > 0 ? $r['Top10']['officename'] : $r['Top10']['officename']; ?></td>
			<td align="center">
				<font style="font-size: 9pt;"><?php echo $r['Top10']['username']; ?></font>
				<font style="font-size: 10pt;"> (<?php echo $r['Top10']['ag1stname'] ?>)</font>
			</td>
			<td align="center"><?php echo $r['Top10']['sales'] > 0 ? $r['Top10']['sales'] : '0'; ?></td>
		</tr>
		<?php
		}
		?>
		</table>
	</td>
	<td>
		<table style="width:100%" style="font-size:90%;">
		<caption style="font-style:italic;">
		<font style="font-weight:bold;color:#0066dd;">ALL TIME TOP 10 AGENTS</font> (Start from 2016-08-14)
		</caption>
		<thead>
		<tr>
			<th>Rank</th>
			<th>Office</th>
			<th>Agent</th>
			<th>Sales</th>
		</tr>
		</thead>
		<?php
		$i = 0;
		foreach ($rs as $r) {
			$i++;
		?>
		<tr>
			<td align="center"><?php echo $i; ?></td>
			<td align="center"><?php echo $r['Top10']['sales'] > 0 ? $r['Top10']['officename'] : $r['Top10']['officename']; ?></td>
			<td align="center">
				<font style="font-size: 9pt;"><?php echo $r['Top10']['username']; ?></font>
				<font style="font-size: 10pt;"> (<?php echo $r['Top10']['ag1stname'] ?>)</font>
			</td>
			<td align="center"><?php echo $r['Top10']['sales'] > 0 ? $r['Top10']['sales'] : '0'; ?></td>
		</tr>
		<?php
		}
		?>
		</table>
	</td>
</tr>
<?php 
if ($userinfo['role'] != -1) {
?>
<tr>
	<td colspan=2>
		<table style="border:0;width:100%;">
		<tr><td style="width:65%">
		<table style="width:100%">
		<caption style="font-style:italic;">
		<font style="font-weight:bold;color:yellow;">Weekly NTCP + SXUP</font> (From <?php echo $weekstart; ?> To <?php echo $weekend; ?>)
		</caption>
		<thead>
		<tr>
			<th>Rank</th>
			<th>Office</th>
			<th>Agent</th>
			<th>Tr_Sale<br/>All</th>
			<th>Bonus<br/>All</th>
			<th>Tr+Bonus<br/>All</th>
			<th>Bonus Ratio</th>
		</tr>
		</thead>
		<?php
		$i = 0;
		foreach ($trboweekrs as $r) {
			$i++;
		?>
		<tr>
			<td align="center"><?php echo $i; ?></td>
			<td align="center"><?php echo $r['TrboTop10']['officename']; ?></td>
			<td align="center"><?php echo $r['TrboTop10']['username'] . ' (' . $r['TrboTop10']['ag1stname'] . ')'; ?></td>
			<td align="center"><?php echo $r['TrboTop10']['sales_trial']; ?></td>
			<td align="center" style="color:#aa2200;"><?php echo $r['TrboTop10']['sales_bonus']; ?></td>
			<td align="center"><?php echo $r['TrboTop10']['sales']; ?></td>
			<td align="center" style="color:#aa2200;">
			<?php echo sprintf("%.2f", ($r['TrboTop10']['sales_bonus'] / $r['TrboTop10']['sales_trial'] * 100)); ?>%
			</td>
		</tr>
		<?php
		}
		?>
		</table></td><td style="width:35%;">
		<table style="width:100%">
		<caption style="font-style:italic;">
			<font style="font-weight:bold;color:#00ff33;">BIWEEKLY OFFICE PROGRESS</font> <br/>(From <?php echo $biweekstart; ?> To <?php echo $biweekend; ?>) <br/><font style="font-size:12px;color:#00ff33">(Sales compared to prior 2 weeks for %)</font>
		</caption>
		<thead>
		<tr>
			<th>Rank</th>
			<th>Office</th>
			<th>Sales</th>
			<th>Growth</th>
		</tr>
		</thead>
		<?php 
		$i = 0;
		foreach ($biweekrs as $r) {
			$i++;
		?>
		<tr>
			<td align="center"><?php echo $i; ?></td>
			<td align="center"><?php echo $r['Top10']['officename']; ?></td>
			<td align="center"><?php echo $r['Top10']['sales']; ?></td>
			<td align="center">
			<?php
			$sales0 = 0;
			foreach ($biweekrs0 as $r0) {
				if ($r0['Top10']['officename'] == $r['Top10']['officename']) {
					$sales0 = $r0['Top10']['sales'];
					break;
				}
			}
			echo sprintf("%.2f", ($r['Top10']['sales'] - $sales0) / $sales0 * 100) . "%";
			?>
			</td>
		</tr>
		<?php 
		}
		?>
		</table>
		</td></tr>
		</table>
	</td>
</tr>
<?php 
}
?>
</table>

<!-- ## for accounts overview
<table style="width:100%">
<caption>All Accounts Overview</caption>
<thead>
<tr>
	<th width="20%"><b></b></th>
	<th width="40%"><b>Office</b></th>
	<th width="40%"><b>Agent</b></th>
</tr>
</thead>
<tr>
	<td>Onlines</td>
	<td><?php echo $totals['cponlines']; ?></td>
	<td><?php echo $totals['agonlines']; ?></td>
</tr>
<tr class="odd">
	<td>Offlines</td>
	<td><?php echo $totals['cpofflines']; ?></td>
	<td><?php echo $totals['agofflines']; ?></td>
</tr>
<tr>
	<td>Activated</td>
	<td><?php echo $totals['cpacts']; ?></td>
	<td><?php echo $totals['agacts']; ?></td>
</tr>
<tr class="odd">
	<td>Suspended</td>
	<td><?php echo $totals['cpsusps']; ?></td>
	<td><?php echo $totals['agsusps']; ?></td>
</tr>
</table>

<table style="width:100%">
<caption>Online Accounts Overview</caption>
<thead>
<tr>
	<th width="15%"><b>Online Username</b></th>
	<th width="25%"><b>Office Name</b></th>
	<th width="25%"><b>Contact Name</b></th>
	<th width="20%"><b>Registered</b></th>
</tr>
</thead>
<?php
$i = 0;
foreach ($cprs as $cpr):
?>
<tr <?php echo $i % 2 == 0 ? '' : 'class="odd"'; ?>>
	<td>
	<?php
	echo $this->Html->image('iconCompany_small.png', array('width' => 16, 'height' => 16, 'border' => 0, 'title' => 'It\'s a company'));
	echo $cpr['ViewCompany']['username'];
	?>
	</td>
	<td><?php echo $cpr['ViewCompany']['officename']; ?></td>
	<td><?php echo $cpr['ViewCompany']['contactname']; ?></td>
	<td><?php echo $cpr['ViewCompany']['regtime']; ?></td>
</tr>
<?php
	$i++;
endforeach;
?>
<?php
$i = 0;
foreach ($agrs as $agr):
?>
<tr <?php echo $i % 2 == 0 ? '' : 'class="odd"'; ?>>
	<td>
	<?php
	echo $this->Html->image('iconAgent_small.png', array('width' => 16, 'height' => 16, 'border' => 0, 'title' => 'It\'s an agent'));
	echo $agr['ViewAgent']['username'];
	?>
	</td>
	<td><?php echo $agr['ViewAgent']['officename']; ?></td>
	<td><?php echo $agr['ViewAgent']['name']; ?></td>
	<td><?php echo $agr['ViewAgent']['regtime']; ?></td>
</tr>
<?php
	$i++;
endforeach;
?>
</table>
-->