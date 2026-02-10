<?php require_once('trava.php'); ?>
<?php
$array=array("t"=>$db['taijutsu'],"n"=>$db['ninjutsu'],"g"=>$db['genjutsu']);
rsort($array);
$array2=array("t"=>$db['taijutsu'],"n"=>$db['ninjutsu'],"g"=>$db['genjutsu']);
arsort($array2);
$tam=220;
require_once('funcoes.php');

$max=250;
$src="_img/bars/bar.png";
?>
<div class="modern-card">
    <div class="modern-card-header">Atributos de <?php echo ucfirst($db['usuario']); ?></div>
    <div class="modern-card-body">
        <p style="color: var(--text-dim); font-size: 13px; margin-bottom: 20px;">
            Atributos de combate, nível e experiência de <?php echo ucfirst($db['usuario']); ?>.
        </p>

        <?php
        if($db['renegado']=='sim'){
            $sqlx=mysql_query("SELECT id FROM usuarios WHERE renegado='sim' ORDER BY nivel DESC, yens_fat DESC, vitorias DESC, derrotas ASC LIMIT 1");
            $dbx=mysql_fetch_assoc($sqlx);
            if($dbx['id']==$db['id']) $nivel='Líder da Akatsuki'; else $nivel='Nukenin';
        } else {
            $sqlx=mysql_query("SELECT id FROM usuarios WHERE vila=".$db['vila']." ORDER BY nivel DESC, yens_fat DESC, vitorias DESC, derrotas ASC LIMIT 1");
            $dbx=mysql_fetch_assoc($sqlx);
            if($dbx['id']==$db['id']){
                switch($db['vila']){
                    case 1: $nivel='Hokage'; break;
                    case 2: $nivel='Kazekage'; break;
                    case 3: $nivel='Otokage'; break;
                    case 4: $nivel='Líder da Vila da Chuva'; break;
                    case 5: $nivel='Raikage'; break;
                    case 6: $nivel='Mizukage'; break;
                    case 8: $nivel='Tsuchikage'; break;
                    case 99: $nivel='Hokage'; break;
                }
            } else $nivel=rankNinja($db['nivel']);
        }
        ?>

        <div style="background: rgba(0,0,0,0.3); border-radius: 8px; border: 1px solid var(--border-subtle); padding: 15px; margin-bottom: 25px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                <div style="padding: 10px; border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <span style="color: var(--text-dim); font-size: 11px; text-transform: uppercase;">Vila</span><br>
                    <b style="color: #fff;"><?php echo $txtvila; ?></b>
                </div>
                <div style="padding: 10px; border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <span style="color: var(--text-dim); font-size: 11px; text-transform: uppercase;">Clã</span><br>
                    <b style="color: #fff;"><?php if(($db['orgid']<=0)) echo '-'; else echo '<a href="?p=vieworg&id='.strtolower($db['orgid']).'">'.$db['orgnome'].'</a>'; ?></b>
                </div>
                <div style="padding: 10px; border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <span style="color: var(--text-dim); font-size: 11px; text-transform: uppercase;">Rank Ninja</span><br>
                    <b style="color: var(--primary-red);"><?php echo $nivel; ?></b>
                </div>
                <div style="padding: 10px; border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <span style="color: var(--text-dim); font-size: 11px; text-transform: uppercase;">Nível</span><br>
                    <b style="color: #fff;"><?php echo $db['nivel']; ?></b>
                </div>
            </div>

            <!-- Attributes Bars -->
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <!-- Taijutsu -->
                <div style="display: flex; align-items: center; gap: 15px;">
                    <span style="width: 80px; font-size: 11px; color: var(--text-dim); text-transform: uppercase; font-weight: 600;">Taijutsu</span>
                    <div style="flex: 1; height: 10px; background: rgba(255,255,255,0.05); border-radius: 5px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1);">
                        <div style="height: 100%; background: linear-gradient(90deg, #ff4d4d, #ff0000); width: <?php echo ($db['taijutsu']/$array[0])*100; ?>%;"></div>
                    </div>
                    <b style="width: 50px; text-align: right; color: #fff; font-size: 14px;"><?php echo $db['taijutsu']; ?></b>
                </div>
                <!-- Ninjutsu -->
                <div style="display: flex; align-items: center; gap: 15px;">
                    <span style="width: 80px; font-size: 11px; color: var(--text-dim); text-transform: uppercase; font-weight: 600;">Ninjutsu</span>
                    <div style="flex: 1; height: 10px; background: rgba(255,255,255,0.05); border-radius: 5px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1);">
                        <div style="height: 100%; background: linear-gradient(90deg, #4d94ff, #0066ff); width: <?php echo ($db['ninjutsu']/$array[0])*100; ?>%;"></div>
                    </div>
                    <b style="width: 50px; text-align: right; color: #fff; font-size: 14px;"><?php echo $db['ninjutsu']; ?></b>
                </div>
                <!-- Genjutsu -->
                <div style="display: flex; align-items: center; gap: 15px;">
                    <span style="width: 80px; font-size: 11px; color: var(--text-dim); text-transform: uppercase; font-weight: 600;">Genjutsu</span>
                    <div style="flex: 1; height: 10px; background: rgba(255,255,255,0.05); border-radius: 5px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1);">
                        <div style="height: 100%; background: linear-gradient(90deg, #b366ff, #8000ff); width: <?php echo ($db['genjutsu']/$array[0])*100; ?>%;"></div>
                    </div>
                    <b style="width: 50px; text-align: right; color: #fff; font-size: 14px;"><?php echo $db['genjutsu']; ?></b>
                </div>
            </div>
            
            <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.05);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                    <span style="font-size: 11px; color: var(--text-dim); text-transform: uppercase;">Experiência</span>
                    <span style="font-size: 11px; color: #fff;"><?php echo $db['exp']; ?> / <?php echo $db['expmax']; ?></span>
                </div>
                <div style="height: 6px; background: rgba(255,255,255,0.05); border-radius: 3px; overflow: hidden;">
                    <div style="height: 100%; background: #ffcc00; width: <?php echo ($db['exp']/$db['expmax'])*100; ?>%;"></div>
                </div>
            </div>
        </div>

        <?php if($db['id']<>$_SESSION['logado']){ ?>
        <!-- Anti-Bot System -->
        <div style="background: rgba(255,50,0,0.1); border: 1px solid rgba(255,50,0,0.2); border-radius: 8px; padding: 20px; text-align: center;">
            <?php $_SESSION['bot']=rand(1,5); ?>
            <?php if(isset($_GET['msg'])) echo '<div class="aviso" style="margin-bottom:15px;">Falha no teste anti-bot. Tente novamente!</div>'; ?>
            
            <h4 style="color: var(--primary-red); margin: 0 0 10px 0;">🛡️ Sistema de Segurança Shinobi</h4>
            <p style="color: var(--text-dim); font-size: 12px; margin-bottom: 20px;">
                Para atacar, clique no <b style="color:#fff; font-size:14px;"><?php echo $_SESSION['bot']; ?>º</b> botão abaixo.<br>
                <span style="color: #ff3c3c; font-size: 11px;">Atenção: 2 erros seguidos resultam em desconexão.</span>
            </p>

            <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
                <?php for($i=1; $i<=5; $i++): ?>
                <button onclick="location.href='?p=attack&bot=<?php echo $i; ?>'" 
                        class="modern-btn" 
                        style="padding: 10px 20px; font-size: 12px; min-width: 80px; <?php if($_SESSION['bot']<>$i) echo 'filter: grayscale(0.5); opacity: 0.8;'; ?>">
                    Atacar
                </button>
                <?php endfor; ?>
            </div>
        </div>
        <?php } ?>

        <div class="sep"></div>

        <!-- Account Stats -->
        <div class="modern-card" style="background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.05);">
            <div class="modern-card-header" style="font-size: 13px; height: 35px; padding: 0 15px; border-left-color: #444;">Estatísticas de Combate</div>
            <div class="modern-card-body" style="padding: 15px;">
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
                    <div style="padding: 8px; background: rgba(255,255,255,0.02); border-radius: 4px; display: flex; justify-content: space-between;">
                        <span style="color: var(--text-dim); font-size: 11px;">Yens Faturados</span>
                        <b style="color: #0f0; font-size: 11px;"><?php echo number_format($db['yens_fat'],0,',','.'); ?></b>
                    </div>
                    <div style="padding: 8px; background: rgba(255,255,255,0.02); border-radius: 4px; display: flex; justify-content: space-between;">
                        <span style="color: var(--text-dim); font-size: 11px;">Yens Perdidos</span>
                        <b style="color: #f00; font-size: 11px;"><?php echo number_format($db['yens_perd'],0,',','.'); ?></b>
                    </div>
                    <div style="padding: 8px; background: rgba(255,255,255,0.02); border-radius: 4px; display: flex; justify-content: space-between;">
                        <span style="color: var(--text-dim); font-size: 11px;">Vitórias</span>
                        <b style="color: #fff; font-size: 11px;"><?php echo $db['vitorias']; ?></b>
                    </div>
                    <div style="padding: 8px; background: rgba(255,255,255,0.02); border-radius: 4px; display: flex; justify-content: space-between;">
                        <span style="color: var(--text-dim); font-size: 11px;">Derrotas</span>
                        <b style="color: #fff; font-size: 11px;"><?php echo $db['derrotas']; ?></b>
                    </div>
                </div>
            </div>
        </div>

        <div class="sep"></div>

        <!-- Presentation -->
        <div class="modern-card" style="background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.05);">
            <div class="modern-card-header" style="font-size: 13px; height: 35px; padding: 0 15px; border-left-color: #444;">Apresentação Ninja</div>
            <div class="modern-card-body" style="padding: 15px; font-size: 13px; color: var(--text-dim); line-height: 1.6;">
                <?php if($db['config_apresentacao']=='') echo 'Nenhum texto de apresentação.'; else echo str_replace(array('<p>','</p>'),array('','<br />'),$db['config_apresentacao']); ?>
            </div>
        </div>
    </div>
</div>
