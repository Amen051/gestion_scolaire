<?php
ob_start();
session_start();
require_once '../includes/db.php';
require '../vendor/autoload.php';
require '../vendor/tecnickcom/tcpdf/tcpdf.php'; 

$eleve_id = intval($_GET['eleve_id']);
$trimestre = $_GET['trimestre'] ?? 'Trimestre 1';
$stmt = $pdo->prepare("SELECT nom, prenom, classe, sexe, YEAR(created_at) as an FROM eleves WHERE id = ?");
$stmt->execute([$eleve_id]);
$eleve = $stmt->fetch();
if (!$eleve) exit("Élève introuvable.");

$classe = $eleve['classe'];
$sexe = $eleve['sexe'];
$annee = $eleve['an'];
$fin = $annee + 1;
$stmt = $pdo->prepare("SELECT COUNT(*) AS total,
                          SUM(sexe = 'M') AS garcons,
                          SUM(sexe = 'F') AS filles
                       FROM eleves WHERE classe = ?");
$stmt->execute([$classe]);
$effectif = $stmt->fetch();

$sql = "SELECT n.matiere, n.coefficient, n.note_devoir, n.note_composition, p.nom AS professeur
        FROM notes n
        JOIN professeurs p ON n.professeur_id = p.id
        WHERE n.eleve_id=? AND n.trimestre=?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$eleve_id, $trimestre]);
$notes = $stmt->fetchAll();
if (empty($notes)) exit("Pas de notes pour ce trimestre.");

$total_coef = 0;
$sum_notes = 0;
foreach ($notes as $row) {
    $moy = ($row['note_devoir'] + $row['note_composition']) / 2;
    $sum_notes += $moy * $row['coefficient'];
    $total_coef += $row['coefficient'];
}
$moy_gen = $total_coef ? round($sum_notes / $total_coef, 2) : 0;

$pdf = new TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
$pdf->SetCreator('CSBJS');
$pdf->SetTitle("Bulletin: {$eleve['prenom']} {$eleve['nom']} - $trimestre");
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(10, 15);
$pdf->AddPage();

$pdf->SetFont('helvetica', '', 9);
$pdf->SetXY(10, 10);
$pdf->MultiCell(80, 5, 
"         MINISTÈRE DES ENSEIGNEMENTS\nPRIMAIRE, SECONDAIRE TECHNIQUE ET DE \n                         L’ARTISANAT\n                          __________\n\n  DIRECTION RÉGIONALE DE L’ÉDUCATION \n                         GRAND LOMÉ\n                          __________\n\n          INSPECTION DE L’ENSEIGNEMENT SECONDAIRE GÉNÉRAL GRAND-LOMÉ EST\n\n                     C.S.B JESUS SAUVE\n                      01 B.P. 2291 Lomé 1\n                Cel : 90 13 50 89 / 99 46 23 74\n                         LOMÉ - TOGO", 
0, 'L');

$pdf->Image('../assets/img/logo.png', 90, 14, 30);

$pdf->SetXY(140, 10);
$pdf->MultiCell(60, 5, 
"          REPUBLIQUE TOGOLAISE\n             Travail – Liberté – Patrie\n\n\n\nANNÉE SCOLAIRE : $annee - $fin \n\nCLASSE : {$classe}\n\nEFFECTIF : {$effectif['total']}\n\nSEXE : {$sexe}\n\nGarçons : {$effectif['garcons']}\nFilles : {$effectif['filles']}", 
0, 'L');

$pdf->SetFont('helvetica', 'B', 13);
$pdf->SetXY(10, 75);
$pdf->Cell(190, 8, "BULLETIN DE NOTES DU $trimestre", 1, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 6, "                                 Nom et Prénoms de l’élève : {$eleve['prenom']} {$eleve['nom']}", 0, 1, 'L');
$pdf->Ln(4);

$pdf->SetFont('helvetica', '', 8.5);

$html = '<table border="1" cellpadding="3">
<thead style="background-color:#d0d0d0; font-weight:bold;">
<tr>
<th>Matière</th><th>Coef</th><th>Devoir</th><th>Composition</th>
<th>Moyenne</th><th>Mention</th><th>Rang</th><th>Professeur</th><th>Signature</th>
</tr></thead><tbody>';

