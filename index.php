<?
	function getSitemap($array_json,$part,$lastPart=null)
	{
	GLOBAL $domain, $fp,$lang,$priority;
	if ($lastPart){
		$lastPart .= "/".$part;
	}
	else
	{
	$lastPart .= $part;
	}
	$link = $domain."/".$lang."/".$lastPart."/";
	$xml_text ='<url> <loc>'.$link.'</loc> <priority>'.$priority.'</priority> </url>';
	fwrite($fp, $xml_text);
	foreach($array_json as $nextPart => $value)
			{
				if (is_array($value)){
					getSitemap ($value,$nextPart,$lastPart);
				}
				else
				{
					if (is_object($value)){			
					foreach($value as $object_part => $nextValue)
						{
							$object_nextPart = $lastPart."/".$nextPart;
							getSitemap($nextValue,$object_part,$object_nextPart);
						}
					}
					else{
					$link = $domain."/".$lang."/".$lastPart."/".$value.".html";
					$xml_text ='<url> <loc>'.$link.'</loc> <priority>'.$priority.'</priority> </url>';
					fwrite($fp, $xml_text);
					}
				}
			
			}
	}
	
	function getLanguage($sitemap)
	{
	$lang = explode(".",$sitemap);
	return ($lang[0]);
	}
	
$domain = "http://site.ru";
$priority = "0.8";
$fp = fopen('sitemap.xml', 'w');
$header = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
$xml_text = '<url> <loc>'.$domain.'/</loc> <priority>1.0</priority> </url>';
fwrite($fp, $header.$xml_text);
print "Доступные языковые локализации<br>";
foreach (glob("*.sitemap.json") as $sitemap)
    {
    echo $sitemap."<br>";
	$lang = getLanguage($sitemap);
	$link = $domain."/".$lang."/";
	$xml_text ='<url> <loc>'.$link.'</loc> <priority>'.$priority.'</priority> </url>';
	fwrite($fp, $xml_text);	
	$json_sitemap = json_decode(file_get_contents($sitemap));
	foreach($json_sitemap as $part => $value)
		{
			getSitemap($value,$part);
		}
    }
$xml_text = "</urlset>";
fwrite($fp, $xml_text);	
fclose($fp);
print "Файл sitemap.xml успешно создан";
?>