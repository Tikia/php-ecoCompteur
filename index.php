<?php
	require("./cfg.ecoCompteur.php");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Mon éco-compteur</title>
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<link rel="stylesheet" href="./buttons.css">
<style type="text/css">
body{
	font-family: Roboto, Arial, sans-serif
}
</style>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="./fct.ecoCompteur.js"></script>
<script language="javascript" type="text/javascript">
	var maxGauge = {data1:<?php echo $gaugeMax["data1"]; ?>, data2:<?php echo $gaugeMax["data2"]; ?>, data3:<?php echo $gaugeMax["data3"]; ?>, data4:<?php echo $gaugeMax["data4"]; ?>, data5:<?php echo $gaugeMax["data5"]; ?>, data6:<?php echo $gaugeMax["data6"]; ?>, dataAll:<?php echo $gaugeMax["dataAll"]; ?>};
	
	var page="./ajax.php?part=";
	var page_inst=page+"<?php echo $pageInst; ?>";
	var page_data=page+"<?php echo $pageData; ?>";
	var page_log1=page+"<?php echo $pageLog1; ?>";
	var page_log1_jour=page_log1;
	var page_log1_semaine=page_log1+"&aggregate=semaine";
	var page_log1_mois=page_log1+"&aggregate=mois";
	var page_log2=page+"<?php echo $pageLog2; ?>";
	google.charts.load('current', {'packages':['corechart','gauge','bar']});
</script>
</head>
<body>
<ul class="button-group">
    <button class="blue button" id="bttn_inst" onClick="onStart('inst');" disabled>Consommation instantanées</button>
    <button class="green button" id="bttn_heure" onClick="onStart('heure');" disabled>Consommation par heure</button>
    <button class="green button" id="bttn_jour" onClick="onStart('jour');" disabled>Consommation par jour</button>
    <button class="green button" id="bttn_semaine" onClick="onStart('semaine');" disabled>Consommation par semaine</button>
    <button class="green button" id="bttn_mois" onClick="onStart('mois');" disabled>Consommation par mois</button>
    <button class="red button" id="bttn_stop" onClick="onStop();" disabled>Stop</button>
</ul>
<div id="inst" style="display:none;">
    <h1>Consommations instantanées</h1>
    <div style="width: 1800px; height: 300px;">
        <div id="inst_stackedBar_div" style="width: 1800px; height: 300px;"></div>
    </div>
    <div style="width: 500px; height: 500px; padding-left: 650px;">
        <div id="inst_gauge_dataAll_div" style="width: 500px; height: 500px; float: left;"></div>
    </div>
    <div style="width: 1800px; height: 300px;">
        <div id="inst_gauge_data1_div" style="width: 300px; height: 300px; float: left;"></div>
        <div id="inst_gauge_data2_div" style="width: 300px; height: 300px; float: left;"></div>
        <div id="inst_gauge_data3_div" style="width: 300px; height: 300px; float: left;"></div>
        <div id="inst_gauge_data4_div" style="width: 300px; height: 300px; float: left;"></div>
        <div id="inst_gauge_data5_div" style="width: 300px; height: 300px; float: left;"></div>
        <div id="inst_gauge_data6_div" style="width: 300px; height: 300px; float: left;"></div>	
    </div>
</div>
<div id="heure" style="display:none;">
    <h1>Consommation par heure</h1>
    <div style="width: 1800px; height: 600px;">
        <div id="heure_chart_line_div" style="width: 1800px; height: 600px;"></div>
        <div id="heure_stackedBar_div" style="width: 1800px; height: 600px;"></div>
    </div>
</div>
<div id="jour" style="display:none;">
    <h1>Consommation par jour</h1>
    <div style="width: 1800px; height: 1200px;">
        <div id="jour_chart_line_div" style="width: 1800px; height: 600px;"></div>
        <div id="jour_stackedBar_div" style="width: 1800px; height: 600px;"></div>
    </div>
</div>
<div id="semaine" style="display:none;">
    <h1>Consommation par semaine</h1>
    <div style="width: 1800px; height: 1200px;">
        <div id="semaine_chart_line_div" style="width: 1800px; height: 600px;"></div>
        <div id="semaine_stackedBar_div" style="width: 1800px; height: 600px;"></div>
    </div>
</div>
<div id="mois" style="display:none;">
    <h1>Consommation par mois</h1>
    <div style="width: 1800px; height: 1200px;">
        <div id="mois_chart_line_div" style="width: 1800px; height: 600px;"></div>
        <div id="mois_stackedBar_div" style="width: 1800px; height: 600px;"></div>
    </div>
</div>
</body>
</html>