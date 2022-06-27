<?php $v->layout("_theme"); ?>

<div class="app-main">
    <!-- begin .main-content -->
    <div class="main-content">
		<!-- begin .container-fluid -->
        <div class="container-fluid p-t-15">
            <!-- BEGIN: .row -->
		<?php if (empty($filter->id)): ?>
            <div class="row">
                <div class="col-xs-12">
					<div class="box">
						
					</div>
					<div class="box">
						<div class="box-body">
							<form class="form-horizontal" action="<?= url("/work/invoicefilter"); ?>" method="post">
								<input type="hidden" name="action" value="create"/>
								<div>
									<section>
										<div class="form-group">
											<div class="col-md-3">
												<label for="type">Tipos de Faturas</label>
												<select name="type" id="type" class="form-control">
													<option value="all">Todas</option>
													<option value="incomepaid">Receitas Recebidas</option>
													<option value="incomeunpaid">Receitas a Receber</option>
													<option value="expensepaid">Despesas Pagas</option>
													<option value="expenseunpaid">Despesas a Pagar</option>	
												</select>
											</div>
											<div class="col-md-3">
												<label for="category">Categorias:</label>
												<select class="form-control" name="category">
													<option value="all">Todas</option>
														<?php foreach ($categories as $category): ?>
														<option <?= (!empty($filter->category) && $filter->category == $category->id ? "selected" : ""); ?>
															value="<?= $category->id; ?>"><?= $category->name; ?></option>
														<?php endforeach; ?>
												</select>
											</div>
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
							
													if (empty($filter->type)) {
														$type = "all";
													
													} else {
														$type = $filter->type;	
													}; 

													if (empty($filter->category)) {
														$category = "all";											
													} else {
														$category = $filter->category;	
													}; 
													
													if (empty($filter->dateinitial)) {
															$dateinitial = "all";
													
														} else {
															$dateinitial = $filter->dateinitial;	
													}; 
													
													if (empty($filter->datefinal)) {
															$datefinal = "all";
													
													} else {
														$datefinal = $filter->datefinal;
														
													}; 
												?>
												<button title="Buscar" class="btn btn-primary"><span class="fa fa-i fa-filter"></span>Buscar</button>
												<a href="<?= url("/work/relatorio/{$type}/{$category}/{$dateinitial}/{$datefinal}");?>" title="Imprimir PDF" class="btn btn-primary "><span class="fa fa-file-pdf-o"></span></a>			
											</div>	
										</div>
									</section>
								</div>
							</form>
						</div>
						<div class="box-body">
							<div class="cafecontrol_launch_box">
								<?php if (!$invoices): ?>
									<?php if (empty($filter->status)): ?>
										<div class="message info"><span class="fa fa-i fa-exclamation-circle"></span>Ainda não existem contas
											a receber ou pagar  Comece lançando suas receitas ou despesas.
										</div>
									<?php else: ?>
										<div class="message info"><span class="fa fa-i fa-exclamation-circle"></span>Não existem contas
											a receber pagar
											 para o filtro aplicado.
										</div>
									<?php endif; ?>
								<?php else: ?>
									<table class="table table-hover">
										<thead>	
											<th scope="col" style="text-align:center;font-size:12px;">DESCRIÇÃO</th>
											<th scope="col" style="text-align:center;font-size:12px;">VENCIMENTO</th>
											<th scope="col" style="text-align:center;font-size:12px;">CARTEIRA</th>
											<th scope="col" style="text-align:center;font-size:12px;">TIPO</th>
											<th scope="col" style="text-align:center;font-size:12px;">CATEGORIA</th>
											<th scope="col" style="text-align:center;font-size:12px;">PARCELA</th>
											<th scope="col" style="text-align:center;font-size:12px;">VALOR</th>
										</thead>
										<tbody>	
												<?php
													$unpaid = 0;
													$paid = 0;
													foreach ($invoices as $invoice):
													
													$wallet = $invoice->getWallet();
													
													?>
													<tr scope="row">
														<td  scope="col" style="text-align:center;font-size:15px;" class="desc cafecontrol_invoice_link transition">
															<span class="cafecontrol_invoice_link">
																<a title="<?= $invoice->description; ?>"
																	href="<?= url("/work/fatura/{$invoice->id}"); ?>"><?= str_limit_words($invoice->description,
																	6, "&nbsp;<span><i class='fa fa-i fa-exclamation-circle fa fa-i fa-notext'></i></span>") ?>
																</a>
															</span>
														</td>
														<td scope="col" style="text-align:center;font-size:15px;" class="date"> <?= date_fmt($invoice->due_at, "d/m/y"); ?></td>
														<td scope="col" style="text-align:center;font-size:15px;" class="date"> <?= $wallet->wallet; ?></td>
														<td scope="col" style="text-align:center;font-size:15px;" class="invoices">
															
															<?php if ($invoice->type == 'income' or $invoice->type == 'fixed_income' ): ?>
															
																<span class ="cafecontrol_invoice_link incomecolor" >Receitas</span>
																
															<?php elseif ($invoice->type == 'expense' or $invoice->type == 'fixed_expense'): ?>
															
																<span class ="cafecontrol_invoice_link expensecolor">Despesas</span>

															<?php endif; ?>	
																	
														</td>
														<td scope="col" style="text-align:center;font-size:15px;" class="category"><?= $invoice->category()->name; ?></td>
														<td scope="col" style="text-align:center;font-size:15px;" class="enrollment">
															<?php if ($invoice->repeat_when == "fixed"): ?>
																<span class="cafecontrol_invoice_link">
																	<a href="<?= url("/work/fatura/{$invoice->invoice_of}"); ?>" 
																	title="Controlar Conta Fixa"><i  class="fa fa-i fa-exchange"></i>Fixa</a>
																	</span>
															<?php elseif ($invoice->repeat_when == 'enrollment'): ?>
																<span class="cafecontrol_invoice_link">
																	<a href="<?= url("/work/fatura/{$invoice->invoice_of}"); ?>"
																		title="Controlar Parcelamento"><?= str_pad($invoice->enrollment_of, 2, 0,
																		0); ?> de <?= str_pad($invoice->enrollments, 2, 0, 0); ?></a>
																</span>
															<?php else: ?>
																<span><i class="fa fa-i fa-calendar-check-o"></i>Única</span>
															<?php endif; ?>
														</td>
														<td scope="col" style="text-align:center;font-size:15px;" class="price">
															<span>R$ <?= str_price($invoice->value); ?></span>
														</td>
													</tr>
												<?php endforeach; ?>
										</tbody>
									</table>
								<?php endif; ?>	
							</div>	
						</div>
					</div>
					<div class="box-body">
						<?=$paginator?>	 
	
					</div>			
				</div>
			</div>
		
			<?php else: ?>
			<div class="row">
                <div class="col-xs-12">
					<div class="box">
						<div class="box-body">
							<form class="form-horizontal" action="<?= url("/work/invoicefilter/{$filter->id}"); ?>" method="post">
								<input type="hidden" name="action" value="create"/>
								<div>
									<section>
										<div class="form-group">
											<div class="col-md-3">
												<label for="type">Tipos de Faturas</label>
												<select name="type" id="type" class="form-control">
													<option value="all">Todas</option>
													<option value="incomepaid">Receitas Recebidas</option>
													<option value="incomeunpaid">Receitas a Receber</option>
													<option value="expensepaid">Despesas Pagas</option>
													<option value="expenseunpaid">Despesas a Pagar</option>	
												</select>
											</div>
											<div class="col-md-3">
												<label for="category">Categorias:</label>
												<select class="form-control" name="category">
													<option value="all">Todas</option>
														<?php foreach ($categories as $category): ?>
														<option <?= (!empty($filter->category) && $filter->category == $category->id ? "selected" : ""); ?>
															value="<?= $category->id; ?>"><?= $category->name; ?></option>
														<?php endforeach; ?>
												</select>
											</div>
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
												
													if (empty($filter->id)) {
														$id = "all";
													
													} else {
														$id = $filter->id;	
													}; 
													
													if (empty($filter->type)) {
														$type = "all";
													
													} else {
														$type = $filter->type;	
													}; 

													if (empty($filter->category)) {
														$category = "all";											
													} else {
														$category = $filter->category;	
													}; 
													
													
													if (empty($filter->dateinitial)) {
															$dateinitial = "all";
													
														} else {
															$dateinitial = $filter->dateinitial;	
													}; 
													
													if (empty($filter->datefinal)) {
															$datefinal = "all";
													
													} else {
														$datefinal = $filter->datefinal;
														
													}; 
												?>
												<button title="Buscar" class="btn btn-primary"><span class="fa fa-i fa-filter"></span>Buscar</button>
												<button type="button" class="btn btn-primary" title="Importar Planilhas" data-toggle="modal" data-target="#fileAddModal">
														Importar Planilha
												</button> 
												<a href="<?= url("/work/relatorio/{$id}/{$type}/{$category}/{$dateinitial}/{$datefinal}");?>" title="Imprimir PDF" class="btn btn-primary "><span class="fa fa-file-pdf-o"></span></a>			
											</div>	
										</div>
									</section>
								</div>
							</form>
							
							
							<!-- Modal File Start Add -->
							<div class="modal fade" id="fileAddModal" tabindex="-1" role="dialog" aria-labelledby="addModalTask">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											<h4 class="modal-title" id="addModalFile">Arquivos</h4>
										</div>
										<div class="modal-body">																		
											<form class="form-horizontal"  action="<?= url("/work/modalimportinvoices");?>" method="post">
											
												<div class="form-group">
													<div class="col-md-12">
														<label for="cover">Inserir Planilha</label>
														<input type="hidden" name="action" value="create"/>
														<input type="hidden" name="id" value="<?= $wallet->id;?>"/>
														<input type="file" class="form-control" name="cover" placeholder="Inserir Documento" required/>
													</div>	
												</div>
												
												<div class="form-group p-a-15">
													<button  class="btn btn-primary m-t-15">Importar</button>
												</div>								
											</form>	
										</div>
									</div>
								</div>
							</div>
							<!-- Modal File End Add -->	
							
							
						</div>
						<div class="form-group">
							<div class="col-md-2">
											
							</div>	
						</div>	
						<div class="form-group">
							<form  action="<?= url("/work/relatorio/{$filter->id}/{$filter->type}/{$filter->category}/{$filter->dateinitial}/{$filter->datefinal}"); ?>" method="post">

								<div class="col-md-2">
								</div>
							</form>
						</div>	
						<div class="box-body">
							<div class="cafecontrol_launch_box">
								<?php if (!$invoices): ?>
									<?php if (empty($filter->status)): ?>
										<div class="message info"><span class="fa fa-i fa-exclamation-circle"></span>Ainda não existem contas
											a receber ou pagar  Comece lançando suas receitas ou despesas.
										</div>
									<?php else: ?>
										<div class="message info"><span class="fa fa-i fa-exclamation-circle"></span>Não existem contas
											a receber pagar
											 para o filtro aplicado.
										</div>
									<?php endif; ?>
								<?php else: ?>
									<table class="table table-hover">
										<thead>	
											<th scope="col" style="text-align:center;font-size:12px;">DESCRIÇÃO</th>
											<th scope="col" style="text-align:center;font-size:12px;">VENCIMENTO</th>
											<th scope="col" style="text-align:center;font-size:12px;">CARTEIRA</th>
											<th scope="col" style="text-align:center;font-size:12px;">TIPO</th>
											<th scope="col" style="text-align:center;font-size:12px;">CATEGORIA</th>
											<th scope="col" style="text-align:center;font-size:12px;">PARCELA</th>
											<th scope="col" style="text-align:center;font-size:12px;">VALOR</th>
										</thead>
										<tbody>	
												<?php
													$unpaid = 0;
													$paid = 0;
													foreach ($invoices as $invoice):
													
														$wallet = $invoice->getWallet();
													
													?>
													<tr scope="row">
														<td  scope="col" style="text-align:center;font-size:15px;" class="desc cafecontrol_invoice_link transition">
															<span class="cafecontrol_invoice_link">
																<a title="<?= $invoice->description; ?>"
																	href="<?= url("/work/fatura/{$invoice->id}"); ?>"><?= str_limit_words($invoice->description,
																	6, "&nbsp;<span><i class='fa fa-i fa-exclamation-circle fa fa-i fa-notext'></i></span>") ?>
																</a>
															</span>
														</td>
														<td scope="col" style="text-align:center;font-size:15px;" class="date"> <?= date_fmt($invoice->due_at, "d/m/y"); ?></td>
														<td scope="col" style="text-align:center;font-size:15px;" class="date"> <?= $wallet->wallet; ?></td>
														<td scope="col" style="text-align:center;font-size:15px;" class="invoices">
															
															<?php if ($invoice->type == 'income' or $invoice->type == 'fixed_income' ): ?>
															
																<span class ="cafecontrol_invoice_link incomecolor" >Receitas</span>
																
															<?php elseif ($invoice->type == 'expense' or $invoice->type == 'fixed_expense'): ?>
															
																<span class ="cafecontrol_invoice_link expensecolor">Despesas</span>

															<?php endif; ?>	
																	
														</td>
														<td scope="col" style="text-align:center;font-size:15px;" class="category"><?= $invoice->category()->name; ?></td>
														<td scope="col" style="text-align:center;font-size:15px;" class="enrollment">
															<?php if ($invoice->repeat_when == "fixed"): ?>
																<span class="cafecontrol_invoice_link">
																	<a href="<?= url("/work/fatura/{$invoice->invoice_of}"); ?>" 
																	title="Controlar Conta Fixa"><i  class="fa fa-i fa-exchange"></i>Fixa</a>
																	</span>
															<?php elseif ($invoice->repeat_when == 'enrollment'): ?>
																<span class="cafecontrol_invoice_link">
																	<a href="<?= url("/work/fatura/{$invoice->invoice_of}"); ?>"
																		title="Controlar Parcelamento"><?= str_pad($invoice->enrollment_of, 2, 0,
																		0); ?> de <?= str_pad($invoice->enrollments, 2, 0, 0); ?></a>
																</span>
															<?php else: ?>
																<span><i class="fa fa-i fa-calendar-check-o"></i>Única</span>
															<?php endif; ?>
														</td>
														<td scope="col" style="text-align:center;font-size:15px;" class="price">			
															<span>R$ <?= str_price($invoice->value); ?></span>					
														</td>
													</tr>
												<?php endforeach; ?>
										</tbody>
									</table>
								<?php endif; ?>	
							</div>	
						</div>
						<div class="box-body">
							<?=$paginator?>	 
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>	
			
		</div>
	</div>  
    <!-- END: .main-footer -->
</div>

		


		








