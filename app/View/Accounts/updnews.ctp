<h1>Update News</h1>
<?php
//echo print_r($results, true);
$userinfo = $this->Session->read('Auth.User.Account');
echo $this->Form->create(null, array('url' =>  array('controller' => 'accounts', 'action' => 'updnews')));
?>
<table style="width:100%">
	<tr>
		<td>
		<div style="float:left">
			<div id="newsTabs">
			<ul>
				<li><a href="#tabs-news" id="lnkNews">First Page</a></li>
				<li><a href="#tabs-news-more" id="lnkNewsMore">More</a></li>
			</ul>
			<div id="tabs-news">
			<?php
			echo $this->Form->input('Bulletin.info', array('div' => null, 'label' => '', 'rows' => '60', 'cols' => '80'));
			?>
			</div>
			<div id="tabs-news-more">
			<?php
			echo $this->Form->input('Bulletin.infomore', array('div' => null, 'label' => '', 'rows' => '60', 'cols' => '80'));
			?>
			</div>
			</div>
			<script type="text/javascript">
			jQuery("#newsTabs").tabs();
			<?php
			if (isset($id) && !empty($id)) {
			?>
			jQuery("#lnkNewsMore").click();
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
echo $this->Form->input('Bulletin.id', array('type' => 'hidden'));
echo $this->Form->end();
?>

<script type="text/javascript">
	CKEDITOR.replace('BulletinInfo',
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
	CKEDITOR.replace('BulletinInfomore',
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
