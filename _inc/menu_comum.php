<?php if(isset($_SESSION['logado'])): ?>
    <?php if(date('Y-m-d H:i:s') < $db['vip']): ?>
        <div class="sidebar-box">
            <div class="sidebar-box-header"><img src="_img/star.png" style="vertical-align: middle; margin-right: 5px;" />CONTA VIP</div>
            <div class="sidebar-box-content" style="text-align:center;">
                <div style="color: #ffaa00; font-weight: bold; margin-bottom: 5px;">Plano Ativo</div>
                <table border="0" width="100%" style="font-size: 10px; color: #aaa;">
                    <tr>
                        <td>Início:</td>
                        <td align="right"><?php $ex=explode(' ',$db['vip_inicio']); $data=explode('-',$ex[0]); echo $data[2].'/'.$data[1].'/'.$data[0]; ?></td>
                    </tr>
                    <tr>
                        <td>Fim:</td>
                        <td align="right"><?php $ex=explode(' ',$db['vip']); $data=explode('-',$ex[0]); echo $data[2].'/'.$data[1].'/'.$data[0]; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <?php if($db['tipodeconta'] == 'admin'): ?>
        <div class="sidebar-box">
            <div class="sidebar-box-header"><img src="_img/star.png" style="vertical-align: middle; margin-right: 5px;" />ADMINISTRAÇÃO</div>
            <div class="sidebar-box-content" style="text-align:center;">
                <a href="?p=painel" class="botao" style="display: block; width: auto; padding: 5px; text-decoration: none; color: #000; font-size: 11px;">PAINEL ADMIN</a>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

<style>
.highlightit img {
    opacity: 0.5;
    margin-top: 5px;
    border-radius: 3px;
    transition: opacity 0.2s;
}
.highlightit:hover img {
    opacity: 1;
}
</style>