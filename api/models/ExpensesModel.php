<?php
class ExpensesModel extends DBModel
{
    protected $addedAt;
    protected $user;
    protected $expenseCategory;
    protected $name;
    protected $value;

    public function __construct($iAdded="2025", $iUser=3, $iCategory="alim", $iName="alim", $iValue=20.20)
    {
        $this->addedAt=$iAdded;
        $this->user=$iUser;
        $this->expenseCategory=$iCategory;
        $this->name=$iName;
        $this->value=$iValue;
    }
     //add Expense
     public function addExpense($addedAt, $userId, $expenseCategory, $name, $value){
       
        $q = "INSERT INTO `expenses`(`addedAt`, `userId`, `expenseCategory`,`name`, `value`) VALUES (?, ?, ?, ?, ?);";
        // prepared statements
        $myPrep = $this->db()->prepare($q);
        // s - string, i - integer, d - double, b - blob
        if($myPrep){
            $myPrep->bind_param("sissd",$addedAt, $userId, $expenseCategory, $name, $value);
       
            return $myPrep->execute();
        }else{
            echo "Error: " . $q . "<br>" . $this->db()->error;
        }
       
        
        // $myPrep->close();
    }

    //get current expenses for user
    public function getCurrentExpenses($userId)
    {
        $sql ="SELECT * FROM `expenses` WHERE YEAR(addedAt) = YEAR(CURRENT_DATE()) AND  MONTH(addedAt) = MONTH(CURRENT_DATE()) AND userId = $userId  ORDER BY STR_TO_DATE(`addedAt`, '%d/%m/%Y') ASC;";
         $result = $this->db()->query($sql);
        $expenses = $result->fetch_all(MYSQLI_ASSOC);

        return $expenses;
    }
     

     //get  expenses for user byname
    public function getCurrentExpensesByName($userId, $searchTerm)
    {
        $sql ="SELECT * FROM `expenses` WHERE YEAR(addedAt) = YEAR(CURRENT_DATE()) AND  MONTH(addedAt) = MONTH(CURRENT_DATE()) AND userId = $userId AND LOWER (`expenses`.`name`) LIKE '$searchTerm%' ORDER BY STR_TO_DATE(`addedAt`, '%d/%m/%Y') ASC;";
         $result = $this->db()->query($sql);
        $expenses = $result->fetch_all(MYSQLI_ASSOC);

        return $expenses;
    }
     //get  expenses for user by category
    public function getCurrentExpensesByCategory($userId, $category)
    {
        $sql ="SELECT * FROM `expenses` WHERE YEAR(addedAt) = YEAR(CURRENT_DATE()) AND  MONTH(addedAt) = MONTH(CURRENT_DATE()) AND userId = $userId AND `expenseCategory`='$category' ORDER BY STR_TO_DATE(`addedAt`, '%d/%m/%Y') ASC;";
        $result = $this->db()->query($sql);
        $expenses = $result->fetch_all(MYSQLI_ASSOC);

        return $expenses;
    }

     //Get  expenses current month by category notNull
     public function getTotalExpensesCurrentMonthByCategoryNotNull($userId)
     {
        $sql = "SELECT expenseCategory FROM expenses  WHERE YEAR(addedAt) = YEAR(CURRENT_DATE()) AND  MONTH(addedAt) = MONTH(CURRENT_DATE()) AND  expenseCategory IS NOT NULL AND userId =$userId ;";
        $result = $this->db()->query($sql);
        $expenses = $result->fetch_all(MYSQLI_ASSOC);

        return $expenses;
     }

     //get expenseCategory distinct
     public function getCurrentDistinctExpenseCategoryForUser($userId)
     {
        $sql ="SELECT DISTINCT expenseCategory FROM expenses WHERE YEAR(addedAt) = YEAR(CURRENT_DATE()) AND  MONTH(addedAt) = MONTH(CURRENT_DATE())  AND userId =$userId;";
        $result = $this->db()->query($sql);
        $expenses = $result->fetch_all(MYSQLI_ASSOC);

        return $expenses;
     }

     //get expenseCategory distinct by selected year, month and user
     public function getDistinctExpenseCategoryByMonthYearAndUserId($year, $month, $userId)
     {
        $sql ="SELECT DISTINCT expenseCategory FROM expenses WHERE YEAR(addedAt) = $year AND MONTH(addedAt) = $month AND userId =$userId;";
        $result = $this->db()->query($sql);
        $expenses = $result->fetch_all(MYSQLI_ASSOC);

        return $expenses;
     }

     //get expenseCategory distinct by selected year and user
     public function getDistinctExpenseCategoryByYearAndUserId($year, $userId)
     {
        $sql ="SELECT DISTINCT expenseCategory FROM expenses WHERE YEAR(addedAt) = $year  AND userId =$userId;";
        $result = $this->db()->query($sql);
        $expenses = $result->fetch_all(MYSQLI_ASSOC);

        return $expenses;
     }

