<?php echo '<?xml version="1.0" encoding="utf-8"?>'."\n"; ?>
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:admin="http://webns.net/mvcb/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:content="http://purl.org/rss/1.0/modules/content/">
<channel>
  <title><?php echo $site_title ?></title>
  <link><?php echo $site_url ?></link>
  <description><?php echo $site_description ?></description>
  <managingEditor><?php echo $site_url ?></managingEditor>
  <generator><?= config_item('site_generator') ?> RSS Generator</generator>		        
  <dc:language><?php echo $language ?></dc:language>  
  <dc:creator></dc:creator>  
  <dc:rights>Copyright <?= date('Y') ?></dc:rights>  
  <admin:generatoragent rdf:resource="<?php echo config_item('site_generator_url') ?>"></admin:generatoragent>
  <?php if ($contents): foreach($contents as $content): ?>
  <item>
    <title><?php echo xml_convert($content->title) ?></title> 
    <link><?php echo base_url().$content->title_url	 ?></link>
    <guid isPermaLink="true"><?php echo $item_base_url.$content->content_id ?></guid>			
    <description><![CDATA[<?= $content->content ?>]]></description>
    <author><?php echo xml_convert($content->name) ?></author>	  
    <pubdate><?php echo standard_date('DATE_RSS', mysql_to_unix($content->created_at)) ?></pubdate>
  </item>
  <?php endforeach; endif; ?>      
</channel>
</rss>