<?php $v->layout("_theme"); ?>

 <!-- begin .app-main -->
<div class="app-main">

    <!-- begin .main-heading -->
    <header class="main-heading shadow-2dp">
            <!-- begin dashhead -->
            <!-- <div class="dashhead bg-white">
              <div class="dashhead-titles">

                <h3 class="dashhead-title">Serviços</h3>
				<h6 class="dashhead-subtitle p-t-15">
                  <a href="index.html">chaldene</a>
                  / forms
                  / Form Wizard
                </h6>
              </div>

              <div class="dashhead-toolbar ">
                <div class="dashhead-toolbar-item p-t-30">
                  <a href="index.html">chaldene</a>
                  / forms
                  / Form Wizard
                </div>
              </div>
            </div>-->
            <!-- END: dashhead -->
    </header>
          <!-- END: .main-heading -->

    <!-- begin .main-content -->
    <div class="main-content bg-clouds">
        <!-- begin .container-fluid -->
        <div class="container-fluid p-t-15"> 
           <div class="row">
                <div class="col-xs-12">
					<div class="box">
						<header>
							<h4>Atualizar Carteiras<small></small> </h4>
							<!-- begin box-tools -->
						<!--<div class="box-tools">
								<a class="fa fa-fw fa-minus" href="#" data-box="collapse"></a>
								<a class="fa fa-fw fa-times" href="#" data-box="close"></a>
								</div>-->
                        <!-- END: box-tools -->
                        </header>
						<div class="box-body">
							<form class="form-horizontal" action="<?= url("/work/wallet-edit/{$walletedit->id}"); ?>" method="post">
								<div>
									<section>
										<div class="form-group">
											<div class="col-md-12">
												<label for="wallet">Nome da Carteira:</label>
												<input type="text" value="<?php echo $walletedit->wallet;?>" class="form-control" id="wallet" name="wallet" placeholder="Nome da Carteira:"/>
												<input type="hidden" name="action" value="update"/>
											</div>
										</div>
										
										<?php if ($walletedit->project == null ):?>
										
										<div class="form-group">
											<div class="col-md-12">
												<label for="type">Tipo de Carteira:</label>
												<input type="type" value="Carteira de Interna" class="form-control" id="type" placeholder=" Carteira de Interna" name="type" readonly/>
											</div>
										</div>
		
										<div class="form-group">
											<div class="col-md-12">
												<label for="status">Status:</label>
												<select name="status" id="status"  class="form-control">			
													<option value="active"<?php if($walletedit->status=="active"){echo "selected";}?>>Ativa</option>
													<option value="finished"<?php if($walletedit->status=="finished"){echo "selected";}?>>Inativa</option>
												</select>
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-6">
												<button type="submit" class="btn btn-primary">Atualizar</button>
											</div>
										</div>
											
										<?php else: 
										$work = $works->findById($walletedit->project);?>
										
										<div class="form-group">
											<div class="col-md-12">
												<label for="type">Tipo de Carteira:</label>
												<input type="type" value="Carteira de Projeto" class="form-control" id="type" placeholder=" Carteira de Projeto" name="type" readonly />
											</div>
										</div>
										
										<div class="form-group">
											<div class="col-md-12">
												<label for="project">Projeto:</label>
												<input type="project" value="<?=  $work->name;?>" class="form-control" id="project" placeholder="Projeto" name="project" readonly />
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-12">
												<label for="status">Status:</label>
												<select name="status" id="status"  class="form-control">			
													<option value="active"<?php if($walletedit->status=="active"){echo "selected";}?>>Ativa</option>
													<option value="finished"<?php if($walletedit->status=="finished"){echo "selected";}?>>Inativa</option>
												</select>
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-6">
												<button type="submit" class="btn btn-primary">Atualizar</button>
											</div>
										</div>
										
										<?php endif; ?>	
										
									</section>
								</div>
							</form>
						</div>
					</div>
                </div>
            </div>
        </div>
            <!-- END: .container-fluid -->
    </div>
          <!-- END: .main-content -->

          <!-- begin .main-footer -->
          <!--  <footer class="main-footer bg-white p-a-5">
           
          </footer>-->
          <!-- END: .main-footer -->

</div>
        <!-- END: .app-main -->	






