<?php require_once('trava.php'); ?>
<?php
if($db['tipodeconta'] =='admin'){
}else{
echo "<script>self.location='?p=home&msgg=5'</script>"; return;
}

$dat=date('Y-m-d H:i:s');

// --- Processamento de LOTERIA ---
if (isset($_POST['loteria']))
{
    if ((!$_POST['preco']) or (!$_POST['creditos'])) {
        echo "<script>self.location='?p=painel&msglot=2'</script>";
    }else{
        $nome_premio = mysqli_real_escape_string($mysqli_link, $_POST['premiolot']);
        $iten=mysqli_query($mysqli_link, "SELECT * FROM table_itens where nome='".$nome_premio."'");
        $it=mysqli_fetch_assoc($iten);
        
        $preco = mysqli_real_escape_string($mysqli_link, $_POST['preco']);
        mysqli_query($mysqli_link, "update `settings` set `value`='".$preco."' where `name`='preco'");
        
        $usuario_post = mysqli_real_escape_string($mysqli_link, $_POST['usuario']);
        mysqli_query($mysqli_link, "update `settings` set `value`='".$usuario_post."' where `name`='adm'");

        if($_POST['tipo']=='creditos'){
            $creditos_val = mysqli_real_escape_string($mysqli_link, $_POST['creditos']);
            mysqli_query($mysqli_link, "update `settings` set `value`='".$creditos_val."' where `name`='vencedor'");
        }elseif($_POST['tipo']=='item'){
            mysqli_query($mysqli_link, "update `settings` set `value`='".$it['id']."' where `name`='vencedor'");
        }
        
        mysqli_query($mysqli_link, "update `settings` set `value`='t' where `name`='loteria'");
        
        $termina = mysqli_real_escape_string($mysqli_link, $_POST['termina']);
        mysqli_query($mysqli_link, "update `settings` set `value`='".$termina."' where `name`='end_lotto'");
        
        $tipo_post = mysqli_real_escape_string($mysqli_link, $_POST['tipo']);
        mysqli_query($mysqli_link, "update `settings` set `value`='".$tipo_post."' where `name`='tipo'");

        echo "<script>self.location='?p=painel&msglot=1'</script>";
    }
}

// --- Processamento de INVASÃO ---
if (isset($_POST['invasao']))
{
    $nome = mysqli_real_escape_string($mysqli_link, $_POST['nome']);
    $hp = mysqli_real_escape_string($mysqli_link, $_POST['hp']);
    $premio = mysqli_real_escape_string($mysqli_link, $_POST['premioinv']);
    $reqgen = mysqli_real_escape_string($mysqli_link, $_POST['reqgen']);
    $exp = mysqli_real_escape_string($mysqli_link, $_POST['exp']);
    $expmax = mysqli_real_escape_string($mysqli_link, $_POST['expmax']);
    $data = mysqli_real_escape_string($mysqli_link, $_POST['data']);
    $usuario = mysqli_real_escape_string($mysqli_link, $_POST['usuario']);
    $nivelmin = mysqli_real_escape_string($mysqli_link, $_POST['nivelmin']);
    $nivelmax = mysqli_real_escape_string($mysqli_link, $_POST['nivelmax']);
    $maxhp = mysqli_real_escape_string($mysqli_link, $_POST['maxhp']);

    $query = mysqli_query($mysqli_link, "update `invasor` set `nome`='".$nome."',`hp`='".$hp."',`premio`='".$premio."',`reqgen`='".$reqgen."', `exp`='".$exp."',`expmax`='".$expmax."',`data`='".$data."',`abertopor`='".$usuario."', `nivelmin`='".$nivelmin."',`nivelmax`='".$nivelmax."',`hpmaximo`='".$maxhp."',`vitorias`='0',`status`='t'");
    echo "<script>self.location='?p=painel&msginv=1'</script>";
}

