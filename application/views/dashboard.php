<?php include("common/template-top.php");
?>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/flot/jquery.min.js"></script>

<script type="text/javascript">
function auto_load(){	
        $.ajax({
          url: "<?php echo base_url(); ?>user/gettotalhits",
          cache: false,
          success: function(data){
		var output = jQuery.parseJSON(data); 
		$("#totalruser").html(output.user);
		$("#activecampaign").html(output.activecamp);
		$("#brandproduct").html(output.brandproduct);
		$("#finalclick").html(output.totalclick);
          } 
        });
      }
			
$(document).ready(function(){ 
	auto_load(); //Call auto_load() function when DOM is Ready 
});
//Refresh auto_load() function after 10000 milliseconds
setInterval(auto_load,10000);
</script>

<!-- page content -->
<div class="right_col" role="main">
  <div class="">
	<div class="row top_tiles">
		<?php if($this->session->userdata('role_id')==2) { ?>
	  <div class="col-md-3 col-sm-3 col-xs-6 tile">
		<span>Total Registered User</span>
		<h2><div id="totalruser"></div></h2>		
	  </div>
		<?php } ?>
	  <div class="col-md-3 col-sm-3 col-xs-6 tile">
		<span>Total Campaigns</span>
		<h2><div id="activecampaign"></div></h2>		
	  </div>
	  <div class="col-md-3 col-sm-3 col-xs-6 tile">
		<span>Total Brand Product</span>
		<h2><div id="brandproduct"></div></h2>		
	  </div>
	  <div class="col-md-3 col-sm-3 col-xs-6 tile">
		<span>Total Hits</span>
		<h2><div id="finalclick"></div></h2>		
	  </div>
	</div>
	<br />
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="dashboard_graph x_panel">
		  <div class="row x_title">
			<div class="col-md-6">
			  <h3>Campaign Graph </h3>
			</div>
			<div class="col-md-6 text-right">
				<?php if(count($result)>0){ ?>
				<i class="btn fsubmit"></i> 
				<?php } ?>
			</div>
		  </div>
	<?php if(count($result)>0)
					{ ?>	  
		  <div class="x_content">
			<div class="row">
				<div class="col-md-6 col-sm-6 col-xs-12">
				
			 
				<table id="datatable" class="table table-striped table-bordered bulk_action">
				  <thead>
					<tr>
					  <th colspan="6">Top Five Campaigns</th>				
					</tr>
					<tr>
					  <th>S. No</th>					 
					  <th>Campaign Code</th>
					  <th>Campaign Name</th>					  
					  <th>Hits Count</th>	
					<th>Last Clicked</th>
					
					</tr>
				  </thead>
				  <tbody>
					<?php				
					if(count($result)>0)
					{
						$i = 1;	
						foreach($result as $row)
						{								
					?>
					<tr>
					  <td><?php echo $i;?></td>					  
					  <td><a href="javascript:void(0)" class="wid <?php echo $i; ?>" id="<?php echo $row->widget_id;?>"><?php echo $row->promo_code; ?></a><br><span><?php  echo $row->domain_name;?></span></td>
					  <td><?php  echo $row->widget_name;?></td>					   
					  <td align="right"><?php  echo $row->TotalClick;?></td>					
						<td align="right"><?php echo date("M d,Y", strtotime($row->LastclickTime));?></td>
						</tr>
					 <?php $i++; }}	else { ?>
						<tr> 
							<td colspan="8"> No Record Available.</td>
						</tr>
					 <?php
					} ?>
				  </tbody>
				</table>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div id="canvas_dahs" style="width: 100%; height:415px;" ></div>
					<div id="graphrange" style="width: 100%;" class="text-center" ></div>
				</div>
				
			</div>
		  </div>
		  <?php }else{ ?>
			<div class="col-md-12 col-sm-12 col-xs-12 text-center">
				<div style="width: 100%; height:415px;">
					<h2>The graph can only be generated, when you have data older than 15 Days</h2>
				</div>
			</div>
		  <?php } ?>
		</div>
	  </div>
	</div>
	<?php 
	if(count($result)>0)
					{?>
	<div class="row">
	    <div class="col-md-8 col-sm-8 col-xs-12 ">
			<div class="x_panel"  style="min-height: 235px">
				<div class="x_title">
				  <h2>Campaign Monthly Hits</h2>
				  <div class="clearfix"></div>
				</div>
				<div class="x_content">
				 <div class="row weather-days" id="mr">
				<div class="clearfix"></div>
				  </div>
				</div>
			</div>
	    </div>
	    <div class="col-md-4 col-sm-4 col-xs-12">
			<div class="x_panel tile  overflow_hidden" style="min-height: 235px">
				<div class="x_title">
					<h2>Device Hits</h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<table class="" style="width:100%">
				   
						<tr>
							<td>
								<canvas id="canvas_pie" height="120" width="120" style="margin: 15px 10px 10px 0"></canvas>
							</td>
							<td>
								<table class="tile_info" >
									 <tr>
									   <td>
									 <p class="h6"><i class="fa fa-square blue"></i>Mb. </p>
									   </td>
									   <td id="mbc" style="font-size: 12px;"></td>
									 </tr>
									 <tr>
									   <td>
									 <p class="h6"><i class="fa fa-square green"></i>Dkt. </p>
									   </td>
									   <td id="dtc" style="font-size: 12px;"></td>
									 </tr>
									
								 </table>
							</td>
						</tr>
					</table>
				</div>
			</div>
	    </div>
	</div>	
	<?php } ?> 
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel"  style="min-height: 235px">
				<div class="x_title">
					<div class="col-md-6">
						<h2>User Activity Trend</h2>
					</div>
					<div class="col-md-6 text-right">
						<i class="btn fsubmit uat"></i>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<div class="col-md-6 col-sm-6 col-xs-12">
						<h5 class="text-center">Current Quarter</h5>
						<div id="activitygraphcur" style="width:100%;height:300px"></div>
					</div> 
					<div class="col-md-6 col-sm-6 col-xs-12">
						<h5 class="text-center">Previous Quarter</h5>
						<div id="activitygraphprev" style="width:100%;height:300px"></div>
					</div>
				</div>
			</div>
		</div>
	</div>	
  </div>
