<div class="cafecontrol_modal_box cafecontrol_modal_stage">
    <p class="title icon-calendar-check-o">Nova Etapa</p>
				<form class="form-horizontal" action="<?= url("/work/modalstage"); ?>" method="post">
				<input type="hidden" name="action" value="update"/>
					<div class="form-group">
						<div class="col-md-12">
						<label for="stage_name">Etapa:</label>
						
							<input type="hidden" name="work" value="<?php echo $project->id;?>"/>
							<select name="stage_name" id="stage_name"   class="form-control">
							<?php foreach($stageCategory as $stageCategory): ?>
								<option value="<?=$stageCategory->name;?>"><?=  $stageCategory->name;?></option>
							<?php endforeach; ?>
							</select>
						</div>	
					</div>
					<div class="form-group">
						<div class="col-md-6">
							<label for="name">Data de Início da Etapa:</label>
							<input type="text" id="name"  name="name"  class="form-control" placeholder=" name" required>
						</div>
						<div class="col-md-6">
							<label for="date_final">Data de Entrega da Etapa:</label>
							<input type="date" id="date_final" name="date_final"  class="form-control"  placeholder="Data Final" required>
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
					
					<div class="form-group">
						<div class="col-md-12">
							<label for="annotations">Anotações</label>
							<textarea type="text" class="form-control" id="annotations" name="annotations" placeholder="value="<?= $etapas2->id; ?>""></textarea>
						</div>	
					</div>
					
					
					<button  class="btn btn-primary">
					
							Cadastrar
					</button>
					
					
				</form>
				
				
				
           
				
		
		<!-- /.modal-content -->
    <!-- /.modal-dialog -->
</div>