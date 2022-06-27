


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
							<div class="cafecontrol_main_right" style="margin: 0;">
								<ul class="cafecontrol_widget_shortcuts">
									<li class="income radius transition" data-modalopen=".cafecontrol_modal_income">
										<p><i class="fa fa-i fa-plus-circle"></i>Receita</p>
									</li>
									<li class="expense radius transition" data-modalopen=".cafecontrol_modal_expense">
										<p><i class="fa fa-i fa-plus-circle"></i>Despesa</p>
									</li>
								</ul>
							</div>	
						</div>
					
						<div class="box-body">
							
							 <?php if (!$invoices): ?>
								<div class="message info"><span class="fa fa-i fa-exclamation-circle"></span>Ainda não existem contas a fixas. Comece lançando
									suas recorrências.
								</div>
							<?php else: ?>
							
							
							
								<table class="table table-hover">
									<thead>	
										<th  scope="col" style="text-align:center;font-size:12px;">DESCRIÇÃO</th>
										<th scope="col" style="text-align:center;font-size:12px;">VENCIMENTO</th>
										<th scope="col" style="text-align:center;font-size:12px;">CATEGORIA</th>
										<th scope="col" style="text-align:center;font-size:12px;">STATUS</th>
										<th scope="col" style="text-align:center;font-size:12px;">VALOR</th>
									</thead>
									<tbody>	
											<?php
												$unpaid = 0;
												$paid = 0;
												foreach ($invoices as $invoice):
											?>
												<tr scope="row">
													<td  scope="col" style="text-align:left;font-size:15px;">
															<span class="desc cafecontrol_invoice_link transition">
																<a title="<?= $invoice->description; ?>" href="<?= url("/work/fatura/{$invoice->id}"); ?>">
																	<?= ($invoice->type == "fixed_income" ? "Receita / " : "Despesa / "); ?>
																	<?= str_limit_words($invoice->description, 6,
																	"&nbsp;<span ><i class='fa fa-i fa-exclamation-circle fa fa-i fa-notext'></i></span>") ?>
																</a>
															</span>
													</td>
													<td scope="col" style="text-align:center;" class="date">Dia <?= date_fmt($invoice->due_at, "d"); ?></td>
													<td scope="col" style="text-align:center;"><span  class="category"><?= $invoice->category()->name; ?></span></td>
													<td scope="col" style="text-align:center;" class="enrollment">
														<span class="enrollment"><?= ($invoice->status == "paid" ? "Ativa" : "Invativa"); ?></span>
													</td>
													<td scope="col" style="text-align:center;font-size:15px;">
														
															<span class="price">R$ 
															<?= str_price($invoice->value); ?> <?= ($invoice->period == "month" ? "/mês" : "/ano"); ?></span>
              
															
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
    <!-- begin .main-footer -->
    <footer class="main-footer bg-white p-a-5">

    </footer>
    <!-- END: .main-footer -->
 </div>

		


		












