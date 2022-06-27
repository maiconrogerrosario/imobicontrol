<?php

namespace Source\Models\ImobApp;

use Source\Core\Model;
use Source\Core\Session;
use Source\Models\User;
use Source\Models\Customer;
/**
 * Class AppInvoice
 * @package Source\Models\ImobApp
 */
class AppInvoice extends Model
{
    /** @var null|int */
    public $wallet;
	public $walletfilter;
	public $session;

    /**
     * AppInvoice constructor.
     */
    public function __construct()
    {
        parent::__construct(
            "app_invoices", ["id"],
            ["application_id","user_id", "wallet_id", "category_id", "description", "type", "value", "due_at", "repeat_when"]
        );

	}

   
	 /**
     * @param User $user
     * @param array $data
     * @return AppInvoice|null
     */
    public function customerInvoice(User $user, array $data, string $walletname, string $value): ?AppInvoice
    {
		
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
		
		$rent_value = str_replace([".", ","], ["", "."], $data["rent_value"]);
		$iptu_value = str_replace([".", ","], ["", "."], $data["iptu_value"]);
		$administration_value = str_replace([".", ","], ["", "."], $data["administration_value"]);
		$condominium_value = str_replace([".", ","], ["", "."], $data["condominium_value"]);	
		$value = $rent_value + $iptu_value + $administration_value + $condominium_value;
				
		$wallet = (new AppWallet())->find("application_id = :application_id AND  wallet = :wallet AND customer_id = :customer_id",
            "application_id={$user->application_id}&wallet={$walletname}&customer_id={$data["customer_id"]}")->fetch();	
			
			
		$mensalidade= "Mensalidade";
		$category = (new AppCategory())->find("application_id = :application_id AND  name = :name",
		"application_id={$user->application_id}&name={$mensalidade}")->fetch();
		
		
		$status = (date($data["date_initial"]) <= date("Y-m-d") ? "paid" : "unpaid");
        $this->user_id = $user->id;
		$this->application_id = $user->application_id;
        $this->wallet_id = $wallet->id;
        $this->category_id = $category->id;
        $this->invoice_of = null;
        $this->description = $walletname;
        $this->type = "income";
        $this->value = $value;
        $this->currency = "BRL";
        $this->due_at = $data["date_initial"];
        $this->repeat_when = "enrollment";
        $this->period = "month";
        $this->enrollments = 12;
        $this->enrollment_of = 1;
        $this->status = $status;
        $this->save();
			
        if ($this->repeat_when == "enrollment") {
            $invoiceOf = $this->id;
            for ($enrollment = 1; $enrollment < $this->enrollments; $enrollment++) {
                $this->id = null;
                $this->invoice_of = $invoiceOf;
                $this->due_at = date("Y-m-d", strtotime($data["date_initial"] . "+{$enrollment}month"));
                $this->status = (date($this->due_at) <= date("Y-m-d") ? "paid" : "unpaid");
                $this->enrollment_of = $enrollment + 1;
                $this->save();
            }
        }

        return $this;
		
    }
	
