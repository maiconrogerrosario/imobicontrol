
	
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
							<h3>Imóveis</h3>
					
							<div class="box-tools">

							</div>
						
						</header>
						<div class="box-body">	
					
							<a href="<?= url("/work/property-add"); ?>" class="btn btn-primary"><i class='fa fa-i fa-plus-circle'></i>Imóvel</a>

						</div>
						<div class="box-body">	
							<table class="table table-bordered table-hover">
								<thead>
									<th scope="col" style="text-align:center;font-size:12px;">ENDEREÇO</th>
									<th scope="col" style="text-align:center;font-size:12px;">BAIRRO</th>
									<th scope="col" style="text-align:center;font-size:12px;">CIDADE</th>
									<th scope="col" style="text-align:center;font-size:12px;">ESTADO</th>
									<th scope="col" style="text-align:center;font-size:12px;">AÇÕES</th>
								</thead>
								<tbody>	
									<?php if (!empty($propertys)): ?>
										<?php foreach ($propertys as $property):?>
											<tr scope="row">
												<td scope="col" style="text-align:center;font-size:12px;"><?php echo $property->fullAddress();?></td>
												<td scope="col" style="text-align:center;font-size:12px;"><?php echo $property->address_neighborhood; ?></td>
												<td scope="col" style="text-align:center;font-size:12px;"><?php echo $property->address_city; ?></td>
												<td scope="col" style="text-align:center;font-size:12px;"><?php echo $property->address_state; ?></td>
												<td scope="col" style="text-align:center;font-size:12px;">
													<a href="<?= url("/work/property-edit/{$property->id}"); ?>" class="btn btn-warning btn-xs"><span class="fa fa-edit fw-fa"></span></a>
													<a class="btn-simple btn btn-danger btn-xs" title="" href="#"
														data-post="<?= url("/work/property-delete/{$property->id}"); ?>"
														data-action="delete"
														data-confirm="Tem Certeza que Deseja Deletar esse Cliente?"
														data-id="<?= $property->id; ?>"><span class="fa fw-i fa-remove"></span></a> 
												</td>
											</tr>
												
										<?php endforeach; ?>
									<?php endif; ?>			
								</tbody>
							</table>			
						</div>
						<div class="box-body">
							<?= $paginator?>	 
						</div>
					</div>			
				</div>
			</div>
			<!-- END: .row -->		
		</div>		
	</div>	
    <!-- END: .main-content -->
	
    <!-- begin .main-footer -->

    <!-- END: .main-footer -->
</div>
<!-- END: .app-main -->	
	



		

