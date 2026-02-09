<?php
$sqlv=mysql_query("SELECT posicao FROM membros WHERE usuarioid=".$db['id']." AND orgid=".$db['orgid']." AND status='sim'");
$dbv=mysql_fetch_assoc($sqlv);
if($dbv['posicao']==3){ echo "<script>self.location='?p=myorg'</script>"; return; }
$sqlc=mysql_query("SELECT * FROM organizacoes WHERE id=".$db['orgid']);
$dbc=mysql_fetch_assoc($sqlc);
//if($dbc['liderid']<>$db['id']){ echo "<script>self.location='?p=myorg&msg=6'</script>"; return; }
if(isset($_POST['org'])){
	$logo = antiinjection($_POST['org_logo']);
	$minimo = antiinjection($_POST['org_nivel']);
	vn($minimo);
	if($minimo<0){ echo "<script>self.location='?p=home'</script>"; return; }
$desc=$_POST['org_desc'];
mysql_query("UPDATE organizacoes SET descricao='$desc', minimo='$minimo', logo='$logo' WHERE id=".$dbc['id']);
echo "<script>self.location='?p=configorg&msg=1'</script>";
}
?>
<div class="box_top">Configurar Clã</div>
<div class="box_middle"><div id="menu">
    <ul class="menu">
        <li><a href="#" class="parent" align="center"><span>Clãn</span></a>
            <ul>

                    <ul>

                            <ul>

                            </ul>
                        </li>


                            <ul>

                            </ul>
                        </li>
                    </ul>
                </li>
                <li><a href="?p=myorg" class="documents"><span>Informações</span></a></li>
                <li><a href="?p=configorg" class="documents"><span>Configurar</span></a></li>
                <li><a href="?p=addorg" class="documents"><span>Recrutar</span></a></li>
            </ul>
        </li>
        <li><a href="#" class="parent"><span>Outros</span></a>
            <ul>

                  <ul>

                    </ul>
                </li>

                    <ul>

                    </ul>
                </li>
                <li><a href="?p=donateorg" class="documents"><span>Doar yens</span></a></li>
                <li><a href="?p=warorg" class="documents"><span>Guerras do Clã</span></a></li>
                <li><a href="?p=cla_shop" class="documents"><span>Loja do clã</span></a></li>
				<li><a href="?p=investimentos" class="documents"><span>Investimentos</span></a></li>
            </ul>
        </li>

    </ul>
</div><div class="sep"></div>
<?php
if(isset($_GET['msg'])){
switch($_GET['msg']){
case 1: $msg='Configurações salvas com sucesso!'; break;
}
echo '<div class="aviso">'.$msg.'</div><div class="sep"></div>';}
?>
<form method="post" action="?p=configorg" onsubmit="subm.value='Carregando...';subm.disabled=true;">
<input type="hidden" id="org" name="org" value="1">
<fieldset><legend>Configuração do Clã</legend>
<span class="destaque">Logotipo do Clã:</span><br />
<input type="text" id="org_logo" name="org_logo" value="<?php echo $dbc['logo']; ?>" size="50"><br />
<span class="sub2">Digite o endereço de uma imagem para o logotipo do clã (tamanho: 195x140 pixels).</span>
<div class="sep"></div>
<span class="destaque">Nível Mínimo:</span><br />
<input type="text" id="org_nivel" name="org_nivel" value="<?php echo $dbc['minimo']; ?>" maxlength="3" size="6"><br />
<span class="sub2">Digite o nível mínimo para recrutar membros para o clã.</span>
<div class="sep"></div>
</fieldset>
<fieldset><legend>Descrição do Clã</legend>
<textarea class="campo" id="org_desc" name="org_desc" style="width:100%;height:250px;"><?php echo $dbc['descricao']; ?></textarea>
<span class="sub2">Digite a descrição de seu clã.</span>
</fieldset>
<div class="sep"></div>
<div align="center"><input type="submit" id="subm" name="subm" class="botao" value="Salvar Configurações" /></div>
</form>
</div>
<div class="box_bottom"></div>