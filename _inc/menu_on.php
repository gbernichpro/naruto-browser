<div class="sidebar-container">
    <?php require_once('trava.php'); ?>
    <?php
    switch($db['vila']){
        case 1: $vila='folha'; if($db['renegado']=='sim') $txtvila='Akatsuki (Vila da Folha)'; else $txtvila='Vila da Folha'; break;
        case 2: $vila='areia'; if($db['renegado']=='sim') $txtvila='Akatsuki (Vila da Areia)'; else $txtvila='Vila da Areia'; break;
        case 3: $vila='som'; if($db['renegado']=='sim') $txtvila='Akatsuki (Vila do Som)'; else $txtvila='Vila do Som'; break;
        case 4: $vila='chuva'; if($db['renegado']=='sim') $txtvila='Akatsuki (Vila da Chuva)'; else $txtvila='Vila da Chuva'; break;
        case 5: $vila='nuvem'; if($db['renegado']=='sim') $txtvila='Akatsuki (Vila da Nuvem)'; else $txtvila='Vila da Nuvem'; break;
        case 6: $vila='nevoa'; if($db['renegado']=='sim') $txtvila='Akatsuki (Vila da Névoa)'; else $txtvila='Vila da Névoa'; break;
        case 8: $vila='pedra'; if($db['renegado']=='sim') $txtvila='Akatsuki (Vila da Pedra)'; else $txtvila='Vila da Pedra'; break;
        case 9: $vila='cachoeira'; if($db['renegado']=='sim') $txtvila='Akatsuki (Vila da Cachoeira)'; else $txtvila='Vila da Cachoeira'; break;
        case 10: $vila='neve'; if($db['renegado']=='sim') $txtvila='Akatsuki (Vila da Neve)'; else $txtvila='Vila da Neve'; break;
        case 11: $vila='grama'; if($db['renegado']=='sim') $txtvila='Akatsuki (Vila da Grama)'; else $txtvila='Vila da Grama'; break;
        case 99: $vila='folha'; $txtvila='Vila da Folha'; break;
        default: $vila='folha'; $txtvila='Vila da Folha'; break;
    } ?>

    <?php if((!isset($_GET['p'])) || ($_GET['p']<>'attack')): ?>
        <?php if((!isset($_GET['p'])) || ($_GET['p']<>'view' && $_GET['p']<>'prepare')): ?>
            <div align="center" style="background:url(_img/personagens/no_avatar.jpg) no-repeat top; height:150px; margin-bottom: 0px; border: 1px solid #333;" id="tip-direita" original-title="Seu personagem é <b><?php echo $db['personagem']; ?></b>">
            <a href="<?php echo ($db['avatar']==0) ? '?p=avatar' : '?p=config&type=avat'; ?>">
                <img src="_img/personagens/<?php echo $db['personagem']; ?>/<?php echo $db['avatar']; ?>.jpg" width="170" height="150" border="0" />
            </a>
        </div>
        <div align="center" style="margin-bottom: 2px; margin-top: -1px;">
            <img src="_img/vilas/<?php if($db['renegado']=='sim') echo 'akatsuki_'; ?><?php echo $vila; ?>.jpg" id="tip-cima" original-title="Você reside na vila: <br><b style='font-size:12px;'><?php echo $txtvila; ?></b>" />
        </div>
            <div id="msg" style="margin-bottom:10px;">
                <?php if($db['energia'] < $db['energiamax']): ?>
                    <script>
                    $(document).ready(function(){
                        $("#energia_icon").click(function(){ top.location='?p=ramen'; });
                    });
                    </script>
                    <div align="center">
                        <img style='opacity:1; cursor:pointer;' src='img/energia.png' id='energia_icon' class='icons-no-menu' onmouseover="Tip('Regenere sua energia agora, indo ao <b>Ichiraku Bar</b>.')" onmouseout='UnTip();'>
                    </div>
                <?php endif; ?>

                <?php
                $sqlm=mysql_query("SELECT count(id) conta FROM mensagens WHERE destino=".$db['id']." AND status='naolido'");
                $dbm=@mysql_fetch_assoc ($sqlm);
                $sqla=mysql_query("SELECT count(id) conta FROM relatorios WHERE inimigoid=".$db['id']." AND status='nao'");
                $dba=@mysql_fetch_assoc ($sqla);
                
                if($dbm['conta']>0){
                    echo '<div class="action"><a href="?p=messages">'.$dbm['conta'].' nova'.($dbm['conta']>1 ? 's' : '').' mensage'.($dbm['conta']>1 ? 'ns' : 'm').'!</a></div>';
                }
                if($dba['conta']>0){
                    echo '<div class="action"><a href="?p=reports">Você foi atacado '.$dba['conta'].' vez'.($dba['conta']>1 ? 'es' : '').'!</a></div>';
                }
                ?>
            </div>
        <?php endif; ?>

        <?php
        if(!isset($_GET['p']) || ($_GET['p']!='view' && $_GET['p']!='prepare')){
            $tempo = time();
            if ($db['premiodiario'] <= $tempo){ ?>
                <script type="text/javascript">
                $(function(){
                    $(".receber").click(function(){
                        var botao = $(this);
                        if (botao.data('carregando')) return false;
                        botao.data('carregando', true).text('Recebendo...');
                        $.ajax({
                            url: '_ajax/ajax_diario.php',
                            type: 'POST',
                            dataType: 'html',
                            data: {csrf_token: <?php echo json_encode(get_csrf_token()); ?>},
                            success: function(resposta) {
                                $('#recebeajax').html(resposta);
                                botao.closest('.action').hide();
                                if (window.gameToast) {
                                    var texto = $('<div>').html(resposta).text().trim() || 'Recompensa recebida com sucesso.';
                                    window.gameToast(texto, 'success');
                                }
                            },
                            error: function(xhr) {
                                var mensagem = xhr.responseText || 'Não foi possível receber a recompensa. Tente novamente.';
                                $('#recebeajax').text(mensagem);
                                botao.data('carregando', false).text('Recompensa Diária');
                                if (window.gameToast) window.gameToast($('<div>').html(mensagem).text(), 'error');
                            }
                        });
                        return false;
                    });
                });
                </script>
                <div id="msg" style="margin-bottom:10px;">
                    <div id="recebeajax"></div>
                    <div class="action"><a href="javascript:void(0)" class="receber"><b>Recompensa Diária</b></a></div>
                </div>
            <?php }
        } ?>
    <?php endif; ?>

    <div class="sidebar-title">Principal</div>
    <div class="sidebar-link-container"><a href="?p=home" class="sidebar-link"><span class="sidebar-icon"></span>Inicio</a></div>
    <div class="sidebar-link-container"><a href="?p=jornal" class="sidebar-link"><span class="sidebar-icon"></span>Jornal</a></div>
    <div class="sidebar-link-container"><a href="?p=messages" class="sidebar-link"><span class="sidebar-icon"></span>Mensagens</a></div>
    <div class="sidebar-link-container"><a href="?p=reports" class="sidebar-link"><span class="sidebar-icon"></span>Relatórios</a></div>
    <div class="sidebar-link-container"><a href="?p=config" class="sidebar-link"><span class="sidebar-icon"></span>Configurações</a></div>

    <div class="sidebar-title">Personagem</div>
    <div class="sidebar-link-container"><a href="?p=book" class="sidebar-link"><span class="sidebar-icon"></span>Bingo Book</a></div>
    <div class="sidebar-link-container"><a href="?p=inventory" class="sidebar-link"><span class="sidebar-icon"></span>Inventário</a></div>
    <div class="sidebar-link-container"><a href="?p=pontos" class="sidebar-link"><span class="sidebar-icon"></span>Atributos</a></div>
    <div class="sidebar-link-container"><a href="?p=jutsus" class="sidebar-link"><span class="sidebar-icon"></span>Jutsus</a></div>
    <?php if($db['orgid']>0) { ?>
        <div class="sidebar-link-container"><a href="?p=myorg" class="sidebar-link"><span class="sidebar-icon"></span>Meu Clã</a></div>
    <?php } else { ?>
        <div class="sidebar-link-container"><a href="?p=org" class="sidebar-link"><span class="sidebar-icon"></span>Clãs</a></div>
    <?php } ?>

    <div class="sidebar-title">Outros</div>
    <div class="sidebar-link-container"><a href="javascript:void(0);" onclick="document.getElementById('city').style.display='block'" class="sidebar-link"><span class="sidebar-icon"></span>Cidade</a></div>
    <div class="sidebar-link-container"><a href="?p=rank" class="sidebar-link"><span class="sidebar-icon"></span>Ranking</a></div>
    <div class="sidebar-link-container"><a href="?p=rankorg" class="sidebar-link"><span class="sidebar-icon"></span>Ranking [org]</a></div>
    <div class="sidebar-link-container"><a href="?p=loteria" class="sidebar-link"><span class="sidebar-icon"></span>Loteria Ninja</a></div>
    <div class="sidebar-link-container"><a href="?p=cassa" class="sidebar-link"><span class="sidebar-icon"></span>Cassino Ninja</a></div>
    <div class="sidebar-link-container"><a href="?p=doarbanco" class="sidebar-link"><span class="sidebar-icon"></span>Banco yens</a></div>
    <div class="sidebar-link-container"><a href="?p=akatsuki" class="sidebar-link"><span class="sidebar-icon"></span>Akatsuki</a></div>
    <div class="sidebar-link-container"><a href="#" class="sidebar-link"><span class="sidebar-icon"></span>Chat</a></div>

    <div class="sidebar-title">Especiais</div>
    <div class="sidebar-link-container"><a href="?p=invasao" class="sidebar-link"><span class="sidebar-icon"></span>Invasão</a></div>
    <div class="sidebar-link-container"><a href="?p=guerra" class="sidebar-link"><span class="sidebar-icon"></span>Guerra de Vilas</a></div>
    <div class="sidebar-link-container"><a href="?p=torneio" class="sidebar-link"><span class="sidebar-icon"></span>Arena</a></div>

    <div class="sidebar-title">Vip</div>
    <div class="sidebar-link-container"><a href="?p=vip" class="sidebar-link"><span class="sidebar-icon"></span>Vip</a></div>
    <div class="sidebar-link-container"><a href="?p=credshop" class="sidebar-link"><span class="sidebar-icon"></span>Credshop</a></div>

    <?php require_once('friendlist.php'); ?>
    <?php require_once('menu_comum.php'); ?>
</div>
