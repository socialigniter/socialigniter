<h3>Widgets</h3>

<form method="post" action="<?= base_url()."site/widgets";?>">

<table border="0" cellpadding="0" cellspacing="0">
<tr>
	<td>Name:</td>
	<td><input type="text" name="name" value="<?= set_value('name', $name) ?>"></td>
</tr>
<tr>		
	<td colspan="2"><input type="submit" value="Update" /></td>
	</tr>
	</table>

</form>