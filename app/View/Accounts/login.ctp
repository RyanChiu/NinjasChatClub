<center>
	<b><font color="red"><?php echo $this->Session->flash('auth'); ?> </font> </b>
	<font color="red"><?php echo $this->Session->flash(); ?> </font>
</center>
<?php
echo $this->Form->create(null, array('url' => array('controller' => 'accounts', 'action' => 'login')));
?>
<table style="border: 0; width: 100%">
	<tr>
		<td rowspan="10" width="195px">
		</td>
		<td colspan="2" align="center">
		</td>
		<td rowspan="10" width="185px" style="vertical-align: top;">
		</td>
	</tr>
	<tr>
		<td align="right"><b><font style="color:white;margin-right:6px;" size="2">User name:</font> </b>
		</td>
		<td align="left">
			<?php
			echo $this->Form->input('Account.username', array('label' => '', 'style' => 'width:112px;'));
			?> 
			<script type="text/javascript">
			jQuery("#AccountUsername").focus();
			</script>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td></td>
	</tr>
	<tr>
		<td align="right">
			<b><font style="color:white;margin-right:6px;" size="2">Password:</font></b>
		</td>
		<td align="left">
			<?php
			echo $this->Form->input('Account.password', array('label' => '', 'style' => 'width:112px; margin-top: 8px;', 'type' => 'password'));
			?>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td></td>
	</tr>
	<tr>
		<td align="right">
			<b><font style="color:white;margin-right:6px;" size="2">Captcha:</font></b>
		</td>
		<td align="left">
			<div style="float: left; margin-right: 10px;">
				<?php
				echo $this->Form->input(
					'Account.vcode', 
					array(
						'label' => '', 
						'style' => 'width:112px;', 
						'div' => array('style' => 'margin-top:8px;')
					)
				);
				?>
			</div>
			<div style="float: left;">
				<script type="text/javascript">
				function __chgVcodes() {
					document.getElementById("imgVcodes").src =
						"<?php echo $this->Html->url(array('controller' => 'accounts', 'action' => 'phpcaptcha')); ?>"
						+ "?" + Math.random();
				}
				</script>
				<?php
				echo $this->Html->link(
						$this->Html->image(array('controller' => 'accounts', 'action' => 'phpcaptcha'),
								array('style' => 'width:100px; height:35; border: 1px solid #222222;', 'id' => 'imgVcodes', 'onclick' => 'javascript:__chgVcodes();')
						),
						'#',
						array('escape' => false, 'title' => 'Click to try another one.(By entering this code you help yourself prevent spam and fake login.)'),
						false
				);
				?>
			</div>
			<div style="float: left;">
				<div id="playPhpcaptcha">
					<object type="application/x-shockwave-flash"
						data="../img/securimage_play.swf?bgcol=#ffffff&amp;icon_file=../img/audio_icon.png&amp;audio_file=<?php
							echo $this->Html->url(array('controller' => 'accounts', 'action' => 'playPhpcaptcha')); 
						?>"
						style="width: 35px; height: 35px; border: 1px solid #666666; margin-top: 1px; margin-left: 2px;">
						<param name="movie"
							value="../img/securimage_play.swf?bgcol=#ffffff&amp;icon_file=../img/audio_icon.png&amp;audio_file=<?php
								echo $this->Html->url(array('controller' => 'accounts', 'action' => 'playPhpcaptcha')); 
							?>"/>
					</object>
				</div>
			</div>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td style="color:white;">Example: 3x3=9, Captcha answer is 9.</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
		<br/>
		<div style="width:220px;height:45px;">
		<?php
		echo $this->Html->image("ninjastar.png", array('style' => "border:0;margin-top:7px;float:left;"));
		echo $this->Form->submit('login-button.png', array('style' => 'border:0px;width:160px;height:45px;float:left;'));
		?>
		</div>
		</td>
	</tr>
	<tr>
		<td align="center" colspan="2"><br /> 
			<?php
			echo $this->Html->link(
					'<b><font size="2">Lost your password?</font></b>',
					array('controller' => 'accounts', 'action' => 'forgotpwd'),
					array('escape' => false), false
			);
			?>
			<br/><br/>
			<font color="white">Contact your account manager, to retrieve your password,</font>
			<br/>
			<font color="white">we do not have agent's REAL email address.</font>
		</td>
	</tr>
</table>
<?php
echo $this->Form->end();
?>

<div style="margin: 0px 15px 0px 15px">
	<?php
	echo $this->element('frauddefblock');
	?>
</div>

<script type="text/javascript">
for (var i = 0; i < 10; i++) {
	jQuery(".suspended-warning").animate({opacity: 'toggle'}, "slow");
}
</script>
