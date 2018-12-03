<?php
	$urlEcoCompteur = "http://maurizio.tikia.net:2180";
	$urlEcoCompteur_inst = $urlEcoCompteur."/inst.json";
	$urlEcoCompteur_data = $urlEcoCompteur."/data.json";
	$urlEcoCompteur_log1 = $urlEcoCompteur."/log1.csv";
	$urlEcoCompteur_log2 = $urlEcoCompteur."/log2.csv";
	function aff($data) {
		echo "<pre>";
		print_r($data);
		echo "</pre>";
	}
	function csv2json4log($csv) {
		//aff($csv);
		$csv=explode("\r\n",$csv);
		$tab=array();
		$tab[0]=explode(" ","jour mois annee heure minute energie_tele_info prix_tele_info energie_circuit1 prix_circuit1 energie_cirucit2 prix_circuit2 energie_circuit3 prix_circuit3 energie_circuit4 prix_circuit4 energie_circuit5 prix_circuit5 volume_entree1 volume_entree2 tarif energie_entree1 energie_entree2 prix_entree1 prix_entree2");
		$flag=true;
		foreach($csv as $line) {
			if($line=="HTTP/1.0 200 OK") $flag=false;
			if($flag) {
				$tab[]=explode(";",$line);
			}
		}
		return json_encode($tab);
	}
	if(isset($_GET['part']) && $_GET['part']=='inst.json') {
		$json=file_get_contents($urlEcoCompteur_inst);
		echo $json;
	}
	elseif(isset($_GET['part']) && $_GET['part']=='data.json') {
		$json=file_get_contents($urlEcoCompteur_data);
		echo $json;
	}
	elseif(isset($_GET['part']) && $_GET['part']=='log1.csv') {
		$csv=file_get_contents($urlEcoCompteur_log1);
		echo csv2json4log($csv);
	}
	elseif(isset($_GET['part']) && $_GET['part']=='log2.csv') {
		$csv=file_get_contents($urlEcoCompteur_log2);
		echo csv2json4log($csv);
	}
	else {
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Mon éco-compteur</title>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="./ajax.js"></script>
<script language="javascript" type="text/javascript">
	var maxGauge = {data1:5000, data2:7000, data3:4000, data4:4000, data5:1000, data6:1000, dataAll:7000};
	
	var page="<?php echo $_SERVER['PHP_SELF']; ?>?part=";
	var page_inst=page+"inst.json";
	var page_data=page+"data.json";
	var page_log1=page+"log1.csv";
	var page_log2=page+"log2.csv";

	var timeOutId1=0;
	var timeOutId2=0;
	var dataJSON='';
	var instJSON='';
	var instTotal=0;
	var ajaxObjects = new Array();
	var ajaxIndex = 0;
	
	google.charts.load('current', {'packages':['corechart','gauge']});

	function data_load() {
		$.ajax({
		  url: page_data,
		  success: function(result) {
			dataJSON=JSON.parse(result);
		  }
		});
	}
	function inst_refresh() {
		$.ajax({
		  url: page_inst,
		  success: function(result) {
			instJSON=JSON.parse(result);
			  	instTotal=0;
			  	for(var i=1;i<7;i++) {
					createChartGauge(i);
				}
			  	createChartGauge('All');
			  	createChartStackedBar();
			timeOutId1=setTimeout(inst_refresh,1000);
		  }
		});
	}
	function log2_refresh() {
		$.ajax({
		  url: page_log2,
		  success: function(result) {
		  	createChartLine(JSON.parse(result));
			timeOutId2=setTimeout(inst_refresh,1000*60*30);
		  }
		});
	}
/*
	function data_load() {
		var ajaxIndex = ajaxObjects.length;
		ajaxObjects[ajaxIndex] = new sack();
		ajaxObjects[ajaxIndex].requestFile = page_data;
		ajaxObjects[ajaxIndex].onCompletion = function(){ data_answer(ajaxIndex); };
		ajaxObjects[ajaxIndex].runAJAX();
	}
	function data_answer() {
		var data = ajaxObjects[ajaxIndex].response;
		ajaxObjects[ajaxIndex] = false;
		console.log(data);
		dataJSON=JSON.parse(data);
	}
	function inst_refresh() {
		var ajaxIndex = ajaxObjects.length;
		ajaxObjects[ajaxIndex] = new sack();
		ajaxObjects[ajaxIndex].requestFile = page_inst;
		alert(page_inst);
		ajaxObjects[ajaxIndex].onCompletion = function(){ inst_refresh_answer(ajaxIndex); };
		ajaxObjects[ajaxIndex].runAJAX();
	}
	function inst_refresh_answer() {
		var data = ajaxObjects[ajaxIndex].response;
		ajaxObjects[ajaxIndex] = false;
		console.log(data);
		instJSON=JSON.parse(data);
		instTotal=0;
		for(var i=1;i<7;i++) {
			createChartGauge(i);
		}
		createChartGauge('All');
		createChartStackedBar();
		timeOutId1=setTimeout(inst_refresh,1000);
	}
	function log2_refresh() {
		var ajaxIndex = ajaxObjects.length;
		ajaxObjects[ajaxIndex] = new sack();
		ajaxObjects[ajaxIndex].requestFile = page_log2;
		ajaxObjects[ajaxIndex].onCompletion = function(){ log2_refresh_answer(ajaxIndex); };
		ajaxObjects[ajaxIndex].runAJAX();
	}
	function log2_refresh_answer() {
		var data = ajaxObjects[ajaxIndex].response;
		ajaxObjects[ajaxIndex] = false;
		console.log(data);
	  	createChartLine(JSON.parse(data));
		timeOutId2=setTimeout(log2_refresh,1000*60*30);
	}
*/
	function createChartLine(json) {
		var tab = [];
		var tmp = ['',getLabel("label_entree1"),getLabel("label_entree2"),getLabel("label_entree3"),getLabel("label_entree4"),getLabel("label_entree5"),getLabel("label_entree6"),getLabel("label_entreeAll"),{role:'annotation'}];
		tab.push(tmp);
		$.each(json,function(i,elem) {
			if(i!=0) {
				var date = elem[0]+"/"+elem[1]+" "+((elem[3]>9) ? elem[3] : "0"+elem[3])+":"+((elem[4]>9) ? elem[4] : "0"+elem[4]);
				var all = parseFloat(elem[7])+parseFloat(elem[9])+parseFloat(elem[11])+parseFloat(elem[13])+parseFloat(elem[15]);
				var autre = (parseFloat(elem[5])-all >0 ) ? parseFloat(elem[5])-all : 0;
				var tmp = [date,parseFloat(elem[7]),parseFloat(elem[9]),parseFloat(elem[11]),parseFloat(elem[13]),parseFloat(elem[15]),autre,parseFloat(elem[5]),''];
				tab.push(tmp);
			}
		});
		var data = google.visualization.arrayToDataTable(tab);
		var options = {
			width: 1680,
			height: 600,
			legend: { position: 'bottom' },
			curveType: 'function'
		};
		var chart = new google.visualization.LineChart(document.getElementById('chart_line_div'));
      	chart.draw(data, options);
	}
	function createChartStackedBar() {
		var data = google.visualization.arrayToDataTable([
			['',getLabel("label_entree1"),getLabel("label_entree2"),getLabel("label_entree3"),getLabel("label_entree4"),getLabel("label_entree5"),getLabel("label_entree6"),getLabel("label_entreeAll"),{ role: 'annotation' } ],
			['', getInstValue("data1"),getInstValue("data2"),getInstValue("data3"),getInstValue("data4"),getInstValue("data5"),getInstValue("data6"),0,'']
		]);
		var options = {
			width: 1680,
			height: 240,
			legend: { position: 'bottom' },
			isStacked: 'percent',
			hAxis: {
				minValue: 0
			},
			bars: 'horizontal'
		};
		var chart = new google.visualization.BarChart(document.getElementById('chart_stackedBar_div'));
      	chart.draw(data, options);
	}
	function createChartGauge(id) {
		var value = getInstValue("data"+id);
		if(id!="All") instTotal=instTotal+value;
		var data = google.visualization.arrayToDataTable([['Label', 'Value'],[getLabel("label_entree"+id), value]]);
		var options = {width:240, height:240, max:100, redFrom:90, redTo:100, yellowFrom:75, yellowTo:90, greenFrom:0, greenTo:75, minorTicks: 5};
		var max = maxGauge["data"+id];
		options["max"]=max;
		options["redTo"]=max;
		options["redFrom"]=max-max/10;
		options["yellowTo"]=options["redFrom"];
		options["yellowFrom"]=max-max/4;
		options["greenTo"]=max-max/2;
		var chart = new google.visualization.Gauge(document.getElementById('chart_gauge_data'+id+'_div'));
		chart.draw(data, options);
	}
	function getLabel(id) {
		if(id=="label_entree6") return "Autre";
		if(id=="label_entreeAll") return "Total";
		return dataJSON[id];
	}
	function getInstValue(id) {
		if(id=="dataAll") return instTotal;
		return instJSON[id];
	}
	function onStart() {
		$('#stop').prop('disabled',false);
		inst_refresh();
		log2_refresh();
		$('#start').prop('disabled',true);
	}
	function onStop() {
		$('#start').prop('disabled',false);
		clearTimeout(timeOutId1);
		clearTimeout(timeOutId2);
		$('#stop').prop('disabled',true);
	}
	$(document).ready(function() {
		data_load();
		$("#start").prop('disabled',false);
		$("#stop").prop('disabled',true);
	});
</script>
</head>
<body>
<button id="start" onClick="onStart();" disabled>Start</button>
<button id="stop" onClick="onStop();" disabled>Stop</button>
<div style="width: 1680px; height: 240px;">
	<div id="chart_gauge_dataAll_div" style="width: 240px; height: 240px; float: left;"></div>
	<div id="chart_gauge_data1_div" style="width: 240px; height: 240px; float: left;"></div>
	<div id="chart_gauge_data2_div" style="width: 240px; height: 240px; float: left;"></div>
	<div id="chart_gauge_data3_div" style="width: 240px; height: 240px; float: left;"></div>
	<div id="chart_gauge_data4_div" style="width: 240px; height: 240px; float: left;"></div>
	<div id="chart_gauge_data5_div" style="width: 240px; height: 240px; float: left;"></div>
	<div id="chart_gauge_data6_div" style="width: 240px; height: 240px; float: left;"></div>	
</div>
<div style="width: 1680px; height: 240px;">
	<div id="chart_stackedBar_div" style="width: 1680px; height: 240px;"></div>
</div>
<div style="width: 1680px; height: 600px;">
	<div id="chart_line_div" style="width: 1680px; height: 600px;"></div>
</div>
</body>
</html>
<?php } ?>