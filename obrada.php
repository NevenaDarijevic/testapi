<?php
//hocemo da svaki put kad se posalje zahtev na kraju forme tj.klikne dugme posalji obradi se post zahtev
include "Database.php";
$mydb=new Database("rest");

if(isset($_POST["posalji"]))
{
    if($_POST["naslov_novosti"]!=null &&
    $_POST["tekst_novosti"]!=null && $_POST["kategorija_odabir"]!=null)
    {
    $niz=["naslov"=>"'".$_POST["naslov_novosti"]."'",
    "tekst"=>"'".$_POST["tekst_novosti"]."'",
    "kategorija_id"=>"'".$_POST["kategorija_odabir"]."'",
    "datumvreme"=> ' NOW()'
    ]; //napravili smo niz koji je pokupio vrednosti iz posta
   $mydb->insert("novosti", "naslov, tekst, kategorija_id, datumvreme",$niz); //pozivamo insert da bismo ubacili ovo iz posta
   echo "vrednosti dodate";
   $_POST=array();
    }
}























?>