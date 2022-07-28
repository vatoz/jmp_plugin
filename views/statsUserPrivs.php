Uživatelé, role, skupiny
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

function getD($SQL){
  global $pdo;
  $dotaz = $pdo->query($SQL);
  $result=array();
  foreach($dotaz  as $Row){
    $result[]=$Row;
   }
  return $result;
}



  $SQL= "SELECT * from ca_users where active=1 and userclass=0 order by lname,fname";

  $users=array();
  $dotaz = $pdo->query($SQL);
  foreach($dotaz  as $Row){
    $Row["vars"]=unserialize(base64_decode($Row['vars']));
	   unset ($Row["volatile_vars"]);
     unset ($Row["password"]);
     $users[]=$Row;
   }

  $todo=array('ca_user_groups'=>"Select * from ca_user_groups",
  'ca_user_roles'=>"Select * from ca_user_roles",
  'ca_users_x_roles'=>"Select * from ca_users_x_roles",
  'ca_users_x_groups'=>"Select * from ca_users_x_groups",
  'ca_groups_x_roles'=>"select * from ca_groups_x_roles");  

  foreach($todo as $key=>$SQL){
    $dotaz = $pdo->query($SQL);
    $$key=array();
    foreach($dotaz  as $Row){
      $$key[]=$Row;
     }
  }

?><table border=1><?php
echo "<tr><td>Username</td><td>jmeno</td><td>mail</td><td>org</td>";
foreach($ca_user_roles as $role){
  echo "<td>".$role["name"]."</td>";
}
echo "<tr>";


foreach($users as $user){
  echo "<tr>";

  echo "<td>".'<A href="/index.php/administrate/access/Users/Edit/user_id/'.$user["user_id"].'">'.$user["user_name"]."</a>";
  echo "</td>";
  echo "<td>".$user["lname"]." ".$user["fname"]."</td>";
  echo "<td>".$user["email"]."</td>";
  echo "<td>";
  echo $user["vars"]["_user_preferences"]["user_profile_organization"];
  //var_export();
  echo "</td>";

  foreach($ca_user_roles as $role){

    echo "<td>";
    foreach($ca_users_x_groups as $uxg){
      if($uxg["user_id"]==$user["user_id"]){

        foreach ($ca_groups_x_roles as $gxr){
          if ($gxr["group_id"]==$uxg["group_id"]){

            if($gxr["role_id"]==$role["role_id"]){

              foreach($ca_user_groups as $g){
                if($g["group_id"]==$uxg["group_id"]){
                  echo "<strong>".$g["name"]."</strong><br>";
                }

              }

              //echo "Group_".$uxg["group_id"]."=>".$role["name"]."<br>";
            }

          }
        }
      }

    }

    foreach($ca_users_x_roles as $uxr){
      if ($uxr["user_id"]==$user["user_id"]){
          if($uxr["role_id"]==$role["role_id"]){
            echo "ANO";
          }
      }
    }



    echo "</td>";
  }
  echo "</tr>";




}
