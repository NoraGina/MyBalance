<?php
class IncomesModel extends DBModel
{
    protected $addedAt;
    protected $user;
    protected $incomeCategory;
    protected $name;
    protected $value;

    public function __construct($iAdded="2025", $iUser=3, $iCategory="Salarii", $iName="salar", $iValue=20.20)
    {
        $this->addedAt=$iAdded;
        $this->user=$iUser;
        $this->incomeCategory=$iCategory;
        $this->name=$iName;
        $this->value=$iValue;
    }
     //add Income
     public function addIncome($addedAt, $userId, $incomeCategory, $name, $value){
       
        $q = "INSERT INTO `incomes`(`addedAt`, `userId`, `incomeCategory`,`name`, `value`) VALUES (?, ?, ?, ?, ?);";
        // prepared statements
        $myPrep = $this->db()->prepare($q);
        // s - string, i - integer, d - double, b - blob
        if($myPrep){
            $myPrep->bind_param("sissd",$addedAt, $userId, $incomeCategory, $name, $value);
       
            return $myPrep->execute();
        }else{
            echo "Error: " . $q . "<br>" . $this->db()->error;
        }
       
        
        // $myPrep->close();
    }

    //get current incomes for user
    public function getCurrentIncomes($userId)
    {
        $sql ="SELECT * FROM `incomes` WHERE YEAR(addedAt) = YEAR(CURRENT_DATE()) AND  MONTH(addedAt) = MONTH(CURRENT_DATE()) AND userId = $userId  ORDER BY STR_TO_DATE(`addedAt`, '%d/%m/%Y') ASC;";
         $result = $this->db()->query($sql);
        $incomes= $result->fetch_all(MYSQLI_ASSOC);

        return $incomes;
    }

     //Get  incomes current month by category notNull
     public function getTotalIncomesCurrentMonthByCategoryNotNull($userId)
     {
        $sql = "SELECT incomeCategory FROM incomes WHERE YEAR(addedAt) = YEAR(CURRENT_DATE()) AND  MONTH(addedAt) = MONTH(CURRENT_DATE()) AND  incomeCategory IS NOT NULL AND userId =$userId ;";
        $result = $this->db()->query($sql);
        $expenses = $result->fetch_all(MYSQLI_ASSOC);

        return $expenses;
     }

      //get expenseCategory distinct
     public function getCurrentDistinctIncomesCategoryForUser($userId)
     {
        $sql ="SELECT DISTINCT incomeCategory FROM incomes WHERE YEAR(addedAt) = YEAR(CURRENT_DATE()) AND  MONTH(addedAt) = MONTH(CURRENT_DATE())  AND userId =$userId;";
        $result = $this->db()->query($sql);
        $incomes = $result->fetch_all(MYSQLI_ASSOC);

        return $incomes;
     }

     //get incomeCategory distinct by selected year, month and user
     public function getDistinctIncomeCategoryByMonthYearAndUserId($year, $month, $userId)
     {
        $sql ="SELECT DISTINCT incomeCategory FROM incomes WHERE YEAR(addedAt) = $year AND MONTH(addedAt) = $month AND userId =$userId;";
        $result = $this->db()->query($sql);
        $expenses = $result->fetch_all(MYSQLI_ASSOC);

        return $expenses;
     }
     //get incomeCategory distinct by selected year and user
     public function getDistinctIncomeCategoryByYearAndUserId($year, $userId)
     {
        $sql ="SELECT DISTINCT incomeCategory FROM incomes WHERE YEAR(addedAt) = $year  AND userId =$userId;";
        $result = $this->db()->query($sql);
        $expenses = $result->fetch_all(MYSQLI_ASSOC);

        return $expenses;
     }

      //Get Last four incomes for loggedin user
     public function getLastFourIncomes($userId)
     {
         $sql = "SELECT * FROM `incomes` WHERE userId=$userId ORDER BY id DESC LIMIT 4;";
         $result = $this->db()->query($sql);
         $num_rows = mysqli_num_rows($result);
         if ($num_rows > 1) {
             return $result->fetch_all(MYSQLI_ASSOC);
         }
 
     }

