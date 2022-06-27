<?php $v->layout("_theme"); ?>

<div class="app-main">
    <!-- begin .main-heading -->
    <!--<header class="main-heading shadow-2dp">
		begin dashhead 
		<div class="dashhead bg-white">
			<div class="dashhead-titles">
				<h6 class="dashhead-subtitle">
					chaldene
					/ tables
				</h6>
				<h3 class="dashhead-title">Table Basic</h3>
			</div>
			<div class="dashhead-toolbar">
				<div class="dashhead-toolbar-item">
					<a href="index.html">chaldene</a>
					/ tables
					/ Table Basic
				</div>
			</div>
		</div>
		<!-- END: dashhead 
    </header>-->
    <!-- END: .main-heading -->
    <!-- begin .main-content -->
    <div class="main-content bg-clouds">
		<!-- begin .container-fluid -->
        <div class="container-fluid p-t-15">
            <!-- BEGIN: .row -->
            <div class="row">
				<div class="col-xs-12">
					<div class="cafecontrol_formbox cafecontrol_widget">
						<form class="cafecontrol_form" action="<?= url("/work/invoice/{$invoice->id}"); ?>" method="post">
							<input type="hidden" name="update" value="true"/>

							<label>
								<span class="field fa fa-lg fa-leanpub"> Descrição:</span>
								<input class="radius" type="text" name="description" value="<?= $invoice->description; ?>" required/>
							</label>

							<div class="label_group">
								<label>
									<span class="field fa fa-lg fa-money"> Valor:</span>
									<input class="radius mask-money" type="text" name="value" value="<?= $invoice->value; ?>" required/>
								</label>

								<label>
									<span class="field fa fa-lg fa-filter"> Dia de vencimento:</span>
									<input class="radius masc-date" type="date"  name="due_day"
										value="<?=  $invoice->due_at; ?>" required />

								</label>
							</div>
							

							<label>
								<span class="field fa fa-lg fa-briefcase"> Carteira:</span>
								<select name="wallet">
									<?php foreach ($wallets as $wallet): ?>
										<option <?= ($wallet->id == $invoice->wallet_id ? "selected" : ""); ?>
											value="<?= $wallet->id; ?>">&ofcir; <?= $wallet->wallet; ?></option>
									<?php endforeach; ?>
								</select>
							</label>

							<div class="label_group">
								<label>
									<span class="field fa fa-lg fa-filter"> Categoria:</span>
										<select name="category">
											<?php foreach ($categories as $category): ?>
												<option <?= ($category->id == $invoice->category_id ? "selected" : ""); ?>
												value="<?= $category->id; ?>">&ofcir; <?= $category->name; ?></option>
											<?php endforeach; ?>
										</select>
								</label>

								<label>
									<span class="field fa fa-lg fa-filter"> Status:</span>
									<select name="status">
										<?php if ($invoice->type == "fixed_income" || $invoice->type == "fixed_expense"): ?>
											<option <?= ($invoice->status != 'paid' ?: "selected"); ?> value="paid">&ofcir; Ativa</option>
											<option <?= ($invoice->status != 'unpaid' ?: "selected"); ?> value="unpaid">&ofcir; Inativa
											</option>
										<?php else: ?>
											<option <?= ($invoice->status == 'paid' ? "selected" : ""); ?> value="paid">
												&ofcir; <?= ($invoice->type == 'income' ? "Recebida" : "Paga"); ?></option>
											<option <?= ($invoice->status == 'unpaid' ? "selected" : ""); ?> value="unpaid">
												&ofcir; <?= ($invoice->type == 'income' ? "Não recebida" : "Não paga"); ?></option>
										<?php endif; ?>
									</select>
								</label>
							</div>

							<div class="al-center">
								<div class="cafecontrol_formbox_actions">
									<span data-invoiceremove="<?= url("/work/remove/{$invoice->id}"); ?>"
										class="btn_remove transition fa fa-lg fa-error"> Excluir</span>
									<button class="btn btn_inline radius transition fa fa-lg fa-pencil-square-o"> Atualizar</button>
									<a class="btn_back transition radius icon-sign-in" href="<?= url_back(); ?>" title="Voltar"> Voltar</a>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
 </div>
		      

 



