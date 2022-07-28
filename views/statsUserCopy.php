Copy user<pre>
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

$from=intval($_REQUEST["from"]);
$to=intval($_REQUEST["to"]);
if(!($from*$to)) die("<vyberte uživatele");

  $SQL= "SELECT * from ca_users_x_roles where user_id = ".$from;
  $dotaz = $pdo->query($SQL);
  foreach($dotaz  as $Row){
      echo "INSERT INTO `ca_users_x_roles` ( `user_id`, `role_id`, `rank`) VALUES (	".$to.",	".$Row["role_id"].",".$Row["rank"].");\n";
  }

  $SQL= "SELECT * from ca_users_x_groups where user_id = ".$from;
  $dotaz = $pdo->query($SQL);
  foreach($dotaz  as $Row){
      echo "INSERT INTO `ca_users_x_groups` ( `user_id`, `group_id`) VALUES (	".$to.",	".$Row["group_id"].");\n";
  }






