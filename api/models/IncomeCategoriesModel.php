<?php
class IncomeCategoriesModel extends DBModel
{
   
    protected $name;
    public function __construct( $cName="corpul")
    {
        $this->name=$cName;
    }

     //add Incomecategory
     public function addIncomeCategory($name){
       
        $q = "INSERT INTO `incomeCategories`( `name`) VALUES (?);";
        // prepared statements
        $myPrep = $this->db()->prepare($q);
        // s - string, i - integer, d - double, b - blob
        if($myPrep){
            $myPrep->bind_param("s", $name);
       
            return $myPrep->execute();
        }else{
            echo "Error: " . $q . "<br>" . $this->db()->error;
        }
       
        
        // $myPrep->close();
    }

    //get all Incomecategories
    public function getAllIncomeCategories()
    {
        $sql = "SELECT *  FROM `incomeCategories` ORDER BY id ;";
        $myPrep = $this->db()->prepare($sql);
        if ($myPrep) {
           
            $myPrep->execute();
            $result = $myPrep->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }else{
            echo "Error: " . $sql . "<br>" . $this->db()->error;
        }

    }
    function findIncomeCategoryById($cId){
        $sql = "SELECT `name` FROM `incomeCategories` WHERE `id` = $cId;";
        $result = $this->db()->query($sql);
        $row = $result->fetch_assoc();
        return $row;
    }
    
     //Function delete Incomecategory
    public function deleteIncomeCategory($id)
    {
        $sql = "DELETE  FROM `incomeCategories` WHERE `id` = ?";
        $myPrep = $this->db()->prepare($sql);
        if ($myPrep) {
            $myPrep->bind_param("i", $id);
            return $myPrep->execute();
        }else{
            echo "Error: " . $sql . "<br>" . $this->db()->error;
        }
    }
    
     //UPDATE Incomecategory
     public function updateIncomeCategory($name, $id)
     {
         // $id = $_GET['id'];
         $sql = "UPDATE `incomeCategories` SET `name` = ? WHERE `incomeCategories`.`id` = $id";
         $myPrep = $this->db()->prepare($sql);
         $myPrep->bind_param("s", $name);
 
         return $myPrep->execute();
     }
}