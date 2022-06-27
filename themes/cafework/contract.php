	
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
								<h3>Contratos</h3>
								<div class="box-tools">
								</div>
							</header>	
						<div class="box-body">
							<a href="<?= url("/work/contract-add"); ?>" class="btn btn-primary"><i class='fa fa-i fa-plus-circle'></i>Contrato</a>

						</div>
						<div class="box-body">
							<table class="table table-bordered table-hover">
								<thead>
									<th scope="col" style="text-align:center;font-size:12px;">NUMERO DO CONTRATO</th>
									<th scope="col" style="text-align:center;font-size:12px;">CLIENTE</th>
									<th scope="col"style="text-align:center;font-size:12px;">PROPRIEDADE</th>
									<th scope="col" style="text-align:center;font-size:12px;">DATA DE INÍCIO</th>
									<th scope="col" style="text-align:center;font-size:12px;">DATA DE FIM</th>
									<th scope="col" style="text-align:center;font-size:12px;">STATUS</th>
									<th scope="col" style="text-align:center;font-size:12px;">AÇÕES</th>	
								</thead>
								<tbody>	
									<?php if (!empty($contract)): ?>
										<?php foreach ($contract as $contract):?>
											
											<?php 	$customer  = $contract->getCustomer();?>
											<?php 	$property  = $contract->getProperty();?>
										
											<tr scope="row">	
												<td scope="col" style="text-align:center;font-size:12px;"><?=  $contract->number_contract;?></td>
												<td scope="col" style="text-align:center;font-size:12px;"><?= $customer->name;  ?></td>
												<td scope="col" style="text-align:center;font-size:12px;"><?= $property->fullAddress();?></td>
												<td scope="col" style="text-align:center;font-size:12px;"><?= ($contract->date_initial ? date_fmt($contract->date_initial, "d/m/Y") : null); ?></td>
												<td scope="col" style="text-align:center;font-size:12px;"><?= ($contract->date_final ? date_fmt($contract->date_final, "d/m/Y") : null); ?></td>
												<td scope="col" style="text-align:center;font-size:12px;">
													<?php if($contract->status == "active"){
															echo "ATIVO";
														}else if($contract->status == "finished"){
															echo "FINALIZADO";
														}else if($contract->status == "budgeting"){
															echo "ORÇANDO";
														}
													?>					
												</td>
												<td scope="col" style="text-align:center;font-size:12px;">
													<a title="Gerenciar Projetos e Serviços" href="<?= url("/work/project/{$contract->id}");?>" class="btn btn-primary btn-xs"><span class="fa fa-file-o fw-fa"></span></a>
													<a title="Editar" href="<?= url("/work/contract-edit/{$contract->id}"); ?>" class="btn btn-warning btn-xs"><span class="fa fa-edit fw-fa"></span></a>
													<a title="Deletar" class="btn-simple btn btn-danger btn-xs"  href="#"
														data-post="<?= url("/work/contract-delete/{$contract->id}"); ?>"
														data-action="delete"
														data-confirm="Tem Certeza que Deseja Deletar esse Projeto?"
														data-id="<?= $contract->id; ?>"><span class="fa fw-i fa-remove"></span></a>
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
	



		



		

