<?php

include_once('TablierSolitaire.php');

$tablier = TablierSolitaire::initTablierEuropeen();

echo "<pre>" . $tablier . "</pre>";

$tablier->deplaceBille(0, 3, 2, 3);

echo "<pre>" . $tablier . "</pre>";

$tablier->deplaceBilleMvt(1, 1, 'E');

echo "<pre>" . $tablier . "</pre>";