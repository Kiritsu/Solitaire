<?php
include_once "TablierSolitaireUI.php";

session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Solitaire</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
if (!isset($_SESSION["solitaire"])) {
    $_SESSION["solitaire"] = new TablierSolitaireUI(TablierSolitaire::initTablierEuropeen());
    $_SESSION["etape"] = 1;
}

$sol = $_SESSION["solitaire"];

if (isset($_SESSION["etape"]) && $_SESSION["etape"] == 2) {
    $sol->realiseMouvement($_SESSION["coord_depart"], $_POST["coord"]);
    $_SESSION["etape"] = 1;
    echo $sol->afficheFormulaireOrigine();
} else if (isset($_POST["coord"])) {
    $_SESSION["coord_depart"] = $_POST["coord"];
    $_SESSION["etape"] = 2;
    echo $sol->afficheFormulaireDestination($_POST["coord"]);
} else {
    echo $sol->afficheFormulaireOrigine();
}
?>
<a href='destroy.php'>
    <button>Réinitialiser</button>
</a>
</body>
</html>