// --- Processamento de GUERRA ---
if (isset($_POST['iniciargr'])){
    $hora_cadastro=date('Y-m-d H:i:s');
    $hour=time()+86400;
    $hora_fim=date('Y-m-d H:i:s', $hour);
    
    $premio = mysqli_real_escape_string($mysqli_link, $_POST['premio']);
    $nivelmin = mysqli_real_escape_string($mysqli_link, $_POST['nivelmin']);
    $nivelmax = mysqli_real_escape_string($mysqli_link, $_POST['nivelmax']);
    $custo = mysqli_real_escape_string($mysqli_link, $_POST['custo']);

    mysqli_query($mysqli_link, "INSERT INTO nal_war SET premio=".$premio." ,nivelmin=".$nivelmin." , nivelmax=".$nivelmax." , custo=".$custo." ,    inicio='$hora_cadastro' , fim='$hora_fim' , status='inscricao'");
    echo "<script>alert('Guerra ninja iniciada com sucesso ');</script>";
    $novoid=mysqli_insert_id($mysqli_link);
    $number=1;

    while($number <= 11){
        mysqli_query($mysqli_link, "insert into nal_war_vilas set vilaid='".$number."' , warid='".$novoid."'");
        $number++;
    }
}

// --- Processamento de ARENA ---
if (isset($_POST['iniciararena'])){
    $hora_cadastro=date('Y-m-d H:i:s');
    $hour=time()+7200;
    $hora_fim=date('Y-m-d H:i:s', $hour);
    
    $premio = mysqli_real_escape_string($mysqli_link, $_POST['premio']);
    $nivelmin = mysqli_real_escape_string($mysqli_link, $_POST['nivelmin']);
    $nivelmax = mysqli_real_escape_string($mysqli_link, $_POST['nivelmax']);
    $custo = mysqli_real_escape_string($mysqli_link, $_POST['custo']);
    $exp = mysqli_real_escape_string($mysqli_link, $_POST['exp']);
    $insc = mysqli_real_escape_string($mysqli_link, $_POST['insc']);

    mysqli_query($mysqli_link, "INSERT INTO nal_torneio SET premio=".$premio." ,nivelmin=".$nivelmin." , nivelmax=".$nivelmax." , custo=".$custo." , exp=".$exp." , inscricoes=".$insc." ,    inicio='$hora_cadastro' , fim='$hora_fim' , status='inscricao'");
    echo "<script>alert('Arena iniciada com sucesso');</script>";
    $novoid=mysqli_insert_id($mysqli_link);
    $number=1;
}

// --- Processamento de ENVIO DE MENSAGEM (Global) ---
if(isset($_GET['act']) && $_GET['act'] == 'send_mail'){
    if(strlen(trim($_POST['assunto'])) >= 3 && strlen(trim($_POST['assunto'])) <= 20){
        // Corrigido typo 'mensahem' no original
        if(strlen(trim($_POST['mensagem'])) >= 10 && strlen(trim($_POST['mensagem'])) <= 2048){
            $users = mysqli_query($mysqli_link, "SELECT * FROM `usuarios` WHERE `status`='ativo'");
            $assunto = mysqli_real_escape_string($mysqli_link, $_POST['assunto']);
            $mensagem = mysqli_real_escape_string($mysqli_link, $_POST['mensagem']);
            
            while($row = mysqli_fetch_assoc($users)){
                mysqli_query($mysqli_link, "INSERT INTO `mensagens` (`data`,`origem`,`destino`,`assunto`,`msg`) VALUES (NOW(),'0','".$row['id']."','".$assunto."','".$mensagem."')");
            }
            echo "<script>alert('Mensagens enviadas com sucesso!');</script>";
        }
    }
}

