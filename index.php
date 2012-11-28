<?
	function getNameSitemap($array_json,$portion,$portion0=null)
	{
	GLOBAL $domain, $fp,$lang;
	if ($portion0){
		$portion0 .= "/".$portion;
	}
	else
	{
	$portion0 .= $portion;
	}
	foreach($array_json as $portion2 => $value2)
			{
				if (is_array($value2)){
					getNameSitemap ($value2,$portion2,$portion);
				}
				else
				{
					$link = $domain."/".$lang."/".$portion0."/".$value2.".html";
					$xml_text ='<url> <loc>'.$link.'</loc> <priority>0.8</priority> </url>';
					fwrite($fp, $xml_text);
				}
			
			}
	}
	
$domain = "http://site.ru";
$fp = fopen('sitemap.xml', 'w');
$xml_text = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
<url> <loc>'.$domain.'</loc> <priority>1.0</priority> </url>';
fwrite($fp, $xml_text);
print "Доступные языковые локализации<br>";
foreach (glob("*.sitemap.json") as $sitemap)
    {
    echo $sitemap."<br>";
	$lang = explode(".",$sitemap);
	$lang = $lang[0];
	$json_sitemap = json_decode(file_get_contents($sitemap));

	foreach($json_sitemap as $portion => $value)
		{
			getNameSitemap($value,$portion);
		/*foreach($value as $portion2 => $value2)
			{
				if (is_array($value2)){
				getNameSitemap ($value2,$portion2,$portion);
				}
				else
				{$link = $domain."/".$portion."/".$value2.".html";
				print $link."<br>";}
			
			}
		*/
		}
    }
$xml_text = "</urlset>";
fwrite($fp, $xml_text);	
fclose($fp);
print "Файл sitemap.xml успешно создан";
?>