<?php
require("Task.php");
require("SimulationChargeGlobale.php");
require("SimulationCoutGlobal.php");
require("SimulationDureeGlobale.php");
require("SimulationMargeFinanciere.php");
/*
 * Project
 *
 * Classe projet
 */
class Project
{
	var $id;
	var $nom;
	var $tacheDebut;
	var $tacheFin;
	var $listeTaches;
	var $listeRessources;
	var $listeParallelPaths;
	var $listeSimulateurs;
	var $bdd;

	private static $_instance = null;

	function __construct($id, $nom, $tacheDebut, $tacheFin)
	{
		$this->id = $id;
		$this->nom = $nom;
		$this->tacheDebut = $tacheDebut;
		$this->tacheFin = $tacheFin;
		$this->listeRessources = array();
		$this->listeParallelPaths = array();
		$this->listeTaches = array();
		$this->listeSimulateurs = array();

		$this->bdd = getBdd();

		// $this->bdd->query('INSERT INTO `projet` (`id`, `nomp`, `description`) VALUES (NULL, $nom, `desc`)');
		$this->id = 1; //$this->bdd->lastInsertId();
		$this->loadListeRessources();
		$this->loadListeTaches();
		$this->loadListeSimulateurs();
	}

	function loadListeTaches()
	{
		$this->listeTaches = array();
		// On récupère tout le contenu de la table tâche
		$reponse = $this->bdd->query('SELECT * FROM tache');

		while ($donnees = $reponse->fetch())
		{
			$tache = new Task($donnees['id'], $donnees['nom'], $this);

			$tache->loadLoi();
			$tache->loadRessource();
			$tache->loadPredecesseurs();
			$tache->loadSuccesseurs();
			array_push($this->listeTaches, $tache);

			if($donnees['nom'] == 'Start') {
				$this->tacheDebut = $tache;
			}
		}

		$this->listeParallelPaths = $this->getParallelPaths();
	}

	function loadListeSimulateurs()
	{
		// On récupère tout le contenu de la table simulateur
		$reponse = $this->bdd->query('SELECT * FROM simulateur');

		$this->listeSimulateurs = array();

		$simulateur = NULL;
		while ($donnees = $reponse->fetch())
		{
			$simulateur = NULL;
			if($donnees['typeSimulateur'] == SimulateurEnum::ChargeGlobale) {
				$simulateur = new SimulationChargeGlobale($donnees['typeSimulateur'], $donnees['nbEchantillons'], $donnees['largeurIntervalle'], $this);
			} else if ($donnees['typeSimulateur'] == SimulateurEnum::CoutGlobal) {
				$simulateur = new SimulationCoutGlobal($donnees['typeSimulateur'], $donnees['nbEchantillons'], $donnees['largeurIntervalle'], $this);
			} else if ($donnees['typeSimulateur'] == SimulateurEnum::DureeGlobale) {
				$simulateur = new SimulationDureeGlobale($donnees['typeSimulateur'], $donnees['nbEchantillons'], $donnees['largeurIntervalle'], $this);
			} else if ($donnees['typeSimulateur'] == SimulateurEnum::MargeFinanciere) {
				$simulateur = new SimulationMargeFinanciere($donnees['typeSimulateur'], $donnees['nbEchantillons'], $donnees['largeurIntervalle'], $this);
			}
			$simulateur->probabilite = $donnees['probabilite'];
			$simulateur->charge = $donnees['charge'];

			array_push($this->listeSimulateurs, $simulateur);
		}
	}
	function loadListeRessources() {
		$reponse = $this->bdd->query('SELECT * FROM ressource');

		while ($donnees = $reponse->fetch())
		{
			$ressource = new Ressource($donnees['id'], $donnees['nom'], $donnees['cout']);

			array_push($this->listeRessources, $ressource);
		}
	}

	public static function getInstance() {

		if(is_null(self::$_instance)) {
			self::$_instance = new Project(1, 'Projet', null, null);
		}

		return self::$_instance;
	}

	public function __get($property) {
    if (property_exists($this, $property)) {
        return $this->$property;
    }
  }

  public function __set($property, $value) {
    if (property_exists($this, $property)) {
        $this->$property = $value;
    }
  }

