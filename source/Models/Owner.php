<?php

namespace Source\Models;

use Source\Core\Model;

/**
 *  Class Owner Active Record Pattern
 *
 * @author Maicon Roger do Rosario <maiconrogerrosario@gmail.com>
 * @package Source\Models
 */
class Owner extends Model
{
    /**
     * Owner constructor.
     */
    public function __construct()
    {
        parent::__construct("owner", ["id"], ["name", "document"]);
    }

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $password
     * @param string|null $document
     * @return Owner
     */
	 	 
    public function bootstrap(
	   string $application_id,
	   string $name,
	   string $document,
	   string $email,
	   string $phone1,
	   string $mobile
	   
	   
    ): Owner {
		$this->application_id = $application_id;
		$this->name = $name;
		$this->document = $document;
		$this->email = $email;
		$this->phone1 = $phone1;
		$this->mobile = $mobile;
        return $this;
    }
		

    public function findByEmail(string $email,string $application_id, string $columns = "*"): ?owner
    {
        $find = $this->find("email = :email AND application_id = :application_id ", "email={$email}&application_id={$application_id}", $columns);
        return $find->fetch();
    } 
	
	public function findByCPF(string $document,string $application_id, string $columns = "*"): ?owner
    {
        $find = $this->find("document = :document AND application_id = :application_id ", "document={$document}&application_id={$application_id}", $columns);
        return $find->fetch();
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
   

        /** Owner Update */
        if (!empty($this->id)) {
            $ownerId = $this->id;

            if ($this->find("document = :document AND id != :i", "document={$this->document}&i={$ownerId}", "id")->fetch()) {
                $this->message->warning("O CPF/CNPJ já esta cadastrado");
                return false;
            }

            $this->update($this->safe(), "id = :id", "id={$ownerId}");
            if ($this->fail()) {
                $this->message->error("Erro ao atualizar, verifique os dados");
                return false;
            }
        }

        /** Owner Create */
        if (empty($this->id)) {
			
            if ($this->findByCPF($this->document,$this->application_id, "id")) {
                $this->message->warning("O CPF/CNPJ já esta cadastrado");
                return false;
            }

            $ownerId = $this->create($this->safe());
            if ($this->fail()) {
                $this->message->error("Erro ao cadastrar, verifique os dados");
                return false;
            }
        }

        $this->data = ($this->findById($ownerId))->data();
        return true;	
	  
		
		
    }
	
}