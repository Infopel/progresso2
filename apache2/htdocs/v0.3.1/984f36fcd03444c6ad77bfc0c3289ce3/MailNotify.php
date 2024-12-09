<?php

/**
 * @author: Edilson D. Mucanze
 * @email: edilsonhmberto@gmail.com
 * @contacto: +258 84 821 3574
 * @date: Dezembro de 2018
 * @Projecto: Sistema de Monitoria de Projecto
 * @Base: MiniCrafted APi
 */

/** Connect Sistem backEnd*/


class MailNotify
{
    protected $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    public function notifySMP()
    {

        $getIssues = $this->query("SELECT issues.id, subject, assigned_to_id, author_id, concat(firstname,' ',lastname) as author_name, concat(firstname,' ',lastname) as name, address as email_to, start_date, due_date, issues.created_on FROM issues inner join users on (users.id = author_id) inner join email_addresses on email_addresses.user_id =users.id  where tracker_id = 11 and due_date is not null;", array());

        $issues = array();
        while ($issue = $getIssues->fetchObject()) {

            if($issue->assigned_to_id == null){
                $issue->isAssined = false;
                $issues[] = $issue;
            }else{

                $user = $this->query("SELECT concat(firstname,' ',lastname) as name, address as email_to from users inner join email_addresses on email_addresses.user_id = users.id where users.id = ?", array($issue->assigned_to_id));

                $user = $user->fetchObject();
                $issue->name = $user->name;
                $issue->email_to = $user->email_to;
                $issue->isAssined = true;
                $issues[] = $issue;
            }
        }
        return $issues;
    }


    public function isNotified($parameter, array $parametersValues)
    {
        $isNotified = $this->query("SELECT * from notification_ where $parameter limit 1", $parametersValues);

        if($isNotified->rowCount() >= 1){
            $_isNotified = array(
                'isNotified' => true,
                'created_at' => $isNotified->fetchObject()->created_at
            );
            return $_isNotified;
        }else{
            $_isNotified = array(
                'isNotified' => false,
                'created_at' => null
            );
            return $_isNotified;
        }
    }

    /**
     * Funcao usada para guardar as notifications
     */
    public function storeNotification($tab, $conluns, $fields, $parametersValues)
    {
        $this->query("INSERT INTO `$tab` ($conluns) VALUES ($fields)", $parametersValues);
    }

    /**
     * Funcao usada para guardar as notifications
     */
    public function updatedNotifys($parametersValues)
    {
        $this->query("UPDATE `notification_` SET `n_nots`= n_nots + 1, updated_at = now() WHERE issue_id = ?", $parametersValues);
    }

    /**
     * Builder da query
     * @return Response
     */
    protected function query($query, array $parameters)
    {
        try{
            $query = $this->conn->prepare($query);
            $query->execute($parameters);
            return $query;
        }catch(\PDOException $e){
            return $e->getMessage();
        }
    }
}
