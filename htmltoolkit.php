<?php
function printErrorPage($error){
	echo printHtmlPage(printCentered("<h5 class=\"error\">" . $error . "</h5>"));
}

function printSuccessPage($text){
	echo printHtmlPage(printCentered("<h5 class=\"success\">" . $text . "</h5>"));
}

function printWrongPassPage($error){
	echo printHtmlPage(printCentered(PrintErrorPage("Wrong password.")));
}

function dashboardRedirect(){
	header("Location: http://moger.net/gameboard/dashboard.php");
	// if anyone knows how to redirect to dashboard.php without specifying the whole url, please do a pull request!
}

function printHtmlPage($text){
	return "<html><body>" . $text . "</body></html>";
}

function printUrl($url, $text){
	return "<p><a href=\"" . $url . "\">" . $text . "</a></p>";
}

function printCentered($inner){
	return "<center>" . $inner . "</center>";
}
?>