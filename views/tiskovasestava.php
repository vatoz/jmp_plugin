<?php
 
 foreach($this->getVar('vars') as $Variable=>$Value ){
//   echo $Variable." ";
//   echo $Value."<br>";   
    $$Variable=$Value;   
 }
 $Cn_coll=count($collections);
 
 
?>
<div class=no-print>
  <div style="border:thin solid orange">
      Tato část nebude vytištěná!<br>      
      Obrázky jsou zatím jen vložené, nestarám se o stránkování.<br>
      Časem se možná sestava přesune přímo do tiskových výstupů.      
    </div>
  	<br>

</div>

<style type="text/css">
    @media print {
        .no-print, .no-print * {
            display: none !important;
        }        
        #topNavContainer,#footerContainer,#leftNav{display:none;}
        #mainContent{border-right:none;  padding: 0 0 0 0;  margin-left:0px;  margin-top:0px;  width:100%;}
        #main{width:100%;  padding: 0 0 0 0;   margin-left:0px; } 
        /*#tiskovasestava{    break-after: left;  }*/
        #pictures,#prepictures{
          position: relative;
        }
        *{
            font-size: 16px;
             font-family: "Times New Roman", Times, serif;
        }
        
        
    }
</style>



<table width="100%" border="1" cellpadding="0" cellspacing="0" id="tiskovasestava">
    <tr>
        <td>
            <table border="0" width="100%">
                <tr>
                    <td>Sbírka:</td>
                    <td width="60%" colspan="3">ŽIM/002-04-02/061002</td>
                </tr>
                <tr>
                    <td>Skupina:</td>
                    <td width="60%"><?php 
                    if ($Cn_coll==1) echo "<b>";
                    if (isset($collections[0])) echo $collections[0]; 
                    if ($Cn_coll==1) echo "</b>";
                    ?></td>

                    <td align="right">Inventární číslo:</td>
                    <td valign="bottom"><b  style="font-size:20px"><?php echo $inventarniCislo; ?></b></td>
                </tr>
                <tr>
                    <td>Podskupina:</td>
                    <td><?php 
                    if ($Cn_coll==2) echo "<b>";
                    if (isset($collections[1])) echo $collections[1]; 
                    if ($Cn_coll==2) echo "</b>";
                    ?></td>
                    <td align="right">Přírůstkové číslo:</td>
                    <td><?php echo $prirustkoveCislo; ?></td>
                </tr>
                <tr>
                    <td>Druh předmětu:</td>
                    <td><?php
                     if ($Cn_coll==3) echo "<b>";
                     if (isset($collections[2])) echo $collections[2];
                     if ($Cn_coll==3) echo "</b>";
                      ?></td>
                    <td align="right">Jiné číslo:</td>
                    <td><?php echo $jineCislo; ?></td>
                <tr>
                    <td>Typ předmětu:</td>
                    <td><?php
                     if ($Cn_coll==4) echo "<b>";
                     if (isset($collections[3])) echo $collections[3];
                     if ($Cn_coll==4) echo "</b>";
                    ?></td>
                    <td align="right">Signatura:</td>
                    <td><?php echo $signatura; ?></td>
                </tr>
                <tr>
                    <td>Specifikace:</td>
                    <td colspan="3">
                        <?php
                        if ($Cn_coll==5) echo "<b>";
                        if (isset($collections[4])) echo $collections[4];
                        if ($Cn_coll==5) echo "</b>";
                        if (isset($result['ca_list_items.preferred_labels'])) echo $result['ca_list_items.preferred_labels']; //TODO VC tohe vypadá rozbitě
                        ?>
                    </td>
                    <?php /* NEBO klicove slovo (krome klicoveho slova "geniza" - zatim neni importovano, ale pocita se s nim) */ ?>
                </tr>
                <tr>
                    <td>Název předmětu:</td>
                    <td colspan="3">
                        <?php
                        if (isset($preferovany)) { //TODO tenhle blok je rozbitý
                            echo $preferovany;
                        } else {
                            echo $nepreferovany;
                        }
                        ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table border="0" width="100%">
    <tr>
        <td width="15%"></td>
        <td></td>
        <td></td>
        <td width="20%">&nbsp;</td>
    </tr>
    <tr>
        <td width="15%">Místo vzniku:</td>
        <td><?php echo $mistoVzniku; ?></td>
        <td align="right">Způsob nabytí:</td>
        <td><?php
            if (isset($result['ca_object_lots.acquisition_place_remark'])) {
                echo str_replace($result['ca_object_lots.acquisition_place_remark'], '', $result['ca_object_lots']);
            } else {
                echo $result['ca_object_lots'];
            }
            ?>
        </td>
    </tr>
    <tr>
        <td>Autor:</td>
        <td><?php echo $autor; ?></td>
        <td align="right">Místo nabytí:</td>
        <td><?php if (isset($result['ca_object_lots.acquisition_place_remark'])) echo $result['ca_object_lots.acquisition_place_remark']; ?></td>
    </tr>
    <tr>
        <td>Datum vzniku:</td>
        <td><?php if (isset($result['ca_objects.creation_date_remark'])) echo $result['ca_objects.creation_date_remark']; ?></td>
    </tr>
    <tr>
        <td valign="top">Materiál:</td>
        <td><?php if (count($materials) > 0) echo implode(', ', $materials); ?></td>
        <td align="right">Typ zásahu:</td>
        <td><?php if (isset($stav['condition_intervention'])) echo $stav['condition_intervention']; ?></td>
    </tr>
    <tr>
        <td valign="top">Technika:</td>
        <td><?php if (count($techniques) > 0) echo implode(', ', $techniques); ?></td>
        <td align="right">Popis stavu:</td>
        <td><?php echo $stav['condition']; ?></td>
    </tr>
    <?php if (count($delka) > 0) { ?>
        <tr>
            <td colspan="1">Délka:</td>
            <td colspan="3"><?php echo implode(' / ', $delka) . ' mm'; ?></td>
        </tr>
    <?php } ?>
    <?php if (count($hloubka) > 0) { ?>
        <tr>
            <td colspan="1">Hloubka:</td>
            <td colspan="3"><?php echo implode(' / ', $hloubka) . ' mm'; ?></td>
        </tr>
    <?php } ?>
    <?php if (count($jiny) > 0) { ?>
        <tr>
            <td colspan="1">Jiný:</td>
            <td colspan="3"><?php echo implode(' / ', $jiny) . ' mm'; ?></td>
        </tr>
    <?php } ?>
    <?php if (count($polomer) > 0) { ?>
        <tr>
            <td colspan="1">Poloměr:</td>
            <td colspan="3"><?php echo implode(' / ', $polomer) . ' mm'; ?></td>
        </tr>
    <?php } ?>
    <?php if (count($prumer) > 0) { ?>
        <tr>
            <td colspan="1">Průměr:</td>
            <td colspan="3"><?php echo implode(' / ', $prumer) . ' mm'; ?></td>
        </tr>
    <?php } ?>
    <?php if (count($vyska) > 0) { ?>
        <tr>
            <td colspan="1">Výška:</td>
            <td colspan="3"><?php echo implode(' / ', $vyska) . ' mm'; ?></td>
        </tr>
    <?php } ?>
    <?php if (count($sirka) > 0) { ?>
        <tr>
            <td colspan="1">Šířka:</td>
            <td colspan="3"><?php echo implode(' / ', $sirka) . ' mm'; ?></td>
        </tr>
    <?php } ?>
    <tr>
        <td>
        </td>
    </tr>
    <tr>
        <td>
        </td>
    </tr>
    <tr>
        <td>
        </td>
    </tr>
    <tr>
        <td>
        </td>
    </tr>
    <tr>
        <td colspan="4">Popis:<br/>
            <?php
            if (isset($collections[2]) && $collections[2] == 'dětská kresba') {
                echo nl2br($result['ca_objects.description']);
            } else {
                echo nl2br($result['ca_objects.remark']);
            } ?>
        </td>
    </tr>
    <tr>
        <td colspan="4" style="height: 25px;"></td>
    </tr>
    <?php
    foreach ($inscriptions as $index => $inscription) {
        ?>
        <tr>
            <td colspan="4">
                Nápis<?php if (count($inscriptions) > 1) echo " #$index"; ?>:<br/>
                <?php if (!empty($inscription->value)) { ?>
                    <!--<bdo dir="<?php echo $inscription->bdo; ?>">-->
                        <?php echo $inscription->value; ?>
                    <!--</bdo>-->
                <?php } else {
                    echo '- nevyplněno -';
                } ?>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                Překlad nápisu<?php if (count($inscriptions) > 1) echo " #$index"; ?>:<br/>
                <?php if (!empty($inscription->translation)) {
                    echo $inscription->translation;
                } else {
                    echo '- nevyplněno -';
                } ?>
            </td>
        </tr>
        <tr>
            <td colspan="4" style="height: 25px;"></td>
        </tr>
        <?php
    }
    /*
    TODO - bude se importovat pozdeji
    <tr>
        <td colspan="4"><br/>Bibliografie:<br/>
            <em>(??odkud - prosim o priklad??)</em>
        </td>
    </tr>
    */ ?>
</table>

<?php

$i=0;
//todo velikost 
foreach($result['ca_object_representations.media.medium'] as $Image){
  $i++;
  if($i==1){
    echo '<div id=prepictures></div>';
    echo '<div id=pictures style="page-break-before:right; break-before:right;" >';
    
  }
  if($i>2) break; //chci jen dvě fotky

  echo $inventarniCislo."<br>";
  echo $Image;

}
if($i>0){
  echo '</div>';
}


?>

<div class="no-print" style="color:grey;">
    <br><br><br>
    <strong>DEBUG: (netiskne se, slouží vývojářům ke kontrole)</strong><br>
    <pre>
        <?php var_export($original_result); ?>
    </pre>
</div>