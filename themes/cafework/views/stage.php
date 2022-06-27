<div class="balance">
    <p class="desc">
		 <b class="cafecontrol_invoice_link transition">
            <a title=""
               href="">
                <?= $stage->stage_name; ?>
            </a></b>
		
		<?php $work = $stage->getWork(); ?>
		
		<b class="cafecontrol_invoice_link transition"><a title=""
               href="" class="date" style="color: var(--color-black);"><?= "Obra:  {$work->name}" ?></a></b>
		
        <?php
			$nowinitial = new DateTime();
			$dueinitial = new DateTime($stage->date_initial);
			$expireinitial = $nowinitial->diff($dueinitial);
			$start = ($expireinitial->days == 1 ? "" : "start");

			if (!$expireinitial->days && $expireinitial->invert):?>
				<span class="date" style="color: var(--color-yellow);">Inicia Hoje</span>
			<?php elseif (!$expireinitial->invert): ?>
				<span class="date">Inicia em <?= ($expireinitial->days <= 1 ? "1 dia" : "{$expireinitial->days} dias") ?></span>
			<?php else: ?>
				<span class="date"
					style="color: var(--color-red);">Executando Há <?= ($expireinitial->days <= 1 ? "1 dia" : "{$expireinitial->days} dias"); ?></span>
			<?php endif; ?>
		
		<?php
			$nowfinal = new DateTime($stage->date_initial);
			$duefinal = new DateTime($stage->date_final);
			$expirefinal = $nowfinal->diff($duefinal);
			$end = ($expirefinal->days == 1 ? "" : "end");

			if (!$expirefinal->days && $expirefinal->invert):?>
				<span class="date" style="color: var(--color-yellow);">Inicia Hoje</span>
			<?php elseif (!$expirefinal->invert): ?>
				<span class="date">Finaliza em <?= ($expirefinal->days <= 1 ? "1 dia" : "{$expirefinal->days} dias") ?></span>
			<?php else: ?>
				<span class="date"
					style="color: var(--color-red);">Finaliza Há <?= ($expirefinal->days <= 1 ? "1 dia" : "{$expirefinal->days} dias"); ?></span>
			<?php endif; ?>
	
    </p>
	
    <p class="price">

      
    </p>
</div>