<?php
// require("Project.php");
/*
 * Task
 *
 * Classe tâche
 */
class Ressource
{
	var $id;
	var $nom;
  var $cout;
  var $nomPresentation;
	// var $bdd;

	function __construct($id, $nom, $cout)
	{
		$this->id = $id;
		$this->nom = $nom;
		$this->cout = $cout;
		// $this->bdd = getBdd();
	}

	public function __get($property) {
    if (property_exists($this, $property)) {
      // if($property == 'nomPresentation') {
      //   return $this->nom . ' (' . $this->cout . ')';
      // } else {
        return $this->$property;
      // }
    }
  }

  public function __set($property, $value) {
    if (property_exists($this, $property)) {
        $this->$property = $value;
    }
  }
}
?>
