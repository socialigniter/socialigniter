<?= '<'?>?xml version="1.0" encoding="UTF-8"?<?= '>' ?>

<feed xml:lang="en-US" xmlns="http://www.w3.org/2005/Atom" xmlns:thr="http://purl.org/syndication/thread/1.0" xmlns:georss="http://www.georss.org/georss" xmlns:activity="http://activitystrea.ms/spec/1.0/" xmlns:media="http://purl.org/syndication/atommedia" xmlns:poco="http://portablecontacts.net/spec/1.0" xmlns:ostatus="http://ostatus.org/schema/1.0" xmlns:statusnet="http://status.net/schema/api/1/">
	<updated><?= date('Y-m-d\TH:i:s\Z', strtotime($activities[0]->created_at)) ?></updated>
	<id><?= base_url() ?>profile/<?= $username ?>/feed</id>
	<title><?= $username ?>'s profile</title>
	<author>
		<name>
			<?= $username ?>

		</name>
	</author>
	<link rel='hub' href='http://pubsubhubbub.appspot.com/'/>
	<link href='<?= base_url() ?>profile/<?= $username ?>'/>
	<? $counter = 0 ?>
	<? foreach($activities as $activity): ?>
		<? if ($activity->type == "status"): ?>
		    <entry>
		    	<id>http://damn.this.spec.is.hard/<?= $activity->activity_id ?></id>
				<title>Status Update</title> 
				<link href='http://need.to.add.com'/>
				<summary><?= $social_igniter->render_item($activity); ?></summary>
				<updated><?= date('Y-m-d\TH:i:s\Z', strtotime($activity->created_at)) ?></updated>
				<?php $counter += 1 ?>
			</entry>
		<? endif; ?>
	<? endforeach; ?>
</feed>
