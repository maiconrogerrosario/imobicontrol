<?php $v->layout("_theme"); ?>
	
<div class="app-main">
	<!-- begin .main-heading -->
   <!--  <header class="main-heading shadow-2dp">
		<!-- begin dashhead -->
       <!-- <div class="dashhead bg-white">
            <div class="dashhead-titles">
				<h6 class="dashhead-subtitle">
                  chaldene
                </h6>
                <h3 class="dashhead-title">Dashboard</h3>
            </div>
            <div class="dashhead-toolbar">
				<div class="dashhead-toolbar-item">
					<a href="index.html">chaldene</a>
                  / Dashboard
                </div>
            </div>
        </div>
		<!-- END: dashhead -->
    <!--  </header>-->
    <!-- END: .main-heading -->
	
    <!-- begin .main-content -->
	<div class="main-content bg-clouds">
	    <!-- begin .container-fluid -->
        <div class="container-fluid p-t-15">
		
		<div class="cafecontrol_main_box">
		
			
			
			

			

		</div>
			
			
		<!-- END: .row -->
		</div>
	<!-- END: .container-fluid -->
	</div>
    <!-- END: .main-content -->
    <!-- begin .main-footer -->
   
    <!-- END: .main-footer -->
</div>
 <!-- END: .app-main -->	
	

<?php $v->start("scripts"); ?>
    <script type="text/javascript">
        $(function () {
            Highcharts.setOptions({
                lang: {
                    decimalPoint: ',',
                    thousandsSep: '.'
                }
            });

            var chart = Highcharts.chart('control', {
                chart: {
                    type: 'areaspline',
                    spacingBottom: 0,
                    spacingTop: 5,
                    spacingLeft: 0,
                    spacingRight: 0,
                    height: (9 / 16 * 100) + '%'
                },
                title: null,
                xAxis: {
                    categories: [<?= $chart->categories;?>],
                    minTickInterval: 1/30
                },
                yAxis: {
                    allowDecimals: true,
                    title: null,
                },
                tooltip: {
                    shared: true,
                    valueDecimals: 2,
                    valuePrefix: 'R$ '
                },
                credits: {
                    enabled: false
                },
                plotOptions: {
                    areaspline: {
                        fillOpacity: 0.5
                    }
                },
                series: [{
                    name: 'Receitas',
                    data: [<?= $chart->income;?>],
                    color: '#61DDBC',
                    lineColor: '#36BA9B'
                }, {
                    name: 'Despesas',
                    data: [<?= $chart->expense;?>],
                    color: '#F76C82',
                    lineColor: '#D94352'
                }]
            });

            $("[data-onpaid]").click(function (e) {
                setTimeout(function () {
                    $.post('<?= url("/work/dash");?>', function (callback) {
                        if (callback.chart) {
                            chart.update({
                                xAxis: {
                                    categories: callback.chart.categories
                                },
                                series: [{
                                    data: callback.chart.income
                                }, {
                                    data: callback.chart.expense
                                }]
                            });
                        }

                        if (callback.wallet) {
							
							
                            $(".cafecontrol_wallet").removeClass("gradient-red gradient-green").addClass(callback.wallet.status);
                            $(".cafecontrol_flex_amount").text("R$ " + callback.wallet.wallet);
                            $(".cafecontrol_flex_balance .income").text("Receitas: " + "R$ " + callback.wallet.income);
                            $(".cafecontrol_flex_balance .expense").text("Despesas: " + "R$ " + callback.wallet.expense);
							$(".cafecontrol_flex_balance .incomeunpaid").text("Receitas a Receber: " + "R$ " + callback.wallet.incomeunpaid);
                            $(".cafecontrol_flex_balance .expenseunpaid").text("Despesas a Pagar: " + "R$ " + callback.wallet.expenseunpaid);
							
							<?php if ($walletfilter == "Saldo Geral"): ?>				
								$(".cafecontrol_flex_balance .projectcost").text("Valor Total dos Projetos: " + "R$ " + callback.wallet.projectcost);
								
							<?php elseif ($walletfilter == "Saldo Anual"): ?>				
								$(".cafecontrol_flex_balance .projectcost").text("Valor Total dos Anual: " + "R$ " + callback.wallet.projectcost);	
								
							<?php elseif ($walletfilter == "Saldo Mensal"): ?>				
								$(".cafecontrol_flex_balance .projectcost").text("Valor Total dos Mensal: " + "R$ " + callback.wallet.projectcost);

							<?php else: ?>	
								$(".cafecontrol_flex_balance .projectcost").text("Valor Total do  Projeto: " + "R$ " + callback.wallet.projectcost);
								
							
							<?php endif; ?>
							
							
                        }
                    }, "json");
                }, 200);
            });
        });
    </script>
<?php $v->end(); ?>