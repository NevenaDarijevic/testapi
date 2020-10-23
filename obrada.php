<?php
//hocemo da svaki put kad se posalje zahtev na kraju forme tj.klikne dugme posalji obradi se post zahtev
include "Database.php";
$mydb=new Database("rest");

if(isset($_POST["posalji"] ) && $_POST["posalji"]="Posalji zahtev")
{
    if($_POST["naslov_novosti"]!=null &&
    $_POST["tekst_novosti"]!=null && $_POST["kategorija_odabir"]!=null)
    {
    $niz=["naslov"=>"'".$_POST["naslov_novosti"]."'",
    "tekst"=>"'".$_POST["tekst_novosti"]."'",
    "kategorija_id"=>"'".$_POST["kategorija_odabir"]."'",
    "datumvreme"=> ' NOW()'
    ]; //napravili smo niz koji je pokupio vrednosti iz posta
  if($mydb->insert("novosti", "naslov, tekst, kategorija_id, datumvreme",$niz)) //pozivamo insert da bismo ubacili ovo iz posta
  { echo "vrednosti dodate";}else{
      echo "vrednosti nisu dodate";
  }

   $_POST=array(); //da ga anuliramo
   exit();
}elseif($_POST["brisanje"]!=null && $_POST["odabir_tabele"]!=null ){
    $tabela=$_POST["odabir_tabele"];
    $id="id";
    $id_val=$_POST["brisanje"];
    if($mydb->delete($tabela, $id, $id_val)){
        echo "red obrisan";
    }else{
        echo "greska prilikom brisanja";
    }
    $_POST=array();
    exit();
}
}























?>