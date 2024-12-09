<?php
/**
 * Script
 * @autor: Edilson H Mucanze
 * @email: edilsonhmberto@gmai.com
 * @contacto: *258 84 821 3574
 *
 * @pro: Updata System
 * SMP
 * **/

use PHP\PDO;

class helperIssuesUpdate{

    private $parents = [151, 152, 153, 154];

    /**
     * Metodo construtor
     */
    public function __construct($conn){
        $this->conn = $conn;
    }

    /**
     * Pega o subject parents
     */
    public function getIssuesParents()
    {
        $i = 1;
        $listElemts = "";
        foreach ($this->parents as $parent) {

            $getParent = $this->conn->prepare("SELECT * FROM bitnami_redmine.issues where issues.parent_id = ?");
            $getParent->execute(array($parent));

            // Manda um object Issues para o metodo que vai pegar os valores
            while ($parentIssue = $getParent->fetchObject()) {

               
                if($parentIssue->parent_id == 151){
                    $i = 1;
                }

                if($parentIssue->parent_id == 152){
                    $i = 2;
                }

                if($parentIssue->parent_id == 153){
                    $i = 3;
                }

                if($parentIssue->parent_id == 154){
                    $i = 4;
                }

                $parents [] = array(
                    'type' => $i,
                    'id' => $parentIssue->id
                );
            }

            $listElemts .= $this->getIssuesSubjects($parents);
            // $i++;
            $parents = null;
        }

        $content = "--- \r".$listElemts;
        echo $this->UpdateRelatedSubjects($content);
    }


    /**
     * Pega os subjects dos filhos da issue
     */

     private function getIssuesSubjects($parentIssue)
     {
        $subjectValue = "";
        foreach ($parentIssue as $parent_id) {
        //    print_r($parent_id['type']."-");

           $getParent = $this->conn->prepare("SELECT * FROM bitnami_redmine.issues where issues.parent_id = ?");
           $getParent->execute(array($parent_id['id']));

            // if(){

            // }

            while ($parentIssue = $getParent->fetchObject()) {
                $subjectValue .= " - Obj.".$parent_id['type'].":#".$parentIssue->id." ".$parentIssue->subject."\r";
            }
        }
        return $subjectValue;
     }

    /**
     * Grava os dados na base de dados
     * para posterior relacionamento
     */
    private function UpdateRelatedSubjects($content)
    {
        $store = $this->conn->prepare("UPDATE `bitnami_redmine`.`custom_fields` SET `possible_values` = ? WHERE `id` = '116'");
        $store->execute(array($content));

        if($store){
            // echo "<pre>".$content."</pre>";

        }else{
            echo "Error";
        }
    }


}