foreach ($notes as $row) {
    $moy = round(($row['note_devoir'] + $row['note_composition']) / 2, 2);
    $bg = ($moy < 10) ? '#f8d7da' : (($moy < 12) ? '#fff3cd' : '#d4edda');

    if ($moy < 10) $mention = 'Insuffisant';
    elseif ($moy < 12) $mention = 'Passable';
    elseif ($moy < 14) $mention = 'Assez Bien';
    elseif ($moy < 16) $mention = 'Bien';
    else $mention = 'Très Bien';

    $stmt = $pdo->prepare("SELECT eleve_id, ((note_devoir + note_composition)/2) as moyenne
                           FROM notes 
                           WHERE classe = ? AND matiere = ? AND trimestre = ?
                           ORDER BY moyenne DESC");
    $stmt->execute([$classe, $row['matiere'], $trimestre]);
    $rankings = $stmt->fetchAll();
    $rang = 1;
    foreach ($rankings as $rank_row) {
        if ($rank_row['eleve_id'] == $eleve_id) break;
        $rang++;
    }

    $html .= "<tr style='background-color:$bg'>
        <td>{$row['matiere']}</td>
        <td>{$row['coefficient']}</td>
        <td>{$row['note_devoir']}</td>
        <td>{$row['note_composition']}</td>
        <td>$moy</td>
        <td>$mention</td>
        <td>$rang e</td>
        <td>{$row['professeur']}</td>
        <td></td>
    </tr>";
}
$html .= '</tbody></table>';
$pdf->writeHTML($html, true, false, false, false, '');

$pdf->Ln(10);



$ordre = ['Trimestre 1' => 1, 'Trimestre 2' => 2, 'Trimestre 3' => 3];
$niveau_actuel = $ordre[$trimestre] ?? 1;

$stmt = $pdo->prepare("SELECT trimestre, SUM((note_devoir + note_composition)/2 * coefficient) AS total,
                              SUM(coefficient) AS coef_total
                       FROM notes
                       WHERE eleve_id = ?
                       GROUP BY trimestre");
$stmt->execute([$eleve_id]);

$moyennes = ['Trimestre 1' => '-', 'Trimestre 2' => '-', 'Trimestre 3' => '-'];

while ($row = $stmt->fetch()) {
    $t_label = $row['trimestre'];
    if (isset($ordre[$t_label]) && $ordre[$t_label] <= $niveau_actuel) {
        $moyennes[$t_label] = $row['coef_total'] ? round($row['total'] / $row['coef_total'], 2) : '-';
    }
}

$moyenne_annuelle = '-';
if ($trimestre === 'Trimestre 3') {
    $valeurs = array_filter($moyennes, fn($v) => $v !== '-');
    if (count($valeurs) > 0) {
        $moyenne_annuelle = round(array_sum($valeurs) / count($valeurs), 2);
    }
}

$stmt = $pdo->prepare("SELECT eleve_id, SUM((note_devoir + note_composition)/2 * coefficient) AS total,
                              SUM(coefficient) AS coef_total
                       FROM notes
                       WHERE classe = ? AND trimestre = ?
                       GROUP BY eleve_id");
$stmt->execute([$classe, $trimestre]);
$rangs = $stmt->fetchAll();

$rang_general = 1;
usort($rangs, function ($a, $b) {
    $moyA = $a['coef_total'] ? $a['total'] / $a['coef_total'] : 0;
    $moyB = $b['coef_total'] ? $b['total'] / $b['coef_total'] : 0;
    return $moyB <=> $moyA;
});
foreach ($rangs as $r) {
    if ($r['eleve_id'] == $eleve_id) break;
    $rang_general++;
}

$pdf->Ln(10);
$pdf->SetFont('helvetica', '', 9);
$affichage_annuel = ($trimestre === 'Trimestre 3') ? $moyenne_annuelle : '-';
$html_moyennes = '
<table border="1" cellpadding="4">
<tr style="background-color:#e0e0e0;">
<th>Trimestre 1</th><th>Trimestre 2</th><th>Trimestre 3</th><th>Moy. Annuelle</th><th>Rang Général</th>
</tr>
<tr>
<td align="center">' . $moyennes['Trimestre 1'] . '</td>
<td align="center">' . $moyennes['Trimestre 2'] . '</td>
<td align="center">' . $moyennes['Trimestre 3'] . '</td>
<td align="center">' . $affichage_annuel . '</td>
<td align="center">' . $rang_general . ' e / ' . $effectif['total'] . '</td>
</tr>
</table>';

$pdf->writeHTML($html_moyennes, true, false, false, false, '');

$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 8, "Moyenne du trimestre : $moy_gen / 20", 0, 1, 'R');

if ($trimestre !== 'Trimestre 3') {
    $decision = "En attente de fin d'annee";
} elseif ($moyenne_annuelle === '-') {
    $decision = "Décision non disponible.";
} elseif ($moyenne_annuelle < 10) {
    $decision = "Reprendre la classe";
} elseif ($moyenne_annuelle < 12) {
    $decision = "Redoubler d’effort. Passe en classe supérieure";
} else {
    $decision = "Bon travail, passe en classe superieure";
}

$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 6, "DÉCISION DU CONSEIL DE CLASSE", 0, 1, 'C');
$pdf->SetFont('helvetica', 'B', 11);
$pdf->MultiCell(0, 6, strtoupper($decision), 0, 'C');

$pdf->Ln(18);
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(95, 6, 'Signature du Titulaire : ____________________', 0, 'L', false, 0);
$pdf->MultiCell(95, 6, 'Signature du Directeur : ____________________', 0, 'R', false, 1);

ob_end_clean();
$pdf->Output("bulletin_{$eleve_id}.pdf", 'I');
?>