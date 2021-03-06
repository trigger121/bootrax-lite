<?php

# LOAD XML FILE 
	$contents = new DOMDocument(); 
	
	if(!file_exists('assets/templates/'.$request['currentpage'].'/data.xml')){
		redirect("http://".$_SERVER['HTTP_HOST'].'/'.$site_name."/not-found", false);	
	}else{
		$contents->load('assets/templates/'.$request['currentpage'].'/data.xml'); 
	}
	
	$XML = new DOMDocument(); 	
	$DATA = $XML->createElement('data');
	$PARAMS = $XML->createElement('params');		
		$xpath = new DOMXpath($contents);
		$elements = $xpath->query("*");
		if ($elements->length > 0) {			
		  foreach ($elements as $element) {						
			$nodes = $element->childNodes;			
			foreach ($nodes as $node) {					
					$page = $element->getAttribute('handle');
					$title = $element->nodeValue;
			}			
		  }					
			
	}
	if(!file_exists('assets/templates/'.$request['currentpage'].'/config.php')){
		redirect("http://".$_SERVER['HTTP_HOST'].'/'.$site_name."/not-found", false);	
	}else{
		$config = include('assets/templates/'.$request['currentpage'].'/config.php');
	}
	foreach($config as $c => $fig){
		$element = $XML->createElement($c,$fig);	
		$PARAMS->appendChild($element);
	}
	include('lib/nav/loadnav.php');
	$DATA->appendChild($PARAMS);	
	$xp = new DOMXpath($contents);
	$c = $xp->query("*");
	foreach($c as $content){
		$node = $content;		
		$DATA->appendChild($XML->importNode($node,true));	
	}	
	$XML->appendChild($DATA);
	$xslt = new XSLTProcessor(); 
	$XSL = new DOMDocument(); 
	if(!file_exists('assets/templates/'.$request['currentpage'].'/page.xsl')){
		redirect("http://".$_SERVER['HTTP_HOST'].'/'.$site_name."/not-found", false);	
	}else{
		$XSL->load( 'assets/templates/'.$request['currentpage'].'/page.xsl', LIBXML_NOCDATA); 
	}
	$xslt->importStylesheet( $XSL ); 
	foreach($PARAMS->childNodes as $param){		
		$xslt->setParameter(null, $param->nodeName, $param->nodeValue);		
	}
	print $xslt->transformToXML( $XML );

?>