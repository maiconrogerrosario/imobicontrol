<?php

namespace Source\Models;

use Source\Core\Model;

/**
 * Contract Active Record Pattern
 *
 * @author Maicon Roger do Rosario <maiconrogerrosario@gmail.com>
 * @package Source\Models
 */
class Contract extends Model
{
    /**
     * Contract constructor.
     */
    public function __construct()
    {
        parent::__construct("contract", ["id"], ["property_id", "owner_id","customer_id","number_contract", "date_initial", "date_final", "rent_value", "iptu_value", "status"]);
    }

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $password
     * @param string|null $document
     * @return User
     */
	 	 
    public function bootstrap(
	   string $application_id,
	   string $property_id,
	   string $owner_id,
	   string $customer_id,
	   string $number_contract,
	   string $date_initial,
	   string $date_final,
	   string $status,
	   string $rent_value,
	   string $iptu_value,
	   string $condominium_value,
	   string $administration_value
	
    ): Contract {
		$this->application_id = $application_id;
		$this->property_id = $property_id;	
		$this->owner_id = $owner_id;
		$this->customer_id = $customer_id;
		$this->number_contract = $number_contract;
		$this->date_initial = $date_initial;
		$this->date_final = $date_final;
		$this->rent_value = $rent_value;
		$this->iptu_value = $iptu_value;
		$this->rent_value = $rent_value;
		$this->condominium_value = $condominium_value;
		$this->administration_value = $administration_value;
		$this->status = $status;
        return $this;
    }
	
	
	public function getCustomer(): ?Customer
	{ 
		if ($this->customer_id) {
			
			 return (new Customer())->findById($this->customer_id);
        }
		
        return null;	
	}	
	
	public function getOwner(): ?Owner
	{ 
		if ($this->owner_id) {
			
			 return (new Owner())->findById($this->owner_id);
        }
		
        return null;	
	}	
	
	public function getProperty(): ?Property
	{ 
		if ($this->property_id) {
			
			 return (new Property())->findById($this->property_id);
        }
		
        return null;	
	}	
	
    /**
     * @return bool
     */
    public function save(): bool
    {
		
		if (!$this->required()) {
			
			
            $this->message->warning("Nome, sobrenome, email e senha são obrigatórios");
            return false;
        }
   
        /** User Update */
        if (!empty($this->id)) {
            $contractId = $this->id;

           

            $this->update($this->safe(), "id = :id", "id={$contractId}");
            if ($this->fail()) {
                $this->message->error("Erro ao atualizar, verifique os dados");
                return false;
            }
        }

        /** User Create */
        if (empty($this->id)) {
			
            $contractId = $this->create($this->safe());
            if ($this->fail()) {
                $this->message->error("Erro ao cadastrar, verifique os dados");
                return false;
            }
        }

        $this->data = ($this->findById($contractId))->data();
        return true;	
	  
    }
	
}