// --- Processamento de BANIR ---
if (isset($_POST['banned'])) {
  if(strlen($_POST['banirnome']) < 1 ){
   echo "<script>alert('Nome Invalido')</script><script>self.location = '?p=painel'</script>";
  exit(); // Changed die() to exit()
  }
 
if (!isset($message)){
    $status_sel = mysqli_real_escape_string($mysqli_link, $_POST['select']);
    $banirnome = mysqli_real_escape_string($mysqli_link, $_POST['banirnome']);
    $query = mysqli_query($mysqli_link, "update `usuarios` set `status`='".$status_sel."' WHERE `usuario`='".$banirnome."'");
    echo "<script>alert('alterado status do usuário!')</script><script>self.location = '?p=painel'</script>";
  }
}

// --- Processamento de CREDITOS ---
if (isset($_POST['credito'])) {
  if(strlen($_POST['userid']) < 1 ){
   echo "<script>alert('Nome Invalido')</script><script>self.location = '?p=painel'</script>";
  exit();
  }
 
if (!isset($message)){
    $creditos = mysqli_real_escape_string($mysqli_link, $_POST['creditos']);
    $userid = mysqli_real_escape_string($mysqli_link, $_POST['userid']);
    $query = mysqli_query($mysqli_link, "update `usuarios` set `creditos`=creditos+'".$creditos."' WHERE `usuario`='".$userid."'");
    echo "<script>alert('Credito Enviado!')</script><script>self.location = '?p=painel'</script>";
  }
}

// --- Processamento de YENS ---
if (isset($_POST['yens_submit'])) { // Alterado nome do submit para diferenciar de creditos, ou usar isset yens
  if(strlen($_POST['nome']) < 1 ){ // post nome vs yens
   echo "<script>alert('Nome Invalido')</script><script>self.location = '?p=painel'</script>";
  exit();
  }
 
if (!isset($message)){
    $yens_val = mysqli_real_escape_string($mysqli_link, $_POST['yens']);
    $nome_user = mysqli_real_escape_string($mysqli_link, $_POST['nome']);
    // BUG ORIGINAL: `usuario`='".$_POST['nome']."' NO UPDATE! Isso mudaria o nome do usuário para o prório nome? Ou é WHERE?
    // Original: update `usuarios` set `yens`=yens+'".$_POST['yens']."' , `usuario`='".$_POST['nome']."'
    // Isso parece errado. Deve ser WHERE usuario = bug.
    // SE for update usuario=nome, não faz sentido.
    // Provavelmente era WHERE. Vou corrigir para WHERE.
    
    // CORREÇÃO CRITICA: Mudei para WHERE
    $query = mysqli_query($mysqli_link, "update `usuarios` set `yens`=yens+'".$yens_val."' WHERE `usuario`='".$nome_user."'");
    
    echo "<script>alert('Yens Enviado!')</script><script>self.location = '?p=painel'</script>";
  }
}

// --- Processamento de VIP ---
if (isset($_POST['vipa'])) {
  if(strlen($_POST['nome']) < 1 ){
   echo "<script>alert('Precisa por o nome do usuario que ira receber')</script><script>self.location = '?p=painel'</script>";
  exit();
  }
  if(strlen($_POST['vipdia']) < 14 ){
   echo "<script>alert('Data Invalida')</script><script>self.location = '?p=painel'</script>";
   exit();
  }
 
if (!isset($message)){
    $vipdia = mysqli_real_escape_string($mysqli_link, $_POST['vipdia']);
    $nome = mysqli_real_escape_string($mysqli_link, $_POST['nome']);
    $query = mysqli_query($mysqli_link, "update `usuarios` set `vip`='".$vipdia."' WHERE `usuario`='".$nome."'");
    echo "<script>alert('vip doado com sucesso!')</script><script>self.location = '?p=painel'</script>";
  }
}
?>

<!-- TinyMCE -->
<script type="text/javascript" src="_js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({
	mode : "textareas",
	theme: "advanced",
	plugins: "emotions",
	theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,cut,copy,paste,|,undo,redo,|,link,unlink,image,|,emotions",
	theme_advanced_buttons2 : "",
	theme_advanced_buttons3 : "",
	theme_advanced_toolbar_location : "top",
	content_css:"_css/tiny.css",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_path : false
});
</script>

