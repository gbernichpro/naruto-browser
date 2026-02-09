<?php require_once('trava.php'); ?> 

<?php 
 if($_GET['en']) { 
 if($db['ticket'] < 1){ 
            echo "<script>self.location='?p=cassa&msg=1'</script>"; return; 
 }else 
  
$rand = rand(1,20); 
if($db['ticket']==13 or 12){ 
switch($rand){ 
case 1: mysql_query("update usuarios set yens=yens+2500 , ticket=ticket-1 where id=".$db['id']); echo "<script>self.location='?p=cassa&msg=11'</script>"; break; 
case 2: mysql_query("update usuarios set yens=yens+0 , ticket=ticket-1 where id=".$db['id']); echo "<script>self.location='?p=cassa&msg=12'</script>"; break; 
case 3: mysql_query("update usuarios set yens=yens+0 , ticket=ticket-1 where id=".$db['id']); echo "<script>self.location='?p=cassa&msg=13'</script>"; break; 
case 4: mysql_query("update usuarios set yens=yens+0 , ticket=ticket-1 where id=".$db['id']); echo "<script>self.location='?p=cassa&msg=14'</script>"; break; 
case 5: mysql_query("update usuarios set yens=yens+2500 , ticket=ticket-1 where id=".$db['id']); echo "<script>self.location='?p=cassa&msg=15'</script>"; break; 
case 6: mysql_query("update usuarios set yens=yens+0 , ticket=ticket-1 where id=".$db['id']); echo "<script>self.location='?p=cassa&msg=16'</script>"; break; 
case 7: mysql_query("update usuarios set yens=yens+0 , ticket=ticket-1 where id=".$db['id']); echo "<script>self.location='?p=cassa&msg=17'</script>"; break; 
case 8: mysql_query("update usuarios set yens=yens+0 , ticket=ticket-1 where id=".$db['id']); echo "<script>self.location='?p=cassa&msg=18'</script>"; break; 
case 9: mysql_query("update usuarios set yens=yens+0 , ticket=ticket-1 where id=".$db['id']); echo "<script>self.location='?p=cassa&msg=19'</script>"; break; 
case 10: mysql_query("update usuarios set yens=yens+2500 , ticket=ticket-1 where id=".$db['id']); echo "<script>self.location='?p=cassa&msg=20'</script>"; break; 
case 11: mysql_query("update usuarios set yens=yens+0 , ticket=ticket-1 where id=".$db['id']); echo "<script>self.location='?p=cassa&msg=21'</script>"; break; 
case 12: mysql_query("update usuarios set yens=yens+0 , ticket=ticket-1 where id=".$db['id']); echo "<script>self.location='?p=cassa&msg=22'</script>"; break; 
case 13: mysql_query("update usuarios set yens=yens+0 , ticket=ticket-1 where id=".$db['id']); echo "<script>self.location='?p=cassa&msg=23'</script>"; break; 
case 14: mysql_query("update usuarios set yens=yens+0 , ticket=ticket-1 where id=".$db['id']); echo "<script>self.location='?p=cassa&msg=24'</script>"; break; 
case 15: mysql_query("update usuarios set yens=yens+2500 , ticket=ticket-1 where id=".$db['id']); echo "<script>self.location='?p=cassa&msg=25'</script>"; break; 
case 16: mysql_query("update usuarios set yens=yens+0 , ticket=ticket-1 where id=".$db['id']); echo "<script>self.location='?p=cassa&msg=26'</script>"; break; 
case 17: mysql_query("update usuarios set yens=yens+0 , ticket=ticket-1 where id=".$db['id']); echo "<script>self.location='?p=cassa&msg=27'</script>"; break; 
case 18: mysql_query("update usuarios set yens=yens+0 , ticket=ticket-1 where id=".$db['id']); echo "<script>self.location='?p=cassa&msg=28'</script>"; break; 
case 19: mysql_query("update usuarios set yens=yens+0 , ticket=ticket-1 where id=".$db['id']); echo "<script>self.location='?p=cassa&msg=29'</script>"; break; 
case 20: mysql_query("update usuarios set yens=yens+2500 , ticket=ticket-1 where id=".$db['id']); echo "<script>self.location='?p=cassa&msg=30'</script>"; break; 
} 
mysql_query("update usuarios set ticket=ticket+0 where id=".$db['id']); 
}else{ 
$msg="Você precisa comprar uma vará para poder jogar!!"; 
}     
$msg="<script>$.prompt('{$msg}');</script>"; 
echo $msg; 

}   
   
     
     
     

  

 ?> 


<div class="box_top"><center><b>Cassino</b></center></div> 
<div class="box_middle"> 
<div style="background: url(../_img/_detalhes/base2.PNG);width: 720px;height: 250px;">
<table cellpadding="0" cellspacing="0" width="712" height="230"><tbody><tr><td width="150">
<img width="142" style="" src="_img/_detalhes/msg/56.png"></td><td valign="top"><br><br><br>
<div style="margin-left: -345px;margin-top: 15px;height: 0px;">
<b id="title" style="font-family: impact;font-size: 20px;font-weight: normal;color: #ffffff;"  onmouseover="style.color='#F5F5F5'" onmouseout="color.color='#ffffff'">
&raquo; Tsunade:</b></div><br>
<div style="margin-left: 0px;margin-top: 35px;font-family: arial;font-size: 12px;color: #fff;"><b>
Olá, amigo acho que você é um especialista, vejo sorte em você!</br>
Deseja tentar a sorte com o caça niqueis da vila? Basta Apostar</BR>
com seus tickets obtidos em caças ou aguardar até o proximo dia</BR>
para receber +6 tickets gratuitos!</br>

