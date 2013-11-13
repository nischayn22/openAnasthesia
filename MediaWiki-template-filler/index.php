<?php
include 'TemplateBase.php';

$personTemplate = new TemplateBase( "Person" );

$key_values = array(
	    'name' => 'Nischay Nahata',
	    'codes in' => 'C++, PHP...',
);

$personTemplate->makeTextFromKeyValue( $key_values );

echo "The filled template is \n\n";

echo $personTemplate->getText();

echo "\n\n Now export it to MediaWiki using the api. \n\n";
