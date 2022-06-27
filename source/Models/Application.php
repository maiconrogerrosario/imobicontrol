<?php

namespace Source\Models;

use Source\Core\Model;

/**
 * FSPHP | Class User Active Record Pattern
 *
 * @author Maicon Roger do Rosario <maiconrogerrosario@gmail.com>
 * @package Source\Models
 */
class Application extends Model
{
    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct("application", ["id"], ["application_name", "admin_name", "email",]);
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
        string $application_name,
        string $admin_name,
        string $email
    ): application {
        $this->application_name = $application_name;
        $this->admin_name = $admin_name;
        $this->email = $email;
      
        return $this;
    }

    /**
     * @param string $email
     * @param string $columns
     * @return null|User
     */
    public function findByEmail(string $email, string $columns = "*"): ?application
    {
        $find = $this->find("email = :email", "email={$email}", $columns);
        return $find->fetch();
    }

    /**
     * @return string
     */
    public function adminName(): string
    {
        return "{$this->admin_name}";
    }
	
	
	/**
     * @return string
     */
    public function fullName(): string
    {
        return "{$this->application_name}";
    }

    /**
     * @return string|null
     */
    public function photo(): ?string
    {
        if ($this->photo && file_exists(__DIR__ . "/../../" . CONF_UPLOAD_DIR . "/{$this->photo}")) {
            return $this->photo;
        }

        return null;
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

        if (!is_email($this->email)) {
            $this->message->warning("O e-mail informado não tem um formato válido");
            return false;
        }

      
        /** User Update */
        if (!empty($this->id)) {
            $applicationId = $this->id;

            if ($this->find("email = :e AND id != :i", "e={$this->email}&i={$applicationId}", "id")->fetch()) {
                $this->message->warning("O e-mail informado já está cadastrado");
                return false;
            }

            $this->update($this->safe(), "id = :id", "id={$applicationId}");
            if ($this->fail()) {
                $this->message->error("Erro ao atualizar, verifique os dados");
                return false;
            }
        }

        /** User Create */
        if (empty($this->id)) {
			
          

            $applicationId = $this->create($this->safe());
            if ($this->fail()) {
                $this->message->error("Erro ao cadastrar, verifique os dados");
                return false;
            }
        }

        $this->data = ($this->findById($applicationId))->data();
        return true;
    }
}