<div class="modal fade" id="stage" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Etapas</h4>
            </div>
            <div class="modal-body">
				<form class="form-horizontal" action="<?= url("/app/modalStage"); ?>" method="post">
					<input type="hidden" name="action" value="create"/>	
					<div class="form-group">
						<div class="col-md-12">
						<label for="stage_name" >Etapa:</label>
							<select name="stage_name" id="stage_name"   class="form-control">
							<?php foreach($stageCategory as $stageCategory): ?>
								<option value="<?=$stageCategory->name; ?>"><?=  $stageCategory->name;?></option>
							<?php endforeach; ?>
							</select>
						</div>	
					</div>
					<div class="form-group">
						<div class="col-md-6">
							<label for="date_initial">Data de Início da Etapa:</label>
							<input type="date" id="date_initial" name="date_initial"  class="form-control" id="date_initial" placeholder="Data Inicial" required>
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
				</form>
            </div>
            <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">
					<i class="fa fa-fw fa-paper-plane-o"></i>
						Cadastrar
				</button>
            </div>
        </div>
		<!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>