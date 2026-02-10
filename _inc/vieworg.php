<?php
if(!isset($_GET['id'])){ echo "<script>self.location='?p=home'</script>"; exit(); }
$id_org = isset($_GET['id']) ? (int)$_GET['id'] : 0; // Cast para int para segurança básica

$sqlo = mysqli_query($mysqli_link, "SELECT * FROM organizacoes WHERE id=".$id_org);
if(mysqli_num_rows($sqlo)==0){ echo "<script>self.location='?p=home'</script>"; exit(); }
$dbo = mysqli_fetch_assoc($sqlo);

// Query de membros
$sqlm = mysqli_query($mysqli_link, "SELECT m.*,u.usuario,u.nivel niveluser FROM membros m LEFT OUTER JOIN usuarios u ON m.usuarioid=u.id WHERE m.status='sim' AND m.orgid='".$id_org."' ORDER BY posicao ASC, niveluser DESC");

?>

<?php
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
	$txtvila = isset($vilas[$dbo['vila']]) ? $vilas[$dbo['vila']] : 'Desconhecida';
	
	// Data Format
	$ex=explode(' ',$dbo['data']); 
	$data=explode('-',$ex[0]); 
	$data_formatada = $data[2].'/'.$data[1].'/'.$data[0].', às '.$ex[1];
	
	// Membros Count
	$num_membros = mysqli_num_rows($sqlm);
	$max_membros = 5+($dbo['nivel']*5);
	
	// Lider (primeiro da lista se ordenado por posicao ASC, mas idealmente seria query separada ou loop check, mas vou manter logica original do php antigo que pegava $dbm no inicio)
	// O php antigo fazia chvia $dbm = mysql_fetch_assoc($sqlm); logo de cara. Isso avança o ponteiro!
	// Se eu fizer isso, perco o primeiro membro no loop lá embaixo se não resetar.
	// O código antigo usava do-while. Então ele pegava o primeiro, exibia o lider, e depois usava do-while.
	// Vou fazer diferente: fetch all ou data seek.
	
	$membros = [];
	while($row = mysqli_fetch_assoc($sqlm)){
	    $membros[] = $row;
	}
	
	// Achar lider (posicao 1 ou o primeiro da lista)
	$lider_nome = 'Nenhum';
    
    // Tenta achar posicao 1
	foreach($membros as $m){
	    if($m['posicao'] == 1) {
            $lider_nome = $m['usuario'];
            break;
        }
	}
    
    // Se não achou (e tem membros), pega o primeiro (Rank mais alto)
    if($lider_nome == 'Nenhum' && count($membros) > 0){
        $lider_nome = $membros[0]['usuario'];
    }
?>

<div class="modern-card">
    <div class="modern-card-header">
        <span style="color: #aaa;">[<?php echo $dbo['sigla']; ?>]</span> <?php echo $dbo['nome']; ?>
    </div>
    <div class="modern-card-body">
        
        <div style="display: flex; gap: 20px; flex-wrap: wrap;">
            <!-- Logo -->
            <div style="flex: 0 0 200px; text-align: center;">
                <?php if($dbo['logo']<>'') { 
                    echo '<img src="'.$dbo['logo'].'" style="max-width: 100%; border-radius: 8px; border: 1px solid #444; box-shadow: 0 0 10px rgba(0,0,0,0.5);">'; 
                } else {
                    echo '<div style="width: 100%; height: 140px; background: #222; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #555;">Sem Logo</div>';
                } ?>
            </div>
            
            <!-- Info -->
            <div style="flex: 1; min-width: 300px;">
                <table class="modern-table" style="width: 100%;">
                    <tr>
                        <td style="color: #aaa; width: 120px;">Vila Oculta:</td>
                        <td><?php echo $txtvila; ?></td>
                    </tr>
                    <tr>
                        <td style="color: #aaa;">Líder:</td>
                        <td><a href="?p=view&amp;view=<?php echo $lider_nome; ?>" style="color: gold;"><?php echo $lider_nome; ?></a></td>
                    </tr>
                    <tr>
                        <td style="color: #aaa;">Fundação:</td>
                        <td><?php echo $data_formatada; ?></td>
                    </tr>
                    <tr>
                        <td style="color: #aaa;">Nível:</td>
                        <td><span class="nivel-badge"><?php echo $dbo['nivel']; ?></span></td>
                    </tr>
                    <tr>
                        <td style="color: #aaa;">Membros:</td>
                        <td><?php echo $num_membros; ?> / <?php echo $max_membros; ?></td>
                    </tr>
                    <tr>
                        <td style="color: #aaa;">Guerras (V/D):</td>
                        <td><span style="color: #afa;"><?php echo $dbo['war_v']; ?></span> / <span style="color: #faa;"><?php echo $dbo['war_d']; ?></span></td>
                    </tr>
                    <tr>
                        <td style="color: #aaa;">Reserva:</td>
                        <td style="color: gold;"><?php echo number_format($dbo['reserva'],2,',','.'); ?> Ryous</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="sep"></div>
        
        <div style="background: rgba(0,0,0,0.2); padding: 15px; border-radius: 5px; font-style: italic; color: #ccc; margin-bottom: 20px;">
            <?php if($dbo['descricao']<>'') echo nl2br(strip_tags($dbo['descricao'])); else echo 'Nenhuma descrição para este clã.'; ?>
        </div>

        <h3 style="border-bottom: 1px solid #444; padding-bottom: 5px; color: #fff; margin-bottom: 15px;">Membros do Clã</h3>

        <table class="modern-table" style="width: 100%;">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Ninja</th>
                    <th>Rank</th>
                    <th>Cargo</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if(count($membros)==0) {
                    echo '<tr><td colspan="4" style="text-align: center; padding: 20px;">Nenhum membro neste clã.</td></tr>';
                } else {
                    $i = 1;
                    foreach($membros as $m){
                        $cargo = 'Membro';
                        $cor_cargo = '#aaa';
                        switch($m['posicao']){
            				case 1: $cargo='Líder'; $cor_cargo='gold'; break;
            				case 2: $cargo='Conselheiro'; $cor_cargo='#d5aec2'; break; // Moderador -> Conselheiro
            				case 3: $cargo='Membro'; $cor_cargo='#aaa'; break;
            			}
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td>
                        <a href="?p=view&amp;view=<?php echo strtolower($m['usuario']); ?>" style="font-weight: bold; color: #fff;">
                            <?php echo $m['usuario']; ?>
                        </a><br>
                        <span style="font-size: 10px; color: #777;">Nível <?php echo $m['niveluser']; ?></span>
                    </td>
                    <td><?php echo htmlspecialchars($m['rank']); ?></td>
                    <td style="color: <?php echo $cor_cargo; ?>; font-weight: bold;"><?php echo $cargo; ?></td>
                </tr>
                <?php $i++; } } ?>
            </tbody>
        </table>

        <?php if(($db['orgid']==0)&&($num_membros < $max_membros) && ($dbo['minimo'] <= $db['nivel'])){ ?>
            <div style="margin-top: 20px; text-align: center;">
                <button class="modern-btn" onclick="if(confirm('Deseja requisitar ingresso neste clã?')) location.href='?p=requestorg&id=<?php echo $c->encode($id_org.','.$dbo['minimo'],$chaveuniversal); ?>';">
                    Requisitar Ingresso
                </button>
            </div>
        <?php } ?>

    </div>
</div>
<?php
// mysqli_free_result não é estritamente necessário no final do script, mas boa prática se fosse script longo.
?>