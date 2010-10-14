<?php if ($user_id != $logged_user_id) { ?>
<p><a class="button_basic_on" href="<?= $follow_url ?>"><span>Follow</span></a></p>
<?php } else { ?>
<p><a href=""><span>Follow</span></a></p>
<?php } ?>