<?php
class ExpenseCategoriesModel extends DBModel
{
   
    protected $name;
    public function __construct( $cName="food")
    {
        $this->name=$cName;
    }

     //add Expensecategory
     public function addExpenseCategory($name){
       
        $q = "INSERT INTO `expenseCategories`( `name`) VALUES (?);";
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

    //get all Expensecategories
    public function getAllExpenseCategories()
    {
        $sql = "SELECT *  FROM `expenseCategories` ORDER BY id ;";
        $myPrep = $this->db()->prepare($sql);
        if ($myPrep) {
           
            $myPrep->execute();
            $result = $myPrep->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }else{
            echo "Error: " . $sql . "<br>" . $this->db()->error;
        }

    }

     //get all Expensecategories by name
    public function getAllExpenseCategoriesByName()
    {
        $sql = "SELECT *  FROM `expenseCategories` ORDER BY name ;";
        $myPrep = $this->db()->prepare($sql);
        if ($myPrep) {
           
            $myPrep->execute();
            $result = $myPrep->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }else{
            echo "Error: " . $sql . "<br>" . $this->db()->error;
        }

    }

    function findExpenseCategoryById($cId){
        $sql = "SELECT `name` FROM `expenseCategories` WHERE `id` = $cId;";
        $result = $this->db()->query($sql);
        $row = $result->fetch_assoc();
        return $row;
    }
    
     //Function delete Expensecategory
    public function deleteExpenseCategory($id)
    {
        $sql = "DELETE  FROM `expenseCategories` WHERE `id` = ?";
        $myPrep = $this->db()->prepare($sql);
        if ($myPrep) {
            $myPrep->bind_param("i", $id);
            return $myPrep->execute();
        }else{
            echo "Error: " . $sql . "<br>" . $this->db()->error;
        }
    }
    
     //UPDATE category
     public function updateExpenseCategory($name, $id)
     {
         // $id = $_GET['id'];
         $sql = "UPDATE `expenseCategories` SET `name` = ? WHERE `expenseCategories`.`id` = $id";
         $myPrep = $this->db()->prepare($sql);
         $myPrep->bind_param("s", $name);
 
         return $myPrep->execute();
     }
}