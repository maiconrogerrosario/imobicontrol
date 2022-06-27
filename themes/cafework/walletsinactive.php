	
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
			<div class="row">
				<div class="col-xs-12">
					<div class="box">
							<header>
								<h3>Carteiras Inativas</h3>
								<div class="box-tools">
								</div>
							</header>	
						<!--<div class="box-body">
					
							<a href="" class="btn btn-primary"><i class='fa fa-i fa-plus-circle'></i>Projeto</a>

						</div>-->
						<div class="box-body">
							<table class="table table-bordered table-hover">
								<thead>
									<th scope="col" style="text-align:center;font-size:12px;">CARTEIRA</th>
									<th scope="col"style="text-align:center;font-size:12px;">SALDO</th>
									<th scope="col" style="text-align:center;font-size:12px;">RECEITAS</th>
									<th scope="col" style="text-align:center;font-size:12px;">DESPESAS</th>
									<th scope="col" style="text-align:center;font-size:12px;">TIPO DE CARTEIRA</th>
									<th scope="col" style="text-align:center;font-size:12px;">AÇÕES</th>
								</thead>
								<tbody>	
									<?php if (!empty($wallets)): ?>
										<?php foreach ($wallets as $wallet): $balance = $wallet->balance() ?>
											<tr scope="row">	
												<td scope="col" style="text-align:center;font-size:12px;"><?php echo $wallet->wallet;?></td>
												<td scope="col" style="text-align:center;font-size:12px;">R$ <?= str_price($balance->income - $balance->expense ); ?></td>
												<td scope="col" style="text-align:center;font-size:12px;">R$ <?= str_price($balance->income); ?></td>
												<td scope="col" style="text-align:center;font-size:12px;">R$ <?= str_price($balance->expense); ?></td>
							
												<td scope="col" style="text-align:center;font-size:12px;">
													<?php if(empty($wallet->project)){
															echo "INTERNA";
														}else if(!empty($wallet->project)){
															echo "SERVIÇO";
														}
													?>					
												</td>								

												<td scope="col" style="text-align:center;font-size:12px;">
													<a title="Ver Fatura" href="<?= url("/work/faturas/{$wallet->id}");?>" class="btn btn-primary btn-xs"><span class="fa fa-file-o fw-fa"></span></a>
													<a title="Editar" href="<?= url("/work/wallet-edit/{$wallet->id}"); ?>" class="btn btn-warning btn-xs"><span class="fa fa-edit fw-fa"></span></a>
													<a title="Deletar" class="btn-simple btn btn-danger btn-xs"  href="#"
														data-walletremove="<?= url("/work/wallets/{$wallet->id}"); ?>"
														data-wallet="<?= $wallet->id; ?>"><span class="fa fw-i fa-remove"></span></a>
												</td>
											</tr>
										<?php endforeach; ?>
									<?php endif; ?>			
								</tbody>
							</table>			
						</div>
						
						
						<div class="box-body">
							<?=$paginator?>	 
						</div>
						
					</div>	
				</div>
			</div>
			<!-- END: .row -->		
		</div>

	</div>	
    <!-- END: .main-content -->
	
    <!-- begin .main-footer -->
	<footer class="main-footer bg-white p-a-5"> 
		<div class="container-fluid p-t-15">
			<div class="row">
				<div class="col-xs-12">
					
				</div>
			</div>
			<!-- END: .row -->		
		</div>	
    </footer>


    <!-- END: .main-footer -->
</div>
<!-- END: .app-main -->	
	



		



		

