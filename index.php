<?php

        include_once('DataBase/database_class.php');
		///the above info should be fetched from a db and stored in parallel arrays
            $db=new DataBase();
            $sql="SELECT id, description FROM manual_news";
            $result=$db->execute_query_return_result($sql);
            $i=0;
            $id=array();
            $docs=array();
            while($row=mysql_fetch_array($result)){
                    $id[$i]=$row['id'];
                    $docs[$row['id']]=$row['description'];//ie id=>description
                    //echo $docs[$i];
                    $i++;
            }
            ////////////////////////////////////////////////////////

            $topScores=array();
            $results=array();
            $threshold=0;
            $i=0;
            
          
//            $topScores=prnt();
//            print_r($topScores);
//              function prnt(){
//                $topScores=array();
//                array_push($topScores, 2);
//            array_push($topScores, 3);
//            array_push($topScores, 5);
//            echo count($topScores);
//           // print_r($topScores);
//            return $topScores;
//            }
//            die("<br/>Thats all for now");
            foreach($docs as $query){

                    preg_match_all('/\w+/', $query, $matches);
                    $queryTerms = $matches[0];//array of words
                   // print_r($queryTerms);
                   // die();
                    echo "We are querying ".$query;


                    $collection = array('terms' => array(), 'length' => 0, 'documents' => array());
                            foreach($docs as $docID => $doc) {
                                            preg_match_all('/\w+/', $doc, $matches);
                                            // store the document length
                                            $collection['documents'][$docID] = count($matches[0]);

                                            foreach($matches[0] as $match) {
                                                            if(!isset($collection['terms'][$match])) {
                                                                            $collection['terms'][$match] = array();
                                                            }
                                                            if(!isset($collection['terms'][$match][$docID])) {
                                                                            $collection['terms'][$match][$docID] = 0;
                                                            }
                                                            $collection['terms'][$match][$docID]++;
                                                            $collection['length']++;
                                            }        
                            }
                            $collection['averageLength'] = $collection['length']/count($collection['documents']);


                                    echo "The resulting scores are:<br/>";
                                    $results[$i] = bm25Weight($queryTerms, $collection);
                                    arsort($results[$i]);
                                 print_r( $results[$i]);
                                 die();
                                   // array_push($topScores, addToTopScores($results[$i]));
                                    $ar= addToTopScores($results[$i]);
                                    foreach($ar as $variable){
                                        array_push($topScores, $variable);
                                    }
                                    foreach ($results[$i] as $j=>$result):
                                            echo "<br>".$j.' =>'.$result;
                                    endforeach;
                            $i=$i+1;
            }//endforeach;
                            $threshold=findThreshold($topScores);
                            echo "<br/>Threshold is ".$threshold."<br/>";
                            //print_r($topScores);
                            
                            function addToTopScores($values)
                            {
                                $topScores=array();
                                    foreach($values as $value):
                                            if($value>20)
                                                    array_push($topScores,$value);
                                                    //$topScores.push($value);
                                            else{
                                                    return $topScores;
                                                //return (array_sum($topScores)/count($topScores));
                                            }
                                    endforeach;
                                    return $topScores;
                                   //return array_sum(($topScores)/count($topScores));
                            }



                            function findThreshold($topScore)
                            {
                                    $sum=(double)0.0;

                                    arsort($topScore);
                                    print_r($topScore);
                                    $MAX=5;
                                    if(count($topScore)<$MAX)
                                            $MAX=count($topScore);
                                    echo "<br/>The count is ".$MAX;
                                    $count=0;
                                    foreach($topScore as $a)
                                    {
                                            $sum=$sum+$a;
                                            $count=$count+1;
                                            if($count>=$MAX){
                                                break;
                                            }
                                            echo "<br/>".$count."the sum is ".$sum;
                                    }
                                    
                                    if($MAX!=0)
                                            $threshold=0.4 * $sum/$MAX;
                                       // print_r("The threshold is ".$threshold);
                                    return $threshold;
                            }




            //this is the main function to relate articles , pass a document as a query and the collection
            //of documents 
            //$tfWeight = 1, $dlWeight = 0.5
            function bm25Weight($query, $collection, $tfWeight = 1.2, $dlWeight = 0.75) {
            $docScores = array();
            $count = count($collection['documents']);
            foreach($query as $term) {
                    $df = count($collection['terms'][$term]);
                    foreach($collection['terms'][$term] as $docID => $tf) {
                            $docLength = $collection['documents'][$docID];
                            $idf = log($count/$df);
                            $num = ($tfWeight + 1) * $tf;
                            $denom = $tfWeight
                                    * ((1 - $dlWeight) + $dlWeight *
                                            ($docLength / $collection['averageLength']))
                                    + $tf;
                            $score = $idf * ($num/$denom);
                            $docScores[$docID] = isset($docScores[$docID]) ?
                                                    $docScores[$docID] + $score : $score;
                    }
            }
            return $docScores;
    }




            //var_dump($results);


?>