	public function addTask($nom, $predecesseurs, $successeurs, $idRessource, $loi)
	{
		$this->bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$q = $this->bdd->prepare("INSERT INTO tache (nom, idRessource, idProjet)
										VALUES (:nom, :idRessource, :idProjet)");
		$q->bindParam(':nom', $nom, PDO::PARAM_STR, 50);
		$q->bindParam(':idRessource', $idRessource, PDO::PARAM_INT);
		$q->bindParam(':idProjet', $this->id, PDO::PARAM_INT);
		$q->execute();

		$idTache = $this->bdd->lastInsertId();

		$q1 = $this->bdd->prepare("INSERT INTO loi (nom, idTache, valeurMin, valeurMax)
										VALUES (:nomLoi, :idTache, :valeurMin, :valeurMax)");
		$q1->bindParam(':nomLoi', $loi['nom'], PDO::PARAM_STR, 50);
		$q1->bindParam(':idTache', $idTache, PDO::PARAM_INT);
		$q1->bindParam(':valeurMin', $loi['valeurMin'], PDO::PARAM_INT);
		$q1->bindParam(':valeurMax', $loi['valeurMax'], PDO::PARAM_INT);
		$q1->execute();

		$idLoi = $this->bdd->lastInsertId();

		if($loi['nom'] == LoiEnum::Beta) {
			$q3 = $this->bdd->prepare("INSERT INTO loiBeta (id, w, v) VALUES (:id, :w, :v)");
			$q3->bindParam(':id', $idLoi, PDO::PARAM_INT);
			$q3->bindParam(':w', $loi['w'], PDO::PARAM_STR, 50);
			$q3->bindParam(':v', $loi['v'], PDO::PARAM_STR, 50);
			$q3->execute();
		} else if($loi['nom'] == LoiEnum::Triangulaire) {
			$q3 = $this->bdd->prepare("INSERT INTO loiTriangulaire (id, c) VALUES (:id, :c)");
			$q3->bindParam(':id', $idLoi, PDO::PARAM_INT);
			$q3->bindParam(':c', $loi['c'], PDO::PARAM_STR, 50);
			$q3->execute();
		} else if($loi['nom'] == LoiEnum::Normale) {
			$q3 = $this->bdd->prepare("INSERT INTO loiNormale (id, mu, sigma) VALUES (:id, :mu, :sigma)");
			$q3->bindParam(':id', $idLoi, PDO::PARAM_INT);
			$q3->bindParam(':mu', $loi['mu'], PDO::PARAM_STR, 50);
			$q3->bindParam(':sigma', $loi['sigma'], PDO::PARAM_STR, 50);
			$q3->execute();
		}

		foreach($predecesseurs as $pred) {
			$q = $this->bdd->prepare("INSERT INTO predecesseur (idPredecesseur, idTache)
										VALUES (:idPredecesseur, :idTache)");
			$q->bindParam(':idPredecesseur', $pred, PDO::PARAM_INT);
			$q->bindParam(':idTache', $idTache, PDO::PARAM_INT);
			$q->execute();

			$q = $this->bdd->prepare("INSERT INTO successeur (idSuccesseur, idTache)
										VALUES (:idSuccesseur, :idTache)");
			$q->bindParam(':idSuccesseur', $idTache, PDO::PARAM_INT);
			$q->bindParam(':idTache', $pred, PDO::PARAM_INT);
			$q->execute();
		}

		foreach($successeurs as $succ) {
			$q = $this->bdd->prepare("INSERT INTO successeur (idSuccesseur, idTache)
										VALUES (:idSuccesseur, :idTache)");
			$q->bindParam(':idSuccesseur', $succ, PDO::PARAM_INT);
			$q->bindParam(':idTache', $idTache, PDO::PARAM_INT);
			$q->execute();

			$q = $this->bdd->prepare("INSERT INTO predecesseur (idPredecesseur, idTache)
										VALUES (:idPredecesseur, :idTache)");
			$q->bindParam(':idPredecesseur', $idTache, PDO::PARAM_INT);
			$q->bindParam(':idTache', $succ, PDO::PARAM_INT);
			$q->execute();
		}

		$this->listeTaches = array();
		$this->loadListeTaches();
	}

	public function removeTask($id)
	{
		$q1 = $this->bdd->prepare("DELETE FROM predecesseur WHERE (idPredecesseur = :id OR idTache = :id)");
		$q1->bindParam(':id', $id, PDO::PARAM_INT);
		$q1->execute();

		$q2 = $this->bdd->prepare("DELETE FROM successeur WHERE (idSuccesseur = :id OR idTache = :id)");
		$q2->bindParam(':id', $id, PDO::PARAM_INT);
		$q2->execute();

		$q3 = $this->bdd->prepare("DELETE FROM tache WHERE id = :id");
		$q3->bindParam(':id', $id, PDO::PARAM_INT);
		$q3->execute();

		$this->listeTaches = array();
		$this->loadListeTaches();
	}

	public function updateTask($id, $nvnom)
	{
		$this->bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$q1 = $this->bdd->prepare("UPDATE tache SET nom = :nvnom WHERE id = :id");
		$q1->bindParam(':id', $id, PDO::PARAM_INT);
		$q1->bindParam(':nvnom', $nvnom, PDO::PARAM_STR, 50);
		$q1->execute();

		$this->listeTaches = array();
		$this->loadListeTaches();
	}

	public function updatePredecesseurs($idTache, $listePredecesseurs) {
		$q = $this->bdd->prepare("DELETE FROM predecesseur WHERE idTache = :idTache");
		$q->bindParam(':idTache', $idTache, PDO::PARAM_INT);
		$q->execute();

		$q = $this->bdd->prepare("DELETE FROM successeur WHERE idSuccesseur = :idTache");
		$q->bindParam(':idTache', $idTache, PDO::PARAM_INT);
		$q->execute();

		foreach($listePredecesseurs as $nvpred) {
			$q = $this->bdd->prepare("INSERT INTO predecesseur (idPredecesseur, idTache)
										VALUES (:idPredecesseur, :idTache)");
			$q->bindParam(':idPredecesseur', $nvpred, PDO::PARAM_INT);
			$q->bindParam(':idTache', $idTache, PDO::PARAM_INT);
			$q->execute();

			$q = $this->bdd->prepare("INSERT INTO successeur (idSuccesseur, idTache)
										VALUES (:idSuccesseur, :idTache)");
			$q->bindParam(':idSuccesseur', $idTache, PDO::PARAM_INT);
			$q->bindParam(':idTache', $nvpred, PDO::PARAM_INT);
			$q->execute();
		}

		$this->listeTaches = array();
		$this->loadListeTaches();
	}

	public function updateSuccesseurs($idTache, $listeSuccesseurs) {
		$q = $this->bdd->prepare("DELETE FROM successeur WHERE idTache = :idTache");
		$q->bindParam(':idTache', $idTache, PDO::PARAM_INT);
		$q->execute();

		$q = $this->bdd->prepare("DELETE FROM predecesseur WHERE idPredecesseur = :idTache");
		$q->bindParam(':idTache', $idTache, PDO::PARAM_INT);
		$q->execute();

		foreach($listeSuccesseurs as $nvsucc) {
			$q = $this->bdd->prepare("INSERT INTO successeur (idSuccesseur, idTache)
										VALUES (:idSuccesseur, :idTache)");
			$q->bindParam(':idSuccesseur', $nvsucc, PDO::PARAM_INT);
			$q->bindParam(':idTache', $idTache, PDO::PARAM_INT);
			$q->execute();

			$q = $this->bdd->prepare("INSERT INTO predecesseur (idPredecesseur, idTache)
										VALUES (:idPredecesseur, :idTache)");
			$q->bindParam(':idPredecesseur', $idTache, PDO::PARAM_INT);
			$q->bindParam(':idTache', $nvsucc, PDO::PARAM_INT);
			$q->execute();
		}

		$this->listeTaches = array();
		$this->loadListeTaches();
	}

	public function updateTaskLoi($id, $loi) {
		$q = $this->bdd->prepare("DELETE FROM loi WHERE idTache = :idTache");
		$q->bindParam(':idTache', $id, PDO::PARAM_INT);
		$q->execute();

		$q1 = $this->bdd->prepare("INSERT INTO loi (nom, idTache, valeurMin, valeurMax)
										VALUES (:nomLoi, :idTache, :valeurMin, :valeurMax)");
		$q1->bindParam(':nomLoi', $loi['nom'], PDO::PARAM_STR, 50);
		$q1->bindParam(':idTache', $id, PDO::PARAM_INT);
		$q1->bindParam(':valeurMin', $loi['valeurMin'], PDO::PARAM_INT);
		$q1->bindParam(':valeurMax', $loi['valeurMax'], PDO::PARAM_INT);
		$q1->execute();

		$idLoi = $this->bdd->lastInsertId();

		if($loi['nom'] == LoiEnum::Beta) {
			$q3 = $this->bdd->prepare("INSERT INTO loiBeta (id, w, v) VALUES (:id, :w, :v)");
			$q3->bindParam(':id', $idLoi, PDO::PARAM_INT);
			$q3->bindParam(':w', $loi['w'], PDO::PARAM_STR, 50);
			$q3->bindParam(':v', $loi['v'], PDO::PARAM_STR, 50);
			$q3->execute();
		} else if($loi['nom'] == LoiEnum::Triangulaire) {
			$q3 = $this->bdd->prepare("INSERT INTO loiTriangulaire (id, c) VALUES (:id, :c)");
			$q3->bindParam(':id', $idLoi, PDO::PARAM_INT);
			$q3->bindParam(':c', $loi['c'], PDO::PARAM_STR, 50);
			$q3->execute();
		} else if($loi['nom'] == LoiEnum::Normale) {
			$q3 = $this->bdd->prepare("INSERT INTO loiNormale (id, mu, sigma) VALUES (:id, :mu, :sigma)");
			$q3->bindParam(':id', $idLoi, PDO::PARAM_INT);
			$q3->bindParam(':mu', $loi['mu'], PDO::PARAM_STR, 50);
			$q3->bindParam(':sigma', $loi['sigma'], PDO::PARAM_STR, 50);
			$q3->execute();
		}

		$this->listeTaches = array();
		$this->loadListeTaches();
	}

	//-----------------------------------------------------------
	//---------------------DEBUT SIMULATIONS---------------------
	//-----------------------------------------------------------
	function addSimulateur($typeSimulateur, $nbEchantillons, $largeurIntervalle, $probabilite, $charge) {
		$q1 = $this->bdd->prepare("INSERT INTO simulateur (idProjet, typeSimulateur, nbEchantillons, largeurIntervalle, probabilite, charge)
					VALUES (:idProjet, :typeSimulateur, :nbEchantillons, :largeurIntervalle, :probabilite, :charge)");
		$q1->bindParam(':nbEchantillons', $nbEchantillons, PDO::PARAM_INT, 10);
		$q1->bindParam(':largeurIntervalle', $largeurIntervalle, PDO::PARAM_INT, 10);
		$q1->bindParam(':typeSimulateur', $typeSimulateur, PDO::PARAM_STR, 50);
		$q1->bindParam(':probabilite', $probabilite, PDO::PARAM_INT, 10);
		$q1->bindParam(':charge', $charge, PDO::PARAM_INT, 10);
		$q1->bindParam(':idProjet', $this->id, PDO::PARAM_INT, 10);
		$q1->execute();

		$this->loadListeSimulateurs();

		$simulateur = $this->getSimulateurByType($typeSimulateur);

		return $simulateur;
	}

	function updateSimulateur($simulateur, $typeSimulateur, $nbEchantillons, $largeurIntervalle, $probabilite, $charge) {
		$q1 = $this->bdd->prepare("UPDATE simulateur SET nbEchantillons = :nbEchantillons,
			largeurIntervalle = :largeurIntervalle, probabilite = :probabilite, charge = :charge
			WHERE typeSimulateur = :typeSimulateur AND idProjet = :idProjet");
		$q1->bindParam(':nbEchantillons', $nbEchantillons, PDO::PARAM_INT, 10);
		$q1->bindParam(':largeurIntervalle', $largeurIntervalle, PDO::PARAM_INT, 10);
		$q1->bindParam(':typeSimulateur', $typeSimulateur, PDO::PARAM_STR, 50);
		$q1->bindParam(':probabilite', $probabilite, PDO::PARAM_INT, 10);
		$q1->bindParam(':charge', $charge, PDO::PARAM_INT, 10);
		$q1->bindParam(':idProjet', $this->id, PDO::PARAM_INT, 10);
		$q1->execute();

		$this->loadListeSimulateurs();

		$simulateur = $this->getSimulateurByType($typeSimulateur);

		return $simulateur;
	}

	function getSimulateur($typeSimulateur, $nbEchantillons, $largeurIntervalle, $probabilite, $charge) {
		$simulateur = NULL;

		for ($i=0; $i < count($this->listeSimulateurs); $i++) {
			if($this->listeSimulateurs[$i]->typeSimulateur == $typeSimulateur) {
				if($this->listeSimulateurs[$i]->nbEchantillons != $nbEchantillons ||
					$this->listeSimulateurs[$i]->largeurIntervalle != $largeurIntervalle ||
					$this->listeSimulateurs[$i]->probabilite != $probabilite ||
					$this->listeSimulateurs[$i]->charge != $charge)
				{
					$simulateur = $this->updateSimulateur($this->listeSimulateurs[$i], $typeSimulateur, $nbEchantillons, $largeurIntervalle, $probabilite, $charge);
				} else {
					$simulateur = $this->listeSimulateurs[$i];
				}
				$i = count($this->listeSimulateurs);
			}
		}

		if(is_null($simulateur)) {
			$simulateur = $this->addSimulateur($typeSimulateur, $nbEchantillons, $largeurIntervalle, $probabilite, $charge);
		}

		return $simulateur;
	}

	function executeSimulation($typeSimulateur, $iteration, $intervalle, $probabilite, $charge) {
		$simulation = $this->getSimulateur($typeSimulateur, $iteration, $intervalle, $probabilite, $charge);
		$res = $simulation->calculate();

		return $res;
	}

	function estimateCharge($typeSimulateur, $iteration, $intervalle, $probabilite, $charge) {
		$simulation = $this->getSimulateur($typeSimulateur, $iteration, $intervalle, $probabilite, $charge);

		$charge = $simulation->estimateChargeGivenProbability($probabilite);
		return $charge;
	}

	function estimateProbability($typeSimulateur, $iteration, $intervalle, $probabilite, $charge) {
		$simulation = $this->getSimulateur($typeSimulateur, $iteration, $intervalle, $probabilite, $charge);

		$probabilite = $simulation->estimateProbabilityGivenCharge($charge);
		return $probabilite;
	}

	function getSimulateurByType($typeSimulateur) {
		$simulateur = NULL;
		for ($i=0; $i < count($this->listeSimulateurs); $i++) {
			if($this->listeSimulateurs[$i]->typeSimulateur == $typeSimulateur) {
				$simulateur = $this->listeSimulateurs[$i];
				$i = count($this->listeSimulateurs);
			}
		}

		return $simulateur;
	}
	//-----------------------------------------------------------
	//----------------------FIN SIMULATIONS----------------------
	//-----------------------------------------------------------

	// public function getLongestPath()
	// {
	// 	//calcular maior caminho ordenado
	// 	return $this->listeTaches;
	// }

	//--------------------------------------------------------------------
	//----------------------DEBUT CHEMINS PARALLELES----------------------
	//--------------------------------------------------------------------
	function getParallelPaths() {
    $start = $this->getTaskById($this->tacheDebut->id);
    $allPathsFromStartToEnd = $this->getAllPathsFromStartToEnd($start);

    return $allPathsFromStartToEnd;
  }

	// renvoie tous les chemins paralleles du graphe commençant par 'start' et finissant par 'end'
  function getAllPathsFromStartToEnd($task) {
    $resultList = array();
    $afterList = array();

    if (count($task->successeurs) == 0) {
      array_push($afterList, $task);
      array_push($resultList, $afterList);
      return $resultList;
    } else {
      for ($i=0; $i < count($task->successeurs); $i++) {
        $id = $task->successeurs[$i]->id;
        $nextTask = $this->getTaskById($id);
        $partialList = $this->getAllPathsFromStartToEnd($nextTask);
        while(count($partialList) > 0) {
          $firstElementList = array_shift($partialList);
          array_unshift($firstElementList, $task);
          array_push($resultList, $firstElementList);
        }
      }

      return $resultList;
    }
  }

  function getTaskById($id) {
    $tache = NULL;

		for ($i=0; $i < count($this->listeTaches); $i++) {
			if($this->listeTaches[$i]->id == $id) {
				$tache = $this->listeTaches[$i];
				$i = count($this->listeTaches);
			}
		}

		return $tache;
  }
	//------------------------------------------------------------------
	//----------------------FIN CHEMINS PARALLELES----------------------
	//------------------------------------------------------------------
}
?>
