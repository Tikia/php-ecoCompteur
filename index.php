<?php
	require("./cfg.ecoCompteur.php");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Mon éco-compteur</title>
<link rel="stylesheet" href="./buttons.css">
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="./fct.ecoCompteur.js"></script>
<script language="javascript" type="text/javascript">
	var maxGauge = {data1:<?php echo $gaugeMax["data1"]; ?>, data2:<?php echo $gaugeMax["data2"]; ?>, data3:<?php echo $gaugeMax["data3"]; ?>, data4:<?php echo $gaugeMax["data4"]; ?>, data5:<?php echo $gaugeMax["data5"]; ?>, data6:<?php echo $gaugeMax["data6"]; ?>, dataAll:<?php echo $gaugeMax["dataAll"]; ?>};
	
	var page="./ajax.php?part=";
	var page_inst=page+"<?php echo $pageInst; ?>";
	var page_data=page+"<?php echo $pageData; ?>";
	var page_log1=page+"<?php echo $pageLog1; ?>";
	var page_log2=page+"<?php echo $pageLog2; ?>";
	google.charts.load('current', {'packages':['corechart','gauge']});
</script>
</head>
<body>
<ul class="button-group">
    <button class="blue button" id="bttn_inst" onClick="onStart('inst');" disabled>Consommation instantanées</button>
    <button class="green button" id="bttn_jour" onClick="onStart('jour');" disabled>Consommation journalière</button>
    <button class="green button" id="bttn_an" onClick="onStart('an');" disabled>Consommation annuelle</button>
    <button class="red button" id="bttn_stop" onClick="onStop();" disabled>Stop</button>
</ul>
<div id="inst" style="display:none;">
    <h1>Consommations instantanées</h1>
    <div style="width: 1440px; height: 240px;">
        <div id="chart_stackedBar_div" style="width: 1440px; height: 240px;"></div>
    </div>
    <div style="width: 320px; height: 320px; padding-left: 560px;">
        <div id="chart_gauge_dataAll_div" style="width: 320px; height: 320px; float: left;"></div>
    </div>
    <div style="width: 1440px; height: 240px;">
        <div id="chart_gauge_data1_div" style="width: 240px; height: 240px; float: left;"></div>
        <div id="chart_gauge_data2_div" style="width: 240px; height: 240px; float: left;"></div>
        <div id="chart_gauge_data3_div" style="width: 240px; height: 240px; float: left;"></div>
        <div id="chart_gauge_data4_div" style="width: 240px; height: 240px; float: left;"></div>
        <div id="chart_gauge_data5_div" style="width: 240px; height: 240px; float: left;"></div>
        <div id="chart_gauge_data6_div" style="width: 240px; height: 240px; float: left;"></div>	
    </div>
</div>
<div id="jour" style="display:none;">
    <h1>Consommation journalière</h1>
    <div style="width: 1440px; height: 600px;">
        <div id="day_chart_line_div" style="width: 1440px; height: 600px;"></div>
        <div id="day_stackedBar_div" style="width: 1440px; height: 600px;"></div>
    </div>
</div>
<div id="an" style="display:none;">
    <h1>Consommation annuelle</h1>
    <div style="width: 1440px; height: 1200px;">
        <div id="year_chart_line_div" style="width: 1440px; height: 600px;"></div>
        <div id="year_stackedBar_div" style="width: 1440px; height: 600px;"></div>
    </div>
</div>
</body>
</html>