      //Get Last four expenses for loggedin user
     public function getLastFourExpense($userId)
     {
         $sql = "SELECT * FROM `expenses` WHERE userId=$userId ORDER BY id DESC LIMIT 4;";
         $result = $this->db()->query($sql);
         $num_rows = mysqli_num_rows($result);
         if ($num_rows > 1) {
             return $result->fetch_all(MYSQLI_ASSOC);
         }
 
     }

    //get all Expenses by mounth
    public function getAllExpenseByMounth($month)
    {
        $sql = "SELECT *  FROM `expenses` WHERE MONTH(addedAt) = $month ORDER BY STR_TO_DATE(`addedAt`, '%d/%m/%Y') ASC ;";
        $result = $this->db()->query($sql);
        $expenses= $result->fetch_all(MYSQLI_ASSOC);

        return $expenses;

    }
    function findExpenseById($id){
        $sql = "SELECT * FROM `expenses` WHERE `id` = $id;";
        $result = $this->db()->query($sql);
        $row = $result->fetch_assoc();
        return $row;
    }
    
     //Function delete expense
    public function deleteExpense($id)
    {
        $sql = "DELETE  FROM `expenses` WHERE `id` = ?";
        $myPrep = $this->db()->prepare($sql);
        if ($myPrep) {
            $myPrep->bind_param("i", $id);
            return $myPrep->execute();
        }else{
            echo "Error: " . $sql . "<br>" . $this->db()->error;
        }
    }
    
     //UPDATE expense
     public function updateExpense($addedAt, $userId, $category, $name, $value, $id)
     {
         // $id = $_GET['id'];
         $sql = "UPDATE `expenses` SET `addedAt` = ?, `userId`=?, `expenseCategory`=?, `name`=?, `value`=?  WHERE  `id` = $id";
         $myPrep = $this->db()->prepare($sql);
         $myPrep->bind_param("sissd", $addedAt, $userId, $category, $name, $value);
 
         return $myPrep->execute();
     }

     //Get total exenses current month
     public function getTotalExpensesCurrentMonth($userId)
     {
        $sql = "SELECT SUM(value) AS total from expenses  WHERE YEAR(addedAt) = YEAR(CURRENT_DATE()) AND  MONTH(addedAt) = MONTH(CURRENT_DATE()) AND userId = $userId";
        $result = $this->db()->query($sql);
        $row1 = $result->fetch_assoc();;
        return $row1['total'];
     }

     //Get total exenses current month by name
     public function getTotalExpensesCurrentMonthByName($userId, $searchTerm)
     {
        $sql = "SELECT SUM(value) AS total from expenses  
        WHERE YEAR(addedAt) = YEAR(CURRENT_DATE())
         AND  MONTH(addedAt) = MONTH(CURRENT_DATE()) 
         AND userId = $userId AND LOWER (`expenses`.`name`) LIKE '$searchTerm%';";
        $result = $this->db()->query($sql);
        $row1 = $result->fetch_assoc();;
        return $row1['total'];
     }

     //Get total exenses current month by category
     public function getTotalExpensesCurrentMonthByCategory($userId, $category)
     {
        $sql = "SELECT SUM(value) AS total from expenses  
        WHERE YEAR(addedAt) = YEAR(CURRENT_DATE())
         AND  MONTH(addedAt) = MONTH(CURRENT_DATE()) 
         AND userId = $userId AND `expenseCategory`='$category' ;";
        $result = $this->db()->query($sql);
        $row1 = $result->fetch_assoc();;
        return $row1['total'];
     }

     //Get total expenses by selected year, month, category and userId
     public function getTotalExpensesByYearMonthCategoryAndUserId($year, $month, $category, $userId)
     {
        $sql = "SELECT SUM(value) AS total from expenses  
        WHERE YEAR(addedAt) = $year
         AND  MONTH(addedAt) = $month 
         AND userId = $userId AND `expenseCategory`='$category' ;";
        $result = $this->db()->query($sql);
        $row1 = $result->fetch_assoc();;
        return $row1['total'];

     }
      //Get total expenses by selected year, month and userId
     public function getTotalExpensesByYearMonthAndUserId($year, $month, $userId)
     {
        $sql = "SELECT SUM(value) AS total from expenses  
        WHERE YEAR(addedAt) = $year
         AND  MONTH(addedAt) = $month 
         AND userId = $userId ;";
        $result = $this->db()->query($sql);
        $row1 = $result->fetch_assoc();;
        return $row1['total'];

     }
      //Get total expenses by selected year,  category and userId
     public function getTotalExpensesByYearCategoryAndUserId($year, $category, $userId)
     {
        $sql = "SELECT SUM(value) AS total from expenses  
        WHERE YEAR(addedAt) = $year
         AND userId = $userId AND `expenseCategory`='$category' ;";
        $result = $this->db()->query($sql);
        $row1 = $result->fetch_assoc();;
        return $row1['total'];

     }

        //Get total expenses by selected year and userId
     public function getTotalExpensesByYearAndUserId($year,  $userId)
     {
        $sql = "SELECT SUM(value) AS total from expenses  
        WHERE YEAR(addedAt) = $year
         AND userId = $userId ;";
        $result = $this->db()->query($sql);
        $row1 = $result->fetch_assoc();;
        return $row1['total'];

     }
    
}