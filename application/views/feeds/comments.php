<?php echo '<?xml version="1.0" encoding="utf-8"?>'."\n"; ?>
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:admin="http://webns.net/mvcb/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:content="http://purl.org/rss/1.0/modules/content/">
<channel>
	<title>Comments for <?php echo $site_title ?></title>
	<link><?php echo $site_url ?></link>
	<description><?php echo $site_description ?></description>
	<managingEditor><?php echo $site_admin ?></managingEditor>
	<generator><?= config_item('site_generator') ?> RSS Generator</generator>		        
	<dc:language><?php echo $language ?></dc:language>  
	<dc:creator></dc:creator>  
	<dc:rights>Copyright <?= date('Y') ?></dc:rights>  
	<admin:generatoragent rdf:resource="<?php echo config_item('site_generator_url') ?>"></admin:generatoragent>
    <?php if ($comments): foreach($comments as $comment): ?>
    <item>
		<title>Comment on <?php echo xml_convert($comment->title) ?> by <?php echo xml_convert($comment->name) ?></title> 
		<link><?php echo base_url().$comment->title_url.'#comment-'.$comment->comment_id;	 ?></link>
		<guid isPermaLink="false"><?php echo base_url().'content/'.$comment->content_id.'#comment-'.$comment->comment_id; ?></guid>			
		<description><![CDATA[<?= $comment->comment ?>]]></description>
		<author><?php echo xml_convert($comment->name) ?></author>	  
		<pubdate><?php echo standard_date('DATE_RSS', mysql_to_unix($comment->created_at)) ?></pubdate>
	</item>
    <?php endforeach; endif; ?>      
</channel>
</rss>