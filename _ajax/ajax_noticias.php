<?php
require_once('../_inc/conexao.php');

$timeout = time() - 900;
// Fetch top 5 players for the mini-ranking
$sql = mysql_query("SELECT usuario, nivel, vitorias FROM usuarios WHERE status<>'banido' ORDER BY nivel DESC, yens_fat DESC, vitorias DESC, derrotas ASC LIMIT 5");

echo '<table width="100%" cellpadding="0" cellspacing="0">';
echo '<tr style="font-weight:bold; color:#FFF;"><td>Ninja</td><td>Nível</td><td>Vitórias</td></tr>';

if(mysql_num_rows($sql) > 0) {
    while($row = mysql_fetch_assoc($sql)) {
        echo '<tr style="color:#BBB;">';
        echo '<td>' . htmlspecialchars($row['usuario']) . '</td>';
        echo '<td>' . $row['nivel'] . '</td>';
        echo '<td>' . $row['vitorias'] . '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="3">Nenhum ninja encontrado.</td></tr>';
}
echo '</table>';
?>
