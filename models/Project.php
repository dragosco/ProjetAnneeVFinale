<?php
require("Task.php");
//require("SimulateurEnum.php");
require("SimulationChargeGlobale.php");
require("SimulationCoutGlobal.php");
require("SimulationMargeFinanciere.php");
//require("cnx.php");
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
	//var $simulationMC;
	var $listeSimulateurs;
	var $bdd;

	private static $_instance = null;

	function __construct($id, $nom, $tacheDebut, $tacheFin) //, $simulationMC
	{
		$this->id = $id;
		$this->nom = $nom;
		$this->tacheDebut = $tacheDebut;
		$this->tacheFin = $tacheFin;
		$this->listeRessources = array();
		//$this->simulationMC = $simulationMC;
		$this->listeTaches = array();
		$this->listeSimulateurs = array();
		// array_push($this->listeSimulateurs, SimulationChargeGlobale::constructBasic(SimulateurEnum::ChargeGlobale, 10000, 30, $this)); //, $chargeEntree, $probabiliteEntree

		$this->bdd = getBdd();

		// $this->bdd->query('INSERT INTO `projet` (`id`, `nomp`, `description`) VALUES (NULL, $nom, `desc`)');
		$this->id = 1; //$this->bdd->lastInsertId();
		$this->loadListeRessources();
		$this->loadListeTaches();
		$this->loadListeSimulateurs();
	}

	function loadListeTaches()
	{
		// On récupère tout le contenu de la table tâche
		$reponse = $this->bdd->query('SELECT * FROM tache');

		while ($donnees = $reponse->fetch())
		{
			$tache = new Task($donnees['id'], $donnees['nom']/* $donnees['duree']*/, $this);

			$tache->loadLoi();
			$tache->loadRessource();
			$tache->loadPredecesseurs();
			$tache->loadSuccesseurs();
			array_push($this->listeTaches, $tache);
		}


	}

	function loadListeSimulateurs()
	{
		// On récupère tout le contenu de la table tâche
		$reponse = $this->bdd->query('SELECT * FROM simulateur');

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
		// echo ($this->listeSimulateurs[0]->nbEchantillons);
		// echo ($this->listeSimulateurs[0]->largeurIntervalle);

	}
	function loadListeRessources() {
		$reponse = $this->bdd->query('SELECT * FROM ressource');

		while ($donnees = $reponse->fetch())
		{
			$ressource = new Ressource($donnees['id'], $donnees['nom'], $donnees['cout']); //, null, null

			array_push($this->listeRessources, $ressource);
		}
	}

	public static function getInstance() {

		if(is_null(self::$_instance)) {
			self::$_instance = new Project(1, 'Gna', null, null); //, null
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

	// function getListeTaches()
	// {
	// 	$reponse = $this->bdd->query('SELECT * FROM tache');
	//
	// 	$this->listeTaches = array();
	//
	// 	while ($donnees = $reponse->fetch())
	// 	{
	// 		array_push($this->listeTaches, new Task($donnees['nom'], null, null, null));
	// 	}
	// 	return $this->listeTaches;
	// }

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



	function addSimulateur($typeSimulateur, $nbEchantillons, $largeurIntervalle, $probabilite, $charge) { //, $chargeEntree, $probabiliteEntree
		// $simulateur = NULL;
		// echo 'add';
		$q1 = $this->bdd->prepare("INSERT INTO simulateur (idProjet, typeSimulateur, nbEchantillons, largeurIntervalle, probabilite, charge)
					VALUES (:idProjet, :typeSimulateur, :nbEchantillons, :largeurIntervalle, :probabilite, :charge)");
		$q1->bindParam(':nbEchantillons', $nbEchantillons, PDO::PARAM_INT, 10);
		$q1->bindParam(':largeurIntervalle', $largeurIntervalle, PDO::PARAM_INT, 10);
		$q1->bindParam(':typeSimulateur', $typeSimulateur, PDO::PARAM_STR, 50);
		$q1->bindParam(':probabilite', $probabilite, PDO::PARAM_INT, 10);
		$q1->bindParam(':charge', $charge, PDO::PARAM_INT, 10);
		$q1->bindParam(':idProjet', $this->id, PDO::PARAM_INT, 10);
		$q1->execute();

		// if($typeSimulateur == SimulateurEnum::ChargeGlobale) {
		// 	$simulateur = new SimulationChargeGlobale($typeSimulateur, $nbEchantillons, $largeurIntervalle, $this);
		// 	array_push($this->listeSimulateurs, $simulateur); //, $chargeEntree, $probabiliteEntree
		// 	//faire insert bd
		// }

		//return $simulateur;

		$this->listeSimulateurs = array();
		$this->loadListeSimulateurs();

		$simulateur = $this->getSimulateurByType($typeSimulateur);

		// $simulateur = NULL;
		// for ($i=0; $i < count($this->listeSimulateurs); $i++) {
		// 	// echo 'entrou loop';
		// 	// echo '$typeSimulateur ' . $typeSimulateur;
		// 	// echo ' ';
		// 	// echo '$this->listeSimulateurs[$i]->typeSimulateur ' . $this->listeSimulateurs[$i]->typeSimulateur;
		// 	if($this->listeSimulateurs[$i]->typeSimulateur == $typeSimulateur) {
		// 		echo 'encontrou';
		// 		$simulateur = $this->listeSimulateurs[$i];
		// 		$i = count($this->listeSimulateurs);
		// 	}
		// }

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

		$this->listeSimulateurs = array();
		$this->loadListeSimulateurs();

		$simulateur = $this->getSimulateurByType($typeSimulateur);

		// $simulateur = NULL;
		// for ($i=0; $i < count($this->listeSimulateurs); $i++) {
		// 	if($this->listeSimulateurs[$i]->typeSimulateur == $typeSimulateur) {
		// 		$simulateur = $this->listeSimulateurs[$i];
		// 		$i = count($this->listeSimulateurs);
		// 	}
		// }
		//
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
		// echo ("executeSimulation");
		// chercher si deja existent
		// sinon créer et insérer dans la bd
		$simulation = $this->getSimulateur($typeSimulateur, $iteration, $intervalle, $probabilite, $charge);
		// $simulation = new SimulationChargeGlobale($typeSimulateur, $iteration, $intervalle, $this);
		$res = $simulation->calculate();

		return $res;
	}

	function estimateCharge($typeSimulateur, $iteration, $intervalle, $probabilite, $charge) {
		$simulation = $this->getSimulateur($typeSimulateur, $iteration, $intervalle, $probabilite, $charge);
		//update bd avec probabilite
		$charge = $simulation->estimateChargeGivenProbability($probabilite);
		return $charge;
	}

	function estimateProbability($typeSimulateur, $iteration, $intervalle, $probabilite, $charge) {
		$simulation = $this->getSimulateur($typeSimulateur, $iteration, $intervalle, $probabilite, $charge);
		//update bd avec charge
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



	public function getLongestPath()
	{
		//calcular maior caminho ordenado
		return $this->listeTaches;
	}

}
?>