	 /**
     * @param User $user
     * @param array $data
     * @return AppInvoice|null
     */
    public function ownerTransfer(User $user, array $data, string $walletname): ?AppInvoice
    {
		
		$rent_value = str_replace([".", ","], ["", "."], $data["rent_value"]);
		$iptu_value = str_replace([".", ","], ["", "."], $data["iptu_value"]);
		$administration_value = str_replace([".", ","], ["", "."], $data["administration_value"]);
		$condominium_value = str_replace([".", ","], ["", "."], $data["condominium_value"]);	
		$value = $rent_value + $iptu_value;
		
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

		$wallet = (new AppWallet())->find("application_id = :application_id AND  wallet = :wallet AND customer_id = :customer_id",
            "application_id={$user->application_id}&wallet={$walletname}&customer_id={$data["customer_id"]}")->fetch();	
		
		$transfer= "Repasse";
		$category = (new AppCategory())->find("application_id = :application_id AND  name = :name",
		"application_id={$user->application_id}&name={$transfer}")->fetch();
		
		
		$status = (date($data["date_initial"]) <= date("Y-m-d") ? "paid" : "unpaid");
        $this->user_id = $user->id;
		$this->application_id = $user->application_id;
        $this->wallet_id = $wallet->id;
        $this->category_id = $category->id;
        $this->invoice_of = null;
        $this->description = $walletname;
        $this->type = "expense";
        $this->value = $value;
        $this->currency = "BRL";
        $this->due_at = $data["date_initial"];
        $this->repeat_when = "enrollment";
        $this->period = "month";
        $this->enrollments = 12;
        $this->enrollment_of = 1;
        $this->status = $status;
        $this->save();
			
        if ($this->repeat_when == "enrollment") {
            $invoiceOf = $this->id;
            for ($enrollment = 1; $enrollment < $this->enrollments; $enrollment++) {
                $this->id = null;
                $this->invoice_of = $invoiceOf;
                $this->due_at = date("Y-m-d", strtotime($data["date_initial"] . "+{$enrollment}month"));
                $this->status = (date($this->due_at) <= date("Y-m-d") ? "paid" : "unpaid");
                $this->enrollment_of = $enrollment + 1;
                $this->save();
            }
        }

        return $this;
		
    }
	
	
	
	
	/**
     * @param User $user
     * @param array $data
     * @return AppInvoice|null
     */
    public function importExcel(User $user, array $spreadsheetdata, string $walletid): ?AppInvoice
    {

        $spreadsheetdata = filter_var_array($spreadsheetdata, FILTER_SANITIZE_STRIPPED);
		
		if (!empty($spreadsheetdata)) {
		
			$wallet = (new AppWallet())->findById($walletid);
			
			if (!$wallet) {
				$this->message->error("A carteira que você informou não existe");
				return null;
			}
		
			$dateformat = $spreadsheetdata["due_at"];
			$due_at = date('Y-m-d', strtotime($dateformat));
			$status = (date($due_at) <= date("Y-m-d") ? "paid" : "unpaid");
			
			
			if ($spreadsheetdata["type"] == "RECEITA") {
				$type = "income";
			
			} else if ($spreadsheetdata["type"] == "DESPESA") {
				$type = "expense";
			}
			
			$category = (new AppCategory())->findByName($spreadsheetdata["category"], $user->application_id);
			
			//PREMIUM RESOURCE
			//$subscribe = (new AppSubscription())->find("application_id = :application_id  AND status != :status",
			//  "application_id={$user->application_id}&status=canceled"); //

			// if (!$wallet->free && !$subscribe->count()) {
			//    $this->message->error("É preciso assinar para lançar nesta carteira");
			//   return null;
			//}
			
			$this->user_id = $user->id;
			$this->application_id = $user->application_id;
			$this->wallet_id = $wallet->id;
			$this->category_id = $category->id;
			$this->invoice_of = null;
			$this->description = $spreadsheetdata["description"];
			$this->type = $type;
			$this->value = $spreadsheetdata["value"];
			$this->currency = "BRL";
			$this->due_at = $due_at;
			$this->repeat_when = "single";
			$this->period = "month";
			$this->enrollments = 1;
			$this->enrollment_of = 1;
			$this->status = $status;

			if (!$this->save()) {
				return null;
			}
		}	
        return $this;
    }
	
	
	/**
     * @param User $user
     * @param string $type
     * @param array|null $filter
     * @param int|null $limit
     * @return array|null
     */
    public function filter(User $user, string $type, ?array $filter, ?int $limit = null): ?array
    {
        $status = (!empty($filter["status"]) && $filter["status"] == "paid" ? "AND status = 'paid'" : (!empty($filter["status"]) && $filter["status"] == "unpaid" ? "AND status = 'unpaid'" : null));
        $category = (!empty($filter["category"]) && $filter["category"] != "all" ? "AND category_id = '{$filter["category"]}'" : null);

        $due_year = (!empty($filter["date"]) ? explode("-", $filter["date"])[1] : date("Y"));
        $due_month = (!empty($filter["date"]) ? explode("-", $filter["date"])[0] : date("m"));
        $due_at = "AND (year(due_at) = '{$due_year}' AND month(due_at) = '{$due_month}')";

        $due = $this->find(
            "application_id = :application_id AND type = :type {$status} {$category} {$due_at} {$this->wallet}",
            "application_id={$user->application_id}&type={$type}"
        )->order("day(due_at) ASC");

        if ($limit) {
            $due->limit($limit);
        }

        return $due->fetch(true);
    }

    /**
     * @return mixed|Model|null
     */
    public function wallet()
    {
        return (new AppWallet())->findById($this->wallet_id);
    }

    /**
     * @return AppCategory
     */
    public function category(): AppCategory
    {
        return (new AppCategory())->findById($this->category_id);
    }

