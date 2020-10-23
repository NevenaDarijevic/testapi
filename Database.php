<?php
//PHP MANUAL SAJT POGLEDATI
class Database{
    //properties
private $hostname="localhost";
private $username="root";
private $password="";
private $dbname;
private $dblink; //link ka bazi
private $result; //rezultati nakon svakog kverija
private $affected; //ukupan broj redova nad kojim je postojao uticaj posle sql queryja
private $records;//ukupan broj redova baze koji su vraceni kroz rezultate


//funkcije
    function __construct($par_dbname){ //konstruktor
       // $this->dbname=$dbname;
       $this->dbname=$par_dbname; //moze dolar ispred dbname i ne mora
        $this->Connect(); //poziv funkcije odozdo
//mora preko this jer su properties
    }
    function Connect(){
        //musqli vraca niz objekata
        //za konekciju sa bazom, mysqli je model za konkeciju sa bazom
        $this->dblink= new mysqli($this->hostname,$this->username, $this->password,$this->dbname);
        //proveravamo da li se desila greska na dblinku
        //erno da li je bilo greske, error gresku tacnu
        if($this->dblink->connect_errno){
            printf("Konekcija neuspesna %s\n", $this->dblink->connect_error);
            exit(); //za nastavljanje dalje ako je sve okej ???
        }
        // postavljamo utf
        $this->dblink->set_charset("utf8");
    }
    function ExecuteQuery($query){
        //dolar ide i ispred parametara i promenljivih
        //ako imamo rezultate zapamticemo ih u rekordima
    if($this->result=$this->dblink->query($query)){
    //u ifu: ako je nesto dodato u nase rezultate.. 
    //ako je prosao query hocemo da vidimo koliko ima unetih redova, koliko redva je afektovano
    if(isset($this->result->num_rows))
    {
        //ako imamo afektovane redove upisi u rekord koliko je to redova
        $this->records=$this->result->num_rows;
    }
    if(isset($this->result->affected_rows)){
        $this->affected=$this->result->affected_rows;
     }
     return true;
        }else{
        return false;
        }
    }
    function getResult(){
        return $this->result;
    }
    //u select ide join jer su ove dve vezane tabele
    //stavicemo u select da se po defaultu poziva za novosti, a ako prosledimo neku drugu tabelu onda ce nad njom
    function select($table="novosti", $rows="*",$join_table="kategorije", $join_key1="kategorija_id",$join_key2="id" , $where=null, $order=null){
        $q='SELECT '.$rows.' FROM '.$table;//tacke su konkatenacija stringova
        //ako je postavljen join, where, order
        if($join_table!=null){
            $q.= ' JOIN '.$join_table.' ON '.$table.'.'.$join_key1.'='.$join_table.'.'.$join_key2  ;    //  .= kao +=
        //select * from novosti join kategorije on novosti.kategorija_id=kategorije.id
        }
        if($where!=null){
            $q.=' WHERE '.$where;
        }
        if($order!=null){
            $q.=' ORDER BY '.$order;
        }
        $this->ExecuteQuery($q); //izvrsi ovaj query
     }
    //select("kategorije", "kategorija"); // nad tabelom kategorije,  kolona kategorija

function insert($table="novosti", $rows="naslov, tekst, kategorija_id, datumvreme", $values){
$query_values=implode(',', $values); // pakuje sa sve ovim zarezima sve unete vrednosti
//$query_values="nova novost, tekst_novosti, 1,23-10-2020"
$q='INSERT INTO '.$table;
if($rows!=null){
    $q.='('.$rows.')'; //insert into novosti(naslov, teskt, kategorija_id, datumvreme)
}
//ovo values gore kao parametar ne moze biti nista, mora bar jedna vrednost
$q.=' VALUES ('.$query_values.')';
if($this->ExecuteQuery($q)){
//ako ovo iz ifa prodje tj.izvrsi se vrati true
return true;
}
else{
    return false;
}
}


function update($table, $id, $keys, $values){
    $query_values="";
    $set_query=array();
    for($i=0;$i<sizeof($keys);$i++){
        $set_query[]="$keys[$i] = $values[$i]";
    }
    $query_values=implode(",", $set_query);
    $q="UPDATE $table SET($query_values) WHERE id=$id";
    if($this->ExecuteQuery($q) && $this->affected>0)
    {
    return true;    
    }else{return false;}

}
function delete($table, $id, $id_value){
    $q="DELETE FROM $table WHERE $table.$id=$id_value ";
    if($this->ExecuteQuery($q)){
        return true;
    }else{
        return false;
    }
}

}




?>