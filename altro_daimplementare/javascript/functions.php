<script>
    $("document").ready(function()
    {
        //needed because jquery mobile will always try to make ajax calls from links
        //$("a").attr("data-ajax", "false");
        /************************************************************************
         *                                                                      *
         *                        Desktop Version Scripts                       *
         *                                                                      * 
         ************************************************************************/
        
        if ($('#anno').length) {
            document.getElementById('mese').disabled = true;
            document.getElementById('giorno').disabled = true;
        }
        if ($('#annoDT').length) {
            document.getElementById('meseDT').disabled = true;
            document.getElementById('giornoDT').disabled = true;
        }
        if ($('#annoCO').length) {
            document.getElementById('meseCO').disabled = true;
            document.getElementById('giornoCO').disabled = true;
        }
        if ($('#annoNA').length) {
            document.getElementById('meseNA').disabled = true;
            document.getElementById('giornoNA').disabled = true;
        }
        
        function disableTextFilter(bool)
        {
            if ($('#anno').length) {
                document.getElementById('mese').disabled = bool;
                document.getElementById('giorno').disabled = bool;
            }
            if ($('#annoDT').length) {
                document.getElementById('meseDT').disabled = bool;
                document.getElementById('giornoDT').disabled = bool;
            }
            if ($('#annoCO').length) {
                document.getElementById('meseCO').disabled = bool;
                document.getElementById('giornoCO').disabled = bool;
            }
            if ($('#annoNA').length) {
                document.getElementById('meseNA').disabled = bool;
                document.getElementById('giornoNA').disabled = bool;
            }
        }
        
        $("#loading").hide();
        $(".lingua").click(function(event)
        {
			var strlang=event.target.id;
			strlang=strlang.substring(0,2);
            $.get("/features/ajax_pages/setlang.php", "lang="+strlang, function(data)
            {
                window.location.reload();
            });                    
        });
        
		// Bind enter events on search forms
		$("div.searchform input").keydown(function(e){
				if(e.keyCode==13) if(visualizzaGrafici) aggiornaGrafico(); else applyfilter();
		});
		$("div.searchformDT input").keydown(function(e){
				if(e.keyCode==13) if(visualizzaGrafici) aggiornaGrafico(); else applyfilterDT();
		});
		$("div.searchformCO input").keydown(function(e){
				if(e.keyCode==13) applyfilterCO();
		});
		$("div.searchformNA input" && visualizzaGrafici).keydown(function(e){
				if(e.keyCode==13) if(visualizzaGrafici) aggiornaGrafico(); else applyfilterNA();
		});
        
        $("#crossreferenceTXT").keydown(function(e){
				if(e.keyCode==13) crossreferenceTXT();
		});
        
        $("#brandingTXT").keydown(function(e){
				if(e.keyCode==13) brandingTXT();
		});
		
		// --
		
		$("#applyfilter").click(function(){applyfilter();});
		
		function applyfilter(){
           var anno = $("#anno").val();
            $("#anno").val = "";
            var mese = $("#mese").val();
            $("#mese").val = "";
            var giorno = $("#giorno").val();
            $("#giorno").val = "";
            if (anno == "" && mese == "" && giorno == "")
            {
                $("#loading").show();
                $("#tabellafatture").html("");
                document.getElementById('applyfilter').disabled = true;
                document.getElementById('anno').disabled = true;
                document.getElementById('mese').disabled = true;
                document.getElementById('giorno').disabled = true;
                $.post("/features/ajax_pages/getfatture.php", "", function(data, status){
                    $("#loading").hide();
                    $("#tabellafatture").html(data);
                    document.getElementById('applyfilter').disabled = false;
                    document.getElementById('anno').disabled = false;
                    document.getElementById('mese').disabled = false;
                    document.getElementById('giorno').disabled = false;
                });
            }
            else
            {
                if (anno != "" && mese == "" && giorno == "")
                {
                    $("#loading").show();
                    $("#tabellafatture").html("");
                    document.getElementById('applyfilter').disabled = true;
                    document.getElementById('anno').disabled = true;
                    document.getElementById('mese').disabled = true;
                    document.getElementById('giorno').disabled = true;
                    $.post("/features/ajax_pages/getfatture.php", "anno="+anno, function(data, status){
                        $("#loading").hide();
                        $("#tabellafatture").html(data);
                        document.getElementById('applyfilter').disabled = false;
                        document.getElementById('anno').disabled = false;
                        document.getElementById('mese').disabled = false;
                        document.getElementById('giorno').disabled = false;
                    });
                }
                else
                {
                    if (anno != "" && mese != "" && giorno == "")
                    {
                        $("#loading").show();
                        $("#tabellafatture").html("");
                        document.getElementById('applyfilter').disabled = true;
                        document.getElementById('anno').disabled = true;
                        document.getElementById('mese').disabled = true;
                        document.getElementById('giorno').disabled = true;
                        $.post("/features/ajax_pages/getfatture.php", "anno="+anno+"&mese="+mese, function(data, status){
                            $("#loading").hide();
                            $("#tabellafatture").html(data);
                            document.getElementById('applyfilter').disabled = false;
                            document.getElementById('anno').disabled = false;
                            document.getElementById('mese').disabled = false;
                            document.getElementById('giorno').disabled = false;
                        });
                    }
                    else
                    {
                        if (anno != "" && mese != "" && giorno != "")
                        {
                            $("#loading").show();
                            $("#tabellafatture").html("");
                            document.getElementById('applyfilter').disabled = true;
                            document.getElementById('anno').disabled = true;
                            document.getElementById('mese').disabled = true;
                            document.getElementById('giorno').disabled = true;
                            $.post("/features/ajax_pages/getfatture.php", "anno="+anno+"&mese="+mese+"&giorno="+giorno, function(data, status){
                                $("#loading").hide();
                                $("#tabellafatture").html(data);
                                document.getElementById('applyfilter').disabled = false;
                                document.getElementById('anno').disabled = false;
                                document.getElementById('mese').disabled = false;
                                document.getElementById('giorno').disabled = false;
                            });
                        }
                    }
                }
            }
		}
        
        $("#applyfilterDT").click(function(){applyfilterDT();});
		function applyfilterDT(){
            var anno = $("#annoDT").val();
            $("#annoDT").val = "";
            var mese = $("#meseDT").val();
            $("#meseDT").val = "";
            var giorno = $("#giornoDT").val();
            $("#giornoDT").val = "";
            if (anno == "" && mese == "" && giorno == "")
            {
                document.getElementById('applyfilterDT').disabled = true;
                document.getElementById('annoDT').disabled = true;
                document.getElementById('meseDT').disabled = true;
                document.getElementById('giornoDT').disabled = true;
                $("#loading").show();
                $("#tabelladocumentotrasporto").html("");
                $.post("/features/ajax_pages/getdt.php", "", function(data, status){
                    $("#loading").hide();
                    $("#tabelladocumentotrasporto").html(data);
                    document.getElementById('applyfilterDT').disabled = false;
                    document.getElementById('annoDT').disabled = false;
                    document.getElementById('meseDT').disabled = false;
                    document.getElementById('giornoDT').disabled = false;
                });
            }
            else
            {
                if (anno != "" && mese == "" && giorno == "")
                {
                    $("#loading").show();
                    $("#tabelladocumentotrasporto").html("");
                    document.getElementById('applyfilterDT').disabled = true;
                    document.getElementById('annoDT').disabled = true;
                    document.getElementById('meseDT').disabled = true;
                    document.getElementById('giornoDT').disabled = true;
                    $.post("/features/ajax_pages/getdt.php", "anno="+anno, function(data, status){
                        $("#loading").hide();
                        $("#tabelladocumentotrasporto").html(data);
                        document.getElementById('applyfilterDT').disabled = false;
                        document.getElementById('annoDT').disabled = false;
                        document.getElementById('meseDT').disabled = false;
                        document.getElementById('giornoDT').disabled = false;
                    });
                }
                else
                {
                    if (anno != "" && mese != "" && giorno == "")
                    {
                        $("#loading").show();
                        $("#tabelladocumentotrasporto").html("");
                        document.getElementById('applyfilterDT').disabled = true;
                        document.getElementById('annoDT').disabled = true;
                        document.getElementById('meseDT').disabled = true;
                        document.getElementById('giornoDT').disabled = true;
                        $.post("/features/ajax_pages/getdt.php", "anno="+anno+"&mese="+mese, function(data, status){
                            $("#loading").hide();
                            $("#tabelladocumentotrasporto").html(data);
                            document.getElementById('applyfilterDT').disabled = false;
                            document.getElementById('annoDT').disabled = false;
                            document.getElementById('meseDT').disabled = false;
                            document.getElementById('giornoDT').disabled = false;
                        });
                    }
                    else
                    {
                        if (anno != "" && mese != "" && giorno != "")
                        {
                            $("#loading").show();
                            $("#tabelladocumentotrasporto").html("");
                            document.getElementById('applyfilterDT').disabled = true;
                            document.getElementById('annoDT').disabled = true;
                            document.getElementById('meseDT').disabled = true;
                            document.getElementById('giornoDT').disabled = true;
                            $.post("/features/ajax_pages/getdt.php", "anno="+anno+"&mese="+mese+"&giorno="+giorno, function(data, status){
                                $("#loading").hide();
                                $("#tabelladocumentotrasporto").html(data);
                                document.getElementById('applyfilterDT').disabled = false;
                                document.getElementById('annoDT').disabled = false;
                                document.getElementById('meseDT').disabled = false;
                                document.getElementById('giornoDT').disabled = false;
                            });
                        }
                    }
                }
            }
		}
        
        $("#applyfilterNA").click(function(){applyfilterNA();});
		function applyfilterNA(){
            var anno = $("#annoNA").val();
            $("#annoNA").val = "";
            var mese = $("#meseNA").val();
            $("#meseNA").val = "";
            var giorno = $("#giornoNA").val();
            $("#giornoNA").val = "";
            if (anno == "" && mese == "" && giorno == "")
            {
                document.getElementById('applyfilterNA').disabled = true;
                document.getElementById('annoNA').disabled = true;
                document.getElementById('meseNA').disabled = true;
                document.getElementById('giornoNA').disabled = true;
                $("#loading").show();
                $("#tabellanoteaccredito").html("");
                $.post("/features/ajax_pages/getna.php", "", function(data, status){
                    $("#loading").hide();
                    $("#tabellanoteaccredito").html(data);
                    document.getElementById('applyfilterNA').disabled = false;
                    document.getElementById('annoNA').disabled = false;
                    document.getElementById('meseNA').disabled = false;
                    document.getElementById('giornoNA').disabled = false;
                });
            }
            else
            {
                if (anno != "" && mese == "" && giorno == "")
                {
                    $("#loading").show();
                    $("#tabellanoteaccredito").html("");
                    document.getElementById('applyfilterNA').disabled = true;
                    document.getElementById('annoNA').disabled = true;
                    document.getElementById('meseNA').disabled = true;
                    document.getElementById('giornoNA').disabled = true;
                    $.post("/features/ajax_pages/getna.php", "anno="+anno, function(data, status){
                        $("#loading").hide();
                        $("#tabellanoteaccredito").html(data);
                        document.getElementById('applyfilterNA').disabled = false;
                        document.getElementById('annoNA').disabled = false;
                        document.getElementById('meseNA').disabled = false;
                        document.getElementById('giornoNA').disabled = false;
                    });
                }
                else
                {
                    if (anno != "" && mese != "" && giorno == "")
                    {
                        $("#loading").show();
                        $("#tabellanoteaccredito").html("");
                        document.getElementById('applyfilterNA').disabled = true;
                        document.getElementById('annoNA').disabled = true;
                        document.getElementById('meseNA').disabled = true;
                        document.getElementById('giornoNA').disabled = true;
                        $.post("/features/ajax_pages/getna.php", "anno="+anno+"&mese="+mese, function(data, status){
                            $("#loading").hide();
                            $("#tabellanoteaccredito").html(data);
                            document.getElementById('applyfilterNA').disabled = false;
                            document.getElementById('annoNA').disabled = false;
                            document.getElementById('meseNA').disabled = false;
                            document.getElementById('giornoNA').disabled = false;
                        });
                    }
                    else
                    {
                        if (anno != "" && mese != "" && giorno != "")
                        {
                            $("#loading").show();
                            $("#tabellanoteaccredito").html("");
                            document.getElementById('applyfilterNA').disabled = true;
                            document.getElementById('annoNA').disabled = true;
                            document.getElementById('meseNA').disabled = true;
                            document.getElementById('giornoNA').disabled = true;
                            $.post("/features/ajax_pages/getna.php", "anno="+anno+"&mese="+mese+"&giorno="+giorno, function(data, status){
                                $("#loading").hide();
                                $("#tabellanoteaccredito").html(data);
                                document.getElementById('applyfilterNA').disabled = false;
                                document.getElementById('annoNA').disabled = false;
                                document.getElementById('meseNA').disabled = false;
                                document.getElementById('giornoNA').disabled = false;
                            });
                        }
                    }
                }
            }
		}
        
        $("#applyfilterCO").click(function(){applyfilterCO();});
		
		function applyfilterCO(){
            var anno = $("#annoCO").val();
            $("#annoCO").val = "";
            var mese = $("#meseCO").val();
            $("#meseCO").val = "";
            var giorno = $("#giornoCO").val();
            $("#giornoCO").val = "";
            if (anno == "" && mese == "" && giorno == "")
            {
                $("#loading").show();
                $("#tabellaconford").html("");
                document.getElementById('applyfilterCO').disabled = true;
                document.getElementById('annoCO').disabled = true;
                document.getElementById('meseCO').disabled = true;
                document.getElementById('giornoCO').disabled = true;
                $.post("/features/ajax_pages/getco.php", "", function(data, status){
                    $("#loading").hide();
                    $("#tabellaconford").html(data);
                    document.getElementById('applyfilterCO').disabled = false;
                    document.getElementById('annoCO').disabled = false;
                    document.getElementById('meseCO').disabled = false;
                    document.getElementById('giornoCO').disabled = false;
                });
            }
            else
            {
                if (anno != "" && mese == "" && giorno == "")
                {
                    $("#loading").show();
                    $("#tabellaconford").html("");
                    document.getElementById('applyfilterCO').disabled = true;
                    document.getElementById('annoCO').disabled = true;
                    document.getElementById('meseCO').disabled = true;
                    document.getElementById('giornoCO').disabled = true;
                    $.post("/features/ajax_pages/getco.php", "anno="+anno, function(data, status){
                        $("#loading").hide();
                        $("#tabellaconford").html(data);
                        document.getElementById('applyfilterCO').disabled = false;
                        document.getElementById('annoCO').disabled = false;
                        document.getElementById('meseCO').disabled = false;
                        document.getElementById('giornoCO').disabled = false;
                    });
                }
                else
                {
                    if (anno != "" && mese != "" && giorno == "")
                    {
                        $("#loading").show();
                        $("#tabellaconford").html("");
                        document.getElementById('applyfilterCO').disabled = true;
                        document.getElementById('annoCO').disabled = true;
                        document.getElementById('meseCO').disabled = true;
                        document.getElementById('giornoCO').disabled = true;
                        $.post("/features/ajax_pages/getco.php", "anno="+anno+"&mese="+mese, function(data, status){
                            $("#loading").hide();
                            $("#tabellaconford").html(data);
                            document.getElementById('applyfilterCO').disabled = false;
                            document.getElementById('annoCO').disabled = false;
                            document.getElementById('meseCO').disabled = false;
                            document.getElementById('giornoCO').disabled = false;
                        });
                    }
                    else
                    {
                        if (anno != "" && mese != "" && giorno != "")
                        {
                            $("#loading").show();
                            $("#tabellaconford").html("");
                            document.getElementById('applyfilterCO').disabled = true;
                            document.getElementById('annoCO').disabled = true;
                            document.getElementById('meseCO').disabled = true;
                            document.getElementById('giornoCO').disabled = true;
                            $.post("/features/ajax_pages/getco.php", "anno="+anno+"&mese="+mese+"&giorno="+giorno, function(data, status){
                                $("#loading").hide();
                                $("#tabellaconford").html(data);
                                document.getElementById('applyfilterCO').disabled = false;
                                document.getElementById('annoCO').disabled = false;
                                document.getElementById('meseCO').disabled = false;
                                document.getElementById('giornoCO').disabled = false;
                            });
                        }
                    }
                }
            }
		}
        
        $("#uhpcertTXT").keyup(function()
        {
            $("#loading").show();
            $("#tabellacertuhppvt").html("");
            var text = $("#uhpcertTXT").val();
            $.post("/features/ajax_pages/getcert.php", "text="+text, function(data, status)
            {
                $("#loading").hide();
                $("#tabellacertuhppvt").html(data);
            });
        });
        
        $("#crossreferenceBTN").click(function(){crossreferenceTXT();});
        function crossreferenceTXT()
        {
            $("#loading").show();
            $("#tabellatrascodifiche").html("");
            document.getElementById('crossreferenceBTN').disabled = true;
            document.getElementById('crossreferenceTXT').disabled = true;
            var text = $("#crossreferenceTXT").val();
            $.post("/features/ajax_pages/getcrossref.php", "text="+text, function(data, status)
            {
                $("#loading").hide();
                $("#tabellatrascodifiche").html(data);
                document.getElementById('crossreferenceBTN').disabled = false;
                document.getElementById('crossreferenceTXT').disabled = false;
            });
        }
        
        $("#brandingBTN").click(function(e){brandingTXT();});
        function brandingTXT()
        {
            $("#loading").show();
            $("#tabellamarcature").html("");
            document.getElementById('brandingBTN').disabled = true;
            document.getElementById('brandingTXT').disabled = true;
            var text = $("#brandingTXT").val();
            $.post("/features/ajax_pages/getbrand.php", "text="+text, function(data, status)
            {
                $("#loading").hide();
                $("#tabellamarcature").html(data);
                document.getElementById('brandingBTN').disabled = false;
                document.getElementById('brandingTXT').disabled = false;
            });
        }
        
        
        $("#resetBTN").click(function()
        {
            document.getElementById("CDPAR").value = "";
            document.getElementById("CDORD").value = "";
            document.getElementById("NRSERIAL").value = "";
        });
        
        $("#searchCertPubBTN").click(function()
        {
            var cdpar = $("#CDPAR").val();
            var cdord = $("#CDORD").val();
            var nrserial = $("#NRSERIAL").val();
            if (cdpar == "" || cdord == "" || nrserial == "")
            {
                alert("<?php echo $localizzazione['cert_pub_err_txt'][$_SESSION['lang']] ?>");
            }
            else
            {
                $("#loading").show();
                $("#tabellacertuhppub").html("");
                $.post("/features/ajax_pages/getcertpub.php", "cdpar="+cdpar+"&cdord="+cdord+"&nrserial="+nrserial, function(data, status){
                    $("#loading").hide();
                    $("#tabellacertuhppub").html(data);
                });
            }
        });
        
        $('.contenitoregrafico').hide();
        $("#aggiornagraficoBTN").hide();
        $('.contenitoregrafico').highcharts({
            chart: {
                plotBackgroundColor: "#2C2F37",
                backgroundColor: "#DFDFDF",
                fontColor: '#FF6600',
                marginBottom: 10,
                width: 700,
                height: 400,
                type: 'pie',
                options3d: {
                    enabled: true,
                    alpha: 45,
                    beta: 0
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    depth: 35,
                    dataLabels: {
                        enabled: true,
                    }
                }
            },
            xAxis: {
                type: 'datetime'
            },
            credits: {
                enabled: false
            },
            title : {
                text : window.location.pathname.slice(1)
            },
            series: [{
               type: "pie",
               data: [29.9, 71.5, 106.4],
               name : 'Importo'
            }]
        });
        var visualizzaGrafici = false;
        
        $(".annitxt").keydown(function(event)
        {
            var testo = $(".annitxt").val();
            if (testo.length < 4)
            {
                // Allow only backspace and delete and tab and refresh
                if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 116 )
                {
                    // let it happen, don't do anything
                    if ((event.keyCode == 46 || event.keyCode == 8) && testo.length == 1)
                    {
                        disableTextFilter(true);
                    }
                }
                else
                {
                    // Ensure that it is a number and stop the keypress
                    if (!(event.keyCode >= 48 && event.keyCode <= 57) && !(event.keyCode >= 96 && event.keyCode <= 105))
                    {
                        event.preventDefault();	
                    }
                    else
                    {
                        disableTextFilter(false);
                    }
                }
            }
            else
            {
                if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 116 )
                {
                    // let it happen, don't do anything
                }
                else
                return false;
            }
        });
        
        $(".linktoggle").click(function()
        {
            var anno = $(".annitxt").val();
            var mese = $(".mesitxt").val();
            var chart = $('.contenitoregrafico').highcharts();
            if (anno == "")
            {
                alert("<?php echo $localizzazione["grafico_error_no_data"][$_SESSION['lang']]; ?>");
                visualizzaGrafici = false;
                return false;
            }
            if (!visualizzaGrafici)
            {
                $(".tabelladiv").hide();
                $(".contenitoregrafico").show();
                visualizzaGrafici = true;
                $.get("/features/ajax_pages/getgrafico.php", "type="+window.location.pathname.slice(1)+"&anno="+anno+"&mese="+mese, function(dato, success)
                {
                    chart.series[0].setData(eval(dato));
                });
                $(".linktoggle").html("<?php echo $localizzazione["visualizza_tabelle"][$_SESSION['lang']]; ?>'");
                $("#aggiornagraficoBTN").show();
            }
            else
            {
                $(".tabelladiv").show();
                $(".contenitoregrafico").hide();
                visualizzaGrafici = false;
                $(".linktoggle").html("<?php echo $localizzazione["visualizza_grafici"][$_SESSION['lang']]; ?>");
                $("#aggiornagraficoBTN").hide();
            }
            
        });
        
        $("#aggiornagraficoBTN").click(function(){aggiornaGrafico();});        
        function aggiornaGrafico()
        {
            var anno = $(".annitxt").val();
            var mese = $(".mesitxt").val();
            var chart = $('.contenitoregrafico').highcharts();
            if (anno == "")
            {
                alert("<?php echo $localizzazione["grafico_error_no_data"][$_SESSION['lang']]; ?>");
                return false;
            }
            $.get("/features/ajax_pages/getgrafico.php", "type="+window.location.pathname.slice(1)+"&anno="+anno+"&mese="+mese, function(dato, success)
            {
                chart.series[0].setData(eval(dato));
            });
        }
        
        /************************************************************************
         *                                                                      *
         *                      Responsive Layout Scripts                       *
         *                                                                      * 
         ************************************************************************/
        //Partiamo con un dispositivo mobile o desktop?
        var isInMobileMode = false;
        if ($(window).width() < 1600)
            isInMobileMode = true;
            
        //variabile booleana necessaria per capire se il menu deve essere visibile o meno
        var clicked = false;
        $(".btn-responsive-menu").click(function()
        {
            $("#mainmenu").toggleClass("show");
            if (clicked)
            {
                //Nasconde Menu
                $(".left-sidebar").removeClass("hidden");
                $(".content").animate({marginLeft: "-=214px"},1000);
                $(".left-sidebar").animate({marginLeft: "-=214"},1000);
                clicked = false;
            }
            else
            {
                //Mostra menu
                $(".left-sidebar").addClass("hidden");
                $(".content").animate({marginLeft: "+=214px"},1000);
                $(".left-sidebar").css("display", "inline-block");
                $(".left-sidebar").animate({marginLeft: "+=214"},1000);
                clicked = true;
            }
        });
        
        //Questa funzione è come un listener. Se sei su un browser o da mobile guardi il sito con il cellulare girato, cambia in modo dinamico la visualizzazione
        $(window).resize(function() {
            if($(window).width() >= 1600)
            {
                $(".content").css("margin-left", "214px");
                $(".left-sidebar").css("margin-left", "0px");
                $(".left-sidebar").css("display", "block");
                isInMobileMode = false;
            }
            else
            {
                if (!clicked)
                {
                    $(".content").css("margin-left", "0px");
                    $(".left-sidebar").css("margin-left", "-214px");
                    $(".left-sidebar").css("display", "none");
                }
                isInMobileMode = true;
            }
        });
        
    });
    
    /************************************************************************
    *                                                                       *
    *                           Login Validation Form                       *
    *                                                                       * 
    *************************************************************************/
    
	function validateform()
	{
		var uname=$.trim($("#user_login").val());
		var upass=$.trim($("#user_pass").val());
		if(uname==""){
			alert("<?php echo $localizzazione['user_obb'][$_SESSION['lang']]; ?>");
			$("#user_login").focus();
			return false;
		}
		if(upass==""){
			alert("<?php echo $localizzazione['pass_obb'][$_SESSION['lang']]; ?>");
			$("#user_pass").focus();
			return false;
		}
	}
</script>