<form name="settings_update" id="settings_update" method="post" action="<?= base_url() ?>api/settings/modify" enctype="multipart/form-data">

<div class="content_wrap_inner">

	<h3>Logo</h3>

	<h3>Links</h3>

	<p><input type="text" maxlength="6" size="6" id="colorpickerField1" value="00ff00" /></p>
	<p><input type="text" maxlength="6" size="6" id="colorpickerField3" value="0000ff" /></p>
	<p><input type="text" maxlength="6" size="6" id="colorpickerField2" value="ff0000" /></p>

	<h3>Background</h3>

	<p>Image</p>

	<p>Color</p>
	
</div>

</form>

<link rel="stylesheet" href="<?= $dashboard_assets ?>colorpicker.css" type="text/css" />
<script type="text/javascript" src="<?= $dashboard_assets ?>jquery.js"></script>
<script type="text/javascript" src="<?= $dashboard_assets ?>colorpicker.js"></script>
<script type="text/javascript" src="<?= $dashboard_assets ?>eye.js"></script>
<script type="text/javascript" src="<?= $dashboard_assets ?>utils.js"></script>
<script type="text/javascript" src="<?= $dashboard_assets ?>layout.js?ver=1.0.2"></script>

<script type="text/javascript">
$(document).ready(function()
{

	$('#colorpickerField1, #colorpickerField2, #colorpickerField3').ColorPicker({
		onSubmit: function(hsb, hex, rgb, el) {
			$(el).val(hex);
			$(el).ColorPickerHide();
		},
		onBeforeShow: function () {
			$(this).ColorPickerSetColor(this.value);
		}
	})
	.bind('keyup', function(){
		$(this).ColorPickerSetColor(this.value);
	});

});
</script>
<?= $shared_ajax ?>