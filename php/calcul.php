<?php
DEFINE("SERV","locahost");
DEFINE("LOGIN","");
DEFINE("MDP","");
DEFINE("NOM_BD","");

class Airport
{
    public $code;
    public $lat;
    public $long;
    public $zone; //7 is corse, 0 no zone
    public function __construct($code, $lat, $long) 
    {
              $this->code = $code;
              $this->lat = $lat;
              $this->long = $long;
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
    $sql="SELECT beacon.codeOACI, lon, lat FROM aerodrome, beacon WHERE aerodrome.codeOACI=beacon.codeOACI";
    $sth=$dbh->prepare($sql);
    $sth->execute();
    $listA=array();
    $id=0;
    while($result=$sth->fetch(PDO:FETCH_OBJ))
    {
        $listA[id] = new Airport($result->codeOACI,$result->lon,$result->lat);
        id++;
    }
    $sth->closeCursor();
    /*FOR TEST*/ print_r(listA); /*FOR TEST*/
    return $listA;
}

?>