    /**
     * @param User $user
     * @return object
     */
    public function balance(User $user): object
    {
        $balance = new \stdClass();
        $balance->income = 0;
        $balance->expense = 0;
        $balance->wallet = 0;
        $balance->balance = "positive";

        $find = $this->find("application_id = :application_id AND status = :status",
            "application_id={$user->application_id}&status=paid",
            "
                (SELECT SUM(value) FROM app_invoices WHERE application_id = :application_id AND status = :status AND type = 'income' {$this->wallet}) AS income,
                (SELECT SUM(value) FROM app_invoices WHERE application_id = :application_id AND status = :status AND type = 'expense' {$this->wallet}) AS expense
            ")->fetch();

        if ($find) {
            $balance->income = abs($find->income);
            $balance->expense = abs($find->expense);
            $balance->wallet = $balance->income - $balance->expense;
            $balance->balance = ($balance->wallet >= 1 ? "positive" : "negative");
        }

        return $balance;
    }
	

    /**
     * @param AppWallet $wallet
     * @return object
     */
    public function balanceWallet(AppWallet $wallet): object
    {
        $balance = new \stdClass();
        $balance->income = 0;
        $balance->expense = 0;
        $balance->wallet = 0;
        $balance->balance = "positive";

        $find = $this->find("application_id = :application_id AND status = :status",
            "application_id={$wallet->application_id}&status=paid",
            "
                (SELECT SUM(value) FROM app_invoices WHERE application_id = :application_id AND wallet_id = {$wallet->id} AND status = :status AND type = 'income') AS income,
                (SELECT SUM(value) FROM app_invoices WHERE application_id = :application_id AND wallet_id = {$wallet->id} AND status = :status AND type = 'expense') AS expense
				
            ")->fetch();

        if ($find) {
            $balance->income = abs($find->income);
            $balance->expense = abs($find->expense);
            $balance->wallet = $balance->income - $balance->expense;
            $balance->balance = ($balance->wallet >= 1 ? "positive" : "negative");
        }

        return $balance;
    }

    /**
     * @param User $user
     * @param int $year
     * @param int $month
     * @param string $type
     * @return object|null
     */
    public function balanceMonth(User $user, int $year, int $month, string $type): ?object
    {
        $onpaid = $this->find(
            "application_id = :application_id",
            "application_id={$user->application_id}&type={$type}&year={$year}&month={$month}",
            "
                (SELECT SUM(value) FROM app_invoices WHERE application_id = :application_id AND type = :type AND year(due_at) = :year AND month(due_at) = :month AND status = 'paid' {$this->wallet}) AS paid,
                (SELECT SUM(value) FROM app_invoices WHERE application_id = :application_id AND type = :type AND year(due_at) = :year AND month(due_at) = :month AND status = 'unpaid' {$this->wallet}) AS unpaid
            "
        )->fetch();

		
		
        if (!$onpaid) {
            return null;
        }

        return (object)[
            "paid" => str_price(($onpaid->paid ?? 0)),
            "unpaid" => str_price(($onpaid->unpaid ?? 0))
        ];
    }

    /**
     * @param User $user
     * @return object
     */
    public function chartData(User $user): object
    {
        $dateChart = [];
        for ($month = -4; $month <= 0; $month++) {
            $dateChart[] = date("m/Y", strtotime("{$month}month"));
        }

        $chartData = new \stdClass();
        $chartData->categories = "'" . implode("','", $dateChart) . "'";
        $chartData->expense = "0,0,0,0,0";
        $chartData->income = "0,0,0,0,0";

        $chart = (new AppInvoice())
            ->find("application_id = :application_id AND status = :status AND due_at >= DATE(now() - INTERVAL 4 MONTH) GROUP BY year(due_at) ASC, month(due_at) ASC",
                "application_id={$user->application_id}&status=paid",
                "
                    year(due_at) AS due_year,
                    month(due_at) AS due_month,
                    DATE_FORMAT(due_at, '%m/%Y') AS due_date,
                    (SELECT SUM(value) FROM app_invoices WHERE application_id = :application_id AND status = :status AND type = 'income' AND year(due_at) = due_year AND month(due_at) = due_month {$this->wallet}) AS income,
                    (SELECT SUM(value) FROM app_invoices WHERE application_id = :application_id AND status = :status AND type = 'expense' AND year(due_at) = due_year AND month(due_at) = due_month {$this->wallet}) AS expense
                "
            )->limit(5)->fetch(true);

        if ($chart) {
            $chartCategories = [];
            $chartExpense = [];
            $chartIncome = [];

            foreach ($chart as $chartItem) {
                $chartCategories[] = $chartItem->due_date;
                $chartExpense[] = $chartItem->expense;
                $chartIncome[] = $chartItem->income;
            }

            $chartData->categories = "'" . implode("','", $chartCategories) . "'";
            $chartData->expense = implode(",", array_map("abs", $chartExpense));
            $chartData->income = implode(",", array_map("abs", $chartIncome));
        }


		
        return $chartData;
    }

	/**
     * @param User $user
     * @param string $type
     * @param array|null $filter
     * @param int|null $limit
     * @return array|null
     */
    public function filterInvoices(User $user, ?array $filter, ?int $limit = null, ?int $offset = null): ?array
    {
		
		$status = null;
		$type = null;
		
		if (!empty($filter["type"]) && $filter["type"] == "incomepaid"){
			
			$status = "AND status = 'paid'";
			$type = "AND type = 'income'";
	
		}
		else if (!empty($filter["type"]) && $filter["type"] == "incomeunpaid"){
		
			$status = "AND status = 'unpaid'";
			$type = "AND type = 'income'";
		
		}else if (!empty($filter["type"]) && $filter["type"] == "expensepaid"){
			
			$status = "AND status = 'paid'";
			$type = "AND type = 'expense'";
			
		}else if (!empty($filter["type"]) && $filter["type"] == "expenseunpaid"){
			
			$status = "AND status = 'unpaid'";
			$type = "AND type = 'expense'";
		
		}else if (!empty($filter["type"]) && $filter["type"] == "all"){
			
			$status = null;
			$type = null;
				
		}
		
		if (!empty($filter["id"])) {
			$walletid = "AND wallet_id = {$filter["id"]}";
			
		} else {
			$walletid = $this->wallet;
		
		}
		
		$category = (!empty($filter["category"]) && $filter["category"] != "all" ? "AND category_id = '{$filter["category"]}'" : null);
		

		$due_at = (!empty($filter["dateinitial"]) && $filter["dateinitial"] != "all" ? "AND due_at >=  '{$filter["dateinitial"]}' AND due_at <= '{$filter["datefinal"]}'" : null);
		
		
		
		$due = $this->find(
			"application_id = :application_id {$type}{$status} {$category} {$due_at} {$walletid}",
			"application_id={$user->application_id}"
		)->order("due_at asc");

	

		if ($limit) {
            $due->limit($limit);
        }
		
		if ($limit) {
            $due->offset($offset);
        }

		return $due->fetch(true);
			
    }
	
	public function filterInvoicesCount(User $user, ?array $filter)
    {
		
		$status = null;
		$type = null;
		
		if (!empty($filter["type"]) && $filter["type"] == "incomepaid"){
			
			$status = "AND status = 'paid'";
			$type = "AND type = 'income'";
	
		}
		else if (!empty($filter["type"]) && $filter["type"] == "incomeunpaid"){
		
			$status = "AND status = 'unpaid'";
			$type = "AND type = 'income'";
		
		}else if (!empty($filter["type"]) && $filter["type"] == "expensepaid"){
			
			$status = "AND status = 'paid'";
			$type = "AND type = 'expense'";
			
		}else if (!empty($filter["type"]) && $filter["type"] == "expenseunpaid"){
			
			$status = "AND status = 'unpaid'";
			$type = "AND type = 'expense'";
		
		}else if (!empty($filter["type"]) && $filter["type"] == "all"){
			
			$status = null;
			$type = null;
				
		}
		
		if (!empty($filter["id"])) {
			$walletid = "AND wallet_id = {$filter["id"]}";
			
		} else {
			$walletid = $this->wallet;
		
		}
		
		$category = (!empty($filter["category"]) && $filter["category"] != "all" ? "AND category_id = '{$filter["category"]}'" : null);
		

		$due_at = (!empty($filter["dateinitial"]) && $filter["dateinitial"] != "all" ? "AND due_at >=  '{$filter["dateinitial"]}' AND due_at <= '{$filter["datefinal"]}'" : null);
		
		
		
		$due = $this->find(
			"application_id = :application_id {$type}{$status} {$category} {$due_at} {$walletid}",
			"application_id={$user->application_id}"
		)->order("due_at desc");

		return $due;
			
    }
	

	

	public function getWallet(): ?AppWallet
	{ 

		if ($this->wallet_id) {
			
            return (new AppWallet())->findById($this->wallet_id
			);
			
        }
		
        return null;

	}
	
	
	
	
	
	
	
	
  
}