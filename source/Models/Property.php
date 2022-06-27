<?php

namespace Source\Models;

use Source\Core\Model;

/**
 * Property Active Record Pattern
 *
 * @author Maicon Roger do Rosario <maiconrogerrosario@gmail.com>
 * @package Source\Models
 */
class Property extends Model
{
    /**
     * Property constructor.
     */
    public function __construct()
    {
        parent::__construct("teste", ["id"], ["address_street", "address_number"]);
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
	   string $owner_id,
	   string $address_street,
	   string $address_number,
	   string $address_neighborhood,
	   string $address_complement,
	   string $address_postalcode,
	   string $address_city,
	   string $address_state,
	   string $address_country
	   
    ): Property {
		$this->application_id = $application_id;
		$this->owner_id = $owner_id;	
		$this->address_street = $address_street;
		$this->address_number = $address_number;
		$this->address_neighborhood = $address_neighborhood;
		$this->address_complement = $address_complement;
		$this->address_postalcode = $address_postalcode;
		$this->address_city = $address_city;
		$this->address_state = $address_state;
		$this->address_country = $address_country;
        return $this;
    }
	
	
	/**
     * @return string
     */
    public function fullAddress(): string
    {
        return "{$this->address_street}" ." ". "nº{$this->address_number}";
    }
		

    public function findByEmail(string $email,string $application_id, string $columns = "*"): ?Property
    {
        $find = $this->find("email = :email AND application_id = :application_id", "email={$email}&application_id={$application_id}", $columns);
        return $find->fetch();
    } 
	
	public function findByAddress( 
	   string $address_street,
	   string $address_number,
	   string $address_neighborhood,
	   string $address_complement,
	   string $address_postalcode,
	   string $address_city,
	   string $address_state,
	   string $address_country,
	   string $application_id,
	   string $columns = "*"): ?Property
    {
        $find = $this->find("
		address_street = :address_street AND 
		address_number = :address_number AND 
		address_neighborhood = :address_neighborhood AND 
		address_postalcode = :address_postalcode AND 
		address_city = :address_city AND 
		address_state = :address_state AND 
		address_country = :address_country AND
		application_id = :application_id "
		, 
		"address_street={$this->address_street}
		&address_number={$this->address_number}
		&address_neighborhood={$this->address_neighborhood}
		&address_postalcode={$this->address_postalcode}
		&address_city={$this->address_city}
		&address_state={$this->address_state}
		&address_country={$this->address_country}
		&application_id={$application_id}"
		, 
		$columns);
        return $find->fetch();
    } 
	
	
    /**
     * @return bool
     */
    public function save(): bool
    {
		
		if (!$this->required()) {
            $this->message->warning("Falta Informar os dados");
            return false;
        }
   
        /** User Update */
        if (!empty($this->id)) {
            $propertyId = $this->id;

           

            $this->update($this->safe(), "id = :id", "id={$propertyId}");
            if ($this->fail()) {
                $this->message->error("Erro ao atualizar, verifique os dados");
                return false;
            }
        }

        /** User Create */
        if (empty($this->id)) {
			
			
            if ($this->findByAddress(
				$this->address_street,
				$this->address_number,
				$this->address_neighborhood,
				$this->address_postalcode,
				$this->address_city,
				$this->address_state,
				$this->address_country,
				$this->application_id
				,
				"id")) {
					
                $this->message->warning("O endereço já esta cadastrado");
                return false;
            }

            $propertyId = $this->create($this->safe());
            if ($this->fail()) {
				
                $this->message->error("Erro ao cadastrar, verifique os dados");
                return false;
            }
        }

        $this->data = ($this->findById($propertyId))->data();
        return true;	
	  
		

    }
	
}