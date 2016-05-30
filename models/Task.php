<?php
require("lois/LoiProbabilite.php");
require 'lois/LoiTriangulaire.php';
require 'lois/LoiNormale.php';
require 'lois/LoiNormaleTronquee.php';
require 'lois/LoiBeta.php';
require 'lois/LoiRand.php';
require 'lois/SansLoi.php';
require("LoiEnum.php");
require("cnx.php");
require("Ressource.php");
// require("Project.php");
/*
 * Task
 *
 * Classe tâche
 */
class Task
{
	var $id;
	var $nom;
	var $projet;
	var $predecesseurs;
	var $successeurs;
	var $loi;
	var $ressource;

	var $bdd;

	function __construct($id, $nom, $projet)
	{
		$this->id = $id;
		$this->nom = $nom;
		$this->predecesseurs = array();
		$this->successeurs = array();
		$this->projet = $projet;
		$this->bdd = getBdd();
	}

	public function loadLoi()
	{
		$this->bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$q = $this->bdd->prepare("SELECT l.* FROM tache t, loi l WHERE t.id = l.idTache AND t.id = ?");
		$q->execute(array($this->id));
		$loi = $q->fetch(PDO::FETCH_ASSOC);

		if($loi['nom'] == LoiEnum::Beta) {
			$this->bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$q = $this->bdd->prepare("SELECT b.* FROM loi l, loiBeta b where b.id = ?");
			$q->execute(array($loi['id']));
			$beta = $q->fetch(PDO::FETCH_ASSOC);

			$this->loi = new LoiBeta($loi['valeurMin'], $loi['valeurMax'], $beta['v'], $beta['w']);
		} else if($loi['nom'] == LoiEnum::Triangulaire) {
			$this->bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$q = $this->bdd->prepare("SELECT t.* FROM loi l, loiTriangulaire t where t.id = ?");
			$q->execute(array($loi['id']));
			$triangulaire = $q->fetch(PDO::FETCH_ASSOC);

			$this->loi = new LoiTriangulaire($loi['valeurMin'], $loi['valeurMax'], $triangulaire['c']);
		} else if($loi['nom'] == LoiEnum::Normale) {
			$this->bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$q = $this->bdd->prepare("SELECT n.* FROM loi l, loiNormale n where n.id = ?");
			$q->execute(array($loi['id']));
			$normale = $q->fetch(PDO::FETCH_ASSOC);

			$this->loi = new LoiNormaleTronquee($loi['valeurMin'], $loi['valeurMax'], $normale['mu'], $normale['sigma']);
		} else if($loi['nom'] == LoiEnum::Uniforme) {
			$this->loi = new LoiRand($loi['valeurMin'], $loi['valeurMax']);
		} else if($loi['nom'] == LoiEnum::SansLoi) {
			$this->loi = new SansLoi($loi['valeurMax']);
		}
	}

	public function loadRessource() {
		$this->bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$q = $this->bdd->prepare("SELECT r.* FROM tache t, ressource r WHERE t.idRessource = r.id AND t.id = ?");
		$q->execute(array($this->id));
		$ressource = $q->fetch(PDO::FETCH_ASSOC);

		$this->ressource = new Ressource($ressource['id'], $ressource['nom'], $ressource['cout']);
	}

	public function loadPredecesseurs() {
		$q = $this->bdd->prepare("SELECT idPredecesseur FROM predecesseur WHERE idTache = ?");
		$q->execute(array($this->id));

		while ($donnees = $q->fetch()) {
			$q1 = $this->bdd->prepare("SELECT nom, idProjet FROM tache WHERE id = ?");
			$q1->execute(array($donnees['idPredecesseur']));
			$row = $q1->fetch(PDO::FETCH_ASSOC);
			$pred = new Task($donnees['idPredecesseur'], $row['nom'], $row['idProjet']);
			array_push($this->predecesseurs, $pred);
		}
	}

	public function loadSuccesseurs() {
		$q = $this->bdd->prepare("SELECT idSuccesseur FROM successeur WHERE idTache = ?");
		$q->execute(array($this->id));

		while ($donnees = $q->fetch()) {
			$q1 = $this->bdd->prepare("SELECT nom, idProjet FROM tache WHERE id = ?");
			$q1->execute(array($donnees['idSuccesseur']));
			$row = $q1->fetch(PDO::FETCH_ASSOC);
			$succ = new Task($donnees['idSuccesseur'], $row['nom'], $row['idProjet']);
			array_push($this->successeurs, $succ);
		}
	}

	public function addPredecesseur($tache) {
		array_push($predecesseurs, $tache);
	}

	public function addSuccesseur($tache) {
		array_push($successeurs, $tache);
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
}
?>
