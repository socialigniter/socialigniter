<?php if ($phones): foreach ($phones as $phone): $details = json_decode($phone->details) ?>
		
	<h3>Your Phone</h3>	
	<p>You may send status updates from this phone number:</p>
	<h3>+1 <?= $phone ?></h3>

	<div class="content_norm_separator"></div>		
	
	<h3>Mobile Settings</h3>
	<form method="post" name="user_mobile_modify" id="user_mobile_modify" action="<?= base_url() ?>api/users/mobile_modify/id/<?= $phone->user_meta_id ?>">
		<table border="0" cellpadding="0" cellspacing="0">
	    <tr>		
			<td>Phone:</td>
			<td>+1 <?= $phone ?><input type="hidden" name="value" value="<?= $phone ?>" /></td>
		</tr>
		<tr>
			<td>Type:</td>
			<td><?= form_dropdown('type', config_item('user_mobile_types'), $details->type); ?></td>
		</tr>		
		<tr>
			<td>Search:</td>
			<td><?= form_checkbox($details->search); ?> Allow others to find you by phone number</td>
		</tr>
		<tr>
			<td>Text Updates:</td>
			<td><?= form_dropdown('phone_active', $phone_active_array, $details->active); ?></td>
		</tr>
		<tr>
			<td><a href="<?= base_url() ?>api/users/mobile_delete">Delete number</a></td>
		</tr>
	    <tr>		
			<td colspan="2">
				<input type="submit" value="Save" />
			</td>
	  	</tr>	
		</table>
	</form>
	
<!-- 
<?php if (!empty($phone) && ($phone_verify != "verified")) { ?>

	<h3>Verify Mobile Number</h3>

	<h3>+1 <?= $phone ?></h3>
	
	<p>By sending a text message to to this number:</p>
	
	<h3>+1 <?= $phone ?></h3>

	<p>With this word as a message:</p>
	<h3><?= $phone_verify; ?></h3>
	
	<p>Or <a href="<?= base_url()."settings/mobile_delete" ?>">delete this number</a></p>
		
<?php } else { ?>
		
	<h3>Add Mobile Number</h3>
	<form method="post" name="user_mobile" id="user_mobile" action="<?= base_url() ?>api/users/mobile_add">
		<table border="0" cellpadding="0" cellspacing="0">
	    <tr>		
			<td>Phone:</td>
			<td>+1 <input type="text" name="phone" size="18" value="<?= set_value('phone', $phone) ?>"></td>
		</tr>
		<tr>
			<td>Search:</td>
			<td><?= form_checkbox($phone_search); ?> Allow others to find you by phone number</td>
		</tr>
		<tr>
			<td>Text Updates:</td>
			<td><?= form_dropdown('phone_active', $phone_active_array, $phone_active); ?></td>
		</tr>
	    <tr>		
			<td colspan="2"><input type="submit" value="Add" /></td>
	  	</tr>	
		</table>		
<?php } ?>
-->	
	
<?php endforeach; endif; ?>

<h3>Add A Phone</h3>
<form method="post" name="user_mobile_add" id="user_mobile_add" action="<?= base_url() ?>api/users/mobile_add">

	<?= form_dropdown('type', config_item('user_mobile_types')); ?>

	+1 <input type="text" name="phone" size="18" value=""> <input type="checkbox" name="phone_search" value="yes"> Allow others to search this number</p>

	<p><input type="submit" value="Add" /></p>
		
</form>	

<script type="text/javascript">
$(document).ready(function()
{
	// Add Mobile
	$("#user_mobile_add").bind("submit", function(e)
	{
		e.preventDefault();
		var details_data = $('#user_mobile_add').serializeArray();
		details_data.push({'name':'module','value':'users'});		
	
		$(this).oauthAjax(
		{
			oauth 		: user_data,
			url			: $(this).attr('ACTION'),
			type		: 'POST',
			dataType	: 'json',
			data		: details_data,
	  		success		: function(result)
	  		{		  					  			  			
				$('html, body').animate({scrollTop:0});
				$('#content_message').notify({scroll:true,status:result.status,message:result.message});	
		 	}
		});		
	});	
});
</script>