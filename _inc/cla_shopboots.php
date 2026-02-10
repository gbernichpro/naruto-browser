<?php
// cla_shopboots.php
$sql = mysqli_query($mysqli_link, "SELECT * FROM table_itens WHERE clashop='sim' AND categoria='calcado' ORDER BY valor ASC");

echo '<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 15px;">';
while($row = mysqli_fetch_assoc($sql)){
    $valor = $row['valor'];
    if(date('Y-m-d H:i:s') < $db['vip']) $valor = floor($valor * 0.8);
    
    $req = "";
    if($row['reqtai'] > 0) $req .= "Atributos: " . $row['reqtai'] . " ";
    
    $bonus = "";
    if($row['tai'] > 0) $bonus .= "Tai: +" . $row['tai'] . " ";
    if($row['nin'] > 0) $bonus .= "Nin: +" . $row['nin'] . " ";
    if($row['gen'] > 0) $bonus .= "Gen: +" . $row['gen'] . " ";
?>
    <div style="background: rgba(0,0,0,0.2); border: 1px solid #333; padding: 15px; border-radius: 8px; display: flex; flex-direction: column; justify-content: space-between;">
        <div style="display: flex; gap: 15px; margin-bottom: 10px;">
            <img src="_img/itens/<?php echo $row['id']; ?>.png" onerror="this.src='_img/itens/default_calcado.png'" style="width: 60px; height: 60px; border: 1px solid #444; border-radius: 4px; background: #111;">
            <div style="flex: 1;">
                <b style="color: #fff; display: block;"><?php echo $row['nome']; ?></b>
                <span style="font-size: 11px; color: #777;"><?php echo $row['descricao']; ?></span>
            </div>
        </div>
        
        <div style="background: rgba(0,0,0,0.3); padding: 8px; border-radius: 4px; margin-bottom: 10px;">
            <div style="font-size: 10px; color: #ffd700; margin-bottom: 2px;"><b>BÔNUS:</b> <?php echo $bonus ?: 'Nenhum'; ?></div>
            <div style="font-size: 10px; color: #f55;"><b>REQ:</b> <?php echo $req ?: 'Nenhum'; ?></div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span style="font-weight: bold; color: #fff; font-size: 14px;">
                <img src="_img/yens.png" width="12" style="filter: hue-rotate(90deg);"> <?php echo number_format($valor, 0, ',', '.'); ?> Pts
            </span>
            <form method="post" action="?p=cla_shop">
                <input type="hidden" name="buy_id2" value="<?php echo $row['id']; ?>">
                <input type="hidden" name="buy_cat2" value="calcado">
                <input type="hidden" name="buy_page" value="boots">
                <input type="submit" class="modern-btn" style="padding: 5px 12px; font-size: 11px; background: #282;" value="Comprar">
            </form>
        </div>
    </div>
<?php
}
echo '</div>';
?>
