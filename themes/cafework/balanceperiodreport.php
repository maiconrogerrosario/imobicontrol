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
		<tbody>	
			<tr>
				<tr>
					<td><strong>Saldo:</strong></td><td><span>R$ <?= str_price($balance->wallet ?? 0); ?></span>
				</tr>		
				<tr>
					<td><strong>Valor Total dos Projetos:</strong></td><td><span>R$ <?= str_price($balance->projectcost ?? 0); ?></span>
				</tr>
				<tr>
					<td><strong>Receitas Recebidas:</strong></td><td><span>R$ <?= str_price($balance->incomepaid ?? 0); ?></span>
				</tr>
				<tr>	
					<td><strong>Despesas Pagas:</strong></td><td><span>R$ <?= str_price($balance->expensepaid ?? 0); ?></span>
				</tr>			
				<tr>
					<td><strong>Receita a Receber:</strong></td><td><span>R$ <?= str_price($balance->incomeunpaid ?? 0); ?></span>
				</tr>			
				<tr>
					<td><strong>Despesas a Pagar:</strong></td><td><span>R$ <?= str_price($balance->expenseunpaid ?? 0); ?></span>
				</tr>	
			</tr>
		</tbody>																		
	</table>
</body>
</html>








