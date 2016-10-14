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
	<td>
		<?php 
		echo $this->Html->link(
			$this->Html->image('Calendar-icon.png', array('border' => 0, 'width' => 23, 'height' => 23)),
			'#datesPicker',
			array('id' => 'lnkCalendar', 'escape' => false),
			false
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
	<table style="width:100%" id="tblSortable">
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
			<td style="<?php echo ($ra1[$k][1] < $v[1] ? "color:red" : "" ); ?>">
			<?php echo sprintf("%.2f%%", ($ra1[$k][1] - $v[1]) / $v[1] * 100)?>
			</td>
		</tr>
	<?php 
	}
	?>
	</table>
<?php 
} else if (isset($comd) && !empty($comd)) {
?>
	<?php 
	foreach ($comd as $ck => $cv) {
	?>
		<div id="chartContainerII_<?php echo $ck; ?>" style="height:300px;width:100%;margin:2px 0 2px 0;"></div>
	<?php 
	}
	?>
	<script type="text/javascript">
	window.onload = function () {
		var chart;
		

		<?php 
		foreach ($comd as $ck => $cv) {
		?>
		chart = new CanvasJS.Chart("chartContainerII_<?php echo $ck; ?>",
	    {
		  theme: "theme3",
	      title:{
	        text: "By Office \"<?php echo $ck; ?>\""             
	      },
	      dataPointMaxWidth: 39,
	      animationEnabled: true,   
	      toolTip: {
	        shared: true,
	        content: function(e){
	          var body ;
	          var head ;
	          head = "<span style = 'color:DodgerBlue; '><strong>"+ (e.entries[0].dataPoint.label)  + "</strong></span><br/>";

	          body = "<span style= 'color:"+e.entries[0].dataSeries.color + "'> " + e.entries[0].dataSeries.name + "</span>: <strong>"+  e.entries[0].dataPoint.y + "</strong>  <!--m/s--><br/> <span style= 'color:"+e.entries[1].dataSeries.color + "'> " + e.entries[1].dataSeries.name + "</span>: <strong>"+  e.entries[1].dataPoint.y + "</strong>%";

	          return (head.concat(body));
	        }
	      },   
	      axisY:{ 
	        title: "Sales",
	        includeZero: false,
	        suffix : "",
	        lineColor: "#369EAD"        
	      },
	      axisY2:{ 
	        title: "Progress",
	        includeZero: false,
	        suffix : "%",
	        lineColor: "#C24642"
	      },
	      axisX: {
	        title: "Monthly",
	        suffix : " ",
	      },
	      data: [
	      {        
	        type: "column",
	        showInLegend: true,
	        name: "Sales",
	        dataPoints: [
		      	<?php 
		      	foreach ($cv as $cvk => $cvv) {
		      	?>
		      		{
			      		label:"<?php echo $cvk; ?>", 
			      		y:<?php echo $cvv[0]; ?>, 
					    indexLabel:"<?php echo $cvv[0]; ?>", 
					    //indexLabelOrientation:"vertical", 
					    indexLabelPlacement: "inside",
					    indexLabelFontSize: 20,
					    indexLabelFontColor: "blue"
					},
		      	<?php 
				}
		      	?>   
	        ]
	      }, 
	      {        
	        type: "spline",
	        showInLegend: true,
	        axisYType: "secondary"  ,
	        name: "Progress",
	        dataPoints: [
		        <?php 
		        foreach ($cv as $cvk => $cvv) {
		        ?>
		        	{
			        	label:"<?php echo $cvk; ?>", 
			        	y:<?php echo $cvv[1]; ?>, 
					    //indexLabel:"<?php //echo $cvv[1]; ?>%", 
					   	indexLabelFontColor:"red"
					},
		        <?php 
				}
		        ?>     
	        ]
	      } 
	      ]
	    });

		chart.render();
		<?php 
		}
		?>
		
	}
	</script>
<?php 
} else {
?>
	<script type="text/javascript">
		jQuery("#tdPeriods").html(
			"<font style='font-weight:bold;color:red'>Please choose pay periods, to compare office performance.</font>");
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
			jQuery("#linkGo").attr("href", "/NinjasChatClub/stats/progresses/bywhat:0/periods:y,e,a,r");
			jQuery("#tdPeriods").html(
				"Get a chart within a whole year (month by month)."
			);
		}
	}
});

/*
 * the following imlement with Mottie-tablesorter
 */
