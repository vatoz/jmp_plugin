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
    <ul>
    <li><a href="/jmp/Print/SbirkovaSestava?object_id=<?php echo $objectId;?>"> Sbírková sestava</a> </li>
    <li><a href="/jmp/Print/ObrazkovaSestava?object_id=<?php echo $objectId;?>"> Obrázková sestava</a></li>
    </ul>
    <hr>
    Kliknutím na obrázek ho odeberete z výtisku.
    
    
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
        *{
            font-size: 16px;
             font-family: "Times New Roman", Times, serif;
        }
        
        
        
    }
    .remove{
      border:thin solid gray;
      border-radius: 4px;
      padding-left:10px;
      padding-right:10px;
      margin-bottom: 10px;
      margin-top: 10px;
      
    }
  
</style>


<?php


$i=0;
//todo velikost 
foreach($result['ca_object_representations.media.medium'] as $Image){
  $i++;

  
  
  echo '<div id=obrazek'.$i.' onclick="jQuery('."'".'#obrazek'.$i.''."'".').remove();" >';
  
  echo '<span class="remove no-print"   onclick="jQuery('."'".'#obrazek'.$i.''."'".').remove();">Odebrat</span><br>';
  echo $Image;
  echo "<br>".$inventarniCislo."<br>";
  echo "</div></div>";

}



