<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:admin="http://webns.net/mvcb/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:content="http://purl.org/rss/1.0/modules/content/">
	<channel>
		<title><?php echo $site_title ?></title>
		<link><?php echo $site_url ?></link>
		<description><?php echo $site_description ?></description>
		<managingEditor><?php echo $site_admin ?></managingEditor>
		<generator><?= config_item('site_generator') ?> RSS Generator</generator>		        
		<dc:language><?php echo $language ?></dc:language>  
		<dc:creator></dc:creator>  
  
        <dc:rights>Copyright <?= date('Y') ?></dc:rights>  
        <admin:generatoragent rdf:resource="<?= config_item('site_generator_url') ?>">
        
        <?php if ($contents): foreach($contents as $content): ?>
        <item>
			<title><?= xml_convert($content->title) ?></title> 
			<link><?= base_url().$content->title_url ?></link>
			<guid><?= base_url().$content->title_url ?></guid>			
			<description><![CDATA[<?= $content->content ?>]]></description>
		    <author><?= xml_convert($content->name) ?></author>	  
			<pubdate><?= standard_date('DATE_RSS', mysql_to_unix($content->created_at)) ?></pubdate>
		</item>
        <?php endforeach; endif; ?> 
          
        </admin:generatoragent>
	</channel>  
</rss>