    //get all Incomes by mounth
    public function getAllIncomesByYearMounthAndUserId($year, $month, $userId)
    {
        $sql = "SELECT *  FROM `incomes` WHERE YEAR(addedAt) = $year AND
         MONTH(addedAt) = $month AND userId= $userId 
          ORDER BY STR_TO_DATE(`addedAt`, '%d/%m/%Y') ASC ;";
        $result = $this->db()->query($sql);
        $incomes= $result->fetch_all(MYSQLI_ASSOC);

        return $incomes;

    }
    function findIncomeById($id){
        $sql = "SELECT * FROM `incomes` WHERE `id` = $id;";
        $result = $this->db()->query($sql);
        $row = $result->fetch_assoc();
        return $row;
    }
    
     //Function delete income
    public function deleteIncome($id)
    {
        $sql = "DELETE  FROM `incomes` WHERE `id` = ?";
        $myPrep = $this->db()->prepare($sql);
        if ($myPrep) {
            $myPrep->bind_param("i", $id);
            return $myPrep->execute();
        }else{
            echo "Error: " . $sql . "<br>" . $this->db()->error;
        }
    }
    
     //UPDATE income
     public function updateIncome($addedAt, $userId, $category, $name, $value, $id)
     {
         // $id = $_GET['id'];
         $sql = "UPDATE `incomes` SET `addedAt` = ?, `userId`=?, `incomeCategory`=?, `name`=?, `value`=?  WHERE  `id` = $id";
         $myPrep = $this->db()->prepare($sql);
         $myPrep->bind_param("sissd", $addedAt, $userId, $category, $name, $value);
 
         return $myPrep->execute();
     }

      //Get total incomes current month
     public function getTotalIncomesCurrentMonth($userId)
     {
        $sql = "SELECT SUM(value) AS total from incomes  WHERE YEAR(addedAt) = YEAR(CURRENT_DATE()) AND  MONTH(addedAt) = MONTH(CURRENT_DATE()) AND userId = $userId";
        $result = $this->db()->query($sql);
        $row1 = $result->fetch_assoc();;
        return $row1['total'];
     }
      //Get total incomes by year, month and userId
     public function getTotalIncomesByYearMonthAndUserId($year, $month, $userId)
     {
        $sql = "SELECT SUM(value) AS total from incomes  WHERE YEAR(addedAt) = $year AND  MONTH(addedAt) = $month AND userId = $userId";
        $result = $this->db()->query($sql);
        $row1 = $result->fetch_assoc();;
        return $row1['total'];
     }

     //Get total incomes current month by category
     public function getTotalIncomesCurrentMonthByCategory($userId, $category)
     {
        $sql = "SELECT SUM(value) AS total from incomes 
        WHERE YEAR(addedAt) = YEAR(CURRENT_DATE())
         AND  MONTH(addedAt) = MONTH(CURRENT_DATE()) 
         AND userId = $userId AND `incomeCategory`='$category' ;";
        $result = $this->db()->query($sql);
        $row1 = $result->fetch_assoc();;
        return $row1['total'];
     }

     //Get total incomes by selected year, month, category and userId
     public function getTotalIncomesByYearMonthCategoryAndUserId($year, $month, $category, $userId)
     {
        $sql = "SELECT SUM(value) AS total FROM incomes
        WHERE YEAR(addedAt) = $year AND MONTH(addedAt) = $month 
        AND userId = $userId AND `incomeCategory` = '$category';";
        $result = $this->db()->query($sql);
        $row1 = $result->fetch_assoc();;
        return $row1['total'];

     }
      //Get total incomes by selected year, category and userId
     public function getTotalIncomesByYearCategoryAndUserId($year, $category, $userId)
     {
        $sql = "SELECT SUM(value) AS total FROM incomes
        WHERE YEAR(addedAt) = $year
        AND incomeCategory = '$category'
        AND userId = $userId ;";
        $result = $this->db()->query($sql);
        $row1 = $result->fetch_assoc();;
        return $row1['total'];

     }
       //Get total incomes by selected year and userId
     public function getTotalIncomesByYearAndUserId($year, $userId)
     {
        $sql = "SELECT SUM(value) AS total FROM incomes
        WHERE YEAR(addedAt) = $year
        AND userId = $userId ;";
        $result = $this->db()->query($sql);
        $row1 = $result->fetch_assoc();;
        return $row1['total'];

     }
}