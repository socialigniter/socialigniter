<form method="post" name="mobile_phone_editor" id="mobile_phone_editor" enctype="multipart/form-data">
	<p><input type="text" name="phone_number" id="phone_number" size="22" value=""></p>
	<p><?= form_dropdown('phone_type', config_item('user_mobile_types')); ?></p>
	<p><input class="nullify" type="checkbox" name="phone_search" value="yes"> Allow search for this number</p>
	<p><input class="nullify" type="checkbox" name="phone_active" value="yes"> Active</p>
</form>