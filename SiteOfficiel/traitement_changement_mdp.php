<?php
function getBD()
{
    $bdd = new PDO('mysql:host=localhost;dbname=ClientFoot;charset=utf8', 'root', 'root');
    return $bdd;
}

$email = $_POST['email'];
$code_verification = $_POST['code_verification'];
$nouveau_mdp = $_POST['nouveau_mdp'];

$db = getBD();
$query = "SELECT COUNT(*) FROM Client WHERE mail = :email AND code = :code";
$stmt = $db->prepare($query);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->bindParam(':code', $code_verification, PDO::PARAM_STR);
$stmt->execute();
$count = $stmt->fetchColumn();

if ($count > 0) {
    $queryUpdateMdp = "UPDATE Client SET mot_de_passe = :mdp, code = NULL WHERE mail = :email";
    $stmtUpdateMdp = $db->prepare($queryUpdateMdp);
    $hashed_password = password_hash($nouveau_mdp, PASSWORD_DEFAULT);
    $stmtUpdateMdp->bindParam(':mdp', $hashed_password, PDO::PARAM_STR);
    $stmtUpdateMdp->bindParam(':email', $email, PDO::PARAM_STR);
    $stmtUpdateMdp->execute();

    $queryDeleteCode = "UPDATE Client SET code = NULL WHERE mail = :email";
    $stmtDeleteCode = $db->prepare($queryDeleteCode);
    $stmtDeleteCode->bindParam(':email', $email, PDO::PARAM_STR);
    $stmtDeleteCode->execute();

    header("Location: confchangmdp.php");
    exit();
} else {
    header("Location: mdpchanger.php?error=1");
    exit();
}
?>

