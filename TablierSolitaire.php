<?php

include_once('CaseSolitaire.php');

class TablierSolitaire
{
    /**
     * @var CaseSolitaire[][] cases du tablier.
     */
    protected $tablier;

    /**
     * @var int nombre de lignes du tablier.
     */
    private $nbLignes;

    /**
     * @var int nombre de colonnes du tablier.
     */
    private $nbColonnes;

    /**
     * Construit un nouveau tablier de solitaire.
     * @param int $nbLignes nombre de lignes.
     * @param int $nbColonnes nombre de colonnes.
     */
    public function __construct($nbLignes = 5, $nbColonnes = 5)
    {
        $this->nbLignes = $nbLignes;
        $this->nbColonnes = $nbColonnes;

        $this->tablier = array();

        for ($l = 0; $l < $nbLignes; $l++) {
            for ($c = 0; $c < $nbColonnes; $c++) {
                $this->tablier[$c][$l] = new CaseSolitaire();
            }
        }
    }

    /**
     * Méthode permettant de retourner le nombre de lignes du tablier.
     * @return int retourne le nombre de lignes du tablier.
     */
    public function getNbLignes()
    {
        return $this->nbLignes;
    }

    /**
     * Méthode permettant de retourner le nombre de colonnes du tablier.
     * @return int retourne le nombre de colonnes du tablier.
     */
    public function getNbColonnes()
    {
        return $this->nbColonnes;
    }

    /**
     * Méthode permettant de retourner le tablier entier.
     * @return CaseSolitaire[][] retourne le tablier.
     */
    public function getTablier()
    {
        return $this->tablier;
    }

    /**
     * Méthode permettant de récupérer une case.
     * @param $ligne int ligne de la case.
     * @param $colonne int colonne de la case.
     * @return CaseSolitaire retourne une case du solitaire.
     */
    public function getCase($ligne, $colonne)
    {
        return $this->tablier[$ligne][$colonne];
    }

    /**
     * Méthode permettant de vider une case.
     * @param $ligne int ligne de la case.
     * @param $colonne int colonne de la case.
     */
    public function videCase($ligne, $colonne)
    {
        $this->getCase($ligne, $colonne)->setValeur(CaseSolitaire::VIDE);
    }

    /**
     * Méthode permettant de remplir une case.
     * @param $ligne int ligne de la case.
     * @param $colonne int colonne de la case.
     */
    public function remplieCase($ligne, $colonne)
    {
        $this->getCase($ligne, $colonne)->setValeur(CaseSolitaire::BILLE);
    }

    /**
     * Méthode permettant de neutraliser une case.
     * @param $ligne int ligne de la case.
     * @param $colonne int colonne de la case.
     */
    public function neutraliseCase($ligne, $colonne)
    {
        $this->getCase($ligne, $colonne)->setValeur(CaseSolitaire::NEUTRALISE);
    }

    /**
     * Méthode indiquant si le mouvement voulu est valide.
     * @param $ligneDepart int ligne de départ.
     * @param $colonneDepart int colonne de départ.
     * @param $ligneArrivee int ligne d'arrivée.
     * @param $colonneArrivee int colonee d'arrivée.
     * @return bool indique si le mouvement est valide.
     */
    public function estValideMvt($ligneDepart, $colonneDepart, $ligneArrivee, $colonneArrivee)
    {
        $caseDepart = $this->getCase($ligneDepart, $colonneDepart);
        $caseArrivee = $this->getCase($ligneArrivee, $colonneArrivee);

        if ($caseDepart == null || $caseArrivee == null) {
            return false;
        }

        if (!$caseDepart->isCaseBille() || !$caseArrivee->isCaseVide()) {
            return false;
        }

        if ($colonneArrivee < 0 || $colonneArrivee > $this->nbColonnes
            || $ligneArrivee < 0 || $ligneArrivee > $this->nbLignes) {
            return false;
        }

        $mvtGauche = $ligneDepart - $ligneArrivee == 0
            && $colonneDepart - $colonneArrivee == 2
            && $this->getCase($ligneDepart, $colonneDepart - 1)->isCaseBille();

        $mvtDroit = $ligneDepart - $ligneArrivee == 0
            && $colonneDepart - $colonneArrivee == -2
            && $this->getCase($ligneDepart, $colonneDepart + 1)->isCaseBille();

        $mvtHaut = $colonneDepart - $colonneArrivee == 0
            && $ligneDepart - $ligneArrivee == 2
            && $this->getCase($ligneDepart - 1, $colonneDepart)->isCaseBille();

        $mvtBas = $colonneDepart - $colonneArrivee == 0
            && $ligneDepart - $ligneArrivee == -2
            && $this->getCase($ligneDepart + 1, $colonneDepart)->isCaseBille();

        return $mvtGauche || $mvtDroit || $mvtHaut || $mvtBas;
    }

