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
								
							</li>
						</ul>
					</div>
					<div class="box">
						<div class="box-body">
							<div class="cafecontrol_launch_header">
								<form class="cafecontrol_launch_form_filter cafecontrol_form" action="<?= url("/work/filter"); ?>" method="post">
									<input type="hidden" name="filter" value="<?= $type; ?>"/>
									
									
									<select name="status">
										<option value="all" <?= (empty($filter->status) ? "selected" : ""); ?>>Todas</option>
										<option value="paid" <?= (!empty($filter->status) && $filter->status == "paid" ? "selected" : ""); ?>><?= ($type == 'income' ? "Receitas recebidas" : "Despesas pagas"); ?></option>
										<option value="unpaid" <?= (!empty($filter->status) && $filter->status == "unpaid" ? "selected" : ""); ?>><?= ($type == 'income' ? "Receitas não recebidas" : "Despesas não pagas"); ?></option>
									</select>

						
									<select name="category">
										<option value="all">Todas</option>
											<?php foreach ($categories as $category): ?>
												<option <?= (!empty($filter->category) && $filter->category == $category->id ? "selected" : ""); ?>
													value="<?= $category->id; ?>"><?= $category->name; ?></option>
											<?php endforeach; ?>
									</select>

									
									<input list="datelist" type="text" class="radius mask-month" name="date"
											placeholder="<?= (!empty($filter->date) ? $filter->date : date("m/Y")); ?>">

									<datalist id="datelist">
									<?php for ($range = -2; $range <= 2; $range++):
										$dateRange = date("m/Y", strtotime(date("Y-m-01") . "+{$range}month"));
										?>
									<option value="<?= $dateRange; ?>"/>
										<?php endfor; ?>
									</datalist>	
									
									

									<button class="filter radius transition fa fa-i fa-filter fa-notext"></button>
								</form>

								<div class="cafecontrol_launch_btn <?= $type; ?> radius transition "
									data-modalopen=".cafecontrol_modal_<?= $type; ?>"><span class="fa fa-i fa-plus-circle"></span>Lançar
									<?= ($type == "income" ? "Receita" : "Despesa"); ?>
								</div>
							</div>	
						</div>
						<div class="box-body">
							<div class="cafecontrol_launch_box">
								<?php if (!$invoices): ?>
									<?php if (empty($filter->status)): ?>
										<div class="message info"><span class="fa fa-i fa-exclamation-circle"></span>Ainda não existem contas
											a <?= ($type == "income" ? "receber" : "pagar"); ?>
											. Comece lançando suas <?= ($type == "income" ? "receitas" : "despesas"); ?>.
										</div>
									<?php else: ?>
										<div class="message info"><span class="fa fa-i fa-exclamation-circle"></span>Não existem contas
											a <?= ($type == "income" ? "receber" : "pagar"); ?>
											para o filtro aplicado.
										</div>
										
									<?php endif; ?>
								<?php else: ?>
									
									<table class="table table-hover">
										<thead>	
											<th scope="col" style="text-align:center;font-size:12px;">DESCRIÇÃO</th>
											<th scope="col" style="text-align:center;font-size:12px;">VENCIMENTO</th>
											<th scope="col" style="text-align:center;font-size:12px;">CATEGORIA</th>
											<th scope="col" style="text-align:center;font-size:12px;">PARCELA</th>
											<th scope="col" style="text-align:center;font-size:12px;">VALOR</th>
										</thead>
										<tbody>	
												<?php
													$unpaid = 0;
													$paid = 0;
													foreach ($invoices as $invoice):
													
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
															<?php if ($invoice->status == 'unpaid'): $unpaid += $invoice->value; ?>
																<span class="check <?= $type; ?> fa fa-i fa-thumbs-o-down transition"
																	data-toggleclass="active fa fa-i fa-thumbs-o-down fa fa-i fa-thumbs-o-up"
																	data-onpaid="<?= url("/work/onpaid"); ?>"
																	data-date="<?= ($filter->date ?? date("m/Y")); ?>"
																	data-invoice="<?= $invoice->id; ?>">
																</span>
															<?php else: $paid += $invoice->value; ?>
																<span class="check <?= $type; ?> fa fa-i fa-thumbs-o-up transition"
																	data-toggleclass="active fa fa-i fa-thumbs-o-down fa fa-i fa-thumbs-o-up"
																	data-onpaid="<?= url("/work/onpaid"); ?>"
																	data-date="<?= ($filter->date ?? date("m/Y")); ?>"
																	data-invoice="<?= $invoice->id; ?>">
																</span>
															<?php endif; ?>	
														</td>
													</tr>
												<?php endforeach; ?>
										</tbody>
									</table>
								<div class="cafecontrol_launch_item footer">
								
									<p class="fa fa-i fa-thumbs-o-down j_total_unpaid">R$ <?= str_price($unpaid); ?></p>
									<p class="fa fa-i fa-thumbs-o-up j_total_paid">R$ <?= str_price($paid); ?></p>
									
								
								</div>
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

		


		








