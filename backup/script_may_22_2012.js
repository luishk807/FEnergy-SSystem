// JavaScript Document
var xmlHttpReq = null;
function getHttpPost() 
{
	try{			
		xmlHttpReq=new XMLHttpRequest();// Firefox, Opera 8.0+, Safari
	}catch (e)
	{		
		try{			
			xmlHttpReq=new ActiveXObject("Msxml2.XMLHTTP"); // Internet Explorer
		}catch (e)
		{		    
			try{				
				xmlHttpReq=new ActiveXObject("Microsoft.XMLHTTP");	
			}catch (e)
			{				
				alert("No AJAX!?");				
				return false;			
			}		
		}	
	}
}
function changeStatSort(value,qu,ascdesc)
{
	getHttpPost();
    xmlHttpReq.onreadystatechange = function()
	{
   		if(xmlHttpReq.readyState == 4)
		{
           document.getElementById("wholegraph_pop").innerHTML=xmlHttpReq.responseText;
        }
    }
	var url = "popupdategraph.php?task="+value+"&"+qu+"&ascdesc="+ascdesc;
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
}
/*function graphswitch_month(value)
{
	getHttpPost();
    xmlHttpReq.onreadystatechange = function()
	{
   		if(xmlHttpReq.readyState == 4)
		{
           document.getElementById("weekcont").innerHTML=xmlHttpReq.responseText;
		   //timer= setInterval(pieChart,1000);
        }
    }
	var url = "graphswitchweek_cont.php?cho_monthx="+value;
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
}
function graphswitch_month_test(value)
{
	getHttpPost();
    xmlHttpReq.onreadystatechange = function()
	{
   		if(xmlHttpReq.readyState == 4)
		{
           document.getElementById("weekcont").innerHTML=xmlHttpReq.responseText;
        }
    }
	var url = "graphswitchweek_cont_test.php?cho_monthx="+value;
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
}*/
function showgraphinfopop(value)
{
	getHttpPost();
    xmlHttpReq.onreadystatechange = function() 
	{
   		if(xmlHttpReq.readyState == 4) 
		{
           document.getElementById('modalform').innerHTML=xmlHttpReq.responseText;
        }
    }
	var url = "showgraphresult.php?"+value;
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
}
function sendEmail(namex,email,comment,phone)
{
	getHttpPost();
    xmlHttpReq.onreadystatechange = function() 
	{
   		if(xmlHttpReq.readyState == 4) 
		{
           document.getElementById('messagexb').innerHTML=xmlHttpReq.responseText;
        }
    }
	var url = "sendEmail.php?namex="+namex+"&email="+email+"&comment="+comment+"&phone="+phone;
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
}
function sendEmaily(namex,phone)
{
	getHttpPost();
    xmlHttpReq.onreadystatechange = function() 
	{
   		if(xmlHttpReq.readyState == 4) 
		{
           document.getElementById('sendexp_message_in').innerHTML=xmlHttpReq.responseText;
        }
    }
	var url = "sendEmail.php?task=faste&namex="+namex+"&phone="+phone;
	xmlHttpReq.open('POST',url, true);
	//xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlHttpReq.send(null);
}
function errorcheckx(task,vars,message)
{
	var cont='message2m';
	var color = "c3c2c2";
	var variable = document.getElementById(vars).value;
	if(task=="text")
	{
		if(variable.length==0 || isNaN(variable)==false)
		{
			document.getElementById(vars).style.background=color;
			document.getElementById(cont).innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById(cont).innerHTML="";
		}
	}
	if(task=="select")
	{
		if(variable=="0")
		{
			document.getElementById(cont).innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(cont).innerHTML="";
		}
	}
	else if(task=="number")
	{
		if(variable.length==0 || isNaN(variable)==true)
		{
			document.getElementById(vars).style.background=color;
			document.getElementById(cont).innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById(cont).innerHTML="";
		}	
	}
	else if(task=="normal")
	{
		if(variable.length==0)
		{
			document.getElementById(vars).style.background=color;
			document.getElementById(cont).innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById(cont).innerHTML="";
		}
	}
	else if(task=="email")
	{
		var email = document.getElementById(vars).value;
		if(email.length !=0)
		{
			if(variable.length==0 || ((variable.indexOf(".")<2) && (variable.indexOf("@")<=0)))
			{
				document.getElementById(vars).style.background=color;
				document.getElementById(cont).innerHTML=message;
				return false;
			}
			else
			{
				document.getElementById(vars).style.background="";
				document.getElementById(cont).innerHTML="";
			}
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById(cont).innerHTML="";
		}
	}
	else if(task=="emailf")
	{
		var email = document.getElementById(vars).value;
		if(variable.length==0 || ((variable.indexOf(".")<2) && (variable.indexOf("@")<=0)))
		{
			document.getElementById(vars).style.background=color;
			document.getElementById(cont).innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById(cont).innerHTML="";
		}
	}
	else if(task=="selects")
	{
		if(document.getElementById(vars).selectedIndex==0)
		{
			document.getElementById(cont).innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(cont).innerHTML="";
		}
	}
	else if(task=="checksa")
	{
		if(document.getElementById(vars).checked==false)
		{
			document.getElementById(cont).innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(cont).innerHTML="";
		}
	}
	return true;
}
function errorcheck(task,vars,message)
{
	var color = "#a3a3a3";
	//var color = "";
	var variable = document.getElementById(vars).value;
	if(task=="text")
	{
		if(variable.length==0 || isNaN(variable)==false)
		{
			document.getElementById(vars).style.background=color;
			document.getElementById("message2").innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById("message2").innerHTML="";
		}
	}
	if(task=="select")
	{
		if(variable=="0")
		{
			document.getElementById("message2").innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById("message2").innerHTML="";
		}
	}
	else if(task=="number")
	{
		if(variable.length==0 || isNaN(variable)==true)
		{
			document.getElementById(vars).style.background=color;
			document.getElementById("message2").innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById("message2").innerHTML="";
		}	
	}
	else if(task=="normal")
	{
		if(variable.length==0)
		{
			document.getElementById(vars).style.background=color;
			document.getElementById("message2").innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById("message2").innerHTML="";
		}
	}
	else if(task=="email")
	{
		var email = document.getElementById(vars).value;
		if(email.length !=0)
		{
			if(variable.length==0 || ((variable.indexOf(".")<2) && (variable.indexOf("@")<=0)))
			{
				document.getElementById(vars).style.background=color;
				document.getElementById("message2").innerHTML=message;
				return false;
			}
			else
			{
				document.getElementById(vars).style.background="";
				document.getElementById("message2").innerHTML="";
			}
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById("message2").innerHTML="";
		}
	}
	else if(task=="emailf")
	{
		var email = document.getElementById(vars).value;
		if(variable.length==0 || ((variable.indexOf(".")<2) && (variable.indexOf("@")<=0)))
		{
			document.getElementById(vars).style.background=color;
			document.getElementById("message2").innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById(vars).style.background="";
			document.getElementById("message2").innerHTML="";
		}
	}
	else if(task=="selects")
	{
		if(document.getElementById(vars).selectedIndex==0)
		{
			document.getElementById("message2").innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById("message2").innerHTML="";
		}
	}
	else if(task=="checksa")
	{
		if(document.getElementById(vars).checked==false)
		{
			document.getElementById("message2").innerHTML=message;
			return false;
		}
		else
		{
			document.getElementById("message2").innerHTML="";
		}
	}
	return true;
}
function preload(images) {
    if (document.images) {
        var i = 0;
        var imageArray = new Array();
        imageArray = images.split(',');
        var imageObj = new Image();
        for(i=0; i<=imageArray.length-1; i++) {
            //document.write('<img src="' + imageArray[i] + '" />');// Write to page (uncomment to check images)
            imageObj.src=images[i];
        }
    }
}
function checkField_fp1()
{
	//create reset password page
	if(!errorcheck("email","femail","Please enter a valid email"))
		return false;
	var femail=document.getElementById("femail").value;
	var uname=document.getElementById("uname").value;
	if(femail.length<1 && uname.length<1)
	{
		document.getElementById("femail").style.background="#b5b5b5";
		document.getElementById("uname").style.background="#b5b5b5";
		document.getElementById("message2").innerHTML="You Must Provide A Mean To Find Your Information.<br/>This could be either an email address or your username";
		return false;
	}
	else
	{
		document.getElementById("femail").style.background="";
		document.getElementById("uname").style.background="";
		document.getElementById("message2").innerHTML="";
	}
	return true;
}
function checkField_fp2()
{
	//reset password page
	if(!errorcheck("normal","fpass","Please your new password"))
		return false;
	if(!errorcheck("normal","rfpass","Please re-type your new password"))
		return false;
	var fpass=document.getElementById("fpass").value;
	var rfpass=document.getElementById("rfpass").value;
	if(fpass != rfpass)
	{
		document.getElementById("fpass").style.background="#b5b5b5";
		document.getElementById("rfpass").style.background="#b5b5b5";
		document.getElementById("message2").innerHTML="Both Password Must Match, Please retry";
		return false;
	}
	else
	{
		document.getElementById("fpass").style.background="";
		document.getElementById("rfpass").style.background="";
		document.getElementById("message2").innerHTML="";
	}
	return true;
}
function checkFieldv()
{
	var cgoal=document.getElementById("cgoal").value;
	var goalused=document.getElementById("goalused").value;
	if(!errorcheck("selects","uoffice","Please Select Office For This goal"))
		return false;
	else if(!errorcheck("number","ugoal","Please Write A Goal For This office"))
		return false;
	var cgoalx=parseInt(cgoal);
	var ugoal=document.getElementById("ugoal").value;
	var ugoalx=parseInt(ugoal);
	var goalusedx=parseInt(goalused);
	if(ugoalx<cgoalx)
	{
		if(ugoalx<goalusedx)
		{
			var confirmx = window.confirm("WARNING!!: THE GOAL YOU ARE ABOUT THE UPDATE IS LOWER THAN THE ORIGINAL GOAL. DOING THIS, WILL CAUSE SYSTEM TO RESET AND DELETE ALL THE GOALS OF THE MANAGERS AND TEAM LEADERS ASSIGNED TO THIS OFFICE. ARE YOU SURE YOU WANT TO PROCEED?!\r\n\r\nClick Yes To Proceed Or Cancel To Cancel The Process.");
			if(confirmx==true)
			{
				document.getElementById("dall").value="yes";
				return true;
			}
			else
				return false;
		}
		else
			return true;
	}
	else
		return true;
}
function checkFieldy()
{
	if(!errorcheck("selects","uoffice","Please Select Office For This goal"))
		return false;
	else if(!errorcheck("number","ugoal","Please Write A Goal For This office"))
		return false;
	return true;
}
function checkFieldx()
{
	if(!errorcheck("selects","umanager","Please Select User To Set This Goal"))
		return false;
	if(!errorcheck("selects","uoffice","Please Select Office For This User"))
		return false;
	else if(!errorcheck("number","ugoal","Please Write A Goal For This User"))
		return false;
	return true;
}
function checkField()
{
	//login page
	if(!errorcheck("normal","uname","Please enter username"))
		return false;
	if(!errorcheck("normal","upass","Please enter password"))
		return false;
	return true;
}
function checkField_m()
{
	//login page
	if(!errorcheckx("normal","uname","Please enter username"))
		return false;
	if(!errorcheckx("normal","upass","Please enter password"))
		return false;
	return true;
}
function checkFieldg()
{
	//account page
	var checkin = document.getElementById("changepass").value;
	if(!errorcheck("normal","uname","Please Write A Username"))
		return false;
	if(checkin=="yes")
	{
		if(!errorcheck("normal","newpass","Please write the new password"))
			return false;
		if(!errorcheck("normal","renewpass","Please re-type the password"))
			return false;
		if(!changechangepass_create("newpass","renewpass","message2",""))
			return false;
	}
	else
	{
		document.getElementById("message2").innerHTML="";
		document.getElementById("newpass").style.background="";
		document.getElementById("renewpass").style.background="";
	}
	if(!errorcheck("normal","realname","Please provide a valid name"))
		return false;
	if(!errorcheck("number","cphonea","Please enter your phone area code"))
		return false;
	if(!errorcheck("number","cphoneb","Please enter your complete phone"))
		return false;
	if(!errorcheck("number","cphonec","Please enter your complete phone"))
		return false;
	var cphonea=document.getElementById("cphonea").value;
	var cphoneb=document.getElementById("cphoneb").value;
	var cphonec=document.getElementById("cphonec").value;
	document.getElementById("cphone").value=cphonea+"-"+cphoneb+"-"+cphonec;
	if(!errorcheck("emailf","uemail","Please provide a valid email"))
		return false;
	if(!errorcheck("normal","utitle","Please provide a title"))
		return false;
	var checktype = document.getElementById("checktype").value;
	if(checktype=="yes")
	{
		if(!errorcheck("selects","officeman","Please select the office for this manager or team leader"))
		return false;
	}
	var checkreportt = document.getElementById("checkreportt").value;
	if(checkreportt=="yes")
	{
		if(!errorcheck("selects","reportto","Please select the manager that this team leader reports to"))
		return false;
	}
	return true;
}
function checkFieldc()
{
	//form from the create form
	if(!errorcheck("normal","uname","Please Write A Username"))
		return false;
	if(!errorcheck("emailf","uemail","Please provide a valid email"))
		return false;
	if(!changechangepass_create("newpass","renewpass","message2","This Account"))
		return false;
	if(!errorcheck("normal","realname","Please provide a valid name"))
		return false;
	if(!errorcheck("number","cphonea","Please enter phone area code"))
		return false;
	if(!errorcheck("number","cphoneb","Please enter complete phone"))
		return false;
	if(!errorcheck("number","cphonec","Please enter complete phone"))
		return false;
	var cphonea=document.getElementById("cphonea").value;
	var cphoneb=document.getElementById("cphoneb").value;
	var cphonec=document.getElementById("cphonec").value;
	document.getElementById("cphone").value=cphonea+"-"+cphoneb+"-"+cphonec;
	if(!errorcheck("normal","utitle","Please provide a title"))
		return false;
	if(!warningpop("status","ustatus",""))
		return false;
	if(!warningpop("type_c","utype",""))
		return false;
	var checktype = document.getElementById("checktype").value;
	if(checktype=="yes")
	{
		if(!errorcheck("selects","officeman","Please select the office for this manager or team leader"))
		return false;
	}
	var checkreportt = document.getElementById("checkreportt").value;
	if(checkreportt=="yes")
	{
		if(!errorcheck("selects","reportto","Please select the manager that this team leader reports to"))
		return false;
	}
	return true;
}
function checkFieldd()
{
	//form from the user setting form
	if(!errorcheck("emailf","uemail","Please provide a valid email"))
		return false;
	if(!errorcheck("normal","uname","Please Write A Username"))
		return false;
	if(!changechangepass("checkchange","newpass","renewpass","message2","This Account"))
		return false;
	if(!errorcheck("normal","realname","Please provide a valid name"))
		return false;
	if(!errorcheck("number","cphonea","Please enter phone area code"))
		return false;
	if(!errorcheck("number","cphoneb","Please enter complete phone"))
		return false;
	if(!errorcheck("number","cphonec","Please enter complete phone"))
		return false;
	var cphonea=document.getElementById("cphonea").value;
	var cphoneb=document.getElementById("cphoneb").value;
	var cphonec=document.getElementById("cphonec").value;
	document.getElementById("cphone").value=cphonea+"-"+cphoneb+"-"+cphonec;
	if(!errorcheck("normal","utitle","Please provide a title"))
		return false;
	if(!warningpop("status","ustatus",""))
		return false;
	if(!warningpop("type","utype",document.getElementById("cutype").value))
		return false;
	var checktype = document.getElementById("checktype").value;
	if(checktype=="yes")
	{
		if(!errorcheck("selects","officeman","Please select the office for this manager or team leader"))
		return false;
	}
	var checkreportt = document.getElementById("checkreportt").value;
	if(checkreportt=="yes")
	{
		if(!errorcheck("selects","reportto","Please select the manager that this team leader reports to"))
		return false;
	}
	return true;
}
function checkFieldd_a()
{
	//form from the report setting form
	if(!errorcheck("normal","aname","Please provide a valid agent name"))
		return false;
	if(!errorcheck("selects","uoffice","Please select the office"))
		return false;
	if(!errorcheck("number","usales","Please provide a valid total of sales"))
		return false;
	if(!checkDater('fromm','fromd','fromy','fhour','fminute','fromdate'))
		return false;
	return true;
}
function checkFieldtotal()
{
	//form from the real total
	if(!errorcheck("number","utotal","Please provide a valid number"))
		return false;
	return true;
}
function checkFieldgraphdate()
{
	//form from graph view for date engine
	var xyear1=document.getElementById("xyear1").value;
	var xyear2=document.getElementById("xyear2").value;
	var xyear1x=parseInt(xyear1);
	var xyear2x=parseInt(xyear2);
	var xyear_total=xyear2x-xyear1x;
	if(xyear_total>1)
	{
		document.getElementById("message").innerHTML="Please select a valid year, only one year of difference is allowed<br/>";
	}
	else if(xyear_total<0)
	{
		document.getElementById("message").innerHTML="Please select a valid year";
	}
	else
	{
		var imonthx="";
		var cho_monthx=document.getElementById("cho_monthx").value;
		if(cho_monthx.length>0)
			imonthx="&cho_monthx="+cho_monthx;
		document.getElementById("message").innerHTML="";
		window.location.href="viewgraph.php?xyear1="+xyear1+"&xyear2="+xyear2+""+imonthx;
	}
}
function checkFieldd_ax()
{
	//form from the report setting form
	if(!errorcheck("selects","uman","Please select the Manager"))
		return false;
	if(!errorcheck("normal","aname","Please provide a valid agent name"))
		return false;
	if(!errorcheck("selects","uoffice","Please select the office"))
		return false;
	if(!errorcheck("number","usales","Please provide a valid total of sales"))
		return false;
	if(!checkDater('fromm','fromd','fromy','fhour','fminute','fromdate'))
		return false;
	return true;
}
function checkFieldv_a()
{
	//form from the report creationg form browser
	if(!errorcheck("normal","aname","Please provide a valid agent name"))
		return false;
	if(!errorcheck("selects","uoffice","Please select the office"))
		return false;
	if(!errorcheck("number","usales","Please provide a valid total of sales"))
		return false;
	if(!checkDaterb('fromdatex','fhour','fminute','fromdate'))
		return false;
	return true;
}
function checkFieldv_ax()
{
	//form from the report edit form browser
	if(!errorcheck("selects","uman","Please select the manager"))
		return false;
	if(!errorcheck("normal","aname","Please provide a valid agent name"))
		return false;
	if(!errorcheck("selects","uoffice","Please select the office"))
		return false;
	if(!errorcheck("number","usales","Please provide a valid total of sales"))
		return false;
	if(!checkDaterb('fromdatex','fhour','fminute','fromdate'))
		return false;
	return true;
}
function checkFieldi()
{
	//office  page
	if(!errorcheck("normal","oname","Please Write A Office Name"))
		return false;
	if(!errorcheck("normal","ocontact","Please Write A Contact Name"))
		return false;
	if(!errorcheck("emailf","oemail","Please provide a valid email"))
		return false;
	if(!errorcheck("normal","ophone","Please provide a valid Phone"))
		return false;
	if(!errorcheck("normal","odays","Please provide days avaliable for meeting at this office"))
		return false;
	if(!errorcheck("normal","ohours","Please provide hours avaliable for meeting at this office"))
		return false;
	if(!errorcheck("normal","oaddress","Please provide a valid address"))
		return false;
	if(!errorcheck("normal","ocity","Please provide a valid city"))
		return false;
	if(!errorcheck("normal","ostate","Please provide a valid state"))
		return false;
	if(!errorcheck("normal","ocountry","Please provide a valid country"))
		return false;
	if(!errorcheck("normal","ozip","Please provide a valid Zip/Postal Code"))
		return false;
	if(!errorcheck("normal","odriving","Please provide a direction instructions by driving"))
		return false;
	if(!errorcheck("normal","owalking","Please provide a direction instructions by walking"))
		return false;
	return true;
}
function allowpassword()
{
	var checking = document.getElementById("checkchange").checked;
	if(checking==true)
	{
		document.getElementById("allowpassworddiv").style.display="block";
		document.getElementById("changepass").value="yes";
	}
	else
	{
		document.getElementById("changepass").value="no";
		document.getElementById("newpass").value="";
		document.getElementById("renewpass").value="";
		document.getElementById("allowpassworddiv").style.display="none";
	}
}
function allowofficeman(value)
{
	if(value=="5")
	{
		document.getElementById("officemandiv").style.display="block";
		document.getElementById("reporttodiv").style.display="block";
		document.getElementById("checktype").value="yes";
		document.getElementById("checkreportt").value="yes";
	}
	else if(value=="6")
	{
		document.getElementById("officemandiv").style.display="block";
		document.getElementById("reporttodiv").style.display="none";
		document.getElementById("checktype").value="yes";
		document.getElementById("checkreportt").value="no";
	}
	else
	{
		document.getElementById("officemandiv").style.display="none";
		document.getElementById("reporttodiv").style.display="none";
		document.getElementById("checktype").value="no";
		document.getElementById("checkreportt").value="no";
	}
}
function warningpop(task,valuex,valuexb)
{
	//show differnet warning pop
	var value= document.getElementById(valuex).value;
	if(task=="status")
	{
		if(value =="2" || value=="3")
		{
			var confirmx = window.confirm("WARNING! You Are About To Block Access For This User!.\r\n\r\nUser Wouldn't be able to access Map System, Task Manager System and Master Recuiter System.\r\n\r\nDo You Want To Proceed?.\r\n\r\nTo Proceed, click Okay or To Cancel click Cancel");
			if(confirmx==false)
				return false;
			else
				return true;
		}
		return true;
	}
	else if(task=="type")
	{
		if((value =="1" || value=="2" || value=="4") && valuexb=='3')
		{
			var confirmx = window.confirm("WARNING! You Are About To Grant Administrator Access To This User!. Doing so user will be able to do task that are normally exclusive for a Super Admin, Admin, and Web Designer!.\r\n\r\nDo You Want To Proceed?.\r\n\r\nTo Proceed, click Okay or To Cancel click Cancel");
			if(confirmx==false)
				return false;
			else
				return true;
		}
		return true;
	}
	else if(task=="type_c")
	{
		if(value =="1" || value=="2" || value=="4")
		{
			var confirmx = window.confirm("WARNING! You Are About To Grant Administrator Access To This User!. Doing so user will be able to do task that are normally exclusive for a Super Admin, Admin, and Web Designer!.\r\n\r\nDo You Want To Proceed?.\r\n\r\nTo Proceed, click Okay or To Cancel click Cancel");
			if(confirmx==false)
				return false;
			else
				return true;
		}
		return true;
	}
	return true;
}
function changechangepass(checkb,newpass,repass,messageb,xname)
{
	//do the password validation, return true or false
	var changepass = document.getElementById(checkb).checked;
	if(changepass==true)
	{
		var name1 = "Please Write New Password For "+xname;
		var name2 = "Please Re-Write New Password "+xname;
		if(!errorcheck("normal",newpass,name1))
			return false;
		if(!errorcheck("normal",repass,name2))
			return false;
		var newpass = document.getElementById(newpass).value;
		var renewpass = document.getElementById(repass).value;
		if(newpass != renewpass)
		{
			document.getElementById(repass).style.background="#a3a3a3";
			document.getElementById(messageb).innerHTML="Both Password Must Match";
			return false;
		}
		else
		{
			document.getElementById(repass).style.background="";
			document.getElementById(messageb).innerHTML="";
			return true;
		}
	}
	return true;
}
function changechangepass_create(newpass,repass,messageb,xname)
{
	//do the password validation, return true or false no from checkbox
	var name1 = "Please Write New Password For "+xname;
	var name2 = "Please Re-Write New Password "+xname;
	if(!errorcheck("normal",newpass,name1))
		return false;
	if(!errorcheck("normal",repass,name2))
		return false;
	var newpass = document.getElementById(newpass).value;
	var renewpass = document.getElementById(repass).value;
	if(newpass != renewpass)
	{
		document.getElementById(repass).style.background="#a3a3a3";
		document.getElementById(messageb).innerHTML="Both Password Must Match";
		return false;
	}
	else
	{
		document.getElementById(repass).style.background="";
		document.getElementById(messageb).innerHTML="";
		return true;
	}
}
function adddriver(value)
{
	var confirmx = window.confirm("WARNING!!: YOU ARE ABOUT TO SET THIS DRIVER TO THE SELECTED VEHICLE. ARE YOU SURE YOU WANT TO PROCEED?!\r\n\r\nClick Yes To Proceed Or Cancel To Cancel The Process.");
	if(confirmx==true)
		window.location.href='http://www.familyenergymap.com/femcar/save.php?task=setcar&v='+value;	
}
function returnadd(value)
{
	var confirmx = window.confirm("WARNING!!: YOU ARE ABOUT TO SET THIS CAR AS RETURNED. ARE YOU SURE YOU WANT TO PROCEED?!\r\n\r\nClick Yes To Proceed Or Cancel To Cancel The Process.");
	if(confirmx==true)
		window.location.href='http://www.familyenergymap.com/femcar/save.php?task=returncar&v='+value;	
}
function checkDate(m1,d2,y3,itemx)
{
	var m1x = document.getElementById(m1).value;
	var d2x = document.getElementById(d2).value;
	var y3x = document.getElementById(y3).value;
	if(m1x !='na' && d2x !='na' && y3x !='na')
		document.getElementById(itemx).value=y3x+"-"+m1x+"-"+d2x;
	else
		document.getElementById(itemx).value="";
}
function checkDater(m1,d2,y3,hx,mx,itemx,msg)
{
	var m1x = document.getElementById(m1).value;
	var d2x = document.getElementById(d2).value;
	var y3x = document.getElementById(y3).value;
	var hxx = document.getElementById(hx).value;
	var mxx= document.getElementById(mx).value;
	if(m1x !='na' && d2x !='na' && y3x !='na' && hxx !='na' && mxx !='na')
	{
		document.getElementById('message2').innerHTML="";
		document.getElementById(itemx).value=y3x+"-"+m1x+"-"+d2x+" "+hxx+":"+mxx+":00";
		return true;
	}
	else
	{
		document.getElementById('message2').innerHTML=msg;
		document.getElementById(itemx).value="";
		return false;
	}
}
function checkDaterb(m1,hx,mx,itemx,msg)
{
	var m1x = document.getElementById(m1).value;
	var hxx = document.getElementById(hx).value;
	var mxx= document.getElementById(mx).value;
	if(m1x !="" && hxx !='na' && mxx !='na')
	{
		document.getElementById('message2').innerHTML="";
		document.getElementById(itemx).value=m1x+" "+hxx+":"+mxx+":00";
		return true;
	}
	else
	{
		document.getElementById('message2').innerHTML=msg;
		document.getElementById(itemx).value="";
		return false;
	}
}
//script for the goals
function deleteindgoalx(idx)
{
	var id=document.getElementById("id").value;
	var value='&id='+id+'&idx='+idx;
	deletetask('deleteindgoalx',value);
}
function updateindgoalx(idx,uman,ug,tgoal)
{
	var id=document.getElementById("id").value;
	var ogoalx=document.getElementById("ogoal").value;
	var umanager=document.getElementById(uman).value;
	var ugoal=document.getElementById(ug).value;
	var lgoal = parseInt(tgoal)-parseInt(ugoal);
	var xugoalx=parseInt(ugoal)
	if(lgoal<0)
	{
		document.getElementById(ug).style.background="#a3a3a3";
		document.getElementById("message2").innerHTML="Invalid: Amount Exceed The Limit of "+ogoalx;
	}
	else if(xugoalx <1 || isNaN(xugoalx)==true)
	{
		document.getElementById(ug).style.background="#a3a3a3";
		document.getElementById("message2").innerHTML="Invalid: Enter A Valid Amount";
	}
	else
	{
		document.getElementById(ug).style.background="";
		document.getElementById("message2").innerHTML="";
		var value='&id='+id+'&idx='+idx+'&umanager='+umanager+'&ugoal='+ugoal;
		//alert(value);
		deletetask('updateindgoalx',value);
	}
}
function creategoalx()
{
	var host="http://www.familyenergymap.com/salesreport/";
	var check=false;
	var id=document.getElementById("id").value;
	var ogoalx=document.getElementById("ogoal").value;
	var xugoal=document.getElementById("xugoal").value;
	var xumanager=document.getElementById("xumanager").value;
	var lgoalx=document.getElementById("lgoalx").value;
	lgoalx=parseInt(lgoalx);
	if(!errorcheck("number","xugoal","Please set a goal"))
		check=false;
	else
		check=true;
	if(check)
	{
		var xugoalx=parseInt(xugoal);
		if(xugoalx>lgoalx)
		{
			document.getElementById("xugoal").style.background="#a3a3a3";
			document.getElementById("message2").innerHTML="Invalid: New Amount Exceed The Limit of "+ogoalx;
		}
		else if(xugoalx <1 || isNaN(xugoalx)==true)
		{
			document.getElementById("xugoal").style.background="#a3a3a3";
			document.getElementById("message2").innerHTML="Invalid: Enter A Valid Amount";
		}
		else
		{
			document.getElementById("xugoal").style.background="";
			document.getElementById("message2").innerHTML="";
			window.location.href=host+'save.php?task=createindgoalx&id='+id+'&ugoal='+xugoal+'&umanager='+xumanager;
		}
	}
}
//end of script for goals
function deletetask(tasks,value)
{
	var host="http://www.familyenergymap.com/salesreport/";
	if(tasks=="users")
	{
		var confirmx = window.confirm("WARNING!!: YOU ARE ABOUT TO DELETE THIS USER. ARE YOU SURE YOU WANT TO PROCEED?!\r\n\r\nClick Yes To Proceed Or Cancel To Cancel The Process.");
		if(confirmx==true)
		window.location.href=host+'save.php?task=delete&id='+value;	
	}
	if(tasks=="agent")
	{
		var confirmx = window.confirm("WARNING!!: YOU ARE ABOUT TO DELETE THIS AGENT. ARE YOU SURE YOU WANT TO PROCEED?!\r\n\r\nClick Yes To Proceed Or Cancel To Cancel The Process.");
		if(confirmx==true)
		window.location.href=host+'save.php?task=deleteagent&id='+value;	
	}
	else if(tasks =="vehicle")
	{
		var confirmx = window.confirm("WARNING!!: YOU ARE ABOUT TO DELETE THIS REPORT. ARE YOU SURE YOU WANT TO PROCEED?!\r\n\r\nClick Yes To Proceed Or Cancel To Cancel The Process.");
		if(confirmx==true)
		window.location.href=host+"save.php?task=deletereport&id="+value;	
	}
	else if(tasks =="goals")
	{
		var confirmx = window.confirm("WARNING!!: YOU ARE ABOUT TO DELETE THIS GOAL. ARE YOU SURE YOU WANT TO PROCEED?!\r\n\r\nClick Yes To Proceed Or Cancel To Cancel The Process.");
		if(confirmx==true)
		window.location.href=host+"save.php?task=deletegoals&id="+value;	
	}
	else if(tasks =="office")
	{
		var confirmx = window.confirm("WARNING!!: YOU ARE ABOUT TO DELETE THIS OFFICE. ARE YOU SURE YOU WANT TO PROCEED?!\r\n\r\nClick Yes To Proceed Or Cancel To Cancel The Process.");
		if(confirmx==true)
		window.location.href=host+"save.php?task=deleteoffice&id="+value;	
	}
	else if(tasks =="report")
	{
		var confirmx = window.confirm("WARNING!!: YOU ARE ABOUT TO DELETE THIS REPORT. ARE YOU SURE YOU WANT TO PROCEED?!\r\n\r\nClick Yes To Proceed Or Cancel To Cancel The Process.");
		if(confirmx==true)
		window.location.href=host+"save.php?task=deletereport&id="+value;	
	}
	else if(tasks=="deletegoalx")
	{
		var confirmx = window.confirm("WARNING!!: YOU ARE ABOUT TO DELETE THE CHOSEN GOAL.\r\n\r\nDOING SO WILL DELETE ALL THE GOALS FOR THE MANAGERS AND TEAM LEADER LINKED TO THIS OFFICE\r\n\r\nARE YOU SURE YOU WANT TO PROCEED?!\r\n\r\nClick Yes To Proceed Or Cancel To Cancel The Process.");
		if(confirmx==true)
		window.location.href=host+"save.php?task=deletegoalx&id="+value;	
	}
	else if(tasks=="deleteindgoalx")
	{
		var confirmx = window.confirm("WARNING!!: YOU ARE ABOUT TO DELETE THE CHOSEN GOAL. ARE YOU SURE YOU WANT TO PROCEED?!\r\n\r\nClick Yes To Proceed Or Cancel To Cancel The Process.");
		if(confirmx==true)
		window.location.href=host+"save.php?task=deleteindgoalx"+value;	
	}
	else if(tasks=="updateindgoalx")
	{
		var confirmx = window.confirm("WARNING!!: YOU ARE ABOUT TO UPDATE THE CHOSEN GOAL. ARE YOU SURE YOU WANT TO PROCEED?!\r\n\r\nClick Yes To Proceed Or Cancel To Cancel The Process.");
		if(confirmx==true)
		window.location.href=host+"save.php?task=updateindgoalx"+value;	
	}
}
function image_roll(img_swap, img_name)
{
	img_name.src = img_swap
}