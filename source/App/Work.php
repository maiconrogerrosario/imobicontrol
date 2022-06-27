<?php

namespace Source\App;

use Source\Core\Controller;
use Source\Core\Session;
use Source\Core\View;
use Source\Models\Auth;
use Source\Models\CafeApp\AppCategory;
use Source\Models\CafeApp\AppInvoice;
use Source\Models\CafeApp\AppOrder;
use Source\Models\CafeApp\AppPlan;
use Source\Models\CafeApp\AppSubscription;
use Source\Models\CafeApp\AppWallet;
use Source\Models\Post;
use Source\Models\Report\Access;
use Source\Models\Report\Online;
use Source\Models\User;
use Source\Support\Email;
use Source\Support\QRC;
use Source\Support\PDF;
use Source\Support\Thumb;
use Source\Support\Upload;
use Source\Support\Pager;
use Source\Models\Reservation;
use Source\Models\Status;
use Source\Models\Payment;
use Source\Models\Owner;
use Source\Models\Property;
use Source\Models\Customer;
use Source\Models\Contract;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Class Work
 * @package Source\Work
 */
class Work extends Controller
{
    /** @var User */
    private $user;
	private $project;

    /**
     * Work constructor.
     */
    public function __construct()
    {
        parent::__construct(__DIR__ . "/../../themes/" . CONF_VIEW_WORK . "/");

        if (!$this->user = Auth::user()) {
            $this->message->warning("Efetue login para acessar o APP.")->flash();
            redirect("/entrar");
        }

        
		 //(new AppWallet())->start($this->user);
         //(new AppInvoice())->fixed($this->user, 3);

        //UNCONFIRMED EMAIL
        if ($this->user->status != "confirmed") {
            $session = new Session();
            if (!$session->has("appconfirmed")) {
                $this->message->info("IMPORTANTE: Acesse seu e-mail para confirmar seu cadastro e ativar todos os recursos.")->flash();
                $session->set("appconfirmed", true);
                (new Auth())->register($this->user);
            }
        }
    }

	 /**
     * @param array|null $data
     */
    public function dash(?array $data): void
    {
        if (!empty($data["wallet"])) {
            $session = new Session();

            if ($data["wallet"] == "all") {
                $session->unset("walletfilter");
                echo json_encode(["filter" => true]);
                return;
            }
			
			if ($data["wallet"] == "anual") {
                $session->set("walletfilter", "anual");
                echo json_encode(["filter" => true]);
                return;
            }
			
			if ($data["wallet"] == "mensal") {
                $session->set("walletfilter", "mensal");
                echo json_encode(["filter" => true]);
                return;
            }
		
            $wallet = filter_var($data["wallet"], FILTER_VALIDATE_INT);
			
            $getWallet = (new AppWallet())->find("application_id = :application_id AND id = :id",
                "application_id={$this->user->application_id}&id={$wallet}")->count();

            if ($getWallet) {
                $session->set("walletfilter", $wallet);
            }

            echo json_encode(["filter" => true]);
            return;
        }

        //CHART UPDATE
        $chartData = (new AppInvoice())->chartData($this->user);
        $categories = str_replace("'", "", explode(",", $chartData->categories));
        $json["chart"] = [
            "categories" => $categories,
            "income" => array_map("abs", explode(",", $chartData->income)),
            "expense" => array_map("abs", explode(",", $chartData->expense))
        ];

        //WALLET
        $wallet = (new AppInvoice())->balance2($this->user);
        $wallet->wallet = str_price($wallet->wallet);
        $wallet->status = ($wallet->balance == "positive" ? "gradient-green" : "gradient-red");
        $wallet->income = str_price($wallet->income);
        $wallet->expense = str_price($wallet->expense);
		$wallet->incomeunpaid = str_price($wallet->incomeunpaid);
        $wallet->expenseunpaid = str_price($wallet->expenseunpaid);
		$wallet->projectcost = str_price($wallet->projectcost);
		
        $json["wallet"] = $wallet;
        echo json_encode($json);
    }

    /**
     * APP HOME
     */
    public function home(): void
    {
      
        //CHART
        //$chartData = (new AppInvoice())->chartData($this->user);
        //END CHART
		  
        /*$income = (new AppInvoice())
            ->find("application_id = :application_id AND type = 'income' AND status = 'unpaid' AND date(due_at) <= date(now() + INTERVAL 1 MONTH) {$whereWallet}",
                "application_id={$this->user->application_id}")
            ->order("due_at")
            ->fetch(true);

        $expense = (new AppInvoice())
            ->find("application_id = :application_id AND type = 'expense' AND status = 'unpaid' AND date(due_at) <= date(now() + INTERVAL 1 MONTH) {$whereWallet}",
                "application_id={$this->user->application_id}")
            ->order("due_at")
            ->fetch(true);
        //END INCOME && EXPENSE

        //WALLET
        $wallet = (new AppInvoice())->balance2($this->user);
        //END WALLET

        //POSTS
        $posts = (new Post())->findPost()->limit(3)->order("post_at DESC")->fetch(true);
        //END POSTS*/

        echo $this->view->render("home", [
            
            /*"chart" => $chartData,
            "income" => $income,
            "expense" => $expense,
            "wallet" => $wallet,
            "posts" => $posts,*/
			
			
        ]);
	    
    }
	

    /**
     * APP LOGOUT
     */
    public function logout(): void
    {
        $this->message->info("Você saiu com sucesso " . Auth::user()->first_name . ". Volte logo :)")->flash();

        Auth::logout();
        redirect("/entrar");
    }
	
	 /**
     * APP LOGOUT Work
     */
    public function workLogout(): void
    {
        $this->message->info("Você saiu com sucesso " . Auth::user()->first_name . ". Volte logo :)")->flash();

        Auth::logout();
        redirect("/work-entrar");
    }
	
	
	/**
     * APP Customer
     */
    public function customer(?array $data): void
    {
		$customers = (new Customer())->find("application_id = :application","application={$this->user->application_id}");	
        $all = "all";
        $pager = new Pager(url("/work/customer/{$all}/"));
        $pager->pager($customers->count(),20, (!empty($data["page"]) ? $data["page"] : 1));
		
		//delete
        if (!empty($data["action"]) && $data["action"] == "delete") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $customerDelete = (new Customer())->findById($data["customer_id"]);

            if (!$customerDelete) {
                $this->message->error("Você tentnou deletar um fornecedor que não existe")->flash();
                echo json_encode(["redirect" => url("/work/customer")]);
                return;
				
            }
  
            $customerDelete->destroy();
            $this->message->success("O Cliente foi excluído com sucesso...")->flash();
            echo json_encode(["redirect" => url("/work/customer")]);
            return;	
			
        }
		