jQuery(function(){
	jQuery('#tblSortable').tablesorter({
		widgets        : ['zebra', 'columns'],
		usNumberFormat : false,
		sortReset      : true,
		sortRestart    : true
	});
});

/*
 * for date picker fancy box
 */
 jQuery(document).ready(function() {
	jQuery("a#lnkCalendar").fancybox({
		'type': 'inline',
		'margin': 0,
		'padding': 0,
		'overlayShow': true,
		'overlayOpacity': 0.3,
		'hideOnOverlayClick': false,
		'modal': true
	});
	jQuery(function() {
		jQuery('#datepicker_from0').datepicker({
			defaultDate: -30,
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd',
			prevText: 'Previous Month',
			nextText: 'Next Month',
			prevBigText: '<<',
			nextBigText: '>>'
		});
	});
	jQuery(function() {
		jQuery('#datepicker_from1').datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd',
			prevText: 'Previous Month',
			nextText: 'Next Month',
			prevBigText: '<<',
			nextBigText: '>>'
		});
	});
	jQuery(function() {
		jQuery('#datepicker_to0').datepicker({
			defaultDate: -30,
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd',
			prevText: 'Previous Month',
			nextText: 'Next Month',
			prevBigText: '<<',
			nextBigText: '>>'
		});
	});
	jQuery(function() {
		jQuery('#datepicker_to1').datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd',
			prevText: 'Previous Month',
			nextText: 'Next Month',
			prevBigText: '<<',
			nextBigText: '>>'
		});
	});
})

function dateSelected() {
	var start = jQuery('#datepicker_from0').datepicker('getDate');
	var end =  jQuery('#datepicker_to0').datepicker('getDate');
	var ss0 = start.getFullYear().toString()
	 	+ "-" + (start.getMonth() >= 9 ? (start.getMonth() + 1).toString() : ("0" + (start.getMonth() + 1).toString()))
	 	+ "-" + (start.getDate() > 9 ? start.getDate().toString() : ("0" + start.getDate().toString()));
	var es0 = end.getFullYear().toString()
	 	+ "-" + (end.getMonth() >= 9 ? (end.getMonth() + 1).toString() : ("0" + (end.getMonth() + 1).toString()))
	 	+ "-" + (end.getDate() > 9 ? end.getDate().toString() : ("0" + end.getDate().toString()));
	start = jQuery('#datepicker_from1').datepicker('getDate');
	end =  jQuery('#datepicker_to1').datepicker('getDate');
	var ss1 = start.getFullYear().toString()
	 	+ "-" + (start.getMonth() >= 9 ? (start.getMonth() + 1).toString() : ("0" + (start.getMonth() + 1).toString()))
	 	+ "-" + (start.getDate() > 9 ? start.getDate().toString() : ("0" + start.getDate().toString()));
	var es1 = end.getFullYear().toString()
	 	+ "-" + (end.getMonth() >= 9 ? (end.getMonth() + 1).toString() : ("0" + (end.getMonth() + 1).toString()))
	 	+ "-" + (end.getDate() > 9 ? end.getDate().toString() : ("0" + end.getDate().toString()));
	jQuery("#linkGo").text("GO!>>");
	jQuery("#linkGo").attr("href", "/NinjasChatClub/stats/progresses/bywhat:0/periods:" + (ss0 + "," + es0) + "," + (ss1 + "," + es1));
	jQuery("#tdPeriods").html(
		"Get a chart with:"
		+ "\"" + (ss0 + "," + es0) + "\" ~ \"" + (ss1 + "," + es1) + "\", "
	);
	javascript:jQuery.fancybox.close();
}
</script>
<!-- for date picker fancy box -->
<div style="display:none">
	<div id="datesPicker" 
		style="width:470px;height:500px;background-color:black;padding:6px;border:1px solid white;">
		<font style="font-weight:bold;color:white;width:100%;">From:</font>
		<div style="">
			<div id='datepicker_from0' style="float:left;margin-right:8px;margin-bottom:6px;"></div>
			<div id='datepicker_to0' style="float:left;margin-bottom:6px;"></div>
		</div>
		<font style="font-weight:bold;color:white;">To:</font>
		<div style="">
			<div id='datepicker_from1' style="float:left;margin-right:8px;margin-bottom:6px;"></div>
			<div id='datepicker_to1' style="float:left;margin-bottom:6px;"></div>
		</div>
		<div style="width:100%;text-align:center;">
			<a href="#" onclick="javascript:dateSelected();">OK</a>
		</div>
	</div>
</div>