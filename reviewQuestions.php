<?php

include 'settings.php';
include 'MediaWiki_Api/MediaWiki_Api_functions.php';
include 'MediaWiki-template-filler/templates/TemplateBase.php';
include 'helperfunctions.php';
$settings['cookiefile'] = "cookies.tmp";

echo "Calling the Api \n";

$headers  = array(
	  "Accept: application/json",
	  "Authorization: Bearer 6b4a536218d7b3802bf7c4e84ef66096c2d3bd02",
);

$json = httpRequest("dev.ammochamber.com/api/question_of_the_day", "", false, 0, $headers);

$apiData = json_decode($json, true);

if(!errorHandlerAmmoApi( $apiData ))
      die();

$reviewTemplate = new TemplateBase( "Review Question" );

$questions = array();
foreach($apiData as $apiDataFields){
    $key_values = questionApiToTemplate($apiDataFields);

    $Qtemplate = new TemplateBase( "Question" );

    $Qtemplate->makeTextFromKeyValue( $key_values );

    $questions[] = $Qtemplate->getText();
}

$reviewTemplate->makeTextFromKeyValue( array( 'Date' => date("F j, Y"), 'Questions' => implode($questions, ',') ) );

$mwApi = new MediaWikiApi($settings['siteUrl']);

echo "Logging in to the wiki\n";

if( $mwApi->login( $settings['user'], $settings['password'] ) ){
    echo "SUCCESS\n";
}

echo "Exporting page\n";

if($mwApi->createPage( "Review:Question of the Day for " . date("F j, Y"), $reviewTemplate->getText() ) == 1 )
    echo "SUCCESS\n";

