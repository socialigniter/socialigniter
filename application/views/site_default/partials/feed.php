<?= '<'?>?xml version="1.0" encoding="UTF-8"?<?= '>' ?>

<feed xml:lang="en-US" xmlns="http://www.w3.org/2005/Atom" xmlns:thr="http://purl.org/syndication/thread/1.0" xmlns:georss="http://www.georss.org/georss" xmlns:activity="http://activitystrea.ms/spec/1.0/" xmlns:media="http://purl.org/syndication/atommedia" xmlns:poco="http://portablecontacts.net/spec/1.0" xmlns:ostatus="http://ostatus.org/schema/1.0" xmlns:statusnet="http://status.net/schema/api/1/">
	<id><?= base_url() ?>profile/<?= $username ?>/feed</id>
	<title><?= $username ?>'s profile</title>
	<author>
		<name>
			<?= $username ?>

		</name>
	</author>
	<link rel='hub' href='http://www.psychicwarlock.com/'/>
	<link href='<?= base_url() ?>profile/<?= $username ?>'/>
	<? foreach($activities as $activity): ?>
		<? if ($activity->type == "status"): ?>
			<? $item_date = format_datetime(config_item('home_date_style'), $activity->created_at); ?>	
		    <entry>
				<title>Status Update</title> 
				<link href='#TODO add link'/>
				<summary><?= $social_igniter->render_item($activity); ?></summary>
				<updated><?= $item_date ?></updated>

			</entry>
		<? endif; ?>
	<? endforeach; ?>
</feed>
