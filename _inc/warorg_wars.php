<?php
include("funcoes.php");
if(!isset($_GET['view']) || empty($_GET['view'])){
?>
<table border="0" style="background:#1c1c1c;padding:2px;border:1px solid #999999;" cellspacing="0" cellpadding="5" width="100%">
	<tr>
		<th width="80" align="center">Clã</th>
		<th width="150" align="center">Título</th>
		<th align="center">Início</th>
		<th align="center">Duração</th>
		<th align="center">Estatísticas</th>
	</tr>
<?php
	$query = mysql_query("SELECT `id`,`war_id` FROM `org_wars` WHERE `org`='".$dbo['id']."' ORDER BY `id` ASC") or die(mysql_error());
	while($row = mysql_fetch_assoc($query)){
		$exe = mysql_fetch_assoc(mysql_query("SELECT * FROM `org_wars_declare` WHERE `to_org`='".$dbo['id']."' AND `war_id`='".$row['war_id']."'")) or die(mysql_error());
		$i_org = mysql_fetch_assoc(mysql_query("SELECT * FROM `organizacoes` WHERE `id`='".$exe['from_org']."' LIMIT 1")) or die(mysql_error());
?>
	<tr style="height:20px;background:#3c3c3c;">
		<td align="center"><a href="?p=vieworg&id=<?php echo $exe['from_org']; ?>"><?php echo $i_org['sigla']; ?></a></td>
		<td align="center"><?php echo $exe['title']; ?></td>
		<td align="center"><?php echo format_date($exe['time']); ?></td>
		<td align="center"><?php echo format_time_now(time()-$exe['time']); ?></td>
		<td align="center"><a href="?p=warorg&m=wars&view=<?php echo $exe['id']; ?>">&raquo; Ver Guerra</a></td>
	</tr>
<?php } ?>
</table>
<?php
}else{
	$exe = mysql_fetch_assoc(mysql_query("SELECT * FROM `org_wars_declare` WHERE `id`='".(int)$_GET['view']."'"));
	$i_org = mysql_fetch_assoc(mysql_query("SELECT * FROM `organizacoes` WHERE `id`='".$exe['from_org']."' LIMIT 1")) or die(mysql_error());

	if($dbo['liderid'] == $db['id']){
		if(isset($_GET['act']) && $_GET['act'] == "o_paz"){
			$sqq = mysql_query("SELECT `id` FROM `org_wars_paz` WHERE `war_id`='".$exe['war_id']."'");
			if(mysql_num_rows($sqq) > 0)
				$error = "Já existe um pedido de paz em aberto!";
			else{
				ofers_paz($dbo['id'],$dbo['sigla'],$exe['from_org'],$i_org['sigla'],$exe['war_id']);
				exit("<script>self.location='?p=warorg&m=wars&view=".$exe['id']."'</script>");
			}
		}
		if(isset($_GET['act']) && $_GET['act'] == "a_paz"){	
			$sqq = mysql_query("SELECT `id` FROM `org_wars_paz` WHERE `war_id`='".$exe['war_id']."'");
			if(mysql_num_rows($sqq) <= 0)
				$error = "O pedido de paz não foi encontrado.";
			else{
				acept_paz($dbo['id'],$dbo['sigla'],$exe['from_org'],$i_org['sigla'],$exe['war_id']);
				exit("<script>self.location='?p=warorg&m=wars&view=".$exe['id']."'</script>");
			}
		}
		if(isset($_GET['act']) && $_GET['act'] == "r_paz"){	
			$sqq = mysql_query("SELECT `id` FROM `org_wars_paz` WHERE `war_id`='".$exe['war_id']."'");
			if(mysql_num_rows($sqq) <= 0)
				$error = "O pedido de paz não foi encontrado.";
			else{
				reject_paz($dbo['id'],$dbo['sigla'],$exe['from_org'],$i_org['sigla'],$exe['war_id']);
				exit("<script>self.location='?p=warorg&m=wars&view=".$exe['id']."'</script>");
			}
		}

		$sqe = mysql_query("SELECT `id` FROM `org_wars_paz` WHERE `war_id`='".$exe['war_id']."'");
		if(mysql_num_rows($sqe) > 0)
			$o_paz = true;
		else
			$o_paz = false;
		$sqq = mysql_query("SELECT `id` FROM `org_wars_paz` WHERE `to_id`='".$dbo['id']."' AND `from_id`='".$exe['from_org']."'");
		if(mysql_num_rows($sqq) > 0)
			$paz = true;
		else
			$paz = false;
	}
?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
	google.load("visualization", "1", {packages:["corechart"]});

	google.setOnLoadCallback(drawChart);
	function drawChart(){
		var data = new google.visualization.DataTable();	
		data.addColumn('string', 'total');
		data.addColumn('number', '<?php echo $dbo['sigla'];?>');
		data.addColumn('number', '<?php echo $i_org['sigla'];?>');
		data.addRows(1);		
		data.setValue(0, 0, 'Total');
		data.setValue(0, 1, <?php echo $exe['total_1']; ?>);
		data.setValue(0, 2, <?php echo $exe['total_2']; ?>);

        var chart = new google.visualization.ColumnChart(document.getElementById('ALL'));
        chart.draw(data, {
			width: 500,
			height: 240,
			backgroundColor: '#EDDBA5',
			legend: 'bottom',
			title: 'Total de Batalhas'
		});
	}

	google.setOnLoadCallback(drawvit);
	function drawvit(){
		var data = new google.visualization.DataTable();	
		data.addColumn('string', 'total');
		data.addColumn('number', '<?php echo $dbo['sigla'];?>');
		data.addColumn('number', '<?php echo $i_org['sigla'];?>');
		data.addRows(1);		
		data.setValue(0, 0, 'Total');
		data.setValue(0, 1, <?php echo $exe['vit_1']; ?>);
		data.setValue(0, 2, <?php echo $exe['vit_2']; ?>);

		var chart = new google.visualization.ColumnChart(document.getElementById('VIT'));
		chart.draw(data, {
			width: 500,
			height: 240,
			backgroundColor: '#EDDBA5',
			legend: 'bottom',
			title: 'Vitórias'
		});
	}

	google.setOnLoadCallback(drawder);
	function drawder(){
		var data = new google.visualization.DataTable();	
		data.addColumn('string', 'total');
		data.addColumn('number', '<?php echo $dbo['sigla'];?>');
		data.addColumn('number', '<?php echo $i_org['sigla'];?>');
		data.addRows(1);			
		data.setValue(0, 0, 'Total');
		data.setValue(0, 1, <?php echo $exe['der_1']; ?>);
		data.setValue(0, 2, <?php echo $exe['der_2']; ?>);

		var chart = new google.visualization.ColumnChart(document.getElementById('DER'));
		chart.draw(data, {
			width: 500,
			height: 240,
			backgroundColor: '#EDDBA5',
			legend: 'bottom',
			title: 'Derrotas'
		});
	}

	google.setOnLoadCallback(drawexp);
	function drawexp(){
		var data = new google.visualization.DataTable();	
		data.addColumn('string', 'total');
		data.addColumn('number', '<?php echo $dbo['sigla'];?>');
		data.addColumn('number', '<?php echo $i_org['sigla'];?>');
		data.addRows(1);				
		data.setValue(0, 0, 'Total');
		data.setValue(0, 1, <?php echo $exe['exp_1']; ?>);
		data.setValue(0, 2, <?php echo $exe['exp_2']; ?>);

		var chart = new google.visualization.ColumnChart(document.getElementById('EXP'));
		chart.draw(data, {
			width: 500,
			height: 240,
			backgroundColor: '#EDDBA5',
			legend: 'bottom',
			title: 'Experiência acumulada'
		});
	}

	function stats(id){
		if($("#"+id).css('display') === 'none'){
			$(".allStats").hide("slow");
			$("#"+id).show("slow");
		}
	}
</script>
<table border="0" style="background:#1c1c1c;padding:2px;border:1px solid #999999;" cellspacing="0" cellpadding="5" width="100%">
	<tr><th><div align="center"><?php echo $exe['title']; ?> (<a href="?p=vieworg&id=<?php echo $exe['to_org']; ?>"><?php echo $dbo['sigla'];?></a> x <a href="?p=vieworg&id=<?php echo $exe['from_org']; ?>"><?php echo $i_org['sigla'];?></a>)</div></th></tr>
</table>
<table border="0" style="background:#1c1c1c;padding:2px;border:1px solid #999999;" cellspacing="0" cellpadding="5" width="100%">
	<tr style="height:20px;background:#3c3c3c;">
		<td width="100%">
			<table border="0" style="background:#1c1c1c;padding:2px;border:1px solid #999999;" cellspacing="0" cellpadding="5" width="100%">
				<tr style="height:20px;background:#3c3c3c;"><th colspan="2">Detalhes</th></tr>
				<tr><td><b>Titulo:</b></td><td><?php echo $exe['title']; ?></td></tr>
				<tr><td><b>Inimigo:</b></td><td><a href="?p=vieworg&id=<?php echo $exe['from_org']; ?>"><?php echo $i_org['sigla'];?></a></td></tr>
				<tr><td><b>Início:</b></td><td><?php echo format_time(time()-$exe['time']); ?></td></tr>
				<tr><td><b>Duração:</b></td><td><?php echo format_time_now(time()-$exe['time']); ?></td></tr>
			</table>
			<table border="0" style="background:#1c1c1c;padding:2px;border:1px solid #999999;" cellspacing="0" cellpadding="5" width="100%">
				<tbody>
					<tr style="height:20px;background:#3c3c3c;"><th colspan="4"><div align="center"><b>Estatísticas (Total)</b></div></th></tr>
					<tr>
						<td align="center"><span style="cursor:pointer;" onclick="stats('ALL')"><b>Batalhas</b></span></td>
						<td align="center"><span style="cursor:pointer;" onclick="stats('VIT')"><b>Vitórias</b></span></td>
						<td align="center"><span style="cursor:pointer;" onclick="stats('DER')"><b>Derrotas</b></span></td>
						<td align="center"><span style="cursor:pointer;" onclick="stats('EXP')"><b>Experiência</b></span></td>
					</tr>
				</tbody>
			</table>   
		</td>
	</tr>
</table>      
<table border="0" style="background:#1c1c1c;padding:2px;border:1px solid #999999;" cellspacing="0" cellpadding="5" width="100%">
	<tr>
		<td width="100%" align="center">
			<div id="ALL" style="display:block" class="allStats"></div>
			<div id="VIT" style="display:none" class="allStats"></div>
			<div id="DER" style="display:none" class="allStats"></div>
			<div id="EXP" style="display:none" class="allStats"></div>
		</td>
	</tr>
</table>
<table border="0" style="background:#1c1c1c;padding:2px;border:1px solid #999999;" cellspacing="0" cellpadding="5" width="100%">
	<tr>
		<td width="100%">    
			<table border="0" style="background:#1c1c1c;padding:2px;border:1px solid #999999;" cellspacing="0" cellpadding="5" width="100%">
				<tr style="height:20px;background:#3c3c3c;">
					<th width="100">&nbsp;</th>
					<th>Experiência</th>
					<th>Total</th>
					<th>Vitórias</th>
					<th>Derrotas</th>
				</tr>
				<tr style="color:#006600;">
					<td><b>Seu Clã</b></td>
					<td><div align="center"><b><?php echo format_number($exe['exp_1']); ?></b></div></td>
					<td><div align="center"><b><?php echo format_number($exe['total_1']); ?></b></div></td>
					<td><div align="center"><b><?php echo format_number($exe['vit_1']); ?></b></div></td>
					<td><div align="center"><b><?php echo format_number($exe['der_1']); ?></b></div></td>
				</tr>
				<tr style="color:#FF0000;">
					<td><b>Clã Inimigo</b></td>
					<td><div align="center"><b><?php echo format_number($exe['exp_2']); ?></b></div></td>
					<td><div align="center"><b><?php echo format_number($exe['total_2']); ?></b></div></td>
					<td><div align="center"><b><?php echo format_number($exe['vit_2']); ?></b></div></td>
					<td><div align="center"><b><?php echo format_number($exe['der_2']); ?></b></div></td>
				</tr>
				<tr>
					<td><b>Diferença</b></td>
					<td><div align="center"><b><?php echo format_number($exe['exp_1']-$exe['exp_2']); ?></b></div></td>
					<td><div align="center"><b><?php echo format_number($exe['total_1']-$exe['total_2']); ?></b></div></td>
					<td><div align="center"><b><?php echo format_number($exe['vit_1']-$exe['vit_2']); ?></b></div></td>
					<td><div align="center"><b><?php echo format_number($exe['der_1']-$exe['der_2']); ?></b></div></td>
				</tr>
				<?php if($dbo['liderid'] == $db['id']){ ?>
				<tr style="height:20px;background:#3c3c3c;">
					<?php if($paz){ ?>
					<th colspan="5"><div align="right"><b>[<a href="?p=warorg&m=wars&view=<?php echo $exe['id']; ?>&act=a_paz">&raquo; Aceitar Oferta de Paz</a>] [<a href="?p=warorg&m=wars&view=<?php echo $exe['id']; ?>&act=r_paz">&raquo; Rejeitar Oferta de Paz</a>]</b></div></th>
					<?php }elseif($o_paz){ ?>
					<th colspan="5"><div align="right"><b>[&raquo; Oferta de Paz (enviada)]</b></div></th>
					<?php }else{ ?>
					<th colspan="5"><div align="right"><b>[<a href="?p=warorg&m=wars&view=<?php echo $exe['id']; ?>&act=o_paz">&raquo; Oferta de Paz</a>]</b></div></th>
					<?php } ?>
				</tr>
				<?php } ?>
			</table>
		</td>
	</tr>
</table>
<?php } ?>