<?php

function keywordApiToTemplate($apiData){
         $key_values = array();
         $key_values['Sources'] = $apiData['keyword_sources'];

         $pubmedIds = array();
         foreach($apiData['field_pubmed'] as $pubmed){
                $pubmedIds[] = $pubmed['id'];
         }
         $key_values['PubMed IDs'] = implode($pubmedIds, ';');

         $key_values['Years'] = '';
         foreach($apiData['keyword_asked_history'] as $asked ){
                  $askedTemplate = new TemplateBase( "Asked" );
                  $askedTemplate->makeTextFromKeyValue(array( 'Percentage' => $asked['percentage'], 'Year' => $asked['year']));
                  $key_values['Years'] .= $askedTemplate->getText();
         }

         $key_values['Images'] = '';
         foreach($apiData['keyword_image'] as $image){
                  $galleryTemplate = new TemplateBase( "Gallery" );
                  $galleryTemplate->makeTextFromKeyValue(array( 'Image' => $image['url'], 'Caption' => $image['title'] ));
                  $key_values['Images'] .= $galleryTemplate->getText();
         }

         $similarKeywords = array();
         foreach($apiData['keyword_similar'] as $similar){
                  $similarKeywords[] = $similar['value'];
         }
         $key_values['Similar keywords'] = implode($similarKeywords, ';');

         $key_values['Category1'] = $apiData['keyword_category_1'];
         $key_values['Category2'] = $apiData['keyword_category_2'];

         $relatedKeywords = array();
         foreach($apiData['keyword_related'] as $related){
                  $relatedKeywords[] = $related['title'];
         }
         $key_values['Related keywords'] = implode($relatedKeywords, ';');

         $seeAlsoKeywords = array();
         foreach($apiData['keyword_see_also'] as $seeAlso){
                  $seeAlsoKeywords[] = $seeAlso['title'];
         }
         $key_values['See also'] = implode($seeAlsoKeywords, ';');

         return $key_values;
}

function questionApiToTemplate($apiData){
	 $key_values = array();
         $key_values['Question'] = $apiData['title'];
         $key_values['Answer1'] = $apiData['question_answer'][0]['value'];
         $key_values['Answer2'] = $apiData['question_answer'][1]['value'];
         $key_values['Answer3'] = $apiData['question_answer'][2]['value'];
         $key_values['Answer4'] = $apiData['question_answer'][3]['value'];
         $correctAnswer = 1;
         foreach($apiData['question_answer'] as $answer){
                if($answer['correct'] != '1')
                           $correctAnswer++; 
                else
                        break;
         }
         $key_values['CorrectAnswerID'] = $correctAnswer;
         $key_values['Explanation'] = $apiData['question_feedback'][0];
         $pubmedIds = array();
         foreach($apiData['question_pubmed'] as $pubmed){
                $pubmedIds[] = $pubmed['id'];
         }
         $key_values['PubMed IDs'] = implode($pubmedIds, ';');
	 return $key_values;
}


function errorHandlerAmmoApi( $json ){
         if(isset($dataFields['error'])) {
                echo "Error:" . $dataFields['error'] . " Description:" . $dataFields['error_description'] . "\n";
                return false;
         }
	 return true;
}



