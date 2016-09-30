<?php
$userinfo = $this->Session->read('Auth.User.Account');
echo $this->element('timezoneblock');
?>

<table style="background-color:balck;border:0;">
<tr>
	<td style="border:0;">
	<?php
	echo $this->Form->input(null,
		array(
			'id' => 'selPeriods',
			'label' => '', 'type' => 'select',
			'options' => array("-" => "Please choose a peroid...") 
				+ $sel_periods,
			'style' => 'width:190px;'
		)
	);
	?>
	</td>
	<td id="tdPeriods" style="border:0;font-weight:bold;color:#008899;">
		<!-- descript the periods -->
	</td>
	<td style="border:0;font-weight:bold;">
		<a href="#selPeriods" id="linkGo"></a>
	</td>
</tr>
</table>

<?php 
if (isset($ra0) && !empty($ra0)) {
?>
	<table style="width:100%">
		<thead>
		<tr>
			<!-- <th>company id</th> -->
			<th>Office Name</th>
			<th>
			<?php echo $periods[0] . "~" . $periods[1]; ?>
			</th>
			<th>
			<?php echo $periods[2] . "~" . $periods[3]; ?>
			</th>
			<th>Progress</th>
		</tr>
		</thead>
	<?php 
	foreach ($ra0 as $k => $v) {
	?>
		<tr>
			<!--  
			<td>
			<?php echo $k; ?>
			</td>
			-->
			<td>
			<?php echo $v[0]; ?>
			</td>
			<td>
			<?php echo $v[1]; ?>
			</td>
			<td>
			<?php echo $ra1[$k][1]; ?>
			</td>
			<td style="color:<?php echo ($ra1[$k][1] < $v[1] ? "red" : "white" ); ?>">
			<?php echo sprintf("%.2f%%", ($ra1[$k][1] - $v[1]) / $v[1] * 100)?>
			</td>
		</tr>
	<?php 
	}
	?>
	</table>
<?php 
} else {
?>
	<script type="text/javascript">
		jQuery("#tdPeriods").html(
			"<font style='font-weight:bold;color:red'>Data not prepared, please contact your administrator to fix it.</font>");
	</script>
<?php 
}
?>
<script type="text/javascript">
jQuery("#selPeriods").change(function(){
	var selv = jQuery("#selPeriods").find("option:selected").val();
	var seli = jQuery("#selPeriods").get(0).selectedIndex;
	var selimax = jQuery("#selPeriods option:last").attr("index");
	var copv = "";
	if (seli + 1 <= selimax) {
		copv = jQuery("#selPeriods").get(0).options[seli + 1].value;
	}
	if (seli > 0 && seli < selimax) {
		jQuery("#linkGo").text("GO!>>");
		jQuery("#linkGo").attr("href", "/NinjasChatClub/stats/progresses/bywhat:0/periods:" + copv + "," + selv);
		jQuery("#tdPeriods").html(
			"Get a chart with:"
			+ "\"" + copv + "\" ~ \"" + selv + "\", "
		);
	} else {
		if (seli == 0) {
			jQuery("#tdPeriods").html("");
			jQuery("#linkGo").text("");
			jQuery("#linkGo").attr("href", "#");
		} else {
			jQuery("#linkGo").text("GO>>!");
			jQuery("#linkGo").attr("href", "/NinjasChatClub/stats/progresses/bywhat:0/y,e,a,r");
			jQuery("#tdPeriods").html(
				"Get a chart within a whole year (month by month)."
			);
		}
	}
});
</script>