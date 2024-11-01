<?php
/*
Plugin Name: Web Music
Plugin URI: http://www.minyatur.com.ar/web-music/
Description: Put in your post players Goear, Ijigg, Evoca, MyBloop, Deezer, Spool.fm to play music or recordings. To use: [mus]url[/mus].
Version: 1.2
Author: Ignacio Cuppi
Author URI: http://www.minyatur.com.ar
*/
function spool($url){
	preg_match('/#track\/(.*)\/(.*)\/(.*)/i', $url, $item);
	
	$emb='<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="200" height="23" id="miniPlayer" align="middle"> <param name="allowScriptAccess" value="sameDomain" /> <param name="movie" value="http://media.spool.fm/player/miniPlayer.swf" /> <param name="FlashVars" value="songid='.substr($item[1], 2).'"> <param name="quality" value="high" /><param name="bgcolor" value="#ffffff" /><embed src="http://media.spool.fm/player/miniPlayer.swf" quality="high" bgcolor="#ffffff" FlashVars="songid='.substr($item[1], 2).'" width="200" height="23" name="miniPlayer" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /> </object>';
	
	return $emb;
	
}


function deezer($url){
	preg_match('/track\/(.*)/i', $url, $item);
	
	$emb='<object width="220" height="55"><param name="movie" value="http://www.deezer.com/embedded/small-widget-v2.swf?idSong='.$item[1].'&colorBackground=0x555552&textColor1=0xFFFFFF&colorVolume=0x39D1FD&autoplay=0"></param><embed src="http://www.deezer.com/embedded/small-widget-v2.swf?idSong='.$item[1].'&colorBackground=0x525252&textColor1=0xFFFFFF&colorVolume=0x39D1FD&autoplay=0" type="application/x-shockwave-flash" width="220" height="55"></embed></object>';
	
	return $emb;
	
}

function goear($url){
	preg_match('/listen\/(.*)\/(.*)/i', $url, $item);
	
	$emb='<object width="353" height="132"><embed src="http://www.goear.com/files/external.swf?file='.$item[1].'" type="application/x-shockwave-flash" wmode="transparent" quality="high" width="353" height="132"></embed></object>';
	
	return $emb;
	
}

function ijigg($url){
	preg_match('/songs\/(.*)/i', $url, $item);
	
	$emb='<object width="315" height="80"><param name="movie" value="http://www.ijigg.com/jiggPlayer.swf?songID='.$item[1].'&Autoplay=0"><param name="scale" value="noscale" /><param name="wmode" value="transparent"><embed src="http://www.ijigg.com/jiggPlayer.swf?Autoplay=0&songID='.$item[1].'" width="315" height="80"  scale="noscale" wmode="transparent"></embed></object>';
	
	return $emb;
	
}

function evoca($url){
	preg_match('/rid=(.*)/i', $url, $item);
	
	$emb='<embed src="http://www.evoca.com/evocaPlayer/evocaPlayer.swf?id='.$item[1].'&teu=" wmode="transparent" allowscriptaccess="never" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" height="85" width="90">';
	
	return $emb;
	
}

function mybloop($url){
	preg_match('/go\/(.*)/i', $url, $item);
	
	$emb='<object width="200" height="95"><param name="movie" value="http://www.mybloop.com/pm/e/'.$item[1].'/3/0/0"></param><embed src="http://www.mybloop.com/pm/e/'.$item[1].'/3/0/0" type="application/x-shockwave-flash" width="200" height="95"></embed></object>';
	
	return $emb;
	
}


function mus_info($url){
	preg_match("/^(http:\/\/)?([^\/]+)/i", $url, $uri);
    $host = $uri[2];
	
	switch ($host){
		case "spool.fm":
		$repro= spool($url);
		break;
		
		case "www.deezer.com":
		$repro= deezer($url);
		break;
		
		case "www.goear.com":
		$repro= goear($url);
		break;
		
		case "www.ijigg.com":
		$repro= ijigg($url);
		break;
		
		case "www.evoca.com":
		$repro= evoca($url);
		break;
		
		case "mybloop.com":
		$repro= mybloop($url);
		break;
	}	
	
	return $repro;
}

function mus_insertar($url){
	
	if ($url == ""){
		$html= "No Embebible";
	}else{		
		$html = mus_info($url);
	}
	
	return $html;
	
}

function insertar($salida) {
	global $wpv_options;

	$pattern = '/([\[|<]mus\s*(.*?)[\]|>](.*?)[\[|<]\/mus[\]|>])/i';

	// Chequeo el post la existencia de [mus] [/mus]
	if (preg_match_all ($pattern, $salida, $matches)) {

		for ($i = 0; $i < count($matches[0]); $i++) {
			$htmlcode = '';
			$url_rep = $matches[3][$i];

			$htmlcode .= '<div align="center">';
			$htmlcode .= mus_insertar($url_rep);
			$htmlcode .= "<br><sup><a href='http://www.minyatur.com.ar/web-music/'>Web Music 1.2</a></sup>";	
			$htmlcode .= '</div>';

			$salida = str_replace($matches[0][$i], $htmlcode, $salida);
		}
	}
    
	return $salida;
}

add_action('the_content', 'insertar');


?>