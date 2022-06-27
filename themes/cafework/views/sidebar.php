
<!-- BEGIN: nav-content -->
 <ul class="metismenu nav nav-inverse nav-bordered nav-stacked" data-plugin="metismenu">
 
			<li>
                <a title="Usuários" href="<?= url("/work");?>">
					<span class="nav-icon">
						<i class="fa fa-fw fa-home"></i>
					</span>		
                    <span class="nav-title">Controle</span>
                </a>
            </li>
		 
			<li>
                <a title="Clientes" href="<?= url("/work/customer"); ?>">
					<span class="nav-icon">
						<i class="fa fa-fw fa-users"></i>
					</span>		
                     <span class="nav-title">Clientes</span>
                </a>
            </li>
		
            <li>
                <a title="Proprietários" href="<?= url("/work/owner"); ?>">
					<span class="nav-icon">
						<i class="fa fa-building"></i>
					</span>		
                     <span class="nav-title">Proprietários</span>
                </a>
            </li>
		
            <li>
                <a title="Imóveis" href="<?= url("/work/property"); ?>" >
					<span class="nav-icon">
						<i class="fa fa-industry"></i>
					</span>		
                    <span class="nav-title">Imóveis</span>
                </a>
            </li>
			
			
			 <li>
                <a title="Contratos" href="<?= url("/work/contract"); ?>" >
					<span class="nav-icon">
						<i class="fa fa-industry"></i>
					</span>		
                    <span class="nav-title">Contratos</span>
                </a>
            </li>
			
			<!-- BEGIN: Categorias -->
			<li>
				<a href="javascript:;">
					<span class="nav-icon"><i class="fa fa-fw fa-dollar"></i></span>                    
					<span class="nav-title">Financeiro</span>
					<span class="nav-tools"><i class="fa fa-fw arrow"></i>
				</a>
				<ul class="nav nav-sub nav-stacked">
					<li><a title="Contratratos Ativos" href="<?= url("/work/carteiras");?>">Contratratos Ativos</a></li>
					<li><a title="Contratratos Inativos" href="<?= url("/work/carteirasinativas");?>">Contratratos Inativos</a></li>
					<li><a title="Mensalidades a Receber" href="<?= url("/work/receber");?>">Mensalidades a Receber</a></li>
					<li><a title="Repasses a Pagar" href="<?= url("/work/pagar");?>">Repasses a Pagar</a></li>
					<li><a title="Faturas Fixas" href="<?= url("/work/fixas");?>">Fixas</a></li>
					<li><a title="Faturas" href="<?= url("/work/faturas");?>">Faturas</a></li>
				</ul>
			</li>
			
			<!-- BEGIN: Categorias -->
			<li>
				<a href="javascript:;">
					<span class="nav-icon"><i class="fa fa-fw fa-th"></i></span>                    
					<span class="nav-title">Categorias</span>
					<span class="nav-tools"><i class="fa fa-fw arrow"></i>
				</a>
				<ul class="nav nav-sub nav-stacked">
					<li><a title="Faturas" href="<?= url("/work/category"); ?>">MENSALIDADES</a></li>
				</ul>
			</li>
			
			
			
			
			<li>
                <a title="Usuários" href="<?= url("/work/user");?>">
					<span class="nav-icon">
						<i class="fa fa-fw fa-user"></i>
					</span>		
                    <span class="nav-title">Usuários</span>
                </a>
            </li>
			<li>
                <a title="Sair" href="<?= url("/work/sair");?>">
					<span class="nav-icon">
						<i class="fa fa-fw fa-sign-out"></i>
					</span>		
                    <span class="nav-title">Sair</span>
                </a>
            </li>	      
  </ul>              
     
	
	
	



