<h1>Update Alerts</h1>
<?php
//echo print_r($results, true);
$userinfo = $this->Session->read('Auth.User.Account');
echo $this->Form->create(null, array('url' => array('controller' => 'accounts', 'action' => 'updalerts')));
?>
<table style="width:100%">
	<tr>
		<td>
		<div style="float:left">
			<div id="alertsTabs">
			<ul>
				<li><a href="#tab-alerts" id="lnkAlerts">First Page</a></li>
				<li><a href="#tab-alerts-more" id="lnkAlertsMore">More</a></li>
			</ul>
			<div id="tab-alerts">
			<?php
			echo $this->Form->input('Admin.notes', array('div' => null, 'label' => '', 'rows' => '60', 'cols' => '80'));
			?>
			</div>
			<div id="tab-alerts-more">
			<?php
			echo $this->Form->input('Admin.notesmore', array('div' => null, 'label' => '', 'rows' => '60', 'cols' => '80'));
			?>
			</div>
			</div>
			<script type="text/javascript">
                        jQuery("#alertsTabs").tabs();
                        <?php
                        if (isset($id) && !empty($id)) {
                        ?>
                        jQuery("#lnkAlertsMore").click();
                        <?php
                        }
                        ?>
                        </script>
		</div>
		</td>
	</tr>
	<tr>
		<td><?php echo $this->Form->submit('Update', array('style' => 'width:112px;')); ?></td>
	</tr>
</table>
<?php
echo $this->Form->input('Admin.id', array('type' => 'hidden'));
echo $this->Form->end();
?>

<script type="text/javascript">
	CKEDITOR.replace('AdminNotes',
		{
	        filebrowserUploadUrl : '/ncc/accounts/upload',
	        filebrowserWindowWidth : '640',
	        filebrowserWindowHeight : '480'
	    }
	);
	CKEDITOR.config.height = '500px';
	CKEDITOR.config.width = '866px';
	CKEDITOR.config.resize_maxWidth = '830px';
	CKEDITOR.config.toolbar =
		[
		    ['Source','-','NewPage','Preview','-','Templates'],
		    ['Cut','Copy','Paste','PasteText','PasteFromWord'],
		    ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
		    '/',
		    ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
		    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
		    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		    ['Link','Unlink','Anchor'],
		    ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
		    '/',
		    ['Styles','Format','Font','FontSize'],
		    ['TextColor','BGColor']
		];
	CKEDITOR.replace('AdminNotesmore',
		{
	        filebrowserUploadUrl : '/ncc/accounts/upload',
	        filebrowserWindowWidth : '640',
	        filebrowserWindowHeight : '480'
	    }
	);
	CKEDITOR.config.height = '500px';
	CKEDITOR.config.width = '866px';
	CKEDITOR.config.resize_maxWidth = '830px';
	CKEDITOR.config.toolbar =
		[
		    ['Source','-','NewPage','Preview','-','Templates'],
		    ['Cut','Copy','Paste','PasteText','PasteFromWord'],
		    ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
		    '/',
		    ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
		    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
		    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		    ['Link','Unlink','Anchor'],
		    ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
		    '/',
		    ['Styles','Format','Font','FontSize'],
		    ['TextColor','BGColor']
		];
</script>
