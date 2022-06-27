<div class="cafecontrol_modal_box cafecontrol_modal_task">
    <p class="title icon-calendar-check-o">Novo Serviço</p>
				<form class="form-horizontal" action="<?= url("/work/modalTask"); ?>" method="post">
				<input type="hidden" name="action" value="create"/>	
					
					<div class="form-group">
						<div class="col-md-12">
							<label for="task_name">Etapa:</label>
							<?php $projeto = $project->id; ?>
								<input type="hidden" name="work" value="<?php echo $project->id;?>"/>
								<select name="stage_id" id="stage_id"   class="form-control">
									<?php foreach($etapas as $etapa): ?>
										<option value="<?=$etapa->id;?>"><?=  $etapa->stage_name;?></option>
									<?php endforeach; ?>
								</select>
						</div>	
					</div>

					<div class="form-group">
						<div class="col-md-12">
						<label for="task_name">Serviço:</label>
							<input type="text" id="task_name" name="task_name"  class="form-control" placeholder="Nome do Serviço:" required>
						</div>	
					</div>	
					
					

					<div class="form-group">
						<div class="col-md-6">
							<label for="date_initial">Data de Início da Etapa:</label>
							<input type="date" id="date_initial" name="date_initial"  class="form-control" placeholder="Data Inicial" required>
						</div>
						<div class="col-md-6">
							<label for="date_final">Data de Entrega da Etapa:</label>
							<input type="date" id="date_final" name="date_final"  class="form-control"  placeholder="Data Final" required>
						</div>	
					</div>
					<div class="form-group">
						<div class="col-md-12">
							<label for="status">Estado da Etapa:</label>
							<select name="status" id="status" class="form-control" required>
								<option value="active">Ativa</option>
								<option value="finished">Finalizada</option>	
							</select>
						</div>	
					</div>
					<div class="form-group">
						<div class="col-md-12">
							<label for="annotations">Anotações</label>
							<textarea type="text" class="form-control" id="annotations" name="annotations" placeholder="Anotações:"></textarea>
						</div>	
					</div>
					
					
					<button  class="btn btn-primary">
					
							Cadastrar
					</button>
					
					
				</form>
				
				
				
				
           
				
		
		<!-- /.modal-content -->
    <!-- /.modal-dialog -->
</div>