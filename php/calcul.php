<?php
DEFINE("SERV","localhost");
DEFINE("LOGIN","root");
DEFINE("MDP","");
DEFINE("NOM_BD","beweb_2017");

class Airport
{
    public $code;
    public $lat;
    public $long;
    public $zone; //7 is corse, 0 no zone
    public function __construct($code, $lat, $long, $zone) 
    {
              $this->code = $code;
              $this->lat = $lat;
              $this->long = $long;
              $this->zone = $zone;
    }
}


function connexion()
{
    try
    {
        $connStr="mysql:host=".SERV.";dbname=".NOM_BD;
        $dbh=new PDO($connStr, LOGIN, MDP);
    }
    catch(PDOException $e)
    {
        echo 'Connection failed: '.$e->getMessage();
        return "fail";
    }
    return $dbh;
}

function getAirport()
{
    $dbh=connexion();
    $sql="SELECT codeOACI, lon, lat, no_zone FROM aerodrome;";
    $sth=$dbh->prepare($sql);
    $sth->execute();
    $listA=array();
    $id=0;
    $html="test <br\>";
    echo $html;
    while($result=$sth->fetch(PDO::FETCH_OBJ))
    {
        $listA[$id] = new Airport($result->codeOACI,$result->lon,$result->lat, $result->no_zone);
        //$html.=$listA[$id]->code." ".$listA[$id]->lat."<br \>";
        $id++;
    }
    echo $html;
    $sth->closeCursor();
    /*FOR TEST*/ print_r($listA); /*FOR TEST*/
    return $listA;
}

getAirport();

?>