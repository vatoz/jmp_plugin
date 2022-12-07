
<?php 
$dsn = 'mysql:dbname=' . __CA_DB_DATABASE__ . ';host=' .__CA_DB_HOST__ . '';
$user = __CA_DB_USER__;
$password = __CA_DB_PASSWORD__;
global $pdo;

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}


//return autoincrement integer identifier 
function aci(){
    static $aci_int=-1;
    $aci_int++;
    return ' id="aci_'.$aci_int.'" ';
}


function selplaces($SQL){
    return "SELECT place_id,  name FROM `ca_place_labels` WHERE `place_id` IN ("
    .$SQL.
    ") and locale_id=1  order by name";
}

    $autocompletes=array(
        'lastplace'=>selplaces("SELECT place_id         FROM `ca_entities_x_places`         WHERE `type_id` = '170'"),

        'tdt'=>"SELECT occurrence_id, name
        FROM `ca_occurrence_labels`
        WHERE `locale_id` = '1' AND `name` LIKE '%-> Terezín%'
        AND occurrence_id in (SELECT occurrence_id FROM `ca_occurrences` WHERE `type_id` = '94')
        
        ",

        'tnv'=>"SELECT occurrence_id, name
        FROM `ca_occurrence_labels`
        WHERE `locale_id` = '1' AND (`name` LIKE '%Terezín ->%' OR `name`  NOT LIKE '%Terezín%')
        AND occurrence_id in (SELECT occurrence_id FROM `ca_occurrences` WHERE `type_id` = '94')
        
        ",

        'placedeparture'=> selplaces("SELECT  distinct place_id
        FROM `ca_places_x_occurrences`
        WHERE `type_id` = '69' 
        AND occurrence_id in (SELECT occurrence_id FROM `ca_occurrences` WHERE `type_id` = '94')
        
        "),

'target'=> selplaces("SELECT  distinct place_id
FROM `ca_places_x_occurrences`
WHERE `type_id` = '70' 
AND occurrence_id in (SELECT occurrence_id FROM `ca_occurrences` WHERE `type_id` = '94')
"), 
'fate'=>'SELECT item_id,name_singular
FROM `ca_list_item_labels`
WHERE `item_id` in (SELECT item_id
FROM `ca_list_items`
WHERE `list_id` = 48 AND `parent_id` IS NOT NULL) and locale_id=1'
        );


$f_checkbox=array("onlyone","fuzzyname");
$f_fuzzy=array();
$f_text=array("fname","lname","lastplace","deathplace","remark", "reason","placedeparture","departure2place","target","fate","tdt","tnv");
$f_date=array("born","death","arrival","departure");


function fuzzyf($name,$endelement='type="text"' ){
    
    $candidate="";
    if(isset($_REQUEST[$name.'_fuzzy'])){
        $candidate=$_REQUEST[$name.'_fuzzy'];
    }else{
        $candidate='exact';
    }

    $result='
<div> 
    <label for="'.$name.'_fuzzy">Přesně toto:</label><br>
    <input type="radio" id="'.$name.'_fuzzy_exact" name="'.$name.'_fuzzy" value="exact" '.($candidate=='exact'?'checked':'').'>
</div>
<div>
    <label for="'.$name.'_fuzzy">Větší:</label><br>
    <input type="radio" id="'.$name.'_fuzzy_bigger" name="'.$name.'_fuzzy" value="bigger"  '.($candidate=='bigger'?'checked':'').'>
</div>
<div>
    <label for="'.$name.'_fuzzy">Menší:</label><br>
    <input type="radio" id="'.$name.'_fuzzy_smaller" name="'.$name.'_fuzzy" value="smaller"  '.($candidate=='smaller'?'checked':'').'>
</div>
<div>
    <label for="'.$name.'_fuzzy">Interval:</label><br>
    <input type="radio" id="'.$name.'_fuzzy_interval" name="'.$name.'_fuzzy" value="interval">';
    
    $candidate="";
    if(isset($_REQUEST[$name.'_fuzzy_val'])){
        $candidate=$_REQUEST[$name.'_fuzzy_val'];
    }

    $result.=
    '
    <input '.$endelement .' id="'.$name.'_fuzzy_val" name="'.$name.'_fuzzy_val"  value="'.htmlspecialchars($candidate).'" >
</div>

';

return $result;



}

function diva($text){
    echo  "
    <div class=a ".aci().">
    ";

    $numargs = func_num_args();
    $arg_list = func_get_args();
    for ($i = 0; $i < $numargs; $i++) {
        echo  $arg_list[$i] . "\n";
    }

    echo "</div>";
}

function row($text){
    echo  "
    <div class=row ".aci().">
    ";

    $numargs = func_num_args();
    $arg_list = func_get_args();
    for ($i = 0; $i < $numargs; $i++) {
        echo  $arg_list[$i] . "\n";
    }

    echo "</div>";

};
function rowa($text){
    $result=  "
    <div class=row ".aci().">
    ";

    $numargs = func_num_args();
    $arg_list = func_get_args();
    for ($i = 0; $i < $numargs; $i++) {
        $result.=  $arg_list[$i] . "\n";
    }

    $result.= "</div>";
    return $result;

};

function chckf($name,$description){
    $checked=0;
    if(isset($_REQUEST[$name])){
        $checked=1;
    }

    return '
    <div>
    <label  for="'.$name.'">'.$description.'</label><br>
    <input type="checkbox" id="'.$name.'" name="'.$name.'" '.($checked?" checked":" " ).'>
    </div>
    ';
}


//Displays infoicon with hover text
function info($text){
    return '<div class="bundleDocumentationLink jmp_info no-print" > <i class="caIcon fa fa-info-circle infoIcon " style="font-size: 15px;"></i>
    <span>'.$text.' </span></div>';
}


/*
display select with some needed variants
@param $name string  Used to name html elements
@param $description string  
@param $SQLVariants string SQL query with two columns (aka key and value)
@return string

*/
function listf($name,$description,$SQLvariants){

    $candidate="";
    if(isset($_REQUEST[$name])){
        $candidate=$_REQUEST[$name];
    }

    $val= '
    <div>
    <label  id="label_'.$name.'" for="'.$name.'">'.$description.'</label><br>
    <select   id="'.$name.'" name="'.$name.'" >';
    $val.= '<option value=""></option>';
    $val.= '<option value="empty"'.('empty'==$candidate?' selected':'') .' >(prázdné)</option>';
    $val.= '<option value="full"'.('full'==$candidate?' selected':'') .'>(vyplněné)</option>';
    

    global $pdo;

    
    if($SQLvariants){
    $dotaz = $pdo->query($SQLvariants,PDO::FETCH_NUM);       
    foreach($dotaz  as $Row){ 
            $val.= '<option value="'.$Row[0].'"'.($Row[0]==$candidate?' selected':'') .'>'.htmlspecialchars($Row[1]).'</option>';
    }
    }

    $val.='</select></div>'."\n";

    return $val;
}


function date_el_f($name,$description, $min,$max){
    $candidate="";
    if(isset($_REQUEST[$name])){
        $candidate=$_REQUEST[$name];
    }

    return '
    <div>
    <label for="'.$name.'" id="label_'.$name.'">'.$description.'</label><br>
    <input  id="'.$name.'" name="'.$name.'" value="'.htmlspecialchars($candidate).'" 
    type="date" min="'.$min.'" max="'.$max.'"
    
    ><br>
    </div>
    ';

}
function datef($name,$description,$min="1800-01-01",$max="1945-12-29"){
    return date_el_f($name,$description,$min,$max).fuzzyf($name,'type="date" min="'.$min.'" max="'.$max.'"');
}

function txtf($name,$description){
    $candidate="";
    if(isset($_REQUEST[$name])){
        $candidate=$_REQUEST[$name];
    }

    return '
    <div>
    <label for="'.$name.'" id="label_'.$name.'">'.$description.'</label><br>
    <input type="text" id="'.$name.'" name="'.$name.'" value="'.htmlspecialchars($candidate).'" ><br>
    </div>
    ';
    
}



?>

<form>
    <?php


    row(
        txtf('fname',"Jméno"),
        txtf('lname',"Příjmení"),
        chckf('fuzzyname',"Podobná"),
        info ("ve standartním hledání se automaticky hledá částečná shoda. (dá se to změnit). <br>Při hledání podobných se hledají slova o několik málo záměn jiná. To budeme ladit.")
    );
    
    row(
        listf('lastplace',"Poslední bydliště", $autocompletes['lastplace']       )
        ,info("Tady zvažme hledání na celou hierarchii.") //todo
    );


    row(
        datef('born',"Narozen")
    );

    row("<div style='background-color:green;'>Řádky výše už fungují</div>", info("A občas i něco níže, co bylo podobné něčemu výše."));

    row(
        datef('death',"Datum úmrtí")
    );

    row(
        txtf('deathplace',"Místo úmrtí"),
        txtf('reason',"Důvod deportace"),
        txtf('remark',"Poznámka")
    );

    row(
        txtf('regnr',"Registrační číslo v protektorátu"),
        fuzzyf('regnr')        
    );


    
            row(
                listf("tdt","Transport do Terezína", $autocompletes['tdt'])
            );




            row(
                txtf('trnr',"číslo v transportu"), fuzzyf('trnr')
            );

            row(
                datef('arrival',"datum příjezdu")
            );


            row(
                listf('placedeparture',"místo odjezdu", $autocompletes['placedeparture'])
            );
        
            row(
                listf("tnv","Transport na východ", $autocompletes['tnv'])
            );


            row(
            
                txtf('tnv_nr',"číslo v transportu"));
            row(
                datef('departure',"datum odjezdu")
            );

            row(
                listf('departure2place',"místo odjezdu", $autocompletes['departure2place']),    listf('target',"Cíl", $autocompletes['target'])
            
            
            );
        
    row(info("Osud, musím zjistit jak se ukládá, nebo co tím bylo myšleno"),
        listf('fate',"Osud", $autocompletes['fate'])
    
    );

    row("<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>",
        chckf('onlyone',"stačí splnit jednu")
    
    );


?><div class=row><div>
<input type=submit>
</div></div>
<br><br>
<?php 
echo info("čeština pro datepicker, chybějící pole, todo");
echo info("chceme víc exportů?");
echo info("jen mající totožné/jen nemající totožné?");
echo info("u fuzzy inputů nevyplněné");




?>

</form>

<style type="text/css">
    @media print {
        .no-print, .no-print * , form{
            display: none !important;
        }        
        #topNavContainer,#footerContainer,#leftNav{display:none;}
        #mainContent{border-right:none;  padding: 0 0 0 0;  margin-left:0px;  margin-top:0px;  width:100%;}
        #main{width:100%;  padding: 0 0 0 0;   margin-left:0px; } 
        /*#tiskovasestava{    break-after: left;  }*/
        }
        
        .row div{
          float:left;
          margin-right :1em;
        }
        .row{clear:both;padding-top:5px;}

        #mainContent{ width:100%;margin-left:2em;}
        #main{width:100%;}
        #leftNav{display:none;       
        }    

        td{padding-left:5px;}
        .jmp_info span{display:none;}
        .jmp_info:hover span{display:block;position:absolute;border:thin solid gray;background-color:white;padding:10px 10px 10px 10px; }

    
</style>


<div id=result>

<h1> Seznam obětí holocaustu</h1>
<?php






function find_name($name,$is_first=1, $fuzzy=0){
    global $pdo;
    $SQL="
    SELECT ".($fuzzy?($is_first? 'forename, other_forenames, middlename, ':'surname,'   ):''    ) .   " entity_id
    FROM `ca_entity_labels`
    where entity_id in (SELECT entity_id
    FROM `ca_entities`
    WHERE `type_id` = '67' AND `deleted` = '0')
    ";

    if(!$fuzzy){
        if(!$is_first){
            $SQL.= "AND surname like ". $pdo->quote("%".$name."%");
        }else{
            $SQL.= "AND ( ".                        
             "forename like ". $pdo->quote("%".$name."%") .
             " or other_forenames like ". $pdo->quote("%".$name."%") .
             "or middlename like ". $pdo->quote("%".$name."%") . 
             ")";             
        }
        return "( ca_entities.entity_id  in (".$SQL. ") )";
    }



      
    $dotaz = $pdo->query($SQL);
    $ok=array();
    
    foreach($dotaz  as $Row){
        if(!$fuzzy){
            $ok[]=$Row['entity_id'];
        }else{
            foreach(($is_first? array("forename", "other_forenames", "middlename"):array("surname")) as $type){
                if($Row[$type]<>""){
                    if(levenshtein($Row[$type],$name)<3){
                        $ok[]=$Row['entity_id'];
                    }                    
                }
            }
        }        
    }

 if( count($ok)){
        return "( ca_entities.entity_id  in (". implode(",",$ok). ") )";
 }

 
 return "(0=1)";
}

//read requests
$values=array();

foreach($f_checkbox as $field){
    $values[$field]=isset($_REQUEST[$field]);
}

//$f_fuzzy=array();

foreach($f_text as $field){
    $values[$field]=isset($_REQUEST[$field])?$_REQUEST[$field]:"" ;
}


$f_date=array("born","death","arrival","departure");

foreach($f_date as $field){
    foreach(array($field,  $field."_fuzzy",$field."_fuzzy_val") as $field2){
        $values[$field2]=isset($_REQUEST[$field2])?$_REQUEST[$field2]:"" ;
    }
}


//prepare queries
$limits=array();
if($values['fname']<>""){
    $limits[]=find_name($values['fname'],1, $values['fuzzyname'] );
}
if($values['lname']<>""){
    $limits[]=find_name($values['lname'],0, $values['fuzzyname'] );
}

if($values['lastplace']<>""){
    $limits['lastplace']= '(ca_entities.entity_id '.($values['lastplace']=='empty'?' NOT':'').' in (
        SELECT entity_id  FROM `ca_entities_x_places` WHERE `type_id` = 170        '.
        (intval($values['lastplace'])? ' and place_id = '.intval($values['lastplace']) :"" )
        .' )  )';
}


foreach(array("tdt","tnv") as $occ){
    if($values[$occ]<>""){
        $limits[$occ]= '(ca_entities.entity_id '.($values[$occ]=='empty'?' NOT':'').' in (
            SELECT entity_id  FROM `ca_entities_x_occurrences` WHERE  1=1         '.
            (intval($values[$occ])? ' and occurrence_id = '.intval($values[$occ]) :"" )
            .' )  )';
    }
}



if($values['fate']<>""){ //toto nefunguje todo opravit
    $limits['fate']= '(ca_entities.entity_id '.($values['fate']=='empty'?' NOT':'').' in (
        SELECT ca_attributes.row_id
        FROM `ca_attributes` left join 
        `ca_attribute_values` on ca_attributes.attribute_id=ca_attribute_values.attribute_id
        WHERE `table_num` = 20 AND ca_attributes.`element_id` = 189'.
        (intval($values['fate'])? ' and item_id = '.intval($values['fate']) :"" )
        .' )  )';
}

$




$aaautocompletes=array(


    'placedeparture'=> selplaces("SELECT  distinct place_id
    FROM `ca_places_x_occurrences`
    WHERE `type_id` = '69' 
    AND occurrence_id in (SELECT occurrence_id FROM `ca_occurrences` WHERE `type_id` = '94')
    
    "),

'target'=> selplaces("SELECT  distinct place_id
FROM `ca_places_x_occurrences`
WHERE `type_id` = '70' 
AND occurrence_id in (SELECT occurrence_id FROM `ca_occurrences` WHERE `type_id` = '94')
")
    


);

function ymd2decimal($ymd){
    if(preg_match("/^([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$ymd,$matches)){
        
        return floatval(  $matches[1].".". substr( "0".$matches[2],-2 ). substr( "0".$matches[3],-2 )   );
    }else {return 0;}
}


if(ymd2decimal($values['born'])>0){

    switch ($values['born_fuzzy']){
        case "exact":
            $datepart="lft.value_decimal1 = ".ymd2decimal($values['born']);
            break; 
        case "bigger":
            $datepart="lft.value_decimal1 > ".ymd2decimal($values['born']);
            break; 
        case "smaller":            
            $datepart="lft.value_decimal2 < ".ymd2decimal($values['born']);
            break; 
        case "interval":
            $datepart="( lft.value_decimal2 <= ".ymd2decimal($values['born']) . " AND ".
            "lft.value_decimal >=  ".ymd2decimal($values['born_fuzzy_val'] ). " )";
            break; 

    }
    
    $limits['born']= '(ca_entities.entity_id in (

        select attr.row_id from
        ca_attribute_values lft
        left join  ca_attribute_values rght on lft.attribute_id =rght.attribute_id
        left join ca_attributes attr on  lft.attribute_id =attr.attribute_id 
        where '.$datepart.'  and lft.element_id=61 and rght.element_id=60 and rght.item_id=102 
        and attr.table_num=20 and attr.element_id=59

        )
        )';



}else{
    echo info($values['born']);

} 






//execute query
if(!count($limits)){
    $limits['alt'] ="(1=2)";
}



$SQL= "SELECT  ca_entities.entity_id , forename,surname,prefix from ca_entities left join ca_entity_labels on  
ca_entities.entity_id = ca_entity_labels.entity_id
 where deleted=0 and ca_entities.type_id=67 and ca_entity_labels.locale_id=1 and("

.
implode( $value['onlyone']?" OR \n":" AND \n",$limits) 

.") order by displayname";

foreach($limits as $limit ){
    echo info ("Použit filtr:<br><pre>".$limit."</pre>");//todo odebrat

}
    //echo $SQL;
$dotaz = $pdo->query($SQL);

$cnt=0;

echo '<table >'."\n".'<tr>';
echo '<td>Příjmení</td>';
echo '<td>Jméno</td>';
echo '<td>Titul</td>';
echo '<td>Narozen</td>';
echo '<td>Poslední<br>bydliště</td>';
echo '<td>Trans.<br>do<br>Terez.</td>';
echo '<td>Číslo(T)</td>';
echo '<td>Místo<br>odjezdu(T)</td>';
echo '<td>Datum<br>příjezdu(T)</td>';
echo '<td>Transport<br>na<br>východ</td>';
echo '<td>Číslo(V)</td>';
echo '<td>Místo<br>odjezdu(V)</td>';
echo '<td>Datum<br>odjezdu(V)</td>';
echo '<td>Cíl</td>';
echo '<td>Registrační<br>číslo</td>';
echo '<td>Důvod<br>deportace</td>';
echo '<td>Místo<br>úmrtí</td>';
echo '<td>Datum<br>úmrtí</td>';
echo '<td>Poznámka</td>';
echo '<td>Stát</td>';
echo '</tr>'."\n";

function loadOcc($id){
    $SQL='select * from ca_entities_x_occurrences where entity_id='.$id;
    $results=array();
    global $pdo;
    $dotaz=$pdo->query($SQL);
    foreach($dotaz as $Row){


        $d= loadAttOcc($Row['occurrence_id']);
        $d['tnum']=sql1l(
            'select ca_attribute_values.value_longtext1 from ca_attributes left join ca_attribute_values
                on ca_attributes.attribute_id  = ca_attribute_values.attribute_id 
            where ca_attributes.table_num = 22 and ca_attributes.row_id='.$Row['relation_id'].' and ca_attributes.element_id in (302) '   );
            

            $SQL='
            select   ca_places_x_occurrences.type_id, name  from ca_places_x_occurrences
            left join ca_place_labels on ca_places_x_occurrences.place_id=ca_place_labels.place_id
            where locale_id=1 and occurrence_id='.$Row['occurrence_id'];
            
            $dotaz2=$pdo->query($SQL);
            foreach($dotaz2 as $Row2){
                $d[$Row2['type_id']=='69'?'start':'cil']=$Row2['name'];                
            }
               
            $results[$d['cil']=='Terezín'?'t':'v']=$d;
    }

    //var_export($results);
    return $results;

}
function sql1l($SQL){
    global $pdo;
    $dotaz=$pdo->query($SQL,PDO::FETCH_NUM);      
    foreach($dotaz as $Row){
        return $Row[0];
    }

}

/*
* return SQL returning rows from ca_attribute_values
* for one element (entity, place, etc..)
*
*
*
*/
function attSQL($rowid,$tablenum, $elementsCSV){
    $SQL='select ca_attribute_values.* from ca_attributes left join ca_attribute_values
    on ca_attributes.attribute_id  = ca_attribute_values.attribute_id 
    where ca_attributes.table_num = '.$tablenum.' and ca_attributes.row_id='.$rowid.' and ca_attributes.element_id in ('.$elementsCSV.') ' ;
    return $SQL;
}

function loadAttOcc($id){
    $SQL=attSQL($id,67,'95,162');
    
    global $pdo;
    $dotaz=$pdo->query($SQL);
    $data=array();
    
    foreach($dotaz  as $Row){
        if ($Row['element_id']==95){
            $data['code' ]=$Row['value_longtext1'];
        }else{
            $data['date' ]=$Row['value_longtext1'];
        }
        
    }

    return $data;
}


function loadAtt($id){
    $SQL=attSQL($id,20,'59,298,2,1');
    
    global $pdo;
    $dotaz=$pdo->query($SQL);
    $data=array();
    $data2=array(
        'born'=>'','death'=>'','regnr'=>'','remark'=>'','description'=>''
    );
    foreach($dotaz  as $Row){
        $data[$Row['attribute_id']][ $Row['element_id'] ]=$Row;
    }

    foreach ($data as $Row){
        
        if(isset($Row[60]) && isset($Row[61])){
            if( $Row[60]['item_id']==102){
                $data2['born']=$Row[61]['value_longtext1'];
            }elseif( $Row[60]['item_id']==103){
                $data2['death']=$Row[61]['value_longtext1'];
            }
        }

        if(isset($Row[299]) && isset($Row[300])){
            if( $Row[299]['item_id']==4212){
                $data2['regnr']=$Row[300]['value_longtext1'];            
            }
        }

        if(isset($Row[2]) ){
                $data2['remark']=$Row[2]['value_longtext1'];            
        }
        if(isset($Row[1]) ){
            $data2['description']=$Row[1]['value_longtext1'];            
        }


    }

    $SQL='
            select   ca_entities_x_places.type_id, name  from ca_entities_x_places
            left join ca_place_labels on ca_entities_x_places.place_id=ca_place_labels.place_id
            where locale_id=1 and ca_entities_x_places.type_id in (37,170) and entity_id='.$id;
            $dotaz2=$pdo->query($SQL);
            foreach($dotaz2 as $Row2){
                $data2[$Row2['type_id']=='37'?'deathplace':'lastplace']=$Row2['name'];                
            }
               
           


    return $data2;
}

foreach($dotaz  as $Row){
$data=loadAtt($Row['entity_id']);
$data2=loadOcc($Row['entity_id']);


    echo '<tr>';
    echo '<td>'.$Row['surname'].'</td>';
    echo '<td>'.$Row['forename'].'</td>';
    echo '<td>'.$Row['prefix'].'</td>';
    echo '<td>'.$data['born'] .'</td>';
    echo '<td>'.$data['lastplace'] .'</td>';
    echo '<td>'.$data2['t']['code'].'</td>';
    echo '<td>'.$data2['t']['tnum'].'</td>';
    echo '<td>'.$data2['t']['start'].'</td>';
    echo '<td>'.$data2['t']['date'].'</td>';
    echo '<td>'.$data2['v']['code'].'</td>';
    echo '<td>'.$data2['v']['tnum'].'</td>';
    echo '<td>'.$data2['v']['start'].'</td>';
    echo '<td>'.$data2['v']['date'].'</td>';
    echo '<td>'.$data2['v']['cil'].'</td>';

    
    echo '<td>'.$data['regnr']. '</td>';
    echo '<td>'.$data['description']. '</td>';
    echo '<td>'.$data['deathplace'] .'</td>';
    echo '<td>' .$data['death'] .'</td>';
    echo '<td>'.$data['remark']. '</td>';
    echo '<td>Stát</td>';
    echo '</tr>'."\n";
    
    

    $cnt++;
}
echo '</table>'."\n";
//display reuslts
echo "<hr>Celkem ve výpisu: ".$cnt;


?>

<br><br><br><br><br><br><br><br><br>

</div>

<script>
$( function() {
    //$.datepicker( $.datepicker.regional[ "cs_CZ" ] );
<?php


    foreach ($autocompletes as $item=>$SQL){
        $dotaz = $pdo->query($SQL);
        $lastpl=array();
        foreach($dotaz    as $Row){
            $lastpl[]=$Row['name'];
        }
       // echo "var TAG".$item." = ".json_encode($lastpl).";\n";
       // echo '$( "#'.$item.'" ).autocomplete({source: TAG'.$item.'});'."\n";
       // echo '$( "#'.$item.'" ).css("border","thick solid green");'."\n";
    }

    foreach($f_date  as $d2){
        foreach(array($d2,$d2.'_fuzzy_val') as $date){
         /*   echo '$( "#'.$date.'" ).datepicker();'."\n";
            echo '$( "#'.$date.'"  ).datepicker( "option", "dateFormat", "yy-mm-dd");'."\n";
            echo '$( "#'.$date.'"  ).datepicker( "option", "regional", "cs");'."\n";
            echo '$( "#'.$date.'"  ).datepicker( "option", "changeYear", true);'."\n";
            echo '$( "#'.$date.'"  ).datepicker( "option", "changeMonth", true);'."\n";
            if($d2=="born"){
                echo '$( "#'.$date.'"  ).datepicker( "option", "yearRange", "1800:1945");'."\n"; 
            }else{
                echo '$( "#'.$date.'"  ).datepicker( "option", "yearRange", "1930:1945");'."\n"; 
            }
           */ 

              
            $candidate="";
            if(isset($_REQUEST[$d2])){
                $candidate=$_REQUEST[$d2];
                if(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$candidate)){
                   // echo '$( "#'.$date.'"  ).datepicker(  "setDate", "'.$candidate.'");'."\n";
                }                
            }
        }
    }



    ?>    
  } );

  </script>