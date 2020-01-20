<?php

include_once "TablierSolitaire.php";

class TablierSolitaireUI
{
    /**
     * @var TablierSolitaire instance du tablier solitaire courant
     */
    private $ts;

    /**
     * Constructeur de l'interface utilisateur du tablier solitaire
     * @param TablierSolitaire $ts tablier solitaire à manipuler.
     */
    public function __construct(TablierSolitaire $ts)
    {
        $this->ts = $ts;
    }

    /**
     * @return string affiche le formulaire avec toutes les cases.
     */
    public function afficheFormulaireOrigine()
    {
        if ($this->ts->isFinPartie()) {
            if ($this->ts->isVictoire()) {
                return "<h1>WIN</h1>";
            } else {
                return "<h1>LOOSE</h1>";
            }
        }

        $str = '<form method="post">';
        $str .= '<table>';
        for ($x = 0; $x < $this->ts->getNbLignes(); $x++) {
            $str .= '<tr>';
            for ($y = 0; $y < $this->ts->getNbColonnes(); $y++) {
                $str .= '<td>';
                if ($this->ts->getTablier()[$x][$y]->isCaseBille()) {
                    if ($this->ts->isBilleJouable($x, $y))
                        $str .= self::getBoutonCaseSolitaire($x, $y, "billeJouable", false);
                    else
                        $str .= self::getBoutonCaseSolitaire($x, $y, "bille", true);
                } else if ($this->ts->getTablier()[$x][$y]->isCaseVide()) {
                    $str .= self::getBoutonCaseSolitaire($x, $y, "vide", true);
                } else {
                    $str .= self::getBoutonCaseSolitaire($x, $y, "neutralise", true);
                }
                $str .= '</td>';
            }
            $str .= '</tr>';
        }
        $str .= '</table>';
        $str .= '</form>';

        return $str;
    }

    /**
     * @param string $coord Coordonnées sur lesquelles on a cliqué.
     * @return string le formulaire d'origine mais après les cases vides cliquables uniquement.
     */
    public function afficheFormulaireDestination(string $coord)
    {
        $str = '<form method="post">';
        $str .= '<table>';
        for ($x = 0; $x < $this->ts->getNbLignes(); $x++) {
            $str .= '<tr>';
            for ($y = 0; $y < $this->ts->getNbColonnes(); $y++) {
                $str .= '<td>';
                if ($this->ts->getTablier()[$x][$y]->isCaseBille()) {
                    $str .= self::getBoutonCaseSolitaire($x, $y, "bille", true);
                } else if ($this->ts->getTablier()[$x][$y]->isCaseVide()) {
                    $str .= self::getBoutonCaseSolitaire($x, $y, "vide", false);
                } else {
                    $str .= self::getBoutonCaseSolitaire($x, $y, "neutralise", true);
                }
                $str .= '</td>';
            }
            $str .= '</tr>';
        }
        $str .= '</table>';
        $str .= '</form>';
        return $str;
    }

    /**
     * Permet de faire un mouvement de bille.
     * @param string $coord_depart coordonnées de la case de la bille de départ.
     * @param string $coord_destination coordonnées de la case d'arrivée.
     */
    public function realiseMouvement(string $coord_depart, string $coord_destination)
    {
        $depart = explode("-", $coord_depart);
        $departX = $depart[0];
        $departY = $depart[1];

        $arrivee = explode("-", $coord_destination);
        $arriveeX = $arrivee[0];
        $arriveeY = $arrivee[1];

        $this->ts->deplaceBille($departX, $departY, $arriveeX, $arriveeY);
    }

    /**
     * @param int $ligne ligne du tablier.
     * @param int $colonne colonne du tablier.
     * @param string $classe indique la classe css à utiliser pour le style.
     * @param bool $disabled indique si le bouton est désactivé ou non.
     * @return string la balise html button avec les différents attributs passés en paramètres.
     */
    private static function getBoutonCaseSolitaire(int $ligne, int $colonne, string $classe, bool $disabled)
    {
        $str = "<button class='$classe' name='coord' value='$ligne-$colonne'";
        if ($disabled) {
            $str .= " disabled";
        }

        return "$str>&nbsp;</button>";
    }
}
