<?php

include 'settings.php';
include 'MediaWiki_Api/MediaWiki_Api_functions.php';
include 'MediaWiki-template-filler/templates/TemplateBase.php';
include 'helperfunctions.php';
$settings['cookiefile'] = "cookies.tmp";

echo "Calling the Api. This may take few seconds... Meanwhile read a random article on wikipedia at http://wikipedia.org/wiki/Special:Random\n";

$headers  = array(
	  "Accept: application/json",
	  "Authorization: Bearer 6b4a536218d7b3802bf7c4e84ef66096c2d3bd02",
);

$json = httpRequest("dev.ammochamber.com/api/keyword", "", false, 0, $headers);

$apiData = json_decode($json, true);

if(!errorHandlerAmmoApi( $apiData ))
      die();

echo count($apiData) . " many keywords found. Exporting one by one.\n";

$mwApi = new MediaWikiApi($settings['siteUrl']);

echo "Logging in to the wiki\n";

if( $mwApi->login( $settings['user'], $settings['password'] ) ){
    echo "SUCCESS\n";
}

foreach($apiData as $keyword){
        $key_values = keywordApiToTemplate($keyword);
        $keywordTemplate = new TemplateBase( "ABA Keyword" );
        $keywordTemplate->makeTextFromKeyValue( $key_values );

        echo "Exporting page with title ABA keyword:" . $keyword['title'] . "\n";

        if($mwApi->editPage( "ABA keyword:" . $keyword['title'] , $keywordTemplate->getText() ) == 1 )
            echo "SUCCESS\n";

        // Uncomment both lines when not debugging
        //echo "If you are seeing this, you are debugging..\n";
        //break;
}


