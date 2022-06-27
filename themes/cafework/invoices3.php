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
							<ul class="cafecontrol_header_widget">
									<li class="radius wallet"><span class="fa fa-i fa-filter"></span>  <?= (session()->has("walletfilter") ? (new \Source\Models\CafeApp\AppWallet())->findById(session()->walletfilter)->wallet : "Saldo Geral"); ?>
										<ul>
											<?php if (session()->has("walletfilter")): ?>
											<li class="radius" data-walletfilter="<?= url("/work/dash"); ?>"
												data-wallet="all"><span class="fa fa-i fa-briefcase"></span>Saldo Geral
											</li>
									<?php endif; ?>

									<?php
									$userId = user()->application_id;
									$wallets = (new \Source\Models\CafeApp\AppWallet())
										->find("application_id = :application_id", "application_id={$userId}")
										->order("wallet")
										->fetch(true);

									foreach ($wallets as $walletIt):
										if (!session()->has("walletfilter") || $walletIt->id != session()->walletfilter):
											?>
											<li class="radius" data-walletfilter="<?= url("/work/dash"); ?>"
												data-wallet="<?= $walletIt->id; ?>"><span class="fa fa-i fa-suitcase"></span>
										
										
												<?= $walletIt->wallet; ?></li>
											<?php
										endif;
									endforeach;
									?>
								</ul>
							</li>
						</ul>
					</div>
					<div class="box">
						<div class="box-body">
							<form class="form-horizontal" action="<?= url("/work/balanceFilter"); ?>" method="post">
								<input type="hidden" name="action" value="create"/>
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
												<a href="<?= url("/work/relatorio/{$dateinitial}/{$datefinal}");?>" title="Imprimir PDF" class="btn btn-primary "><span class="fa fa-file-pdf-o"></span></a>	
													
											</div>	
											
											
											
										</div>
											
									</section>
								</div>
							</form>
						</div>
						
						<div class="form-group">

							<div class="col-md-2">
									
	
											
							</div>	
						</div>	
						
						<div class="form-group">
							<form  action="<?= url("/work/relatorio/{$filter->dateinitial}/{$filter->datefinal}"); ?>" method="post">

								<div class="col-md-2">
								</div>
							</form>
							
							
							
						
						</div>	
						<div class="box-body">
							<div class="cafecontrol_launch_box">
								<?php if (!$wallets): ?>
										<div class="message info"><span class="fa fa-i fa-exclamation-circle"></span>Ainda não existem 
											carteiras criadas
										</div>
									
										
								<?php else: ?>
									<table class="table table-hover">
										<thead>											
											<th scope="col" style="text-align:center;font-size:12px;">CARTEIRA</th>
											<th scope="col" style="text-align:center;font-size:12px;">SALDO</th>
											<th scope="col" style="text-align:center;font-size:12px;">RECEITAS RECEBIDAS</th>
											<th scope="col" style="text-align:center;font-size:12px;">RECEITAS A PAGAR</th>
											<th scope="col" style="text-align:center;font-size:12px;">DESPESAS PAGAS</th>
											<th scope="col" style="text-align:center;font-size:12px;">DESPESAS A PAGAR</th>
										</thead>
										<tbody>	
													
													<tr scope="row">
													
														<td scope="col" style="text-align:center;font-size:15px;">
														
															<span>Geral</span>
											
														</td>
													
													
														<td scope="col" style="text-align:center;font-size:15px;">
														
															<span>R$ <?= str_price($totalbalancewallet->wallet); ?></span>
											
														</td>
													
														<td scope="col" style="text-align:center;font-size:15px;">
														
															<span>R$ <?= str_price($totalbalancewallet->incomepaid); ?></span>
											
														</td>
														<td scope="col" style="text-align:center;font-size:15px;" >
														
															<span>R$ <?= str_price($totalbalancewallet->incomeunpaid); ?></span>
											
														</td>
														<td scope="col" style="text-align:center;font-size:15px;">
														
															<span>R$ <?= str_price($totalbalancewallet->expensepaid); ?></span>
											
														</td>
														<td scope="col" style="text-align:center;font-size:15px;">
														
															<span>R$ <?= str_price($totalbalancewallet->expenseunpaid); ?></span>
											
														</td>
													
													</tr>
													
													<?php foreach ($wallet as $wallet):
													
														$balancewalletperiod = $wallet->BalanceWalletPeriod($data ??  null );?>

													
														<tr scope="row">
													
														<td scope="col" style="text-align:center;font-size:15px;">
														
															<span><?= $wallet->wallet; ?></span>
											
														</td>
													
													
														<td scope="col" style="text-align:center;font-size:15px;">
														
															<span>R$ <?= str_price($balancewalletperiod->wallet); ?></span>
											
														</td>
													
														<td scope="col" style="text-align:center;font-size:15px;">
														
															<span>R$ <?= str_price($balancewalletperiod->incomepaid); ?></span>
											
														</td>
														<td scope="col" style="text-align:center;font-size:15px;" >
														
															<span>R$ <?= str_price($balancewalletperiod->incomeunpaid); ?></span>
											
														</td>
														<td scope="col" style="text-align:center;font-size:15px;">
														
															<span>R$ <?= str_price($balancewalletperiod->expensepaid); ?></span>
											
														</td>
														<td scope="col" style="text-align:center;font-size:15px;">
														
															<span>R$ <?= str_price($balancewalletperiod->expenseunpaid); ?></span>
											
														</td>
													
													</tr>
													
													
													
													
													<?php endforeach; ?>
										</tbody>
									</table>
								
								<?php endif; ?>	
							</div>	
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>  
    <!-- END: .main-footer -->
</div>

		


		