    /**
     * Méthode indiquant si le mouvement voulu en fonction de la direction est valide.
     * @param $ligneDepart int ligne de départ.
     * @param $colonneDepart int colonne de départ.
     * @param $direction string direction du mouvement.
     * @return bool indique si le mouvement est valide.
     */
    public function estValideMvtDir($ligneDepart, $colonneDepart, $direction)
    {
        $case = $this->getCase($ligneDepart, $colonneDepart);

        if ($case->isCaseNeutralise() || $case->isCaseVide()) {
            return false;
        }

        switch ($direction) {
            case 'n':
            case 'N':
                return $this->estValideMvt($ligneDepart, $colonneDepart,
                    $ligneDepart - 2, $colonneDepart);
            case 's':
            case 'S':
                return $this->estValideMvt($ligneDepart, $colonneDepart,
                    $ligneDepart + 2, $colonneDepart);
            case 'o':
            case 'O':
                return $this->estValideMvt($ligneDepart, $colonneDepart,
                    $ligneDepart, $colonneDepart - 2);
            case 'e':
            case 'E':
                return $this->estValideMvt($ligneDepart, $colonneDepart,
                    $ligneDepart, $colonneDepart + 2);
            default:
                return false;
        }
    }

    /**
     * Méthode indiquant si la bille est jouable.
     * @param $ligneDepart int ligne de départ.
     * @param $colonneDepart int colonne de départ.
     * @return bool indique si la bille est jouable.
     */
    public function isBilleJouable($ligneDepart, $colonneDepart)
    {
        return $this->estValideMvtDir($ligneDepart, $colonneDepart, 'N')
            || $this->estValideMvtDir($ligneDepart, $colonneDepart, 'O')
            || $this->estValideMvtDir($ligneDepart, $colonneDepart, 'E')
            || $this->estValideMvtDir($ligneDepart, $colonneDepart, 'S');
    }

    /**
     * Méthode permettant de déplacer la bille.
     * Vérifie si le mouvement est possible avant.
     * @param $ligneDepart int ligne de départ.
     * @param $colonneDepart int colonne de départ.
     * @param $ligneArrivee int ligne d'arrivée.
     * @param $colonneArrivee int colonee d'arrivée.
     */
    public function deplaceBille($ligneDepart, $colonneDepart, $ligneArrivee, $colonneArrivee)
    {
        if (!$this->estValideMvt($ligneDepart, $colonneDepart, $ligneArrivee, $colonneArrivee)) {
            return;
        }

        $this->getCase($ligneDepart, $colonneDepart)->setValeur(CaseSolitaire::VIDE);
        $this->getCase($ligneArrivee, $colonneArrivee)->setValeur(CaseSolitaire::BILLE);

        $caseMilieu = null;
        if ($ligneDepart - $ligneArrivee == 0
            && $colonneDepart - $colonneArrivee == 2) {
            $caseMilieu = $this->getCase($ligneDepart, $colonneDepart - 1);
        } else if ($ligneDepart - $ligneArrivee == 0
            && $colonneDepart - $colonneArrivee == -2) {
            $caseMilieu = $this->getCase($ligneDepart, $colonneDepart + 1);
        } else if ($colonneDepart - $colonneArrivee == 0
            && $ligneDepart - $ligneArrivee == 2) {
            $caseMilieu = $this->getCase($ligneDepart - 1, $colonneDepart);
        } else if ($colonneDepart - $colonneArrivee == 0
            && $ligneDepart - $ligneArrivee == -2) {
            $caseMilieu = $this->getCase($ligneDepart + 1, $colonneDepart);
        }

        if ($caseMilieu != null) {
            $caseMilieu->setValeur(CaseSolitaire::VIDE);
        }
    }

    /**
     * Méthode permettant de déplacer la bille en fonction de la direction.
     * Vérifie si le mouvement est possible avant.
     * @param $ligneDepart int ligne de départ.
     * @param $colonneDepart int colonne de départ.
     * @param $direction string direction du mouvement.
     */
    public function deplaceBilleMvt($ligneDepart, $colonneDepart, $direction)
    {
        if (!$this->estValideMvtDir($ligneDepart, $colonneDepart, $direction)) {
            return;
        }

        switch ($direction) {
            case 'n':
            case 'N':
                $this->deplaceBille($ligneDepart, $colonneDepart,
                    $ligneDepart - 2, $colonneDepart);
                break;
            case 's':
            case 'S':
                $this->deplaceBille($ligneDepart, $colonneDepart,
                    $ligneDepart + 2, $colonneDepart);
                break;
            case 'o':
            case 'O':
                $this->deplaceBille($ligneDepart, $colonneDepart,
                    $ligneDepart, $colonneDepart - 2);
                break;
            case 'e':
            case 'E':
                $this->deplaceBille($ligneDepart, $colonneDepart,
                    $ligneDepart, $colonneDepart + 2);
                break;
        }
    }

