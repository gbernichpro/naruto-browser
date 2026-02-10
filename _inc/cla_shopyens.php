<?php
// cla_shopyens.php
$sql = mysqli_query($mysqli_link, "SELECT * FROM table_clashop WHERE categoria='yens' ORDER BY valor ASC");

echo '<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 15px;">';
while($row = mysqli_fetch_assoc($sql)){
    $valor = $row['valor'];
    if(date('Y-m-d H:i:s') < $db['vip']) $valor = floor($valor * 0.8);
?>
    <div style="background: rgba(0,0,0,0.2); border: 1px solid #333; padding: 15px; border-radius: 8px; display: flex; flex-direction: column; justify-content: space-between;">
        <div style="display: flex; gap: 15px; margin-bottom: 15px;">
            <img src="_img/yens.png" style="width: 50px; height: 50px; margin: 5px;">
            <div style="flex: 1;">
                <b style="color: #fff; display: block;"><?php echo $row['nome']; ?></b>
                <span style="font-size: 11px; color: #777;"><?php echo $row['descricao']; ?></span>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span style="font-weight: bold; color: #fff; font-size: 14px;">
                <img src="_img/yens.png" width="12" style="filter: hue-rotate(90deg);"> <?php echo number_format($valor, 0, ',', '.'); ?> Pts
            </span>
            <form method="post" action="?p=cla_shop">
                <input type="hidden" name="buy_id" value="<?php echo $row['id']; ?>">
                <input type="hidden" name="buy_cat" value="yens">
                <input type="hidden" name="buy_page" value="yens">
                <input type="submit" class="modern-btn" style="padding: 5px 12px; font-size: 11px; background: #282;" value="Adquirir">
            </form>
        </div>
    </div>
<?php
}
echo '</div>';
?>
