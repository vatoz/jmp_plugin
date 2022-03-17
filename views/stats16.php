Multipart Idno<br>
Pro každou tabulku ze seznamu vypisuje blok, ve kterém jsou na začátku identifikátory použité vícekrát.

Myslím si, že k tomu může dojít ve chvíli, kdy si někdo předkliká víc panelů „Přidat novou osobu“ do zásoby. Číslo se totiž generuje už v okamžiku otevření okna (a ta hodnota v databázi se navýší až když se to poprvé uloží.)
Druhá možnost je, že aktéra stejného typu přidával v tu samou dobu ještě někdo jiný.
<br>
Je asi potřeba idno ručně pozměnit, u list_items jsem to nedělal.
<br>
Tím si mohu rozhodit ca_multipart_idno_sequences, a proto počítám největší použité idno, zda sedí se syntaxí.



<pre>
<?php


$dsn = 'mysql:dbname=' . __CA_DB_DATABASE__ . ';host=' .__CA_DB_HOST__ . '';
$user = __CA_DB_USER__;
$password = __CA_DB_PASSWORD__;

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

$list=array("ca_places","ca_objects","ca_entities","ca_list_items","ca_collections","ca_object_representations");


foreach($list as $adept){
   echo "<h1>".$adept."</h1><br>"; 
  $SQL= "SELECT idno,count(*) as c  FROM `".$adept."`  where deleted=0   group by idno    having count(*)>1";
  $dotaz = $pdo->query($SQL);
  foreach($dotaz  as $Row){
      echo $Row["idno"]."  (".$Row["c"].")  <small> SELECT from ".$adept." where idno= '".$Row["idno"]."'</small><br>";
  }

  $SQL= "SELECT idno_stub,seq from ca_multipart_idno_sequences where format= '".$adept."'";
  $dotaz = $pdo->query($SQL);
  
  foreach($dotaz  as $Row){
    echo "<h2>".$Row["idno_stub"]." ". $Row["seq"]." </h2> ";
    $max=0;
    $SQL= "SELECT idno from ".$adept." where idno like  '".$Row["idno_stub"]."%' and deleted=0 ";
    $dotaz2 = $pdo->query($SQL);
    foreach($dotaz2  as $Row2){
        $t=substr($Row2["idno"],strlen($Row["idno_stub"] )+1);
        $t=intval($t);        
        $max=($t>$max?$t:$max);        
    }
    if ($max>$Row["seq"]){
        echo "Possible error! Possible repair : UPDATE ca_multipart_idno_sequences set seq= ".($max+1)." where idno_stub='".$Row["idno_stub"]."'  limit 1 <br>";
        echo "List: ".$SQL."<br>";
    }
    
        echo $max;
    
}

  




}