</div>
<!-- /page content -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/flot/jquery.flot.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/flot/jquery.flot.pie.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/flot/jquery.flot.time.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/flot/jquery.flot.stack.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/flot/jquery.flot.resize.js"></script>
<!-- Flot plugins -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/flot/jquery.flot.orderBars.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/flot/jquery.flot.spline.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/flot/curvedLines.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/Chart.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	jQuery.noConflict();
		var options = {
			legend: true,
			responsive: false
		      };
		      
		var firstId = $(".1").attr("id");
		$(".wid").click(function () {
			firstId = this.id;
			//alert(firstId)
			generateGraph();
		});
	function showTooltip(x, y, contents) {
            $('<div id="tooltip">' + contents + '</div>').css({
                position: 'absolute',
                display: 'none',
                top: y-320,
		left: x-890,
                border: '1px solid #E84B42',
                padding: '2px',
                'background-color': '#E84B42',
		'color':'#FFFFFF',
                opacity: 1.00
            }).appendTo("#canvas_dahs").fadeIn(200);
        }
		function generateGraph(){
			$(".fsubmit").show();
			var data1 = [], data2=[],graphrange='';
			$.getJSON(_BASEPATH_+"user/campaignGraph?id="+firstId, function (data) {
				//alert(data);
				//alert(data.graphresult.length); return false;
				for (var i = 0; i < data.graphresult.length; i++) {
					
					if(data.graphresult[i].DektopClick >=1){
						data1.push ([gd(data.graphresult[i].y, data.graphresult[i].m, data.graphresult[i].d), data.graphresult[i].DektopClick]);
					}
					if(data.graphresult[i].MobileClick >=1){
						data2.push ([gd(data.graphresult[i].y, data.graphresult[i].m, data.graphresult[i].d), data.graphresult[i].MobileClick]);
					}
				
				}
				 $("#canvas_dahs").length && $.plot($("#canvas_dahs"), [
				{ data: data1, label: " Dkt."},
				{ data: data2, label: " Mb."}
				], {
				  series: {
					lines: {
					  show: false,
					  fill: true
					},
					splines: {
					  show: true,
					  tension: 0.4,
					  lineWidth: 1,
					  fill: 0.4
					},
					points: {
					  radius: 1,
					  show: true
					},
					shadowSize: 2
				  },
				  grid: {
					verticalLines: true,
					hoverable: true,
					clickable: true,
					tickColor: "#d5d5d5",
					borderWidth: 1,
				      //color: '#fff',
				  },
				colors: ["#1ABB9C", "#3498DB"],
				//colors: ["rgba(38, 185, 154, 0.38)", "rgba(3, 88, 106, 0.38)"],
				  xaxis: {
					tickColor: "rgba(51, 51, 51, 0.06)",
					mode: "time",
					tickSize: [15, "day"],
					//tickLength: 10,
					axisLabel: "Date",
					axisLabelUseCanvas: true,
					axisLabelFontSizePixels: 12,
					axisLabelFontFamily: 'Verdana, Arial',
					axisLabelPadding: 10
				  },
				  yaxis: {
					ticks: 8,
					tickColor: "rgba(51, 51, 51, 0.06)",
				  },
				  
			});

			var previousPoint = null;
			$("#canvas_dahs").on("plothover", function(event, pos, item) {
			
				$("#x").text(pos.x.toFixed(2));
				$("#y").text(pos.y.toFixed(2));
				if (item) {
					if (previousPoint != item.dataIndex) {
					
						previousPoint = item.dataIndex;
			
						$("#tooltip").remove();
						var x = item.datapoint[0]-1,
							y = item.datapoint[1];
					   
						//showTooltip(item.pageX, item.pageY,'hello');
						if(y>0)
						{
							var tooltip_label = item.series.label + " Hits : " + y;
							showTooltip(item.pageX, item.pageY,tooltip_label);
						}
						
					}
				}
				else {
					$("#tooltip").remove();
					previousPoint = null;
				}
	
			});
		
				var monthReport= '';
				var mobileClick = 0;
				var desktopClick = 0;
				var TotalClick = 0;
				var Totaldays = [];
				var monthDeviceReport = '';
				//alert(data)
				for (var i = 0; i < data.monthlyresult.length; i++) {
					
					monthReport+='<div class="col-sm-4"><div class="daily-weather"><h2 class="day">'+data.monthlyresult[i].TotalClick+'</h2><h3 class="degrees">'+data.monthlyresult[i].months+'</h3></div></div>';
					desktopClick+=parseInt(data.monthlyresult[i].DektopClick);
					mobileClick+=parseInt(data.monthlyresult[i].MobileClick);
					TotalClick+=parseInt(TotalClick) + (desktopClick+mobileClick);
				}
					graphrange+='<div ><div><b>Hits Duration:&nbsp;</b>'+data.graphresult[0].startdate+'-to-'+data.graphresult[0].enddate+'</div></div>';
				$('#graphrange').html(graphrange);
				$('#mr').html(monthReport);
				$('#mbc').html(mobileClick);
				$('#dtc').html(desktopClick);
				$('#canvas_pie').parent().find("iframe").remove();
				new Chart(document.getElementById("canvas_pie"), {
					type: 'doughnut',
					tooltipFillColor: "rgba(51, 51, 51, 0.55)",
					data: {
					  labels: [              
					    "Mobile",
					    "Desktop"
					  ],
					  datasets: [{
					    data: [mobileClick, desktopClick],
					    backgroundColor: [                
					      "#3498DB",
					      "#26B99A"
					    ]
					  }]
					},
					options: options
				});
				$(".fsubmit").hide();
				$(".uat").show();
				
			});
			
        }
		generateGraph();
        function gd(year, month, day) {
          return new Date(year, month - 1, day).getTime();
        }
    });

