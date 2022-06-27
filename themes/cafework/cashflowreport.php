<!doctype html>
<html>
<head>
    
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">


	
	<!-- END: theme main stylesheet files -->
	
	<style>
	
      


        table.blueTable {
            background:	#FFF;		
            width: 100%;
            text-align: left;
            border-collapse: collapse;
            font-family: 'Open Sans', sans-serif;
		
				
        }
		
		
		th, td {
			text-align: left;
			padding: 5px;
		}

		

        table.blueTable td,
        table.blueTable th {
			align-items: center;
			padding: 15px 0;
			font-size: 12px;
			text-transform: uppercase;
				border-bottom: 1px solid #ddd;

      	
        }
		

        table.blueTable tbody td {
            font-size: 10px;
        }

        

        table.blueTable thead {
           border-bottom: 2px solid #ddd;
        }

        table.blueTable thead tr th {
		
            background: none;
			font-size: .7em;
			text-transform: uppercase;
			
        }

        table.blueTable tfoot td {
            font-size: 14px;
        }

        table.blueTable tfoot .links {
            text-align: right;
        } table.blueTable {
            background:	#FFF;		
            width: 100%;
            text-align: left;
            border-collapse: collapse;
            font-family: 'Open Sans', sans-serif;
		
				
        }
		
		
		th, td {
			text-align: left;
			padding: 5px;
		}

		

        table.blueTable td,
        table.blueTable th {
			align-items: center;
			padding: 15px 0;
			font-size: 12px;
			text-transform: uppercase;
				border-bottom: 1px solid #ddd;

      	
        }
		

        table.blueTable tbody td {
            font-size: 10px;
        }

        

        table.blueTable thead {
           border-bottom: 2px solid #ddd;
        }

        table.blueTable thead tr th {
		
            background: none;
			font-size: .7em;
			text-transform: uppercase;
			
        }

        table.blueTable tfoot td {
            font-size: 14px;
        }

        table.blueTable tfoot .links {
            text-align: right;
        }
		



    </style>
	
	
	
</head>
<body>
	
				<table class="blueTable">
										<thead>	
											<tr>		
												<th scope="col" style="text-align:center;">CARTEIRA</th>
												<th scope="col" style="text-align:center;">SALDO</th>
												<th scope="col" style="text-align:center;">RECEITAS RECEBIDAS</th>
												<th scope="col" style="text-align:center;">RECEITAS A PAGAR</th>
												<th scope="col" style="text-align:center;">DESPESAS PAGAS</th>
												<th scope="col" style="text-align:center;">DESPESAS A PAGAR</th>
											</tr>		
										</thead>
										<tbody>	
													
													<tr scope="row">
													
														<td  style="text-align:center;">
														
															<span>Geral</span>
											
														</td>
													
													
														<td  style="text-align:center;">
														
															<span>R$ <?= str_price($totalbalancewallet->wallet); ?></span>
											
														</td>
													
														<td  style="text-align:center;">
														
															<span>R$ <?= str_price($totalbalancewallet->incomepaid); ?></span>
											
														</td>
														<td  style="text-align:center;" >
														
															<span>R$ <?= str_price($totalbalancewallet->incomeunpaid); ?></span>
											
														</td>
														<td  style="text-align:center;">
														
															<span>R$ <?= str_price($totalbalancewallet->expensepaid); ?></span>
											
														</td>
														<td  style="text-align:center;">
														
															<span>R$ <?= str_price($totalbalancewallet->expenseunpaid); ?></span>
											
														</td>
													
													</tr>
													
													<?php foreach ($wallet as $wallet):
													
														$balancewalletperiod = $wallet->BalanceWalletPeriod($data ??  null );?>

													
														<tr scope="row">
													
														<td  style="text-align:center;">
														
															<span><?= $wallet->wallet; ?></span>
											
														</td>
													
													
														<td  style="text-align:center;">
														
															<span>R$ <?= str_price($balancewalletperiod->wallet); ?></span>
											
														</td>
													
														<td  style="text-align:center;">
														
															<span>R$ <?= str_price($balancewalletperiod->incomepaid); ?></span>
											
														</td>
														<td  style="text-align:center;" >
														
															<span>R$ <?= str_price($balancewalletperiod->incomeunpaid); ?></span>
											
														</td>
														<td  style="text-align:center;">
														
															<span>R$ <?= str_price($balancewalletperiod->expensepaid); ?></span>
											
														</td>
														<td  style="text-align:center;">
														
															<span>R$ <?= str_price($balancewalletperiod->expenseunpaid); ?></span>
											
														</td>
													
													</tr>
													
													
													
													
													<?php endforeach; ?>
										</tbody>
									</table>
	
	
	
 
           
                
										
											
												
												
											
							
								
						

									

 



</body>
</html>
