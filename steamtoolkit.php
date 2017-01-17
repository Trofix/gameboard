<?php
include "config.php";

function getSteamLongID($customURL){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, "http://api.steampowered.com/ISteamUser/ResolveVanityURL/v0001/?" . http_build_query(array('key' => $steam, 'vaintyurl' => $customURL)));
	var_dump(http_build_query(array('key' => $steam, 'vaintyurl' => $customURL)));
	$content = curl_exec($ch);
	var_dump($steam);
	var_dump($content);
	$responseObject = json_decode($content);
	if ($responseObject === NULL) {
		return 1; // response wasn't json
	}
	$responseArray = (array)$responseObject;
	$success = $responseArray["response"]["success"];
	if($success == 42){
		return 2; // user not found
	} elseif ($success == 1) {
		return $responseArray["response"]["steamid"]; // steamid64 value
	} else {
		return 3; // unknown error
	}
}

function getOwnedGames($steamID){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, "http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?" . http_build_query(array('key' => $steamapikey, 'steamid' => $steamID, 'format' => 'json')));
	$content = curl_exec($ch);
	$responseObject = json_decode($content);
	if ($responseObject === NULL) {
		return 1; // response wasn't json
	}
	$responseArray = (array)$responseObject;
	$game_count = $responseArray["response"]["game_count"];
	if($game_count == 0){
		return 2; // no games
	}
	$games = $responseArray["response"]["games"];
	$gameidarr = array();
	foreach ($games as $gamearr) {
		array_push($gameidarr, $gamearr["appid"]);
	}
	return $gameidarr;
}

function getGameName($appid){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, "http://api.steampowered.com/ISteamUserStats/GetSchemaForGame/v2/?" . http_build_query(array('key' => $steamapikey, 'appid' => $appid, 'format' => 'json')));
	$content = curl_exec($ch);
	$responseObject = json_decode($content);
	if ($responseObject === NULL) {
		return 1; // response wasn't json
	}
	$responseArray = (array)$responseObject;
	if($responseArray == array()){
		return 2; // game doesn't exist
	}
	return $responseArray["game"]["gameName"];
}

function getAchievedAchievementsCount($appid, $steamid){ //NOTE: I needed to use -1 and -2 here, because Steam may send 1 or 2 as response, and error handling would confuse.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, "http://api.steampowered.com/ISteamUserStats/GetSchemaForGame/v2/?" . http_build_query(array('key' => $steamapikey, 'appid' => $appid, 'format' => 'json')));
	$content = curl_exec($ch);
	$responseObject = json_decode($content);
	if ($responseObject === NULL) {
		return -1; // response wasn't json
	}
	$responseArray = (array)$responseObject;
	$success = $responseArray["playerstats"]["success"];
	if ($success == false){
		return -2; //User doesn't have this game.
	}
	$achievements = $responseArray["playerstats"]["achievements"];
	$achievedAchievements = 0;
	foreach ($achievements as $achievement) {
		if($achievement["achieved"] == 1){
			$achievedAchievements++;
		}
	}
	return $achievedAchievements;
}
?>