<div class="modern-card">
    <div class="modern-card-header">Painel Administrativo</div>
    <div class="modern-card-body">
    
        <!-- MENSSAGEM GLOBAL -->
        <div class="modern-card" style="margin-bottom: 20px;">
            <div class="modern-card-header">Fazer comunicado aos jogadores</div>
            <div class="modern-card-body">
                <form method="post" action="?p=painel&act=send_mail" onsubmit="var b=this.querySelector('input[type=submit]'); if(b) { b.value='Carregando...'; b.disabled=true; }">
                    <span class="destaque">Assunto da Mensagem:</span><br />
                    <input type="text" name="assunto" maxlength="60" class="modern-input" style="width: 100%; margin-bottom: 10px;" required /><br />
                    
                    <span class="destaque">Mensagem:</span>
                    <textarea id="msg_msg" name="mensagem" style="width:100%; height: 100px;"></textarea>
                    <span class="sub2" style="font-size: 11px;">Max: 2048 caracteres. Envia para TODOS os ativos.</span>
                    <div class="sep"></div>
                    <div align="center"><input type="submit" id="subm" name="sub2" class="modern-btn" value="Enviar Mensagem"></div>
                </form>
            </div>
        </div>

        <!-- INVASÃO -->
        <div class="modern-card" style="margin-bottom: 20px;">
            <div class="modern-card-header">Invasão</div>
            <div class="modern-card-body">
                <?php
                if(isset($_GET['msginv'])){
                    switch($_GET['msginv']){
                        case 1: $msg='<b>Invasão iniciada</b>. Não mexa nas configurações enquanto estiver aberta.'; break;
                    }
                    echo '<div class="aviso" style="margin-bottom: 10px;">'.$msg.'</div>';
                }
                ?>
                <form method="POST" action="?p=painel">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <div><label>Nome:</label><input type="text" name="nome" class="modern-input" style="width:100%"></div>
                        <div><label>HP:</label><input type="text" name="hp" class="modern-input" style="width:100%"></div>
                        <div><label>HP Max:</label><input type="text" name="maxhp" class="modern-input" style="width:100%"></div>
                        <div><label>Prêmio Inicial:</label><input type="text" name="premioinv" class="modern-input" style="width:100%"></div>
                        <div><label>Genjutsu Req:</label><input type="text" name="reqgen" class="modern-input" style="width:100%"></div>
                        <div><label>Nível Min:</label><input type="text" value="1" name="nivelmin" class="modern-input" style="width:100%"></div>
                        <div><label>Nível Max:</label><input type="text" name="nivelmax" class="modern-input" style="width:100%"></div>
                        <div><label>EXP:</label><input type="text" value="1" name="exp" class="modern-input" style="width:100%"></div>
                        <div><label>EXP Max:</label><input type="text" name="expmax" class="modern-input" style="width:100%"></div>
                    </div>
                    <input type="hidden" id="data" name="data" value="<?php echo $dat; ?>">
                    <input type="hidden" id="usuario" name="usuario" value="<?php echo $db['usuario']; ?>">
                    
                    <div style="margin-top: 10px;">
                        <?php
                        $inv=mysqli_query($mysqli_link, "SELECT * FROM invasor");
                        $inv=mysqli_fetch_assoc($inv);
                        if($inv['status']=='t'){ ?>
                            <input class="modern-btn" type="submit" name="invasao" value="Iniciar Invasão">
                        <?php }else{ ?>
                            <b>Invasão já iniciada</b>
                        <?php } ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- LOTERIA -->
        <div class="modern-card" style="margin-bottom: 20px;">
            <div class="modern-card-header">Loteria <?php echo isset($_POST['premiolot']) ? $_POST['premiolot'] : ''; ?></div>
            <div class="modern-card-body">
                <?php
                if(isset($_GET['msglot'])){
                    switch($_GET['msglot']){
                        case 1: $msg='<b>Loteria iniciada</b>.'; break;
                        case 2: $msg='<b>ERRO</b>: Preencha todos os campos.'; break;
                    }
                    echo '<div class="aviso" style="margin-bottom: 10px;">'.$msg.'</div>';
                }
                ?>
                <form method="POST" action="?p=painel">
                    <div style="text-align: center; margin-bottom: 15px;">
                        <b>Tipo de premiação:</b><br>
                        <label style="cursor:pointer; margin-right: 20px;">
                            <img src="_img/items.jpg" border="0"><br>
                            <input name="tipo" value="item" type="radio" CHECKED> Item
                        </label>
                        <label style="cursor:pointer;">
                            <img src="_img/creditos.jpg" border="0"><br>
                            <input name="tipo" value="creditos" type="radio"> Créditos
                        </label>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <div>
                            <label>Termina em:</label>
                            <select name="termina" class="modern-input" style="width:100%">
                               <option value="<?php echo(time()+800);?>">12 minutos</option>
                               <option value="<?php echo(time()+1800);?>">30 minutos</option>
                               <option value="<?php echo(time()+3600);?>">1 hora</option>
                               <option value="<?php echo(time()+7200);?>">2 horas</option>
                               <option value="<?php echo(time()+18000);?>">5 horas</option>
                               <option value="<?php echo(time()+36000);?>">10 horas</option>
                            </select>
                        </div>
                        <div>
                            <label>Prêmio (Item):</label>
                            <select name="premiolot" class="modern-input" style="width:100%">
                            <?php
                            $sqle=mysqli_query($mysqli_link, "SELECT * FROM table_itens");
                            while($dbr=mysqli_fetch_assoc($sqle)) { echo '<option value="'.$dbr['id'].'">'.$dbr['nome'].'</option>'; }
                            ?>
                            </select>
                        </div>
                        <div>
                            <label>Prêmio (Créditos):</label>
                            <input type="text" name="creditos" size="20" class="modern-input" style="width:100%">
                        </div>
                        <div>
                            <label>Preço Ticket:</label>
                            <input type="text" value="300" name="preco" size="20" class="modern-input" style="width:100%">
                        </div>
                    </div>
                    <input type="hidden" id="usuario" name="usuario" value="<?php echo $db['usuario']; ?>">
                    
                    <div style="margin-top: 15px;">
                        <?php
                        $lo=mysqli_query($mysqli_link, "SELECT * FROM settings where name='end_lotto'");
                        $lot=mysqli_fetch_assoc($lo);
                        if($lot['value']<time()){ ?>
                            <input class="modern-btn" type="submit" name="loteria" value="Iniciar Loteria">
                        <?php }else{ ?>
                            <b>Loteria já iniciada</b>
                        <?php } ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- GUERRA E ARENA -->
        <div class="modern-card" style="margin-bottom: 20px;">
            <div class="modern-card-header">Guerra de Vilas e Arena</div>
            <div class="modern-card-body">
                <?php
                if(isset($_GET['msgwar'])){
                    switch($_GET['msgwar']){
                        case 1: echo '<div class="aviso"><b>Guerra iniciada com sucesso</b></div>'; break;
                    }
                }
                ?>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <!-- Guerra -->
                    <div>
                        <h4>Guerra de Vilas</h4>
                        <form method="post" action="?p=painel">
                            <label>Prêmio:</label><input type="text" name="premio" class="modern-input" style="width:100%"><br>
                            <label>Min Lvl:</label><input type="text" name="nivelmin" class="modern-input" style="width:100%"><br>
                            <label>Max Lvl:</label><input type="text" name="nivelmax" class="modern-input" style="width:100%"><br>
                            <label>Custo:</label><input type="text" name="custo" class="modern-input" style="width:100%"><br>
                            <input type="submit" name="iniciargr" value="Iniciar Guerra" class="modern-btn" style="margin-top: 5px;">
                        </form>
                    </div>
                    <!-- Arena -->
                    <div>
                        <h4>Arena (Torneio)</h4>
                        <form method="post" action="?p=painel">
                            <label>Prêmio:</label><input type="text" name="premio" class="modern-input" style="width:100%"><br>
                            <label>Min Lvl:</label><input type="text" name="nivelmin" class="modern-input" style="width:100%"><br>
                            <label>Max Lvl:</label><input type="text" name="nivelmax" class="modern-input" style="width:100%"><br>
                            <label>Custo:</label><input type="text" name="custo" class="modern-input" style="width:100%"><br>
                            <label>EXP Inicial:</label><input type="text" name="exp" class="modern-input" style="width:100%"><br>
                            <label>Inscrições:</label><input type="text" name="insc" class="modern-input" style="width:100%"><br>
                            <input type="submit" name="iniciararena" value="Iniciar Arena" class="modern-btn" style="margin-top: 5px;">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- FERRAMENTAS RAPIDAS -->
        <div class="modern-card">
            <div class="modern-card-header">Ferramentas de Gerenciamento</div>
            <div class="modern-card-body">
                
                <!-- BANIR -->
                <div style="margin-bottom: 20px; border-bottom: 1px solid #444; padding-bottom: 10px;">
                    <h4>Banir / Status</h4>
                    <form method='post' action="?p=painel">
                        Nome: <input type="text" name="banirnome" maxlength="14" class="modern-input">
                        Status: 
                        <select name="select" class="modern-input">
                            <option value="banido">Banir</option>
                            <option value="ativo">Desbanir (Ativo)</option>
                        </select>
                        <input class="modern-btn" type="submit" name="banned" value="Confirmar">
                    </form>
                </div>

                <!-- CRÉDITOS -->
                <div style="margin-bottom: 20px; border-bottom: 1px solid #444; padding-bottom: 10px;">
                    <h4>Doar Créditos</h4>
                    <form method='post' action="?p=painel">
                        Nome: <input type="text" name="userid" maxlength="14" class="modern-input">
                        Qtd: <input type="text" name="creditos" onkeypress='return SomenteNumero(event)' class="modern-input">
                        <input class="modern-btn" type="submit" name="credito" value="Doar">
                    </form>
                </div>

                <!-- YENS -->
                <div style="margin-bottom: 20px; border-bottom: 1px solid #444; padding-bottom: 10px;">
                    <h4>Doar Yens</h4>
                    <form method='post' action="?p=painel">
                        Nome: <input type="text" name="nome" maxlength="14" class="modern-input">
                        Yens: <input type="text" name="yens" onkeypress='return SomenteNumero(event)' class="modern-input">
                        <input class="modern-btn" type="submit" name="yens_submit" value="Doar"> <!-- Name alterado para yens_submit -->
                    </form>
                </div>

                <!-- VIP -->
                <div>
                    <h4>Doar VIP</h4>
                    <p style="font-size: 11px; color: #ff5555;">Formato: AAAA-MM-DD HH:MM:SS (Ex: 2030-01-01 12:00:00)</p>
                    <form method='post' action="?p=painel">
                        Nome: <input type="text" name="nome" maxlength="14" class="modern-input">
                        Data Fim: <input type="text" name="vipdia" class="modern-input">
                        <input class="modern-btn" type="submit" name="vipa" value="Doar VIP">
                    </form>
                </div>

            </div>
        </div>
        
    </div>
</div>

<script language='JavaScript'>
function SomenteNumero(e){
    var tecla=(window.event)?event.keyCode:e.which;  
    if((tecla>47 && tecla<58)) return true;
    else{
        if (tecla==8 || tecla==0) return true;
        else  return false;
    }
}
</script>