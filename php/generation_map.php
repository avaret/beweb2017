<?php
require_once('db.php');

// Start XML file, create parent node
$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);

// Connect and Set the active MySQL database
$dbh = connection();
    
// Select all the rows in the markers table
$query = "SELECT codeOACI, lat, lon, description FROM AERODROME";
$sth=$dbh->prepare($query);
$sth->execute();

header("Content-type: text/xml");

// Iterate through the rows, adding XML nodes for each

while($result=$sth->fetch(PDO::FETCH_OBJ))
{
    // Add to XML document node
    $node = $dom->createElement("marker");
    $newnode = $parnode->appendChild($node);
    
    $newnode->setAttribute("codeOACI", $result->codeOACI);
    $newnode->setAttribute("lat", $result->lat);
    $newnode->setAttribute("lng", $result->lon);
    $newnode->setAttribute("description", $result->description);
    $newnode->setAttribute("type", "aerodrome");
}

echo $dom->saveXML();

?>