</b>
</div></td></tr></tbody></table></div>
<b><center>- Você tem, <?php echo number_format($db['ticket']); ?> Ticket(s)!.</center></b><br /> 
<table>

<div class="sep">  
</div> 
</center> 
<br></br> 



<center> 


    <?php if(isset($_GET['msg'])){ 
        switch($_GET['msg']){ 
        case 1: $msg='Você, não tem tickets para poder jogar.'; break; 
        case 11: $msg='<img src="_img/cass/ouro.gif" /> <img src="_img/cass/ouro.gif" />  <img src="_img/cass/ouro.gif" /></br>Você ganhou 2,500 Yens'; break; 
        case 12: $msg='<img src="_img/cass/bronze.gif" /> <img src="_img/cass/prata.gif" />  <img src="_img/cass/ouro.gif" /></br>Você Perdeu!'; break; 
        case 13: $msg='<img src="_img/cass/bronze.gif" /> <img src="_img/cass/prata.gif" />  <img src="_img/cass/ouro.gif" /></br>Você Perdeu!'; break; 
        case 14: $msg='<img src="_img/cass/prata.gif" /> <img src="_img/cass/bronze.gif" />  <img src="_img/cass/ouro.gif" /></br>Você Perdeu!'; break; 
        case 15: $msg='<img src="_img/cass/prata.gif" /> <img src="_img/cass/prata.gif" />  <img src="_img/cass/prata.gif" /></br>Você ganhou 2,500 Yens'; break; 
        case 16: $msg='<img src="_img/cass/bronze.gif" /> <img src="_img/cass/ouro.gif" />  <img src="_img/cass/bronze.gif" /></br>Você Perdeu!'; break; 
        case 17: $msg='<img src="_img/cass/ouro.gif" /> <img src="_img/cass/prata.gif" />  <img src="_img/cass/ouro.gif" /></br>Você Perdeu!'; break; 
        case 18: $msg='<img src="_img/cass/prata.gif" /> <img src="_img/cass/ouro.gif" />  <img src="_img/cass/ouro.gif" /></br>Você Perdeu!'; break; 
        case 19: $msg='<img src="_img/cass/ouro.gif" /> <img src="_img/cass/prata.gif" />  <img src="_img/cass/prata.gif" /></br>Você Perdeu!'; break; 
        case 20: $msg='<img src="_img/cass/bronze.gif" /> <img src="_img/cass/bronze.gif" />  <img src="_img/cass/bronze.gif" /></br>Você ganhou 2,500 Yens'; break; 
        case 21: $msg='<img src="_img/cass/bronze.gif" /> <img src="_img/cass/prata.gif" />  <img src="_img/cass/prata.gif" /></br>Você Perdeu!'; break; 
        case 22: $msg='<img src="_img/cass/ouro.gif" /> <img src="_img/cass/bronze.gif" />  <img src="_img/cass/bronze.gif" /></br>Você Perdeu!'; break; 
        case 23: $msg='<img src="_img/cass/prata.gif" /> <img src="_img/cass/prata.gif" />  <img src="_img/cass/ouro.gif" /></br>Você Perdeu!'; break; 
        case 24: $msg='<img src="_img/cass/ouro.gif" /> <img src="_img/cass/prata.gif" />  <img src="_img/cass/ouro.gif" /></br>Você Perdeu!'; break; 
        case 25: $msg='<img src="_img/cass/prata.gif" /> <img src="_img/cass/prata.gif" />  <img src="_img/cass/prata.gif" /></br>Você ganhou 2,500 Yens'; break; 
        case 26: $msg='<img src="_img/cass/ouro.gif" /> <img src="_img/cass/prata.gif" />  <img src="_img/cass/bronze.gif" /></br>Você Perdeu!'; break; 
        case 27: $msg='<img src="_img/cass/prata.gif" /> <img src="_img/cass/bronze.gif" />  <img src="_img/cass/ouro.gif" /></br>Você Perdeu!'; break; 
        case 28: $msg='<img src="_img/cass/bronze.gif" /> <img src="_img/cass/prata.gif" />  <img src="_img/cass/ouro.gif" /></br>Você Perdeu!'; break; 
        case 29: $msg='<img src="_img/cass/ouro.gif" /> <img src="_img/cass/bronze.gif" />  <img src="_img/cass/prata.gif" /></br>Você Perdeu!'; break; 
        case 30: $msg='<img src="_img/cass/bronze.gif" /> <img src="_img/cass/bronze.gif" />  <img src="_img/cass/bronze.gif" /></br>Você ganhou 2,500 Yens'; break; 
             

        } 
    echo ''.$msg.'<div class="sep"></div>'; 
    } ?> 

<table border="0" width="100%"> 
<tr style="background: none repeat scroll 0% 0% rgb(50, 50, 50);" onmouseover="style.background='#2C2C2C'" onmouseout="style.background='#323232'"> 
         <center><form method="post" action="?p=cassa&en=ok1" onsubmit="subm.value='Carregando';subm.disabled=true;"> 
          <input id="subm" name="subm" class="botao" value="Apostar" type="submit">            </form><center></center> 
        </form> 

</td></tr></table> 
</iframe></div><div class="box_bottom"></div>