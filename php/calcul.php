<?php
include_once 'bdd.php';
require_once 'bdd.php';

class Airport
{
    public $code;
    public $lat;
    public $long;
    public $zone; //7 is corse, 0 no zone
    public $distP; //Use for navpoint to get the distance from the previous one
    public function __construct($code, $lat, $long, $zone) 
    {
            $this->code = $code;
            $this->lat = $lat;
            $this->long = $long;
            $this->zone = $zone;
    }
}


function getAirport()
{
    $dbh=connection();
    $sql="SELECT codeOACI, lon, lat, no_zone FROM aerodrome;";
    $sth=$dbh->prepare($sql);
    $sth->execute();
    $listA=array();
    $id=0;
    //$html="test <br\>";
    //echo $html;
    while($result=$sth->fetch(PDO::FETCH_OBJ))
    {
        $listA[$id] = new Airport($result->codeOACI,$result->lon,$result->lat, $result->no_zone);
        $id++;
    }
    //echo $html;
    $sth->closeCursor();
    /*FOR TEST*/ //print_r($listA); /*FOR TEST*/
    return $listA;
}

function traj()
{
    $listA=getAirport();
    $name="FUCKYU";
    //creatFlight($name,"Some team","F-THIS","71r3d");
    
    $dbh=connection();
    $key=searchC($listA,"LFGA");
    $listN[0]=$listA[$key];
   
    $sql="INSERT INTO `NAVPOINT` VALUES ('".$listA[$key]->code."', '".$name."', '".date('Y-m-d G:i:s',time())."');";
    $sth=$dbh->prepare($sql);
    $sth->execute();
    array_splice($listA, $key, 1); //remove this airport from the list
    
    $t=0;
    for($i=1; $i<=99; $i++)
    {
        $id=rand(0,count($listA)-1);
        $listN[$i]=$listA[$id];
        $listN[$i]->distP=dist($listN[$i-1],$listN[$i]);
        $t+=($listN[$i]->distP/200)*3600;
        
        $sql="INSERT INTO `NAVPOINT` VALUES ('".$listA[$id]->code."', '".$name."', '".date('Y-m-d G:i:s',time()+$t)."');";
        $sth=$dbh->prepare($sql);
        $sth->execute();
        array_splice($listA, $id, 1);
    }
    $sth->closeCursor();
    //print_r($listN);
    //echo date('Y-m-d G:i:s',time());
    return $listN;
}

function searchC($list, $what)
{
    foreach ($list as $key => $value)
    {
        if ($value->code==$what)
            $k=$key;
    }
    return $k;            
}

function dist($ad1,$ad2)
{
    $r = 6366;
    $lat1 = deg2rad($ad1->lat);
    $lon1 = deg2rad($ad1->long);
    $lat2 = deg2rad($ad2->lat);
    $lon2 = deg2rad($ad2->long);
    
    $dp= 2 * asin(sqrt(pow(sin(($lat1-$lat2)/2), 2)+cos($lat1)*cos($lat2)* pow(sin(($lon1-$lon2)/2), 2)));
    $distance = $dp * $r;
    return $distance;
}


function creatFlight($id,$nameT,$acNum,$user)
{
    $dbh=connection();
    $sql="INSERT INTO `FLIGHT` VALUES ('".$id."', '".$nameT."', '".$acNum."', '".$user."');";
    $sth=$dbh->prepare($sql);
    $sth->execute();
    $sth->closeCursor();
}
function deleteNav()
{
    $dbh=connection();
    $sql="DELETE FROM NAVPOINT;";
    $sth=$dbh->prepare($sql);
    $sth->execute();
    $sth->closeCursor();
}
deleteNav();
traj();
?>