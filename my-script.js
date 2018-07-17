function sistedes_accordion( button, id ) 
{
  var panel = document.getElementById( id );

  if ( panel ) {
    
    if ( panel.style.maxHeight ) {
      panel.style.maxHeight = null;
    } 
    else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    }

    if ( button ) {
      button.classList.toggle( "sistedes-accordion-collapsed" );
      button.classList.toggle( "sistedes-accordion-expanded" );
    }
  }
}

function listjson(data){
  var day = document.getElementById("day");
  var strDay = day.options[day.selectedIndex].value;

  var conf = document.getElementById("conf");
  var strConf = conf.options[conf.selectedIndex].value;

//  var url= "http://gsx2json.com/api?id=184lxggv9onMDb9_awsRjmlo8Xe_WGv96tkOa4luTVw8&sheet="+strConf;
  var url= "http://gsx2json.com/api?id="+data+"&sheet="+strConf;

  document.getElementById("program").innerHTML="";

  jQuery.getJSON( url ).done(function(data){
    filteredData=filterJSON(data.rows,strDay,strConf);
    var unique=getUniqueSessions(filteredData);
		for (var i = 0; i < unique.length; i++){
      var sessionName=unique[i];
      var talks=getSessions(sessionName,filteredData);
      printSession(sessionName,talks);
    }

  }); 
}

function filterJSON(data,strDay,strConf){
  var filtered=[];
  for (var i = 0; i < data.length; i++){
    var session=data[i];
    if(session.dia==strDay){
      filtered.push(session);
    }
  }
  return filtered;
}

function getUniqueSessions(arr){
  var uniqueNames = [];
	for (var i = 0; i < arr.length; i++){
		var obj = arr[i];

		if(uniqueNames.indexOf(obj.nombresesion) === -1){
			uniqueNames.push(obj.nombresesion);        
		}        
	}
	return uniqueNames;
}

function getSessions(sessionName,data){
  var objs =[];
  for (var i = 0; i < data.length; i++){
    var obj = data[i];
    if(obj.nombresesion ==sessionName){
      objs.push(obj);
    }
  }
  return objs;
}

function printSession(name,talks){
  var longName= talks[0].dia+" "+talks[0].hora+" "+name;
  var talksRes = "<button class=\"sistedes-accordion-button sistedes-accordion-collapsed\" onclick=\"sistedes_accordion(this,\'"+name+"\');\"><h4 class=\"sistedes-accordion-header\">"+
  longName+  "</h4></button><dl id=\""+name+"\" class=\"sistedes-accordion-panel\">"
  for (var i = 0; i < talks.length; i++){
    talksRes +="<dt>"+talks[i].tipo+"</dt>";
    talksRes +="<dd>"+talks[i].paper+" "+talks[i].autores+"</dd>";
  }
  talksRes+="</dl>";
  document.getElementById("program").innerHTML+=talksRes;
}