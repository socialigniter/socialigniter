<form method="post" name="mobile_phone_editor" id="mobile_phone_editor" enctype="multipart/form-data">

	<p><?= form_dropdown('type', config_item('user_mobile_types')); ?></p>
	<p>+1 <input type="text" name="phone_number" size="18" value=""></p>
	<p><input class="nullify" type="checkbox" name="phone_search" value="yes"> Allow others to search this number</p>
	<p><?php //form_dropdown('phone_active', $phone_active_array, $phone_active); ?></p>
	<p><input type="submit" value="Add Mobile" /></p>

</form>