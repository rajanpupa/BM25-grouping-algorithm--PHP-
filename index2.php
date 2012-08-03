<?php
/*
 * TO   MAKE    this code usable for your own system, first goto DataBase/connection.php and put your database information
 * second , edit the sql in the Document collection.php takeDocumentsFromDatabase();
 * 
 */
        include_once ('Collection/DocumentCollection.php');
        $dc=new DocumentCollection();
        $dc->clearPreviousRecords();
        //die();
        $dc->takeDocumentsFromDatabase();//sql should be corrected to select articles
       // $dc->printAllDocuments();die();
        //print_r($dc->getScores(1));die();
//        $result=$dc->getRelatedDocumentsId(2);
//        echo "<br/>finall result is :";
        //print_r($dc->getBM25Scores(15));
//        print_r($result);
        $dc->storeRelationOfAll();
        $dc->updateGroupIdOfArticlesAtLast();
        //echo $dc->getGroupId(2);
?>
