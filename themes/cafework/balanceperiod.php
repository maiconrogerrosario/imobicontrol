<?php $v->layout("_theme"); ?>

<div class="app-main">
    <!-- begin .main-content -->
    <div class="main-content">
		<!-- begin .container-fluid -->
        <div class="container-fluid p-t-15">
            <!-- BEGIN: .row -->
            <div class="row">
                <div class="col-xs-12">
					
					<div class="box">
						<div class="box-body">
							<form class="form-horizontal" action="<?= url("/work/filterPeriodMonthYear"); ?>" method="post">
		
								<div>
									<section>
										<div class="form-group">
											<div class="col-md-3">
												<label for="dateinitial">Data Inicial</label>
												<input id="dateinitial" value="dateinitial" type="date" name="dateinitial" class="form-control" placeholder="<?= (!empty($filter->dateinitial) ? $filter->dateinitial : "all"); ?>">	
											</div>						
											<div class="col-md-3">
												<label for="datefinal">Data Final</label>
												<input type="date" name="datefinal" id="datefinal" class="form-control">		
											</div>	
										</div>
										<div class="form-group">
											<div class="col-md-12">
												<?php 
									
													if(empty($filter->dateinitial)){
													
														$dateinitial = null;
													
													}else {
														
														$dateinitial = $filter->dateinitial;	
														
													}; 
													
													if(empty($filter->datefinal)){
													
														$datefinal = null;
													
													}else {
														
														$datefinal = $filter->datefinal;	
														
													};
													
												?>
	
												<button title="Buscar" class="btn btn-primary"><span class="fa fa-i fa-filter"></span>Buscar</button>
												<?php 
									
													if(empty($filter->dateinitial)){
													
														$dateinitial = null;
													
													}else {
														
														$dateinitial = $filter->dateinitial;	
														
													}; 
													
													if(empty($filter->datefinal)){
													
														$datefinal = null;
													
													}else {
														
														$datefinal = $filter->datefinal;	
														
													};
													
												?>
												
												<?php if((empty($filter->dateinitial)) && (empty($filter->datefinal))): ?>
												
													<a href="<?= url("/work/relatoriobalançofinanceiro");?>" title="Imprimir PDF" class="btn btn-primary "><span class="fa fa-file-pdf-o"></span></a>
													
												<?php endif; ?>
												
												<?php if((($filter->dateinitial)) && (($filter->datefinal))): ?>

													<a href="<?= url("/work/relatoriobalançofinanceiro/{$dateinitial}/{$datefinal}");?>" title="Imprimir PDF" class="btn btn-primary "><span class="fa fa-file-pdf-o"></span></a>
	
												<?php endif; ?>
												
												
													
													
											</div>	
										</div>	
									</section>
								</div>
							</form>
						</div>
						
						<div class="box-body">
							<table class="table table-striped" align="left"  width="100%">		
								<tbody>
									<tr>
										<tr>
											<td><strong>Saldo:</strong></td><td><span>R$ <?= str_price($balance->wallet ?? 0); ?></span>
										</tr>		
										<tr>
											<td><strong>Valor Total dos Projetos:</strong></td><td><span>R$ <?= str_price($balance->projectcost ?? 0); ?></span>
										</tr>
										<tr>
											<td><strong>Receitas Recebidas:</strong></td><td><span>R$ <?= str_price($balance->incomepaid ?? 0); ?></span>
										</tr>
										<tr>	
											<td><strong>Despesas Pagas:</strong></td><td><span>R$ <?= str_price($balance->expensepaid ?? 0); ?></span>
										</tr>			
										<tr>
											<td><strong>Receita a Receber:</strong></td><td><span>R$ <?= str_price($balance->incomeunpaid ?? 0); ?></span>
										</tr>			
										<tr>
											<td><strong>Despesas a Pagar:</strong></td><td><span>R$ <?= str_price($balance->expenseunpaid ?? 0); ?></span>
										</tr>	
									</tr>	
								</tbody>								
							</table>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>  
    <!-- END: .main-footer -->
</div>

<?php $v->start("scripts"); ?>
    <script type="text/javascript">
        $(function () {
            Highcharts.setOptions({
                lang: {
                    decimalPoint: ',',
                    thousandsSep: '.'
                }
            });

            var chart = Highcharts.chart('grafico', {
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
        });
    </script>
<?php $v->end(); ?>
		




