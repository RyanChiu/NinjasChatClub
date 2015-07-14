<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html>
<head>
<title><?php echo $title_for_layout; ?>
</title>
<?php
echo $this->Html->meta('icon', $this->Html->url('/../fav.png'));
/*for default whole page layout*/
echo $this->Html->css('main');

/*for jQuery*/
echo $this->Html->script('jQuery/Datepicker/jquery-1.3.2.min');

/*for cufon*/
echo $this->Html->script('cufon/cufon-yui');
echo $this->Html->script('cufon/Chiller_400.font');

echo $scripts_for_layout;

?>
<script type="text/javascript">
	Cufon.replace(".header");
</script>
</head>
<body bgcolor="#ffffff">
	<div class="wrapper">
		<!-- Start Border-->
		<div id="border">
			<!-- Start Header -->
			<div class="header">
				<div style="width:100%;margin-left:10px;">
					<?php echo $this->Html->image('main/topbanner.png', array('style' => 'border:0px;width:100%;'));?>
				</div>
			</div>
			<!-- End Header -->
			<!-- Start Right Column -->
			<div id="rightcolumn">
				<!-- Start Main Content -->
				<div class="maincontent" style="background-color:black;">
					<center>
						<b><font color="red"><?php echo $this->Session->flash(); ?></font> </b>
					</center>
					<div class="content-top"></div>
					<div class="content-mid">

						<?php echo $content_for_layout; ?>

					</div>
					<div class="content-bottom"></div>
				</div>
				<!-- End Main Content -->
			</div>
			<!-- End Right Column -->
		</div>
		<!-- End Border -->
		<!-- Start Footer -->
		<div id="footer">
			<font size="2" color="#b80f16"><b>Copyright &copy; 2015 www.NinjasChatClub.com All
					Rights Reserved.&nbsp;&nbsp;</b> </font>
		</div>
		<!-- End Footer -->
	</div>
	
	<!-- To avoid delays, initialize Cufón before other scripts at the bottom -->
	<script type="text/javascript"> Cufon.now(); </script>
</body>
</html>
