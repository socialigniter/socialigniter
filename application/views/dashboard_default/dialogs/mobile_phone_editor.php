<form method="post" name="mobile_phone_editor" id="mobile_phone_editor" enctype="multipart/form-data">
	<p>+1 <input type="text" name="phone_number" id="phone_number" placeholder="(503) 552-1212" size="22" value=""></p>
	<p><?= form_dropdown('phone_type', config_item('user_phone_types')); ?></p>
	<p><input class="nullify" type="checkbox" name="phone_private" value="yes"> Private</p>
	<p><input class="nullify" type="checkbox" name="phone_active" value="yes"> Active</p>
</form>