    /**
     * Méthode indiquant si la partie est terminée.
     * @return bool indique si la partie est terminée.
     */
    public function isFinPartie()
    {
        $nbJouables = 0;

        for ($l = 0; $l < $this->nbLignes; $l++) {
            for ($c = 0; $c < $this->nbColonnes; $c++) {
                if ($this->getCase($l, $c)->isCaseBille()
                    && $this->isBilleJouable($l, $c)) {
                    $nbJouables++;
                }
            }
        }

        return $nbJouables > 0;
    }

    /**
     * Méthode indiquant si la partie est une victoire.
     * @return bool indique si la partie est une victoire.
     */
    public function isVictoire()
    {
        $count = 0;

        for ($l = 0; $l < $this->nbLignes; $l++) {
            for ($c = 0; $c < $this->nbColonnes; $c++) {
                if ($this->getCase($l, $c)->isCaseBille()) {
                    $count++;
                }
            }
        }

        return $count == 1;
    }

    /**
     * Retourne le tablier sous forme de caractères.
     * @return string tablier sous forme de caractères.
     */
    public function __toString()
    {
        $str = "";

        for ($l = 0; $l < $this->nbLignes; $l++) {
            for ($c = 0; $c < $this->nbColonnes; $c++) {
                $str .= $this->getCase($l, $c);
            }
            $str .= '<br />';
        }

        return $str;
    }

    /**
     * Méthode initialisant un tablier européen.
     * @return TablierSolitaire un tablier européen.
     */
    public static function initTablierEuropeen()
    {
        $tab = new self(7, 7);

        $tab->getCase(0, 0)->setValeur(CaseSolitaire::NEUTRALISE);
        $tab->getCase(1, 0)->setValeur(CaseSolitaire::NEUTRALISE);
        $tab->getCase(0, 1)->setValeur(CaseSolitaire::NEUTRALISE);

        $tab->getCase(0, 6)->setValeur(CaseSolitaire::NEUTRALISE);
        $tab->getCase(1, 6)->setValeur(CaseSolitaire::NEUTRALISE);
        $tab->getCase(0, 5)->setValeur(CaseSolitaire::NEUTRALISE);

        $tab->getCase(6, 0)->setValeur(CaseSolitaire::NEUTRALISE);
        $tab->getCase(6, 1)->setValeur(CaseSolitaire::NEUTRALISE);
        $tab->getCase(5, 0)->setValeur(CaseSolitaire::NEUTRALISE);

        $tab->getCase(6, 6)->setValeur(CaseSolitaire::NEUTRALISE);
        $tab->getCase(6, 5)->setValeur(CaseSolitaire::NEUTRALISE);
        $tab->getCase(5, 6)->setValeur(CaseSolitaire::NEUTRALISE);

        $tab->getCase(2, 3)->setValeur(CaseSolitaire::VIDE);

        return $tab;
    }

    /**
     * Méthode initialisant un tablier anglais.
     * @return TablierSolitaire un tablier anglais.
     */
    public static function initTablierAnglais()
    {
        $tab = new self(7, 7);

        $tab->getCase(0, 0)->setValeur(CaseSolitaire::NEUTRALISE);
        $tab->getCase(1, 0)->setValeur(CaseSolitaire::NEUTRALISE);
        $tab->getCase(0, 1)->setValeur(CaseSolitaire::NEUTRALISE);
        $tab->getCase(1, 1)->setValeur(CaseSolitaire::NEUTRALISE);

        $tab->getCase(0, 6)->setValeur(CaseSolitaire::NEUTRALISE);
        $tab->getCase(1, 6)->setValeur(CaseSolitaire::NEUTRALISE);
        $tab->getCase(0, 5)->setValeur(CaseSolitaire::NEUTRALISE);
        $tab->getCase(1, 5)->setValeur(CaseSolitaire::NEUTRALISE);

        $tab->getCase(6, 0)->setValeur(CaseSolitaire::NEUTRALISE);
        $tab->getCase(6, 1)->setValeur(CaseSolitaire::NEUTRALISE);
        $tab->getCase(5, 0)->setValeur(CaseSolitaire::NEUTRALISE);
        $tab->getCase(5, 1)->setValeur(CaseSolitaire::NEUTRALISE);

        $tab->getCase(6, 6)->setValeur(CaseSolitaire::NEUTRALISE);
        $tab->getCase(6, 5)->setValeur(CaseSolitaire::NEUTRALISE);
        $tab->getCase(5, 6)->setValeur(CaseSolitaire::NEUTRALISE);
        $tab->getCase(5, 5)->setValeur(CaseSolitaire::NEUTRALISE);

        $tab->getCase(3, 3)->setValeur(CaseSolitaire::VIDE);

        return $tab;
    }

    /**
     * Méthode initialisant un tablier gagnant.
     * @return TablierSolitaire un tablier gagnant.
     */
    public static function initTablierGagnant()
    {
        return new self();
    }

    /**
     * Méthode initialisant un tablier perdant.
     * @return TablierSolitaire un tablier perdant.
     */
    public static function initTablierPerdant()
    {
        return new self();
    }
}