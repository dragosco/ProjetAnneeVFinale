<?php
Class SimulationDureeGlobale extends SimulationMC {
  // var $visitedList;

  function calculate()
  {
		$max = 0;

    $allPaths = $this->projet->listeParallelPaths;

    foreach ($this->projet->listeTaches as $tache)
    {
  	  $max = $max + $tache->loi->valeurMax;
		}

    $nbCat = floor(($max / $this->largeurIntervalle) + 1);

    for($cc = 0; $cc <= $nbCat; $cc++)
    {
    	$distrib[$cc] = 0;
    }

    for($i=0; $i<$this->nbEchantillons; $i++)
    {
      $valeursGenerees = array();
      foreach ($this->projet->listeTaches as $tache)
      {
        $valeursGenerees[$tache->id] = $tache->loi->generate();
      }

      $maxSomme = 0;
      $maxPath = array();
      foreach ($allPaths as $path) {
        $somme = 0;
        foreach ($path as $tache) {
          $somme = $somme + $valeursGenerees[$tache->id];
        }
        if($somme > $maxSomme) {
          $maxSomme = $somme;
          $maxPath = $path;
        }
      }

      // foreach ($maxPath as $tache)
      // {
    	//   $max = $max + $tache->loi->valeurMax;
  		// }
      //
      // $nbCat = floor(($max / $this->largeurIntervalle) + 1);
      //
      // for($cc = 0; $cc <= $nbCat; $cc++)
      // {
      // 	$distrib[$cc] = 0;
      // }

    	$index = floor($maxSomme/$this->largeurIntervalle);
    	$distrib[$index]++;
    }


		// foreach ($this->projet->listeTaches as $tache)
    // {
  	//   $max = $max + $tache->loi->valeurMax;
		// }
    //
    // $nbCat = floor(($max / $this->largeurIntervalle) + 1);
    //
    // for($cc = 0; $cc <= $nbCat; $cc++)
    // {
    // 	$distrib[$cc] = 0;
    // }
    //
    // for($i=0; $i<$this->nbEchantillons; $i++)
    // {
    // 	$ech = 0;
    //   foreach ($this->projet->listeTaches as $tache)
    //   {
    //     $ech = $ech + $tache->loi->generate();
    //   }
    //
    // 	$index = floor($ech/$this->largeurIntervalle);
    // 	$distrib[$index]++;
    // }

    $this->resultatCalcul = new ResultatSimulation($distrib, $this->nbEchantillons, $this->largeurIntervalle);
    // echo 'count($allPaths) ' . count($allPaths) . ' ';

    // echo '(';
    // for ($i=0; $i < count($allPaths); $i++) {
    //   // echo 'count($allPaths) ' . count($allPaths) . ' ';
    //   // echo 'allPaths[$i] ' . $allPaths[$i] . ' ';
    //   for ($j=0; $j < count($allPaths[$i]); $j++) {
    //     if($j == 0) {
    //       echo '(';
    //     }
    //   //   // echo 'count($allPaths[$i]) ' . count($allPaths[$i]) . ' ';
    //     echo $allPaths[$i][$j]->nom . ', ';
    //   }
    //   echo ') ';
    //   echo "\n";
    //   // echo $allPaths[$i]->nom . ', ';
    // }
    // echo ') ';
    // echo "\n";

    return $this->resultatCalcul;
  }

  // function getParallelPaths() {
  //   // $this->visitedList = array();
  //   // for ($i=0; $i < count($this->projet->listeTaches); $i++) {
	// 	// 	$this->visitedList[$this->projet->listeTaches[$i]->id] = 0;
	// 	// }
  //
  //   $allPaths = $this->getAllSubPaths();
  //   // $nonRepeatedSubPaths = $this->removeRepeatedPaths($allPaths);
  //
  //   return $allPaths;
  // }
  //
  // function removeRepeatedPaths($allPaths) {
  //   return $allPaths;
  // }
  //
  // function getAllSubPaths() {
  //   $allSubPaths = array();
  //   $start = $this->getTaskById(1);
  //   // $beforeList = array();
  //   // array_push($beforeList, $start);
  //
  //   // $allPaths = $this->getAllSubPathsFromTask($start, $beforeList);
  //   $allPathsFromStartToEnd = $this->getAllPathsFromStartToEnd($start);
  //
  //   // for
  //
  //   return $allPathsFromStartToEnd;
  // }
  //
  // function getAllPathsFromStartToEnd($task) {
  //   $resultList = array();
  //   $afterList = array();
  //   // echo "\n";
  //
  //   // array_push($beforeList, $task);
  //   if (count($task->successeurs) == 0) {
  //     // echo $task->nom . ' ';
  //     // echo '$resultList count before end ' . count($resultList) . ' ';
  //     array_push($afterList, $task);
  //     array_push($resultList, $afterList);
  //     return $resultList;
  //     // return array_push($resultList, $beforeList);
  //     // array_push($beforeList, $task);
  //     // echo '$resultList count after end ' . count($resultList) . ' ';
  //     // echo 'contenu list end ' . $resultList[0]->nom . ' ';
  //   } else if(count($task->successeurs) == 1) {
  //     // echo $task->nom . ' ';
  //     $id = $task->successeurs[0]->id;
  //     // echo '$id ' . $id . ' ';
  //     $nextTask = $this->getTaskById($id);
  //     $partialList = $this->getAllPathsFromStartToEnd($nextTask);
  //     while(count($partialList) > 0) {
  //       $firstElementList = array_shift($partialList);
  //       // echo '$firstElementList[0] ' . $firstElementList[0]->nom . ' ';
  //       array_unshift($firstElementList, $task);
  //       array_push($resultList, $firstElementList);
  //     }
  //     // array_unshift($resultList, $task);
  //     return $resultList;
  //   } else {
  //     for ($i=0; $i < count($task->successeurs); $i++) {
  //       // echo $task->nom . ' ';
  //       // echo $this->getAllPathsFromStartToEnd($this->getTaskById($task->successeurs[$i]->id));
  //       $id = $task->successeurs[$i]->id;
  //       // echo '$id ' . $id . ' ';
  //       $nextTask = $this->getTaskById($id);
  //       // echo '$nextTask ' . $nextTask->nom . ' ';
  //       // echo '$afterList count before ' . count($afterList) . ' ';
  //       $partialList = $this->getAllPathsFromStartToEnd($nextTask);
  //       // echo '$afterList count after ' . count($afterList) . ' ';
  //       // echo '$resultList count before ' . count($resultList) . ' ';
  //       while(count($partialList) > 0) {
  //         $firstElementList = array_shift($partialList);
  //         array_unshift($firstElementList, $task);
  //         array_push($resultList, $firstElementList);
  //         // array_unshift($partialList[$j], $task);
  //         // array_push($resultList, $partialList[$j]);
  //       }
  //       // array_unshift($partialList, $task);
  //       // array_push($resultList, $partialList);
  //       // $resultList = $afterList;
  //       // echo '$resultList count after ' . count($resultList) . ' ';
  //       // echo 'contenu list ' . $resultList[0]->nom . ' ';
  //     }
  //
  //     return $resultList;
  //   }
  // }
  //
  // function getAllSubPathsFromTask($task, $beforeList) {
  //   $subPaths = array();
  //   $listWithCurrentTask = array();
  //   $firstListElement = array();
  //   array_push($beforeList, $task);
  //   // echo 'task ' . $task->nom . ' ';
  //
  //   // if($this->visitedList[$task->id] == 0) {
  //     // echo ' ' . $task->nom . ' ';
  //     for ($i=0; $i < count($task->successeurs); $i++) {
  //     // echo '$task->successeurs[$i]->id ' . $task->successeurs[$i]->id . ' ';
  //       // echo ' ' . $task->successeurs[$i]->nom . ' ';
  //       // echo 'entrou loop ';
  //       // array_push($listWithCurrentTask, $task);
  //
  //       $subPathsSuc = $this->getAllSubPathsFromTask($this->getTaskById($task->successeurs[$i]->id), $beforeList);
  //       //pegar primeiro elemento de subPathsSuc
  //       if(!is_null($subPathsSuc) and count($subPathsSuc) > 0) {
  //         $firstListElement = $subPathsSuc[0];
  //
  //         // for ($i=0; $i < count($firstListElement); $i++) {
  //         //   // echo 'count($allPaths) ' . count($allPaths) . ' ';
  //         //   // echo 'allPaths[$i] ' . $allPaths[$i] . ' ';
  //         //   // for ($j=0; $j < count($firstListElement[$i]); $j++) {
  //         //     if($i == 0) {
  //         //       echo '(';
  //         //     }
  //         //     // echo 'count($allPaths[$i]) ' . count($allPaths[$i]) . ' ';
  //         //     echo $firstListElement[$i]->nom . ', ';
  //         //   // }
  //         // }
  //         // echo ') ';
  //         // echo "\n";
  //       }
  //       //fazer array_unshift $task no primeiro (que eh uma lista)
  //       $newSubList = array();
  //       while(count($firstListElement) > 0) {
  //         $listWithCurrentTask = $firstListElement;
  //         array_unshift($listWithCurrentTask, $task);
  //         //fazer array_unshift dessa lista em $subPathsSuc
  //         array_push($newSubList, $listWithCurrentTask);
  //         array_pop($firstListElement);
  //       }
  //       //
  //       $merge = array_merge($newSubList, $subPathsSuc);
  //       $subPaths = array_merge($subPaths, $merge);
  //       // $subPaths = array_merge($listTask, $subPathsSuc);
  //
  //     }
  //     if (count($task->successeurs) == 0) {
  //       array_push($firstListElement, $task);
  //       array_push($subPaths, $firstListElement);
  //     }
  //     // $this->visitedList[$task->id] = 1;
  //   // }
  //
  //   return $subPaths;
  // }
  //
  // function getTaskById($id) {
  //   $tache = NULL;
  //   // echo 'entrou getTaskById';
  //   // echo '$id ' . $id;
  //
	// 	for ($i=0; $i < count($this->projet->listeTaches); $i++) {
  //     // echo '$i ' . $i;
  //     // echo 'tache id ' . $this->projet->listeTaches[$i]->id;
	// 		if($this->projet->listeTaches[$i]->id == $id) {
	// 			$tache = $this->projet->listeTaches[$i];
  //       // echo 'getTaskById id ' . $id;
  //       // echo 'getTaskById nom ' . $tache->nom;
	// 			$i = count($this->projet->listeTaches);
	// 		}
	// 	}
  //
	// 	return $tache;
  // }
}
?>
