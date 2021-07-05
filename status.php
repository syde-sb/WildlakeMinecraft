<?php
//ini_set("display_errors", 1);
//ini_set("track_errors", 1);
//ini_set("html_errors", 1);
//error_reporting(E_ALL);

//The following script is tested only with servers running on Minecraft 1.7.

$SERVER_IP = "95.217.96.162"; //Insert the IP of the server you want to query. 
$SERVER_PORT = "25565"; //Insert the PORT of the server you want to ping. Needed to get the favicon, motd, players online and players max. etc
$QUERY_PORT = "25565"; //Port of query.port="" in your server.properties. Needed for the playerlist! Can be the same like the port or different. Query must be enabled in your server.properties file!

$HEADS = "normal"; //"normal" / "3D"
$show_max = "unlimited"; // how much playerheads should we display? "unlimited" / "10" / "53"/ ...
$SHOW_FAVICON = "off"; //"off" / "on"

$TITLE = "";
$TITLE_BLOCK_ONE = "Informatsioon";
$TITLE_BLOCK_TWO = "Mängijad";

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$ping = json_decode(file_get_contents('http://api.minetools.eu/ping/' . $SERVER_IP . '/' . $SERVER_PORT . ''), true);
$query = json_decode(file_get_contents('http://api.minetools.eu/query/' . $SERVER_IP . '/' . $QUERY_PORT . ''), true);

//Put the collected player information into an array for later use.
if(empty($ping['error'])) { 
    $version = $ping['version']['name'];
    $online = $ping['players']['online'];
    $max = $ping['players']['max'];
    $motd = $ping['description'];
    $favicon = $ping['favicon'];
}

if(empty($query['error'])) {
    $playerlist = $query['Playerlist'];
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
            <title>
                <?php echo htmlspecialchars($TITLE); ?>
            </title>
            <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
                <link rel="stylesheet" href="https://chrisl.wildlakerp.eu/wildlakemc/animate.min.css">
                	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
                    <script src="https://kit.fontawesome.com/6cad0b531a.js"></script>
                    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
                    <script language="javascript">
               jQuery(document).ready(function() {
                   $("[rel='tooltip']").tooltip();
               });
    </script>
                </head>
                <body>
                    <div class="container-fluid" >
                        <div class="row" >
                            <div class="col col-lg-4">
                                <div class="card border-secondary" style= "color: #737976 !important; background-color: #181818;">
                                    <div class="card-header">
                                        <h3>
                                            <?php echo htmlspecialchars($TITLE_BLOCK_ONE); ?>
                                        </h3>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item" style= "color: #737976 !important; background-color: #202020;">
                                            <i class="fas fa-server"></i> mc.wildlakerp.eu
                                        </li>
                                        <?php if(empty($ping['error'])) { ?>
                                        <li class="list-group-item" style= "color: #737976 !important; background-color: #202020;">
                                            <i class="fas fa-sync-alt"></i> 1.8.x-1.15.x
                                        </li>
                                        <?php } ?>
                                        <?php if(empty($ping['error'])) { ?>
                                        <li class="list-group-item" style= "color: #737976 !important; background-color: #202020;">
                                            <i class="fas fa-users"></i>
                                            <?php echo "".$online." / ".$max."";?>
                                        </li>
                                        <?php } ?>
                                        <li class="list-group-item" style= "color: #737976 !important; background-color: #202020;">
                                            <i class="fas fa-signal"></i>
                                            <?php if(empty($ping['error'])) { echo "
                                            <span class=\"badge badge-pill badge-success\">Võrgus</span>"; } else { echo "
                                            <span class=\"badge badge-pill badge-danger\">Võrguühenduseta</span>";}?>
                                        </li>
                                        <?php if(empty($ping['error'])) { ?>
                                        <?php if(!empty($favicon)) { ?>
                                        <?php if ($SHOW_FAVICON == "on") { ?>
                                        <li style="list-style: none">&lt;</li>
                                        <li class="list-group-item">Favicon 
                                            <img height="64px" src='%3C?php%20echo%20$favicon;%20?%3E' style="float:left;" width="64px">
                                            </li>
                                            <?php } ?>
                                            <?php } ?>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-6 border-primary">
                                    <h3>
                                        <?php echo htmlspecialchars($TITLE_BLOCK_TWO); ?>
                                    </h3>
                                    <hr class="my-4 bg-secondary">
                                    <?php
                                if($HEADS == "3D") {
                                    $url = "https://minotar.net/cube/";
                                } else {
                                    $url = "https://minotar.net/helm/";
                                }

                                if(empty($query['error'])) {
                                    if($playerlist != "null") { //is at least one player online? Then display it!
                                        $shown = "0";
                                        foreach ($playerlist as $player) {
                                            $shown++;
                                            if($shown < $show_max + 1 || $show_max == "unlimited") {
                                        ?>
                                    <a data-placement="top" rel="tooltip" style="display: inline-block;" title="<?php echo $player;?>">
                                        <img height="40" src="<?php echo $url.$player;?>/50" style="width: 40px; height: 40px; margin-bottom: 5px; margin-right: 5px; border-radius: 3px;" width="40">
                                        </a>
                                        <?php   }
                                        }
                                        if($shown > $show_max && $show_max != "unlimited") {
                                            echo '
                                        <div class="span8" style="font-size:16px; margin-left: 0px;">';
                                            echo "and " . (count($playerlist) - $show_max) . " more ...";
                                            echo '</div>';
                                        }
                                    } else {
                                        echo "
                                        <div class=\"alert\" style=\"font-size:16px;\"> Mängijad puuduvad!</div>";
                                    }
                                } else {
                                    echo "
                                        <div class=\"alert\" style=\"font-size:16px;\"> Server on kahjuks võrguühenduseta!</div>";
                                } ?>
                                    </div>
                                </div>
                            </div>
                        </body>
                    </html>