</script>
<script type="text/javascript">
$(document).ready(function() {
	jQuery.noConflict();
	var data = [], currQtStdt = [], preQtdt = [], datap = [];
	 $.ajax({
          url: _BASEPATH_+"user/getQuarterlyHits",
          cache: false,
          async: false,
          success: function(result){
          
		  var output = jQuery.parseJSON(result);

		  currQtStdt.push ([output.currQuarterStartDt]);
          data.push ([0, output.currQuarter.slot1]);
          data.push ([1, output.currQuarter.slot2]);
          data.push ([2, output.currQuarter.slot3]);
          data.push ([3, output.currQuarter.slot4]);
          data.push ([4, output.currQuarter.slot5]);
          data.push ([5, output.currQuarter.slot6]);

          preQtdt.push ([output.preQuarterStartDt]);
          preQtdt.push ([output.preQuarterEndDt]);
		  datap.push ([0, output.preQuarter.slot1]);
          datap.push ([1, output.preQuarter.slot2]);
          datap.push ([2, output.preQuarter.slot3]);
          datap.push ([3, output.preQuarter.slot4]);
          datap.push ([4, output.preQuarter.slot5]);
          datap.push ([5, output.preQuarter.slot6]);
          
      	}

	}); 



	var dataset = [{ label: currQtStdt + " - <?php echo date('d M Y'); ?>", data: data, color: "#48A2DF" }];
	var ticks = [[0, "00:00-03:59"], [1, "04:00-07:59"], [2, "08:00-11:59"], [3, "12:00-15:59"],[4, "16:00-19:59"], [5, "20:00-23:59"]];

	var options = {
		series: {
			bars: {
				show: true
			}
		},
		bars: {
			align: "center",
			barWidth: 0.5
		},
		xaxis: {
			axisLabel: "World Cities",
			axisLabelUseCanvas: true,
			axisLabelFontSizePixels: 12,
			axisLabelFontFamily: 'Verdana, Arial',
			axisLabelPadding: 10,
			ticks: ticks
		},
		yaxis: {
			axisLabel: "Average Temperature",
			axisLabelUseCanvas: true,
			axisLabelFontSizePixels: 12,
			axisLabelFontFamily: 'Verdana, Arial',
			axisLabelPadding: 3,
			tickFormatter: function (v, axis) {
				return v ;
			}
		},
		legend: {
			noColumns: 0,
			labelBoxBorderColor: "#E6E9ED",
			position: "nw"
		},
		grid: {
			hoverable: true,
			borderWidth: 2,
			backgroundColor: { colors: ["#ffffff", "#EDF5FF"] }
		}
	};


	//creating previous quarter activity chart
	

	var datasetp = [{ label: preQtdt[0] + ' - '+ preQtdt[1], data: datap, color: "#48A2DF" }];
	var ticksp = [[0, "00:00-03:59"], [1, "04:00-07:59"], [2, "08:00-11:59"], [3, "12:00-15:59"],[4, "16:00-19:59"], [5, "20:00-23:59"]];

	var optionsp = {
		series: {
			bars: {
				show: true
			}
		},
		bars: {
			align: "center",
			barWidth: 0.5
		},
		xaxis: {
			axisLabel: "World Cities",
			axisLabelUseCanvas: true,
			axisLabelFontSizePixels: 12,
			axisLabelFontFamily: 'Verdana, Arial',
			axisLabelPadding: 10,
			ticks: ticks
		},
		yaxis: {
			axisLabel: "Average Temperature",
			axisLabelUseCanvas: true,
			axisLabelFontSizePixels: 12,
			axisLabelFontFamily: 'Verdana, Arial',
			axisLabelPadding: 3,
			tickFormatter: function (v, axis) {
				return v ;
			}
		},
		legend: {
			noColumns: 0,
			labelBoxBorderColor: "#E6E9ED",
			position: "nw"
		},
		grid: {
			hoverable: true,
			borderWidth: 2,
			backgroundColor: { colors: ["#ffffff", "#EDF5FF"] }
		}
	};
	
	$(document).ready(function () {
		$.plot($("#activitygraphcur"), dataset, options);
		$("#activitygraphcur").UseTooltip();
		$.plot($("#activitygraphprev"), datasetp, optionsp);
		$("#activitygraphprev").UseTooltip();
		 $(".uat").hide();
		setTimeout(function(){
		  $(".uat").hide();
		}, 3000);
	});
	
	var previousPoint = null, previousLabel = null;

	$.fn.UseTooltip = function () {
		$(this).bind("plothover", function (event, pos, item) {
			
			if (item) {
				if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
					previousPoint = item.dataIndex;
					previousLabel = item.series.label;
					$("#tooltip").remove();

					var x = item.datapoint[0];
					var y = item.datapoint[1];

					var color = item.series.color;

					//console.log(item.series.xaxis.ticks[x].label);                

					showTooltipAG(item.pageX,
					item.pageY,
					color,
					"<strong>" + item.series.label + "</strong><br>" + item.series.xaxis.ticks[x].label + " : <strong>" + y + "</strong> Hits");
				}
			} else {
				$("#tooltip").remove();
				previousPoint = null;
			}
		});
	};

	function showTooltipAG(x, y, color, contents) {
		$('<div id="tooltip">' + contents + '</div>').css({
			position: 'absolute',
			display: 'none',
			top: y - 40,
			left: x - 120,
			border: '2px solid ' + color,
			padding: '3px',
			'font-size': '9px',
			'border-radius': '5px',
			'background-color': '#fff',
			'font-family': 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
			opacity: 0.9
		}).appendTo("body").fadeIn(200);
	}
	$(document).ajaxStop(function() {
	  $(".uat").hide();
	});
});
</script>
<?php include("common/template-bottom.php"); ?>