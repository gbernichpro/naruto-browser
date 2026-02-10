<?php
// Paginação
$pagina = isset($_GET["pagina"]) ? (int)$_GET["pagina"] : 0;
$Qtde = 20;

// Count Total
$sql_count = mysqli_query($mysqli_link, "SELECT count(*) as total FROM organizacoes");
$row_count = mysqli_fetch_assoc($sql_count);
$Total = $row_count['total'];
$Paginas = ceil($Total/$Qtde);

$limit = $pagina;
$PaginaCorrente = $pagina + 1;
$inicio = $limit * $Qtde;

// Query Principal Otimizada
$query = "SELECT o.*, u.usuario as lider_nome 
          FROM organizacoes o 
          LEFT JOIN usuarios u ON o.liderid = u.id 
          ORDER BY o.nivel DESC, o.reserva DESC 
          LIMIT $inicio, $Qtde";
$sqlo = mysqli_query($mysqli_link, $query);
?>

<div class="modern-card">
    <div class="modern-card-header">Ranking das Organizações</div>
    <div class="modern-card-body">
        
        <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 20px;">
            <img src="_img/_detalhes/msg/23.png" style="width: 80px; border-radius: 8px;">
            <div>
                <h3 style="color: gold; margin: 0 0 5px 0;">Elite Shinobi</h3>
                <p style="color: #ccc; font-size: 13px;">
                    As organizações mais poderosas disputam o controle do mundo ninja.<br>
                    Aumente o nível e a reserva do seu clã para subir no ranking!
                </p>
            </div>
        </div>

        <!-- Navegação Topo -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding: 10px; background: rgba(0,0,0,0.2); border-radius: 5px;">
            <div>
                <?php if($pagina > 0){
                    $menos = $pagina - 1;
                    echo "<a href='?p=rankorg&pagina=$menos' class='modern-btn'>&laquo; Anterior</a>";
                } ?>
            </div>
            <div style="color: #aaa; font-size: 12px;">Página <?php echo $PaginaCorrente; ?> de <?php echo $Paginas; ?></div>
            <div>
                <?php if(($pagina + 1) < $Paginas) {
                    $mais = $pagina + 1;
                    echo "<a href='?p=rankorg&pagina=$mais' class='modern-btn'>Próxima &raquo;</a>";
                } ?>
            </div>
        </div>

        <table class="modern-table" style="width: 100%;">
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">#</th>
                    <th>Organização</th>
                    <th style="text-align: center;">Vila</th>
                    <th style="text-align: center;">Nível</th>
                    <th style="text-align: center;">Guerra (V/D)</th>
                    <th style="text-align: right;">Reserva</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(mysqli_num_rows($sqlo)==0): ?>
                    <tr><td colspan="6" style="text-align: center; padding: 20px;">Nenhuma organização encontrada.</td></tr>
                <?php else: 
                    $pos = $inicio + 1;
                    $vilas = [
                		1 => 'Vila da Folha',
                		2 => 'Vila da Areia',
                		3 => 'Vila do Som',
                		4 => 'Vila da Chuva',
                		5 => 'Vila da Nuvem',
                		6 => 'Vila da Névoa',
                		7 => 'Akatsuki',
                		8 => 'Vila da Pedra',
                		9 => 'Vila da Cachoeira',
                		10 => 'Vila da Neve',
                		11 => 'Vila da Grama'
                	];

                    while($dbo = mysqli_fetch_assoc($sqlo)): 
                         $vila_nome = isset($vilas[$dbo['vila']]) ? $vilas[$dbo['vila']] : 'Desconhecida/Neutro';
                    ?>
                    <tr>
                        <td style="text-align: center; font-weight: bold; color: gold; font-size: 16px;"><?php echo $pos; ?>º</td>
                        <td>
                            <a href="?p=vieworg&id=<?php echo $dbo['id']; ?>" style="font-weight: bold; color: #fff; font-size: 14px;">
                                [<?php echo $dbo['sigla']; ?>] <?php echo $dbo['nome']; ?>
                            </a>
                            <div style="font-size: 11px; color: #777;">Líder: <span style="color: #aaa;"><?php echo $dbo['lider_nome'] ? $dbo['lider_nome'] : 'Nenhum'; ?></span></div>
                        </td>
                        <td style="text-align: center; font-size: 12px; color: #aaa;"><?php echo $vila_nome; ?></td>
                        <td style="text-align: center;"><span class="nivel-badge"><?php echo $dbo['nivel']; ?></span></td>
                        <td style="text-align: center; font-size: 12px;">
                            <span style="color: #afa;"><?php echo $dbo['war_v']; ?></span> / <span style="color: #faa;"><?php echo $dbo['war_d']; ?></span>
                        </td>
                        <td style="text-align: right; color: gold; font-weight: bold;"><?php echo number_format($dbo['reserva'],2,',','.'); ?></td>
                    </tr>
                    <?php 
                        $pos++;
                    endwhile; 
                endif; ?>
            </tbody>
        </table>

        <!-- Navegação Rodapé -->
        <div style="display: flex; justify-content: center; margin-top: 20px;">
             <?php
             // Exibir paginação simplificada se houver muitas páginas
             // Por enquanto, apenas os botões anterior/proximo já existem no topo.
             ?>
        </div>

    </div>
</div>
<?php
// mysqli_free_result($sqlo); // Opcional
?>
