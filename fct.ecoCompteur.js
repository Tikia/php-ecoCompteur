function getTooltip(label,date,conso_wh,conso_euro,total_kWh,total_euro) {
	var txt="<div style='padding: 10px;'>"+
		"<b>"+date+"</b><br />"+
		"Consomation "+label+": <br />"+
		"&nbsp;&nbsp;"+getValue_kWh(conso_wh)+" kWh / "+total_kWh+" kWh<br />"+
		"&nbsp;&nbsp;"+getValue_euro(conso_euro)+" € / "+total_euro+" €"+
		"</div>";
		
	return txt;
}
function getValue_kWh(value) {
	return value;
}
function getValue_euro(value) {
	var montant=value;
	
	return montant.toFixed(3);
}
function createYearChart(json) {
	var date="";
	var all_kwh=0;
	var all_euro=0;
	var autre_kwh=0;
	var autre_euro=0;
	var tab = [];
	var tmp = [
			'Date',
			getLabel("label_entree1"),{'type': 'string', role:'tooltip', 'p': {'html': true}},
			getLabel("label_entree2"),{'type': 'string', role:'tooltip', 'p': {'html': true}},
			getLabel("label_entree3"),{'type': 'string', role:'tooltip', 'p': {'html': true}},
			getLabel("label_entree4"),{'type': 'string', role:'tooltip', 'p': {'html': true}},
			getLabel("label_entree5"),{'type': 'string', role:'tooltip', 'p': {'html': true}},
			getLabel("label_entree6"),{'type': 'string', role:'tooltip', 'p': {'html': true}}
		];
	tab.push(tmp);
	$.each(json,function(i,elem) {
		date = ((elem[0]>9) ? elem[0] : "0"+elem[0])+"/"+elem[1]+"/"+elem[2];
		all_kwh = parseFloat(elem[7])+parseFloat(elem[9])+parseFloat(elem[11])+parseFloat(elem[13])+parseFloat(elem[15]);
		all_euro = parseFloat(elem[8])+parseFloat(elem[10])+parseFloat(elem[12])+parseFloat(elem[14])+parseFloat(elem[16]);
		autre_kwh = (parseFloat(elem[5])-all_kwh >0 ) ? parseFloat(elem[5])-all_kwh : 0;
		autre_euro = (parseFloat(elem[6])-all_euro >0 ) ? parseFloat(elem[6])-all_euro : 0;
		tmp = [
			date,
			getValue_kWh(parseFloat(elem[7])),getTooltip(getLabel("label_entree1"),date,parseFloat(elem[7]),parseFloat(elem[8]),parseFloat(elem[5]),parseFloat(elem[6])),
			getValue_kWh(parseFloat(elem[9])),getTooltip(getLabel("label_entree2"),date,parseFloat(elem[9]),parseFloat(elem[10]),parseFloat(elem[5]),parseFloat(elem[6])),
			getValue_kWh(parseFloat(elem[11])),getTooltip(getLabel("label_entree3"),date,parseFloat(elem[11]),parseFloat(elem[12]),parseFloat(elem[5]),parseFloat(elem[6])),
			getValue_kWh(parseFloat(elem[13])),getTooltip(getLabel("label_entree4"),date,parseFloat(elem[13]),parseFloat(elem[14]),parseFloat(elem[5]),parseFloat(elem[6])),
			getValue_kWh(parseFloat(elem[15])),getTooltip(getLabel("label_entree5"),date,parseFloat(elem[15]),parseFloat(elem[16]),parseFloat(elem[5]),parseFloat(elem[6])),
			getValue_kWh(autre_kwh),getTooltip(getLabel("label_entree6"),date,autre_kwh,autre_euro,parseFloat(elem[5]),parseFloat(elem[6]))
		];
		//console.log(tmp);
		tab.push(tmp);
	
	});
	var data = google.visualization.arrayToDataTable(tab);
	var options = {
		width: 1440,
		height: 600,
		legend: { position: 'bottom' },
		curveType: 'none',
		tooltip: {
			isHtml: true,
			textStyle: { 'color': '#555555', 'fontName': 'Helvatica', 'fontSize': 16},
			},
		vAxis: {
			title: 'kWh',
			minValue: 0,
		}
	};
	var chart = new google.visualization.LineChart(document.getElementById('year_chart_line_div'));
	chart.draw(data, options);
	
	var data = google.visualization.arrayToDataTable(tab);
	var options = {
		width: 1440,
		height: 600,
		legend: { position: 'bottom' },
		isStacked: 'true',
		orientation: 'horizontal',
		tooltip: {
			isHtml: true,
			textStyle: { 'color': '#555555', 'fontName': 'Helvatica', 'fontSize': 16},
			},
		vAxis: {
			title: 'kWh',
			minValue: 0,
		}
	};
	var chart = new google.visualization.BarChart(document.getElementById('year_stackedBar_div'));
	chart.draw(data, options);
}
function createDayChart(json) {
	var dateLong="";
	var dateShort="";
	var all_kwh=0;
	var all_euro=0;
	var autre_kwh=0;
	var autre_euro=0;
	var tab = [];
	var tmp = [
			'Date',
			getLabel("label_entree1"),{'type': 'string', role:'tooltip', 'p': {'html': true}},
			getLabel("label_entree2"),{'type': 'string', role:'tooltip', 'p': {'html': true}},
			getLabel("label_entree3"),{'type': 'string', role:'tooltip', 'p': {'html': true}},
			getLabel("label_entree4"),{'type': 'string', role:'tooltip', 'p': {'html': true}},
			getLabel("label_entree5"),{'type': 'string', role:'tooltip', 'p': {'html': true}},
			getLabel("label_entree6"),{'type': 'string', role:'tooltip', 'p': {'html': true}}
		];
	tab.push(tmp);
	$.each(json,function(i,elem) {
		dateLong = ((elem[0]>9) ? elem[0] : "0"+elem[0])+"/"+elem[1]+"/"+elem[2]+" - "+((elem[3]>9) ? elem[3] : "0"+elem[3])+"h";
		dateShort = ((elem[3]>9) ? elem[3] : "0"+elem[3])+"h";
		all_kwh = parseFloat(elem[7])+parseFloat(elem[9])+parseFloat(elem[11])+parseFloat(elem[13])+parseFloat(elem[15]);
		all_euro = parseFloat(elem[8])+parseFloat(elem[10])+parseFloat(elem[12])+parseFloat(elem[14])+parseFloat(elem[16]);
		autre_kwh = (parseFloat(elem[5])-all_kwh >0 ) ? parseFloat(elem[5])-all_kwh : 0;
		autre_euro = (parseFloat(elem[6])-all_kwh >0 ) ? parseFloat(elem[6])-all_kwh : 0;
		tmp = [
			dateShort,
			getValue_kWh(parseFloat(elem[7])),getTooltip(getLabel("label_entree1"),dateLong,parseFloat(elem[7]),parseFloat(elem[8]),parseFloat(elem[5]),parseFloat(elem[6])),
			getValue_kWh(parseFloat(elem[9])),getTooltip(getLabel("label_entree2"),dateLong,parseFloat(elem[9]),parseFloat(elem[10]),parseFloat(elem[5]),parseFloat(elem[6])),
			getValue_kWh(parseFloat(elem[11])),getTooltip(getLabel("label_entree3"),dateLong,parseFloat(elem[11]),parseFloat(elem[12]),parseFloat(elem[5]),parseFloat(elem[6])),
			getValue_kWh(parseFloat(elem[13])),getTooltip(getLabel("label_entree4"),dateLong,parseFloat(elem[13]),parseFloat(elem[14]),parseFloat(elem[5]),parseFloat(elem[6])),
			getValue_kWh(parseFloat(elem[15])),getTooltip(getLabel("label_entree5"),dateLong,parseFloat(elem[15]),parseFloat(elem[16]),parseFloat(elem[5]),parseFloat(elem[6])),
			getValue_kWh(autre_kwh),getTooltip(getLabel("label_entree6"),dateLong,autre_kwh,autre_euro,parseFloat(elem[5]),parseFloat(elem[6]))
		];
		//console.log(tmp);
		tab.push(tmp);
	});
	var data = google.visualization.arrayToDataTable(tab);
	var options = {
		width: 1440,
		height: 600,
		legend: { position: 'bottom' },
		curveType: 'none',
		tooltip: {
			isHtml: true,
			textStyle: { 'color': '#555555', 'fontName': 'Helvatica', 'fontSize': 16},
			},
		vAxis: {
			title: 'kWh',
			minValue: 0,
		}
	};
	var chart = new google.visualization.LineChart(document.getElementById('day_chart_line_div'));
	chart.draw(data, options);
	
	var data = google.visualization.arrayToDataTable(tab);
	var options = {
		width: 1440,
		height: 600,
		legend: { position: 'bottom' },
		isStacked: 'true',
		orientation: 'horizontal',
		tooltip: {
			isHtml: true,
			textStyle: { 'color': '#555555', 'fontName': 'Helvatica', 'fontSize': 16},
			},
		vAxis: {
			title: 'kWh',
			minValue: 0,
		}
	};
	//var chart = new google.visualization.BarChart(document.getElementById('day_stackedBar_div'));
	var chart = new google.visualization.BarChart(document.getElementById('day_stackedBar_div'));
	chart.draw(data, options);
}
function createChartStackedBar() {
	var data = google.visualization.arrayToDataTable([
		['',getLabel("label_entree1"),getLabel("label_entree2"),getLabel("label_entree3"),getLabel("label_entree4"),getLabel("label_entree5"),getLabel("label_entree6"),getLabel("label_entreeAll"),{ role: 'annotation' } ],
		['', getInstValue("data1"),getInstValue("data2"),getInstValue("data3"),getInstValue("data4"),getInstValue("data5"),getInstValue("data6"),0,'']
	]);
	var options = {
		width: 1440,
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
	var data = google.visualization.arrayToDataTable([['Label', 'Value'],[getLabel("label_entree"+id), value]]);
	if(id=="All") {
		var options = {width:240, height:240, max:100, redFrom:90, redTo:100, yellowFrom:75, yellowTo:90, greenFrom:0, greenTo:75, minorTicks: 5};
	}
	else {
		var options = {width:240, height:240, max:100, redFrom:90, redTo:100, yellowFrom:75, yellowTo:90, greenFrom:0, greenTo:75, minorTicks: 5};
		instTotal=instTotal+value;
	}
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
		timeOutId=setTimeout(inst_refresh,1000);
	  }
	});
}
function data_load() {
	$.ajax({
	  url: page_data,
	  success: function(result) {
		dataJSON=JSON.parse(result);
	  }
	});
}
function log2_refresh() {
	$.ajax({
	  url: page_log2,
	  success: function(result) {
		createDayChart(JSON.parse(result));
		timeOutId=setTimeout(log2_refresh,1000*60*30);
	  }
	});
}
function log1_refresh() {
	$.ajax({
	  url: page_log1,
	  success: function(result) {
		createYearChart(JSON.parse(result));
		timeOutId=setTimeout(log1_refresh,1000*60*60*12);
	  }
	});
}
function onStart(type) {
	$('#bttn_stop').prop('disabled',false);
	if(type=='inst') {
		$("#inst").show();
		inst_refresh();
	}
	else if(type=='jour') {
		$("#jour").show();
		log2_refresh();
	}
	else if(type=='an') {
		$("#an").show();
		log1_refresh();		
	}
	$("#bttn_inst").prop('disabled',true);
	$("#bttn_jour").prop('disabled',true);
	$("#bttn_an").prop('disabled',true);
}
function onStop() {
	$("#bttn_inst").prop('disabled',false);
	$("#bttn_jour").prop('disabled',false);
	$("#bttn_an").prop('disabled',false);
	$("#inst").hide();
	$("#jour").hide();
	$("#an").hide();
	clearTimeout(timeOutId);
	$('#bttn_stop').prop('disabled',true);
}
$(document).ready(function() {
	data_load();
	$("#bttn_inst").prop('disabled',false);
	$("#bttn_jour").prop('disabled',false);
	$("#bttn_an").prop('disabled',false);
	$("#bttn_stop").prop('disabled',true);
});