        echo $this->view->render("customer", [
          
            "customers" => $customers->order("id")->limit($pager->limit())->offset($pager->offset())->fetch(true),
			"paginator" => $pager->render(),
        ]);

    }

	/**
     * Customer Add
     */
    public function customerAdd(?array $data): void
    {
		//create
        if (!empty($data["action"]) && $data["action"] == "create"){
			
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
			$customerCreate = new Customer();	
			$customerCreate->user_id = $this->user->id;					
            $customerCreate->application_id = $this->user->application_id;				
			$customerCreate->name = $data["name"];
			$customerCreate->document = preg_replace("/[^0-9]/", "", $data["document"]);
			$customerCreate->email = $data["email"];
			$customerCreate->phone1 = preg_replace("/[^0-9]/", "", $data["phone1"]);
			$customerCreate->mobile = preg_replace("/[^0-9]/", "", $data["mobile"]);
		
				if (!$customerCreate->save()) {

					$json["message"] = $customerCreate->message()->render();
					echo json_encode($json);
					return;	
				}
				
			$json["message"] = $this->message->success("Cadastro Realizado com sucesso!")->render();
            echo json_encode($json);
            return;
          
        }
		
      
        echo $this->view->render("customer-add", [
          
        ]);
	
    }
	
	/**
     * Customer Edit
     */
    public function customerEdit(?array $data): void
    {
		 //update
        if (!empty($data["action"]) && $data["action"] == "update") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $customerUpdate = (new Customer())->findById($data["customer_id"]);			
			$customerUpdate->name = $data["name"];
			$customerUpdate->document = preg_replace("/[^0-9]/", "", $data["document"]);
			$customerUpdate->email = $data["email"];
			$customerUpdate->phone1 = preg_replace("/[^0-9]/", "", $data["phone1"]);
			$customerUpdate->mobile = preg_replace("/[^0-9]/", "", $data["mobile"]);
			

            if (!$customerUpdate->save()) {
                $json["message"] = $customerUpdate->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Cliente atualizado com sucesso...")->flash();
            echo json_encode(["reload" => true]);
            return;
        }
		
        $customerEdit = null;
        if (!empty($data["customer_id"])) {
            $customerId = filter_var($data["customer_id"], FILTER_VALIDATE_INT);
            $customerEdit = (new Customer())->findById($customerId);
        }

       
	
        echo $this->view->render("customer-edit", [
			"customer" => $customerEdit          
		]);
    }
	
	/**
     * APP Owner
     */
    public function owner(?array $data): void
    {
		$owners = (new Owner())->find("application_id = :application","application={$this->user->application_id}");	
        $all = "all";
        $pager = new Pager(url("/work/owner/{$all}/"));
        $pager->pager($owners->count(),20, (!empty($data["page"]) ? $data["page"] : 1));
		
		//delete
        if (!empty($data["action"]) && $data["action"] == "delete") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $ownerDelete = (new owner())->findById($data["owner_id"]);

            if (!$ownerDelete) {
                $this->message->error("Você tentnou deletar um fornecedor que não existe")->flash();
                echo json_encode(["redirect" => url("/work/owner")]);
                return;
				
            }
  
            $ownerDelete->destroy();
            $this->message->success("O Cliente foi excluído com sucesso...")->flash();
            echo json_encode(["redirect" => url("/work/owner")]);
            return;	
			
        }
		
		

        echo $this->view->render("owner", [
            "owners" => $owners->order("id")->limit($pager->limit())->offset($pager->offset())->fetch(true),
			"paginator" => $pager->render(),
        ]);

    }

	/**
     * owner Add
     */
    public function ownerAdd(?array $data): void
    {
		//create
        if (!empty($data["action"]) && $data["action"] == "create"){
			
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
			$ownerCreate = new Owner();	
			$ownerCreate->user_id = $this->user->id;					
            $ownerCreate->application_id = $this->user->application_id;				
			$ownerCreate->name = $data["name"];
			$ownerCreate->document = preg_replace("/[^0-9]/", "", $data["document"]);
			$ownerCreate->email = $data["email"];
			$ownerCreate->phone1 = preg_replace("/[^0-9]/", "", $data["phone1"]);
			$ownerCreate->mobile = preg_replace("/[^0-9]/", "", $data["mobile"]);
		
				if (!$ownerCreate->save()) {

					$json["message"] = $ownerCreate->message()->render();
					echo json_encode($json);
					return;	
				}
				
			$json["message"] = $this->message->success("Cadastro Realizado com sucesso!")->render();
            echo json_encode($json);
            return;
          
        }
		
        

        echo $this->view->render("owner-add", [
         
        ]);
	
    }
	
	/**
     * owner Edit
     */
    public function ownerEdit(?array $data): void
    {
		 //update
        if (!empty($data["action"]) && $data["action"] == "update") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $ownerUpdate = (new owner())->findById($data["owner_id"]);			
			$ownerUpdate->name = $data["name"];
			$ownerUpdate->document = preg_replace("/[^0-9]/", "", $data["document"]);
			$ownerUpdate->email = $data["email"];
			$ownerUpdate->phone1 = preg_replace("/[^0-9]/", "", $data["phone1"]);
			$ownerUpdate->mobile = preg_replace("/[^0-9]/", "", $data["mobile"]);
			

            if (!$ownerUpdate->save()) {
                $json["message"] = $ownerUpdate->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Cliente atualizado com sucesso...")->flash();
            echo json_encode(["reload" => true]);
            return;
        }
		
        $ownerEdit = null;
        if (!empty($data["owner_id"])) {
            $ownerId = filter_var($data["owner_id"], FILTER_VALIDATE_INT);
            $ownerEdit = (new owner())->findById($ownerId);
        }

        
	
        echo $this->view->render("owner-edit", [
			"owner" => $ownerEdit          
		]);
    }
	
	
	
	
	/**
     * APP property
     */
    public function property(?array $data): void
    {
		$propertys = (new Property())->find("application_id = :application","application={$this->user->application_id}");	
        $all = "all";
        $pager = new Pager(url("/work/property/{$all}/"));
        $pager->pager($propertys->count(),20, (!empty($data["page"]) ? $data["page"] : 1));
		
		//delete
        if (!empty($data["action"]) && $data["action"] == "delete") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $propertyDelete = (new Property())->findById($data["property_id"]);

            if (!$propertyDelete) {
                $this->message->error("Você tentnou deletar um fornecedor que não existe")->flash();
                echo json_encode(["redirect" => url("/work/property")]);
                return;
				
            }
  
            $propertyDelete->destroy();
            $this->message->success("O Cliente foi excluído com sucesso...")->flash();
            echo json_encode(["redirect" => url("/work/property")]);
            return;	
			
        }
		
	

        echo $this->view->render("property", [
            "propertys" => $propertys->order("id")->limit($pager->limit())->offset($pager->offset())->fetch(true),
			"paginator" => $pager->render(),
        ]);

    }

	/**
     * property Add
     */
    public function propertyAdd(?array $data): void
    {

		$owners = (new Owner())->find("application_id = :application","application={$this->user->application_id}");	
		//create
        if (!empty($data["action"]) && $data["action"] == "create"){
			
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
			$propertyCreate = new Property();	
			$propertyCreate->user_id = $this->user->id;					
            $propertyCreate->application_id = $this->user->application_id;	
			$propertyCreate->owner_id = $data["owner_id"];					
			$propertyCreate->address_street = $data["address_street"];
			$propertyCreate->address_number = $data["address_number"];
			$propertyCreate->address_neighborhood = $data["address_neighborhood"];
			$propertyCreate->address_postalcode = preg_replace("/[^0-9]/", "", $data["address_postalcode"]);
			$propertyCreate->address_city = $data["address_city"];
			$propertyCreate->address_state = $data["address_state"];
			$propertyCreate->address_country = $data["address_country"];
		
			if (!$propertyCreate->save()) {

				$json["message"] = $propertyCreate->message()->render();
				echo json_encode($json);
				return;	
			}
				
			$json["message"] = $this->message->success("Cadastro Realizado com sucesso!")->render();
            echo json_encode($json);
            return;
          
        }
		
       

        echo $this->view->render("property-add", [
			"owners" => $owners->order("name")->fetch(true)
        ]);
	
    }
	
	/**
     * property Edit
     */
    public function propertyEdit(?array $data): void
    {
		
		$owners = (new Owner())->find("application_id = :application","application={$this->user->application_id}");	

		 //update
        if (!empty($data["action"]) && $data["action"] == "update") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $propertyUpdate = (new Property())->findById($data["property_id"]);			
			$propertyUpdate->owner_id = $data["owner_id"];					
			$propertyUpdate->address_street = $data["address_street"];
			$propertyUpdate->address_number = $data["address_number"];
			$propertyUpdate->address_neighborhood = $data["address_neighborhood"];
			$propertyUpdate->address_postalcode = preg_replace("/[^0-9]/", "", $data["address_postalcode"]);
			$propertyUpdate->address_city = $data["address_city"];
			$propertyUpdate->address_state = $data["address_state"];
			$propertyUpdate->address_country = $data["address_country"];

            if (!$propertyUpdate->save()) {
                $json["message"] = $propertyUpdate->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Cliente atualizado com sucesso...")->flash();
            echo json_encode(["reload" => true]);
            return;
        }
		
        $propertyEdit = null;
        if (!empty($data["property_id"])) {
            $propertyId = filter_var($data["property_id"], FILTER_VALIDATE_INT);
            $propertyEdit = (new property())->findById($propertyId);
        }

	
        echo $this->view->render("property-edit", [
			"property" => $propertyEdit,
			"owners" => $owners->order("name")->fetch(true),
		]);
    }
	
	
	
	/**
     * Contract List
     */
    public function contract(?array $data): void
    {
		
		$contract = (new Contract())->find("application_id = :application","application={$this->user->application_id}");	
        $all = "all";
        $pager = new Pager(url("/work/contract/{$all}/"));
        $pager->pager($contract->count(),20, (!empty($data["page"]) ? $data["page"] : 1));
		
		//delete
        if (!empty($data["action"]) && $data["action"] == "delete") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $contractDelete = (new Contract())->findById($data["property_id"]);

            if (!$contractDelete) {
                $this->message->error("Você tentnou deletar um fornecedor que não existe")->flash();
                echo json_encode(["redirect" => url("/work/contract")]);
                return;
				
            }
  
            $propertyDelete->destroy();
            $this->message->success("O Cadastro foi excluido com sucesso")->flash();
            echo json_encode(["redirect" => url("/work/contract")]);
            return;	
			
        }
		
	
        echo $this->view->render("contract", [
    
            "contract" => $contract->order("id")->limit($pager->limit())->offset($pager->offset())->fetch(true),
			"paginator" => $pager->render(),
        ]);
		

       

    }

	/**
     * Contract Add
     */
    public function contractAdd(?array $data): void
    {
		
		$owners = (new Owner())->find("application_id = :application","application={$this->user->application_id}");	
		$customers = (new Customer())->find("application_id = :application","application={$this->user->application_id}");	
		$property = (new Property())->find("application_id = :application","application={$this->user->application_id}");	


        if (!empty($data["action"]) && $data["action"] == "create"){
			
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
			$contractCreate = new Contract();	
			$contractCreate->user_id = $this->user->id;					
            $contractCreate->application_id = $this->user->application_id;				
			$contractCreate->property_id = $data["property_id"];
			$contractCreate->owner_id = $data["owner_id"];
			$contractCreate->customer_id = $data["customer_id"];
			$contractCreate->number_contract = $data["number_contract"];
			$contractCreate->date_initial = $data["date_initial"];
			$contractCreate->date_final = $data["date_final"];
			$contractCreate->status = $data["status"];
			$contractCreate->rent_value = str_replace([".", ","], ["", "."], $data["rent_value"]);
			$contractCreate->iptu_value = str_replace([".", ","], ["", "."], $data["iptu_value"]);
			$contractCreate->administration_value = str_replace([".", ","], ["", "."], $data["administration_value"]);
			$contractCreate->condominium_value = str_replace([".", ","], ["", "."], $data["condominium_value"]);
			
			$rent_value = str_replace([".", ","], ["", "."], $data["rent_value"]);
			$iptu_value = str_replace([".", ","], ["", "."], $data["iptu_value"]);
			$administration_value = str_replace([".", ","], ["", "."], $data["administration_value"]);
			$condominium_value = str_replace([".", ","], ["", "."], $data["condominium_value"]);	
			$value_total = ($rent_value + $iptu_value + $administration_value + $condominium_value)*12;
			
			
			$contractCreate->value_total = $value_total;		
			$contractCreate->save();
			
			$customer = (new Customer())->findById($data["customer_id"]);	

			$walletName = "{$customer->name}" ." "."{$data["number_contract"]}";
			
			$wallet = new AppWallet();		
			$wallet->user_id = $this->user->id;
			$wallet->application_id = $this->user->application_id;
			$wallet->contract_id = $data["customer_id"];
			$wallet->wallet = $walletName;
			$wallet->save();
			
			
			$invoice = new AppInvoice();
			$invoice->customerInvoice($this->user, $data, $walletName, $value_total);
			$invoice->ownerTransfer($this->user, $data, $walletName, $value_total);

		
			
				if (!$contractCreate->save()) {
						
					$json["message"] = $contractCreate->message()->render();
					echo json_encode($json);
					return;	
				}
				
			$json["message"] = $this->message->success("Cadastro Realizado com sucesso!")->render();
            echo json_encode($json);
            return;
          
        }
		
       

        echo $this->view->render("contract-add", [
         
			"owners" => $owners->order("name")->fetch(true),
			"customers" => $customers->order("name")->fetch(true),
			"property" => $property->order("name")->fetch(true)
			
			
        ]);
	
    }
	
	/**
     * Contract Edit
     */
    public function contractEdit(?array $data): void
    {
		 /*update
        if (!empty($data["action"]) && $data["action"] == "update") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $customerUpdate = (new Customer())->findById($data["customer_id"]);			
			$customerUpdate->name = $data["name"];
			$customerUpdate->document = preg_replace("/[^0-9]/", "", $data["document"]);
			$customerUpdate->email = $data["email"];
			$customerUpdate->contact = $data["contact"];
			$customerUpdate->phone1 = preg_replace("/[^0-9]/", "", $data["phone1"]);
			$customerUpdate->mobile = preg_replace("/[^0-9]/", "", $data["mobile"]);
			$customerUpdate->phone2 = preg_replace("/[^0-9]/", "", $data["phone2"]);
			$customerUpdate->fax = preg_replace("/[^0-9]/", "",  $data["fax"]);
			$customerUpdate->address_street = $data["address_street"];
			$customerUpdate->address_number = $data["address_number"];
			$customerUpdate->address_neighborhood = $data["address_neighborhood"];
			$customerUpdate->address_complement = $data["address_complement"];
			$customerUpdate->address_postalcode = preg_replace("/[^0-9]/", "", $data["address_postalcode"]);
			$customerUpdate->address_city = $data["address_city"];
			$customerUpdate->address_state = $data["address_state"];
			$customerUpdate->address_country = $data["address_country"];
			$customerUpdate->observation = $data["observation"];

            if (!$customerUpdate->save()) {
                $json["message"] = $customerUpdate->message()->render();
                echo json_encode($json);
                return;
            }

            $this->message->success("Cliente atualizado com sucesso...")->flash();
            echo json_encode(["reload" => true]);
            return;
        }
		
        $customerEdit = null;
        if (!empty($data["customer_id"])) {
            $customerId = filter_var($data["customer_id"], FILTER_VALIDATE_INT);
            $customerEdit = (new Customer())->findById($customerId);
        }
		
		*/

        $head = $this->seo->render(
			"Atualizar Cliente",
			CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );
	
        echo $this->view->render("contract-edit", [
            "head" => $head,
			"property" => $propertyEdit          
		]);
    }
	
	
    /**
     * APP User
     */
    public function user(?array $data): void
    {	
		$users = (new User())->find("application_id = :application","application={$this->user->application_id}");	
		$search = null;
        $all = ("all");
		$pager = new Pager(url("/work/user/{$all}/"));
        $pager->pager($users->count(), 10, (!empty($data["page"]) ? $data["page"] : 1));
	    
		//delete
        if (!empty($data["action"]) && $data["action"] == "delete") {
			
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $userDelete = (new User())->findById($data["user_id"]);

            if (!$userDelete) {
				
                $this->message->error("Você tentnou deletar um usuário que não existe")->flash();
                echo json_encode(["redirect" => url("/work/user")]);
                return;
				
            }
            $userDelete->destroy();
            $this->message->success("O usuário foi excluído com sucesso...")->flash();
            echo json_encode(["redirect" => url("/work/user")]);

            return;
        }
		
		$head = $this->seo->render(
			"Lista de Usuários",
			CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );
	
        echo $this->view->render("user", [
            "head" => $head,
			"search" => $search,
            "users" => $users->order("first_name desc")->limit($pager->limit())->offset($pager->offset())->fetch(true),
			"paginator" => $pager->render(),
        ]);
			
    }
	
	/**
     * APP User Add
     */
    public function userAdd(?array $data): void
    {
		//create
        if (!empty($data["action"]) && $data["action"] == "create") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $userCreate = new User();
			$userCreate->application_id = $this->user->application_id;
            $userCreate->first_name = $data["first_name"];
            $userCreate->last_name = $data["last_name"];
            $userCreate->email = $data["email"];
            $userCreate->password = $data["password"];
            $userCreate->level = $data["level"];
            $userCreate->genre = $data["genre"];
            $userCreate->datebirth = date_fmt_back($data["datebirth"]);
            $userCreate->document = preg_replace("/[^0-9]/", "", $data["document"]);
            $userCreate->status = $data["status"];

            //upload photo
            if (!empty($_FILES["photo"])) {
                $files = $_FILES["photo"];
                $upload = new Upload();
                $image = $upload->image($files, $userCreate->fullName(), 600);

                if (!$image) {
                    $json["message"] = $upload->message()->render();
                    echo json_encode($json);
                    return;
                }

                $userCreate->photo = $image;
            }

            if (!$userCreate->save()) {
                $json["message"] = $userCreate->message()->render();
                echo json_encode($json);
                return;
            }

            $json["message"] = $this->message->success("Cadastro Realizado com sucesso!")->render();
            echo json_encode($json);
            return;
        }
		
        echo $this->view->render("user-add", [
            "head" => $head,
            "user" => $this->user,
            "photo" => ($this->user->photo() ? image($this->user->photo, 360, 360) :
                theme("/assets/images/avatar.jpg", CONF_VIEW_APP))
        ]);
				
    }
	
	/**
     * APP User Edit
     */
    public function userEdit(?array $data): void
    {
		if (!empty($data["action"]) && $data["action"] == "update") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
			list($d, $m, $y) = explode("/", $data["datebirth"]);
			$userUpdate = (new User())->findById($this->user->id);		
			$userUpdate->first_name = $data["first_name"];
            $userUpdate->last_name = $data["last_name"];
            $userUpdate->email = $data["email"];
            $userUpdate->password = (!empty($data["password"]) ? $data["password"] : $userUpdate->password);
            $userUpdate->level = $data["level"];
            $userUpdate->genre = $data["genre"];
			$userUpdate->datebirth = "{$y}-{$m}-{$d}";		
            $userUpdate->document = preg_replace("/[^0-9]/", "", $data["document"]);
            $userUpdate->status = $data["status"];	
			
			//upload photo
            if (!empty($_FILES["photo"])) {
                if ($userUpdate->photo && file_exists(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$userUpdate->photo}")) {
                    unlink(__DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/{$userUpdate->photo}");
                    (new Thumb())->flush($userUpdate->photo);
                }

                $files = $_FILES["photo"];
                $upload = new Upload();
                $image = $upload->image($files, $userUpdate->fullName(), 600);

                if (!$image) {
                    $json["message"] = $upload->message()->render();
                    echo json_encode($json);
                    return;
                }

                $userUpdate->photo = $image;
            }
			

		        if (!$userUpdate->save()) {	
					$json["message"] = $userUpdate->message()->render();
					echo json_encode($json);	
					return;
				}

            $json["message"] = $this->message->success("Cadastro Realizado com sucesso!")->render();
            echo json_encode($json);
            return;
        }

        $userEdit = null;
		
        if (!empty($data["id"])) {
            $userId = filter_var($data["id"], FILTER_VALIDATE_INT);
            $userEdit = (new User())->findById($userId);
        }

		
        echo $this->view->render("user-edit", [
            "head" => $head,
            "user" => $userEdit

        ]);		  	 
			
    }
	
	
	/**
     * @param array $data
     * @throws \Exception
     */
    public function filter(array $data): void
    {
        $status = (!empty($data["status"]) ? $data["status"] : "all");
        $category = (!empty($data["category"]) ? $data["category"] : "all");
        $date = (!empty($data["date"]) ? $data["date"] : date("m/Y"));

        list($m, $y) = explode("/", $date);
        $m = ($m >= 1 && $m <= 12 ? $m : date("m"));
        $y = ($y <= date("Y", strtotime("+10year")) ? $y : date("Y", strtotime("+10year")));

        $start = new \DateTime(date("Y-m-t"));
        $end = new \DateTime(date("Y-m-t", strtotime("{$y}-{$m}+1month")));
        $diff = $start->diff($end);

        if (!$diff->invert) {
            $afterMonths = (floor($diff->days / 30));
            (new AppInvoice())->fixed($this->user, $afterMonths);
        }
		
		$redirect = ($data["filter"] == "income" ? "receber" : "pagar");
        $json["redirect"] = url("/work/{$redirect}/{$status}/{$category}/{$m}-{$y}");
        echo json_encode($json);
	 
    }

    /**
     * @param array|null $data
     */
    public function income(?array $data): void
    {
       
        $categories = (new AppCategory())
            ->find("application_id = :application AND type = :t", "t=income&application={$this->user->application_id}", "id, name")
            ->order("order_by, name")
            ->fetch("true");

        echo $this->view->render("invoices", [
            "user" => $this->user,
            "type" => "income",
            "categories" => $categories,
            "invoices" => (new AppInvoice())->filter($this->user, "income", ($data ?? null)),
            "filter" => (object)[
                "status" => ($data["status"] ?? null),
                "category" => ($data["category"] ?? null),
				"date" => (!empty($data["date"]) ? str_replace("-", "/", $data["date"]) : null) || ($data["date"] ?? null),
            ]
        ]);
    }

    /**
     * @param array|null $data
     */
    public function expense(?array $data): void
    {
       
        $categories = (new AppCategory())
            ->find("application_id = :application AND type = :t", "t=expense&application={$this->user->application_id}", "id, name")
            ->order("order_by, name")
            ->fetch("true");

        echo $this->view->render("invoices", [
            "user" => $this->user,
            "type" => "expense",
            "categories" => $categories,
            "invoices" => (new AppInvoice())->filter($this->user, "expense", ($data ?? null)),
            "filter" => (object)[
                "status" => ($data["status"] ?? null),
                "category" => ($data["category"] ?? null),
                "date" => (!empty($data["date"]) ? str_replace("-", "/", $data["date"]) : null)
            ]
        ]);
    }
	
	/**
     * @param array|null $data
     */
    public function invoices(?array $data): void
    {
       
		$wallet = new AppWallet();
	
		$categories = (new AppCategory())
            ->find("application_id = :application", "application={$this->user->application_id}", "id, name")
            ->order("order_by, name")
            ->fetch("true");
					
		if (empty($data["type"])) {
			$type = "all";			
		} else {
			$type = $data["type"];
		}	
		
		if (empty($data["category"])) {
			$category = "all";			
		} else {
			$category = $data["category"];
		}	

		if (empty($data["dateinitial"])) {
			$dateinitial = "all";		
		} else {
			$dateinitial = $data["dateinitial"];
		}	

		if (empty($data["datefinal"])) {
			$datefinal = "all";	
		} else {
			$datefinal = $data["datefinal"];
		}	
		
		if (!empty($data["id"])){
			$pager = new Pager(url("/work/faturas/{$data["id"]}/{$type}/{$category}/{$dateinitial}/{$datefinal}/"));
			$count = (new AppInvoice())->filterInvoicesCount($this->user,($data ?? null));
			
		} elseif (empty($data["id"])) {		
			$pager = new Pager(url("/work/faturas/{$type}/{$category}/{$dateinitial}/{$datefinal}/"));
			$count = (new AppInvoice())->filterInvoicesCount($this->user,($data ?? null));	
		}
	
		$pager->pager($count->count(),25,(!empty($data["page"]) ? $data["page"] : 1));

        echo $this->view->render("invoices2", [
            "user" => $this->user,
            "categories" => $categories,
			"wallet" => $wallet,
			"paginator" => $pager->render(),
            "invoices" => (new AppInvoice())->filterInvoices($this->user,($data ?? null),$pager->limit(),$pager->offset()),
            "filter" => (object)[
				"type" => ($data["type"] ?? null),
                "category" => ($data["category"] ?? null),
                "dateinitial" => ($data["dateinitial"] ??  null),
				"datefinal" => ($data["datefinal"] ??  null),
				"id" => ($data["id"] ??  null)
            ]
		
        ]);
    }
	
	

	/**
     * @param array $data
     * @throws \Exception
     */
    public function invoiceFilter(array $data): void
    {
		
		if ((empty($data["dateinitial"]) or $data["dateinitial"] == "all") && (!empty($data["datefinal"]) && $data["datefinal"] != "all")) {	
			$json["message"] = $this->message->error("Escolha Uma Data inicial")->render();
            echo json_encode($json);
            return;	
						
		} elseif ((!empty($data["dateinitial"]) && $data["dateinitial"] != "all") && (empty($data["datefinal"]) or $data["datefinal"] == "all")) {
			$json["message"] = $this->message->error("Escolha Uma Data Final")->render();
            echo json_encode($json);
            return;
		}
		
		if (!empty($data["id"])) {
			$type = (!empty($data["type"]) ? $data["type"] : "all");
			$category = (!empty($data["category"]) ? $data["category"] : "all");
			$dateinitial = (!empty($data["dateinitial"]) ? $data["dateinitial"] : "all");
			$datefinal = (!empty($data["datefinal"]) ? $data["datefinal"] : "all");
			$redirect = "faturas/{$data["id"]}";
			$json["redirect"] = url("/work/{$redirect}/{$type}/{$category}/{$dateinitial}/{$datefinal}");
			echo json_encode($json);
			
		} else if (empty($data["id"])) {
			$type = (!empty($data["type"]) ? $data["type"] : "all");
			$category = (!empty($data["category"]) ? $data["category"] : "all");
			$dateinitial = (!empty($data["dateinitial"]) ? $data["dateinitial"] : "all");
			$datefinal = (!empty($data["datefinal"]) ? $data["datefinal"] : "all");
			$redirect = "faturas";
			$json["redirect"] = url("/work/{$redirect}/{$type}/{$category}/{$dateinitial}/{$datefinal}");
			echo json_encode($json);		
		}
    }
	
	/**
     * @param array|null $data
     */
    public function invoicesImport(?array $data): void
    {
     
		if ($_FILES["cover"]["name"] != '') {
			$allowed_extension = array('xls', 'csv', 'xlsx');
			$file_array = explode(".", $_FILES["cover"]["name"]);
			$file_extension = end($file_array);
			
			if (in_array($file_extension, $allowed_extension)) {
				$file_name = time() . '.' . $file_extension;
				move_uploaded_file($_FILES['cover']['tmp_name'], $file_name);
				$file_type = IOFactory::identify($file_name);
				$reader = IOFactory::createReader($file_type);

				$spreadsheet = $reader->load($file_name);

				unlink($file_name);

				$planilha = $spreadsheet->getActiveSheet()->toArray();

				foreach ($planilha as $row) {
					$insert_data  = [
						'description'		=>	$row[0],
						'type'		        =>	$row[1],
						'category'		    =>	$row[2],
						'due_at'			=>	$row[3],
						'value'		        =>	$row[4],	
						'status'		    =>	$row[5]
					];
					
					
					$invoice = new AppInvoice();
					if (!$invoice->importExcel($this->user, $insert_data, $data["wallet"])) {
						$json["message"] = $invoice->message()->render();
						echo json_encode($json);
						return;
					}
				}
				
				$json["message"] = $this->message->error("Faturas Importadas com Sucesso")->render();
				echo json_encode($json);
				return;	
				
			} else {
				$json["message"] = $this->message->error("Apenas arquivos no formato .xls .csv or .xlsx são permitidos")->render();
				echo json_encode($json);
				return;	
			}
		} else {
			$json["message"] = $this->message->error("Por favor escolha um arquivo")->render();
			echo json_encode($json);
			return;	
		}

		$head = $this->seo->render(
            "Importar Faturas",
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );
		
        echo $this->view->render("invoices-import", [
            "user" => $this->user,
            "categories" => $categories,
			"paginator" => $pager->render(),
            "invoices" => (new AppInvoice())->filterInvoices($this->user,($data ?? null),$pager->limit(),$pager->offset()),
            "filter" => (object)[
				"type" => ($data["type"] ?? null),
                "category" => ($data["category"] ?? null),
                "dateinitial" => ($data["dateinitial"] ??  null),
				"datefinal" => ($data["datefinal"] ??  null),
				"id" => ($data["id"] ??  null)
            ]
		
        ]);
    }

	/**
     * @param array|null $data
     */
    public function cashFlow(?array $data): void
    {
        $head = $this->seo->render(
            "Minhas despesas - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );
		
		//CHART
        $chartDataPeriod = (new AppInvoice())->chartDataPeriod($this->user,($data ??  null ));
	
		$totalbalancewallet = (new AppInvoice())->totalBalanceByPeriod($this->user,($data ??  null ));

		$wallet = (new AppWallet())
            ->find("application_id = :application_id", "application_id={$this->user->application_id}")
            ->order("wallet")
            ->fetch(true);
			
        echo $this->view->render("cashflow", [
            "user" => $this->user,
            "head" => $head,
			"data" => $data,
			"chart" => $chartDataPeriod,
            "totalbalancewallet" => $totalbalancewallet,
			"wallet" => $wallet,
            "filter" => (object)[
                "dateinitial" => ($data["dateinitial"] ??  null),
				"datefinal" => ($data["datefinal"] ??  null)
            ]
		
        ]);
    }
	
	/**
     * @param array $data
     * @throws \Exception
     */
    public function balanceFilter(array $data): void
    {
		
		$dateinitial = (!empty($data["dateinitial"]) ? $data["dateinitial"] : "all");
		$datefinal = (!empty($data["datefinal"]) ? $data["datefinal"] : "all");

		$redirect = "fluxodecaixa";
		$json["redirect"] = url("/work/{$redirect}/{$dateinitial}/{$datefinal}");
		echo json_encode($json);
	
    }
	
	/**
     * APP Work
     */
    public function relatorio(?array $data): void
    {
		
		$options = new Options();
		/**$options->(__DIR__ . "/../../themes/" . CONF_VIEW_WORK . "/");*/
	
		$options->setIsRemoteEnabled(true);
		$dompdf = new Dompdf($options);
		
		$walletr = (new AppWallet())
            ->find("application_id = :application_id", "application_id={$this->user->application_id}")->fetch();
	
		$html = $this->view->render("relatorio", [
            "user" => $this->user,	
            "invoices" => (new AppInvoice())->filterInvoices($this->user,($data ?? null)),
            "walletr" => $walletr->balance($this->user) 

        ]);
		
		$dompdf->loadHtml("{$html}");
		$dompdf->setPaper('A4');
		$dompdf->render();
		$dompdf->stream("relatorio.php", ["Attachment" => false]);
		
    }
	
	/**
     * APP Work
     */
    public function cashFlowReport(?array $data): void
    {
		
		$options = new Options();
		/**$options->(__DIR__ . "/../../themes/" . CONF_VIEW_WORK . "/");*/
	
		$options->setIsRemoteEnabled(true);
		$dompdf = new Dompdf($options);
			
		$totalbalancewallet = (new AppInvoice())->totalBalanceByPeriod($this->user,($data ??  null ));
		
		$wallet = (new AppWallet())
            ->find("application_id = :application_id", "application_id={$this->user->application_id}")
            ->order("wallet")
            ->fetch(true);
			
        $html = $this->view->render("cashflowreport", [
            "user" => $this->user,
			"data" => $data,
            "totalbalancewallet" => $totalbalancewallet,
			"wallet" => $wallet
            	
        ]);
		
		$dompdf->loadHtml("{$html}");
		$dompdf->setPaper('A4');
		$dompdf->render();
		$dompdf->stream("fluxodecaixa.php", ["Attachment" => false]);
	
    }
	
    /**
     * APP fixed
     */
    public function fixed(): void
    {
        $head = $this->seo->render(
            "Minhas contas fixas - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );

        $whereWallet = "";
        if ((new Session())->has("walletfilter")) {
            $whereWallet = "AND wallet_id = " . (new Session())->walletfilter;
        }

        echo $this->view->render("recurrences", [
            "head" => $head,
            "invoices" => (new AppInvoice())->find("user_id = :user AND type IN('fixed_income', 'fixed_expense') {$whereWallet}",
                "user={$this->user->id}")->fetch(true)
        ]);
    }

    /**
     * @param array|null $data
     */
    public function wallets(?array $data): void
    {
        //create
        if (!empty($data["wallet"]) && !empty($data["wallet_name"])) {

            //PREMIUM RESOURCE Cobranaça
            /** $subscribe = (new AppSubscription())->find("user_id = :user AND status != :status",
                "user={$this->user->id}&status=canceled");

            if (!$subscribe->count()) {
                $this->message->error("Desculpe {$this->user->first_name}, para criar novas carteiras é preciso ser PRO. Confira abaixo...")->flash();
                echo json_encode(["redirect" => url("/app/assinatura")]);
                return;
            }  */
			
			if (!empty($data["project_id"])){
			
				$wallet = new AppWallet();		
				$wallet->user_id = $this->user->id;
				$wallet->application_id = $this->user->application_id;
				$wallet->project = filter_var($data["project_id"], FILTER_SANITIZE_STRIPPED);
				$wallet->wallet = filter_var($data["wallet_name"], FILTER_SANITIZE_STRIPPED);
			
				if (!$wallet->save()) {
					$json["message"] = $wallet->message()->render();
					echo json_encode($json);
					return;	
				}
				echo json_encode(["reload" => true]);
				return;
				
			}else{
				
				$wallet = new AppWallet();		
				$wallet->user_id = $this->user->id;
				$wallet->application_id = $this->user->application_id;
				$wallet->wallet = filter_var($data["wallet_name"], FILTER_SANITIZE_STRIPPED);
			
				if (!$wallet->save()) {
					$json["message"] = $wallet->message()->render();
					echo json_encode($json);
					return;	
				}
				echo json_encode(["reload" => true]);
				return;

			}

        }

        //edit
        if (!empty($data["wallet"]) && !empty($data["wallet_edit"])) {
            $wallet = (new AppWallet())->find("application_id = :application_id AND id = :id",
                "application_id={$this->user->application_id}&id={$data["wallet"]}")->fetch();

            if ($wallet) {
                $wallet->wallet = filter_var($data["wallet_edit"], FILTER_SANITIZE_STRIPPED);
                $wallet->save();
            }

            echo json_encode(["wallet_edit" => true]);
            return;
        }

        //delete
        if (!empty($data["wallet"]) && !empty($data["wallet_remove"])) {
            $wallet = (new AppWallet())->find("application_id = :application_id AND id = :id",
                "application_id={$this->user->application_id}&id={$data["wallet"]}")->fetch();

            if ($wallet) {
                $wallet->destroy();
                (new Session())->unset("walletfilter");
            }

            echo json_encode(["wallet_remove" => true]);
            return;
        }

        $head = $this->seo->render(
            "Minhas carteiras - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );

        $wallets = (new AppWallet())
            ->find("application_id = :application_id AND status = 'active'", "application_id={$this->user->application_id}")
            ->order("wallet")
            ->fetch(true);

        echo $this->view->render("wallets", [
            "head" => $head,
            "wallets" => $wallets
        ]);
    }
	
	/**
     * @param array|null $data
     */
    public function walletEdit(?array $data): void
    {
		
		$works = (new Works());
		
		//create
        if (!empty($data["action"]) && $data["action"] == "update") {
			
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
			$walletUdate = (new AppWallet())->findById($data["id"]);
			$walletUdate->wallet = $data["wallet"];
			$walletUdate->status = $data["status"];
			
            if (!$walletUdate->save()) {
				
                $json["message"] = $workUdate->message()->render();
                echo json_encode($json);
                return;
				
            }
			
            $this->message->success("Carteira Atualizada")->flash();
            echo json_encode(["reload" => true]);
            return;
        }
		
        $walletEdit = null;
		
        if (!empty($data["id"])) {
            $walletId = filter_var($data["id"], FILTER_VALIDATE_INT);
            $walletEdit = (new AppWallet())->findById($walletId);
        }

        $head = $this->seo->render(	
		   "Atualizar a Carteira",
			CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
			
        );
	
        echo $this->view->render("wallet-edit", [
            "head" => $head,
			"walletedit" => $walletEdit,
			"works" => $works,
		
		]);
    }
	
	
	/**
     * @param array|null $data
     */
    public function walletsInactive(?array $data): void
    {
		
		$wallets = (new AppWallet())
           ->find("application_id = :application_id AND status='finished'", "application_id={$this->user->application_id}")
           ->order("wallet")
           ->fetch(true);
		   
		$search = null;

        $all = ("all");
		$pager = new Pager(url("/work/carteirasinativas/{$all}/"));
        $pager->pager(10, 10, (!empty($data["page"]) ? $data["page"] : 1));
        $pager->pager(10, 10, (!empty($data["page"]) ? $data["page"] : 1));
	   
		//delete
        if (!empty($data["wallet"]) && !empty($data["wallet_remove"])) {
            $wallet = (new AppWallet())->find("application_id = :application_id AND id = :id",
                "application_id={$this->user->application_id}&id={$data["wallet"]}")->fetch();

            if ($wallet) {
                $wallet->destroy();
                (new Session())->unset("walletfilter");
            }

            echo json_encode(["wallet_remove" => true]);
            return;
        }
	  
        $head = $this->seo->render(
			"Lista de Carteiras Finalziadas ",
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );
		
        echo $this->view->render("walletsinactive", [
            "head" => $head,
            "wallets" => $wallets,
			"paginator" => $pager->render()
			
        ]);
    }
	
    /**
     * @param array $data
     */
    public function launch(array $data): void
    {
        if (request_limit("applaunch", 20, 60 * 5)) {
            $json["message"] = $this->message->warning("Foi muito rápido {$this->user->first_name}! Por favor aguarde 5 minutos para novos lançamentos.")->render();
            echo json_encode($json);
            return;
        }

        $invoice = new AppInvoice();

        $data["value"] = (!empty($data["value"]) ? str_replace([".", ","], ["", "."], $data["value"]) : 0);
        if (!$invoice->launch($this->user, $data)) {
			
            $json["message"] = $invoice->message()->render();
            echo json_encode($json);
            return;
        }

        $type = ($invoice->type == "income" ? "receita" : "despesa");
        $this->message->success("Tudo certo, sua {$type} foi lançada com sucesso")->flash();

        $json["reload"] = true;
        echo json_encode($json);
    }

 
	/**
     * @param array $data
     */
    public function onpaid(array $data): void
    {
        $invoice = (new AppInvoice())
            ->find("user_id = :user AND id = :id", "user={$this->user->id}&id={$data["invoice"]}")
            ->fetch();

        if (!$invoice) {
            $this->message->error("Ooops! Ocorreu um erro ao atualizar o lançamento :/")->flash();
            $json["reload"] = true;
            echo json_encode($json);
            return;
        }
			
        $invoice->status = ($invoice->status == "paid" ? "unpaid" : "paid");
        $invoice->save();

        $y = date("Y");
        $m = date("m");
        if (!empty($data["date"])) {
            list($m, $y) = explode("/", $data["date"]);
        }

        $json["onpaid"] = (new AppInvoice())->balanceMonth($this->user, $y, $m, $invoice->type);
        echo json_encode($json);
    }
	
	/**
     * @param array $data
     */
    public function onpaidincome(array $data): void
    {
         $invoice = (new AppInvoice())
            ->find("user_id = :user AND id = :id", "user={$this->user->id}&id={$data["invoice"]}")
            ->fetch();
				
		$walletid = (new AppWallet())
            ->find("user_id = :user AND id = :id", "user={$this->user->id}&id={$invoice->wallet_id}")->fetch();
			
        if (!$invoice) {
            $this->message->error("Ooops! Ocorreu um erro ao atualizar o lançamento :/")->flash();
            $json["reload"] = true;
            echo json_encode($json);
            return;
        }
		
        $invoice->status = ($invoice->status == "paid" ? "unpaid" : "paid");
        $invoice->save();

        $y = date("Y");
        $m = date("m");
        if (!empty($data["date"])) {
            list($m, $y) = explode("/", $data["date"]);
        }

		$json["onpaid"] = (new AppInvoice())->balanceProject($this->user, $y, $m, $invoice->type, $walletid);       
		echo json_encode($json);
    }
	
	
	/**
     * @param array $data
     */
    public function onpaidexpense(array $data): void
    {
		$invoice = (new AppInvoice())
            ->find("id = :id AND application_id = :application_id", "id={$data["invoice"]}&application_id={$this->user->application_id}")
            ->fetch();
				
		$walletid = (new AppWallet())
            ->find("id = :id AND application_id = :application_id", "id={$invoice->wallet_id}&application_id={$this->user->application_id}")->fetch();
				
        if (!$invoice) {
            $this->message->error("Ooops! Ocorreu um erro ao atualizar o lançamento :/")->flash();
            $json["reload"] = true;
            echo json_encode($json);
            return;
        }
		
        $invoice->status = ($invoice->status == "paid" ? "unpaid" : "paid");
        $invoice->save(); 
       
        $y = date("Y");
        $m = date("m");
        if (!empty($data["date"])) {
            list($m, $y) = explode("/", $data["date"]);
        }

		$json["onpaid"] = (new AppInvoice())->balanceProject($this->user, $y, $m, $invoice->type, $walletid);       
		echo json_encode($json);
		
    }
	
	
    /**
     * @param array $data
     */
    public function invoice(array $data): void
    {
        if (!empty($data["update"])) {
            $invoice = (new AppInvoice())->find("application_id = :application_id AND id = :id",
                "application_id={$this->user->application_id}&id={$data["invoice"]}")->fetch();

            if (!$invoice) {
                $json["message"] = $this->message->error("Ooops! Não foi possível carregar a fatura {$this->user->first_name}. Você pode tentar novamente.")->render();
                echo json_encode($json);
                return;
            }

            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
           
		    $check = \DateTime::createFromFormat("Y-m-d", $data["due_day"]);
			
			if (!$check || $check->format("Y-m-d") != $data["due_day"]) {
				
				$json["message"] = $this->message->error("O vencimento da fatura não tem um formato válido")->render();
                echo json_encode($json);
                return;
							
			}
			
			$invoice->user_id = $this->user->id;
			$invoice->application_id = $this->user->application_id;
			$due_day = $data["due_day"];
            $invoice->category_id = $data["category"];
            $invoice->description = $data["description"];
            $invoice->due_at = $due_day;
            $invoice->value = str_replace([".", ","], ["", "."], $data["value"]);
            $invoice->wallet_id = $data["wallet"];
            $invoice->status = $data["status"];

            if (!$invoice->save()) {
                $json["message"] = $invoice->message()->before("Ooops! ")->after(" {$this->user->first_name}.")->render();
                echo json_encode($json);
                return;
            }
			
            if (!$invoice->save()) {
                $json["message"] = $invoice->message()->before("Ooops! ")->after(" {$this->user->first_name}.")->render();
                echo json_encode($json);
                return;
            }
			
            $invoiceOf = (new AppInvoice())->find("application_id = :application_id AND invoice_of = :of",
                "application_id={$this->user->application_id}&of={$invoice->id}")->fetch(true);

            if (!empty($invoiceOf) && in_array($invoice->type, ["fixed_income", "fixed_expense"])) {
                foreach ($invoiceOf as $invoiceItem) {
                    if ($data["status"] == "unpaid" && $invoiceItem->status == "unpaid") {
                        $invoiceItem->destroy();
                    } else {
						
		
                      	$due_day = $data["due_day"];
                        $invoiceItem->category_id = $data["category"];
                        $invoiceItem->description = $data["description"];
                        $invoiceItem->wallet_id = $data["wallet"];

                        if ($invoiceItem->status == "unpaid") {
                            $invoiceItem->value = str_replace([".", ","], ["", "."], $data["value"]);
                            $invoiceItem->due_at = $due_day;
                        }

                        $invoiceItem->save();
                    }
                }
            }

            $json["message"] = $this->message->success("Pronto {$this->user->first_name}, a atualização foi efetuada com sucesso!")->render();
            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            "Aluguel - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );

        $invoice = (new AppInvoice())->find("application_id = :application_id AND id = :invoice",
            "application_id={$this->user->application_id}&invoice={$data["invoice"]}")->fetch();

        if (!$invoice) {
            $this->message->error("Ooops! Você tentou acessar uma fatura que não existe")->flash();
            redirect("/app");
        }

        echo $this->view->render("invoice", [
            "head" => $head,
            "invoice" => $invoice,
            "wallets" => (new AppWallet())
                ->find("application_id = :application_id", "application_id={$this->user->application_id}", "id, wallet")
                ->order("wallet")
                ->fetch(true),
            "categories" => (new AppCategory())
                ->find("application_id = :application AND type = :type", "type={$invoice->category()->type}&application={$this->user->application_id}")
                ->order("order_by, name")
                ->fetch(true)
        ]);
    }
	

	/**
     * @param array|null $data
     */
    public function BalanceByPeriodMonthYear(?array $data): void
    {
        $head = $this->seo->render(
            "Minhas despesas - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );
		
		//CHART
        $chartDataPeriod = (new AppInvoice())->chartDataPeriod($this->user,($data ??  null));
		$balanceByPeriodMonthYear = (new AppInvoice())->balanceByPeriodMonthYear($this->user,($data ??  null));
		
		$wallet = (new AppWallet())
            ->find("application_id = :application_id", "application_id={$this->user->application_id}")
            ->order("wallet")
            ->fetch(true);
			
        echo $this->view->render("balanceperiod", [
            "user" => $this->user,
            "head" => $head,
			"data" => $data,
			"chart" => $chartDataPeriod,
            "balance" => $balanceByPeriodMonthYear,
			"wallet" => $wallet,
            "filter" => (object)[
                "dateinitial" => ($data["dateinitial"] ??  null),
				"datefinal" => ($data["datefinal"] ??  null)
            ]
		
        ]);
    }
	
	/**
     * @param array $data
     * @throws \Exception
     */
    public function filterPeriodMonthYear(array $data): void
    {
      
        $dateinitial = (!empty($data["dateinitial"]) ? $data["dateinitial"] : "all");
		$datefinal = (!empty($data["datefinal"]) ? $data["datefinal"] : "all");
		$redirect = "balançofinanceiro";
		$json["redirect"] = url("/work/{$redirect}/{$dateinitial}/{$datefinal}");
		echo json_encode($json);
		
    } 
	
	/**
     * APP BalanceByPeriodMonthYearReport
     */
    public function BalanceByPeriodMonthYearReport(?array $data): void
    {
		
		$options = new Options();
		/**$options->(__DIR__ . "/../../themes/" . CONF_VIEW_WORK . "/");*/
	
		$options->setIsRemoteEnabled(true);
		$dompdf = new Dompdf($options);
		
		//CHART
        $chartDataPeriod = (new AppInvoice())->chartDataPeriod($this->user,($data ??  null));
		$balanceByPeriodMonthYear = (new AppInvoice())->balanceByPeriodMonthYear($this->user,($data ??  null));
		
		$wallet = (new AppWallet())
            ->find("application_id = :application_id", "application_id={$this->user->application_id}")
            ->order("wallet")
            ->fetch(true);
			
        $html = $this->view->render("balanceperiodreport", [
            "user" => $this->user,
			"data" => $data,
			"chart" => $chartDataPeriod,
            "balance" => $balanceByPeriodMonthYear,
			"wallet" => $wallet   
        ]);
		
		$dompdf->loadHtml("{$html}");
		$dompdf->setPaper('A4');
		$dompdf->render();
		$dompdf->stream("fluxodecaixa.php", ["Attachment" => false]);
	
    }
	

    /**
     * @param array $data
     */
    public function remove(array $data): void
    {
        $invoice = (new AppInvoice())->find("application_id = :application_id AND id = :invoice",
            "application_id={$this->user->application_id}&invoice={$data["invoice"]}")->fetch();

        if ($invoice) {
            $invoice->destroy();
        }

        $this->message->success("Tudo pronto {$this->user->first_name}. O lançamento foi removido com sucesso!")->flash();
        $json["redirect"] = url("/work");
        echo json_encode($json);
    }
	
	
	/**
     *Work Stage Categories
     */
    public function Category(?array $data): void
    {
		$categories = (new AppCategory())->find("application_id = :application","application={$this->user->application_id}");	
		$search = null;
        $all = ($search ?? "all");
        $pager = new Pager(url("/work/category/{$all}/"));
        $pager->pager($categories->count(),20, (!empty($data["page"]) ? $data["page"] : 1));
		
		//delete
        if (!empty($data["action"]) && $data["action"] == "delete") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $categoryDelete = (new StageCategory())->findById($data["id"]);

            if (!$categoryDelete) {
				
                $this->message->error("Você tentou deletar uma categoria que não existe")->flash();
                echo json_encode(["redirect" => url("/work/category")]);
                return;
	
            }
			
            $categoryDelete->destroy();
			$this->message->success("A categoria foi deletado com sucesso...")->flash();
            echo json_encode(["reload" => true]);
            return;
			
        }
	
		$head = $this->seo->render(
			"Categorias de Etapas",
			CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );

        echo $this->view->render("category",[
            "head" => $head,
			"search" => $search,
            "categories" => $categories->order("name asc")->limit($pager->limit())->offset($pager->offset())->fetch(true),
			"paginator" => $pager->render(),
        ]);
		
		
    }
	
	/**
     * Work Stage Categories Add
     */
    public function CategoryAdd(?array $data): void
    {	
	
       //create
        if (!empty($data["action"]) && $data["action"] == "create") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

				$categoryCreate = new AppCategory();
				$categoryCreate->user_id = $this->user->id;	
				$categoryCreate->application_id = $this->user->application_id;					
				$categoryCreate->name = $data["name"];
				$categoryCreate->type = $data["type"];

				
				 if (!$categoryCreate->save()) {
					 
					$json["message"] = $categoryCreate->message()->render();
					echo json_encode($json);
					return;
				 }

			$json["message"] = $this->message->success("Cadastro Realizado com sucesso!")->render();
            echo json_encode($json);
            return;
			
		}
         
		$head = $this->seo->render(
            "Cadastro de Categoria",
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );

        echo $this->view->render("category-add", [
            "head" => $head,
        ]);
		
    }

   
	
	
	
	
	
	
	
}