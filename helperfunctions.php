<?php

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



