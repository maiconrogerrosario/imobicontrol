<div class="cafecontrol_modal" data-modalclose="true">
    <!--INCOME-->
    <?php
    $user = user();
    $wallets = (new \Source\Models\ImobApp\AppWallet())
        ->find("application_id = :application_id", "application_id={$user->application_id}", "id, wallet")
        ->order("wallet")
        ->fetch(true);
		
   
	
	$v->insert("views/invoice", [
        "type" => "income",
        "wallets" => $wallets,
        "categories" => (new \Source\Models\ImobApp\AppCategory())
			->find("application_id = :aplication AND type = :t", "t=income&aplication={$user->application_id}", "id, name")
            ->order("order_by, name")
            ->fetch(true)
    ]);
	
    $v->insert("views/invoice", [
        "type" => "expense",
        "wallets" => $wallets,
        "categories" => (new \Source\Models\ImobApp\AppCategory())
			->find("application_id = :aplication AND type = :t", "t=expense&aplication={$user->application_id}", "id, name")
            ->order("order_by, name")
            ->fetch(true)
    ]);
	
	

   
	
	
    ?>
	
	

    <!--SUPPORT-->
    <div class="cafecontrol_modal_box cafecontrol_modal_contact">
        <p class="title"><i class="fa fa-i fa-calendar-minus-o"></i>Fale conosco:</p>
        <form class="cafecontrol_form" action="<?= url("/work/support"); ?>" method="post">
            <label>
                <span class="field"><i class=" fa fa-i fa-life-ring"></i> O que precisa?</span>
                <select name="subject" required>
                    <option value="Pedido de suporte">&ofcir; Preciso de suporte</option>
                    <option value="Nova sugestão">&ofcir; Enviar uma sugestão</option>
                    <option value="Nova reclamação">&ofcir; Enviar uma reclamação</option>
                    <option value="Mudança de plano">&ofcir; Mudar meu plano</option>
                    <option value="Pedido de cancelamento">&ofcir; Cancelar assinatura</option>
                </select>
            </label>

            <label>
                <span class="field"><i class="fa fa-i fa-comments-o"></i>Mensagem:</span>
                <textarea class="radius" name="message" rows="4" required></textarea>
            </label>

            <button class="btn radius transition"><i class="fa fa-i fa-paper-plane-o"></i> Enviar Agora</button>
        </form>
    </div>
</div>

 

