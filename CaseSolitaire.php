<?php

class CaseSolitaire
{
    const BILLE = 1;
    const VIDE = 0;
    const NEUTRALISE = -1;

    /**
     * @var int valeur de la case.
     */
    protected $valeur;

    /**
     * Construit une nouvelle case de solitaire.
     * @param int $valeur valeur initiale de la case.
     */
    public function __construct($valeur = self::BILLE)
    {
        $this->valeur = $valeur;
    }

    /**
     * @return string retourne une représentation de la valeur.
     */
    public function __toString()
    {
        if ($this->isCaseBille())
            return 'o';

        if ($this->isCaseVide())
            return '.';

        if ($this->isCaseNeutralise())
            return ' ';
    }

    /**
     * Méthode permettant de retourner la valeur de la case.
     * @return int valeur de la case (soit 1, 0 ou -1).
     */
    public function getValeur()
    {
        return $this->valeur;
    }

    /**
     * Méthode permettant de changer la valeur de la case courante.
     * @param void change la valeur de la case.
     */
    public function setValeur($valeur)
    {
        if ($valeur < self::NEUTRALISE || $valeur > self::BILLE) {
            return;
        }

        $this->valeur = $valeur;
    }

    /**
     * Méthode indiquant si la case est vide.
     * @return bool indique si la case est vide.
     */
    public function isCaseVide()
    {
        return $this->getValeur() == self::VIDE;
    }

    /**
     * Méthode indiquant si la case est une bille.
     * @return bool indique si la case est une bille.
     */
    public function isCaseBille()
    {
        return $this->getValeur() == self::BILLE;
    }

    /**
     * Méthode indiquant si la case est neutralisée.
     * @return bool indique si la case est neutralisée.
     */
    public function isCaseNeutralise()
    {
        return $this->getValeur() == self::NEUTRALISE;
    }
}