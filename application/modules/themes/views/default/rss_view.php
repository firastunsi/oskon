<?php  echo '<?xml version="1.0" encoding="' . $encoding . '"?>' . "\n"; ?>
<rss version="2.0"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
    xmlns:admin="http://webns.net/mvcb/"
    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:content="http://purl.org/rss/1.0/modules/content/">
 
    <channel>
     
    <title><?php echo $feed_name; ?></title>
 
    <link><?php echo $feed_url; ?></link>
    <description><?php echo $page_description; ?></description>
    <dc:language><?php echo $page_language; ?></dc:language>
    <dc:creator><?php echo $creator_email; ?></dc:creator>

    <dc:rights>Copyright <?php echo gmdate("Y", time()); ?></dc:rights>
    <admin:generatorAgent rdf:resource="http://www.codeigniter.com/" />
 
    <?php foreach($posts->result() as $post): ?>
     	<?php 
     	$estate_title =  get_title_for_edit_by_id_lang($post->id,$curr_lang);
     	$description = get_description_for_edit_by_id_lang($post->id,$curr_lang);
     	$detail_link = site_url('property/'.$post->unique_id.'/'.url_title($estate_title));
     	?>
        <item>
 
          <title><?php echo xml_convert($estate_title); ?></title>
          <link><?php echo $detail_link; ?></link>
          <guid><?php echo $detail_link; ?></guid>
 
            <description><![CDATA[ <?php echo character_limiter($description, 200); ?> ]]></description>
            <pubDate><?php echo date('Y-m-d',$post->create_time); ?></pubDate>
        </item>
 
         
    <?php endforeach; ?>
     
    </channel>
</rss>