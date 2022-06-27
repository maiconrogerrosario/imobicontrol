
<?php $v->layout("_theme"); ?>
	
<div class="app-main">
	
    <!-- END: .main-heading -->
	<header class="main-heading shadow-2dp">
         <!-- begin dashhead -->
				 <!--<<div class="dashhead bg-white">
					<div class="dashhead-titles">
						<hclass="dashhead-title">Carteiras</h3>
						  <!--<h6 class="dashhead-subtitle p-t-15">
							 <a href="index.html">chaldene</a>
						   / form
							T
						</h6
					</div>

					 <!--<div class="dashhead-toolbar ">
						<div class="dashhead-toolbar-item p-t-30">
							<a href="index.html">chaldene</a>
							/ forms
							/ Form Wizard
						</div>
					</div>
				</div>
            <!-- END: dashhead -->
    </header>
	
    <!-- begin .main-content -->
	<div class="main-content bg-clouds">
	    <!-- begin .container-fluid -->
        <div class="container-fluid p-t-15">
            <!-- BEGIN: .row --> 
			<div class="row">
				<div class="col-sm-12">	
					<section class="cafecontrol_wallets">
						<article class="wallet wallet_add radius gradient-blue">
							<h2><i class="fa fa-fw fa-exclamation-circle fa fa-fw fa-notext"></i></h2>
							<h3>Crie e gerencie suas carteiras</h3>
							<p>Organize suas contas de diferentes fontes como <b>Minha Casa</b> para PF, <b>Minha Empresa</b> para PJ, ou
							ainda <b>Cartão 6431</b> para organizar cartões. Controle tudo...</p>
							<span class="btn wallet_action radius transition"><i class="fa fa-fw fa-plus-circle"></i> Adicionar Carteira</span>

							<div class="wallet_overlay radius" style="background-color: var(--color-blue)">
								<div>
									<p>Insira o nome da sua nova carteira, como <b>Minha Casa</b>, <b>Minha Empresa</b>, <b>Cartão 5546</b>...
									e tudo pronto!</p>
									<form action="<?= url("/work/wallets/new"); ?>" method="post" autocomplete="off">
										<input type="text" name="wallet_name" placeholder="Ex: Casa, Empresa, Cartão 5546" required/>
										<button class="form_btn  gradient radius transition gradient-blue gradient-hover">
											<i class="fa fa-fw fa-check"></i> Abrir Carteira
										</button>
									</form>
									<span class="wallet_overlay_close  transition"><i class="fa fa-fw fa-sign-out"></i> fechar</span>
								</div>
							</div>
						</article>

						<?php foreach ($wallets as $wallet): $balance = $wallet->balance() ?>
							<article class="wallet radius <?= ($balance->balance == "positive" ? "gradient-green" : "gradient-red"); ?>">
								<span class="wallet_remove wallet_action"><i class="fa fa-fw fa-times-circle fa fa-fw fa-notext"></i></span>
								<h2><i class="fa fa-fw fa-briefcase fa fa-fw fa-notext"></i></h2>
								<a title="Carteira <?= $wallet->wallet; ?>" href="<?= url("/work/wallet-edit/{$wallet->id}");?>"> <p class="wallet_balance"><?=$wallet->wallet;?></p></a>

								<p class="wallet_balance">R$ <?= str_price($balance->wallet); ?></p>
								<p class="wallet_income">Receitas Recebidas: R$ <?= str_price($balance->income); ?></p>
								<p class="wallet_expense">Despesas Pagas: R$ <?= str_price($balance->expense); ?></p>
								<a  href="<?= url("/work/faturas/{$wallet->id}");?>">Ver Faturas</a>
								<div class="wallet_overlay radius">
									<div>
										<h3><i class="fa fa-fw fa-warning"></i> ATENÇÃO:</h3>
										<p>Ao deletar uma carteira todos as contas lançadas nesta serão perdidas. Tem certeza que deseja
										deletar a carteira?</p>
										<span data-walletremove="<?= url("/work/wallets/{$wallet->id}"); ?>"
											data-wallet="<?= $wallet->id; ?>"
											class="btn radius transition"><i class="fa fa-fw fa-warning"></i> Sim, DELETAR!</span>

										<span class="wallet_overlay_close  transition"><i class="fa fa-fw fa-sign-out"></i> fechar</span>
									</div>
								</div>
							</article>
						<?php endforeach; ?>
					</section>		
				</div>
			</div>	
		<!-- END: .container-fluid -->
		</div>
	</div>	
    <!-- END: .main-content -->
    <!-- begin .main-footer -->
   
    <!-- END: .main-footer -->
</div>
 <!-- END: .app-main -->	
	




