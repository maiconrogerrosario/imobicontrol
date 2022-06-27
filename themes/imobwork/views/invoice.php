<div class="cafecontrol_modal_box cafecontrol_modal_<?= $type; ?>">
    <p class="title"><i class="fa fa-i fa-calendar-check-o"></i>Nova <?= ($type == 'income' ? "Receita" : "Despesa"); ?>:</p>
    <form class="cafecontrol_form" action="<?= url("/work/launch"); ?>" method="post">
        <input type="hidden" name="currency" value="BRL"/>
        <input type="hidden" name="type" value="<?= $type; ?>"/>
        <label>
            <span class="field"><i class="fa fa-i fa-leanpub"></i>Descrição:</span>
            <input class="radius" type="text" name="description" placeholder="Ex: Aluguel" required/>
        </label>
        <div class="label_group">
            <label>
                <span class="field"><i class="fa fa-i fa-money"></i>Valor:</span>
                <input class="radius mask-money" type="text" name="value" required/>
            </label>

            <label>
                <span class="field"><i class="fa fa-i fa-filter"></i>Data:</span>
                <input class="radius masc-date" type="date" name="due_at" required/>
            </label>
        </div>

        <div class="label_group">
            <label>
                <span class="field"><i class="fa fa-i fa-briefcase"></i>Carteira:</span>
                <select name="wallet_id">
                    <?php foreach ($wallets as $wallet): ?>
                        <option value="<?= $wallet->id; ?>">&ofcir; <?= $wallet->wallet ?></option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label>
                <span class="field"><i class="fa fa-i fa-filter"></i>Categoria:</span>
                <select name="category_id">
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category->id; ?>">&ofcir; <?= $category->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
        </div>

        <div class="label_check">
            <p class="field"><i class="fa fa-i fa-exchange"></i>Repetição:</p>
            <label class="check"
                   data-checkbox="true"
                   data-slideup=".cafecontrol_modal_<?= $type; ?> .repeate_item_fixed, .cafecontrol_modal_<?= $type; ?> .repeate_item_enrollment">
                <input type="radio" name="repeat_when" value="single" checked> Única
            </label>

            <label data-checkbox="true"
                   data-slideup=".cafecontrol_modal_<?= $type; ?> .repeate_item_enrollment"
                   data-slidedown=".cafecontrol_modal_<?= $type; ?> .repeate_item_fixed">
                <input type="radio" name="repeat_when" value="fixed"> Fixa
            </label>

            <label data-checkbox="true"
                   data-slideup=".cafecontrol_modal_<?= $type; ?> .repeate_item_fixed"
                   data-slidedown=".cafecontrol_modal_<?= $type; ?> .repeate_item_enrollment">
                <input type="radio" name="repeat_when" value="enrollment"> Parcelada
            </label>
        </div>

        <label class="repeate_item repeate_item_fixed" style="display: none">
            <select name="period">
                <option value="month">&ofcir; Mensal</option>
                <option value="year">&ofcir; Anual</option>
            </select>
        </label>

        <label class="repeate_item repeate_item_enrollment" style="display: none">
            <input class="radius" type="number" value="1" min="1" max="420" placeholder="1 parcela" name="enrollments"/>
        </label>

        <button class="btn radius transition">
		<i class="fa fa-i fa-check-square-o"></i>Lançar <?= ($type == 'income' ? "Receita" : "Despesa"); ?></button>
    </form>
</div>