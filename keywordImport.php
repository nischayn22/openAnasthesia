<?php

include 'settings.php';
include 'MediaWiki_Api/MediaWiki_Api_functions.php';
include 'MediaWiki-template-filler/templates/TemplateBase.php';
include 'helperfunctions.php';

$settings['cookiefile'] = "cookies.tmp";

if(empty($settings['auth_token'])) {
        echo "auth_token in settings can't be empty. Dying now...\n";
        die();
}

echo "Calling the Api. This may take few seconds... Meanwhile read a random article on wikipedia at http://wikipedia.org/wiki/Special:Random\n";

$headers  = array(
	  "Accept: application/json",
	  "Authorization: Bearer " . $settings['auth_token'],
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

$copiedPages = array();
foreach($apiData as $keyword) {
        $key_values = keywordApiToTemplate($keyword);
        $keywordTemplate = new TemplateBase( "ABA keyword" );
        $keywordTemplate->makeTextFromKeyValue( $key_values );

        echo "Exporting page with title ABA:" . $keyword['title'] . "\n";

        $free_text = $keyword['keyword_definition'];
        $content = $free_text . $keywordTemplate->getText();
        if($mwApi->editPage( "ABA:" . $keyword['title'] , $content ) == 1 )
        {
            echo "SUCCESS\n";
            $copiedPages[] = "ABA:" . $keyword['title'];
        }

        // Uncomment both lines when debugging
        //echo "If you are seeing this, you are debugging..\n";
        //break;
}

if(!isset($settings['ABA_namespace'])) {
        echo "No namespace provided for deleting pages..Aborting deletion\n";
        die();
}

$pagesInABA_keyed = $mwApi->listPageInNamespace( $settings['ABA_namespace'] );
$pagesInABA = array();
foreach($pagesInABA_keyed as $page){
        $pagesInABA[] = (string)$page['title'];
}

$pagesToDel = array_diff($pagesInABA, $copiedPages);

foreach($pagesToDel as $delTitle) {
       echo "Deleting $delTitle\n";
       $mwApi->deleteByTitle($delTitle);
}