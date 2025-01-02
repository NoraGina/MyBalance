<?php
class SavingsModel extends DBModel
{
    protected $addedAt;
    protected $userId;
    protected $value;

    public function __construct($sAddedAt="2024", $sUserId=3, $sValue=200)
    {
        $this -> addedAt = $sAddedAt;
        $this -> userId = $sUserId;
        $this -> value = $sValue;
    }

    //add Expense
     public function addSavings($addedAt, $userId,  $value){
       
        $q = "INSERT INTO `savings`(`addedAt`, `userId`, `value`) VALUES (?, ?, ?);";
        // prepared statements
        $myPrep = $this->db()->prepare($q);
        // s - string, i - integer, d - double, b - blob
        if($myPrep){
            $myPrep->bind_param("sid",$addedAt, $userId, $value);
       
            return $myPrep->execute();
        }else{
            echo "Error: " . $q . "<br>" . $this->db()->error;
        }
       
        
        // $myPrep->close();
    }

     //get current savings for user
    public function getCurrentSavings($userId)
    {
        $sql ="SELECT * FROM `savings` WHERE YEAR(addedAt) = YEAR(CURRENT_DATE()) AND  MONTH(addedAt) = MONTH(CURRENT_DATE()) AND userId = $userId  ORDER BY STR_TO_DATE(`addedAt`, '%d/%m/%Y') ASC;";
         $result = $this->db()->query($sql);
        $savings = $result->fetch_all(MYSQLI_ASSOC);

        return $savings;
    }

      //get  savingss for user by month and year
    public function getCurrentSavingsByMonthYearAndUserId($userId,$month, $year)
    {
        $sql ="SELECT * FROM `savings` WHERE YEAR(addedAt) = $year AND  MONTH(addedAt) = $month AND userId = $userId  ORDER BY STR_TO_DATE(`addedAt`, '%d/%m/%Y') ASC;";
        $result = $this->db()->query($sql);
        $savings = $result->fetch_all(MYSQLI_ASSOC);

        return $savings;
    }

      //get  savings for user by  year
    public function getCurrentSavingsByYearAndUserId($userId, $year)
    {
        $sql ="SELECT * FROM `savings` WHERE YEAR(addedAt) = $year  AND userId = $userId  ORDER BY STR_TO_DATE(`addedAt`, '%d/%m/%Y') ASC;";
        $result = $this->db()->query($sql);
        $savings = $result->fetch_all(MYSQLI_ASSOC);

        return $savings;
    }

    //get savings by id
    function findSavingsById($id){
        $sql = "SELECT * FROM `savings` WHERE `id` = $id;";
        $result = $this->db()->query($sql);
        $row = $result->fetch_assoc();
        return $row;
    }

     //Function delete expense
    public function deleteSaving($id)
    {
        $sql = "DELETE  FROM `savings` WHERE `id` = ?";
        $myPrep = $this->db()->prepare($sql);
        if ($myPrep) {
            $myPrep->bind_param("i", $id);
            return $myPrep->execute();
        }else{
            echo "Error: " . $sql . "<br>" . $this->db()->error;
        }
    }
    
     //UPDATE expense
     public function updateSavings($addedAt, $userId, $value, $id)
     {
         
         $sql = "UPDATE `savings` SET `addedAt` = ?, `userId`=?, `value`=?  WHERE  `id` = $id";
         $myPrep = $this->db()->prepare($sql);
         if($myPrep)
         {
            $myPrep->bind_param("sid", $addedAt, $userId, $value);
 
            return $myPrep->execute();
         }
         
     }

     //Get total savings current  for user
     public function getTotalSavingsCurrentMonth($userId)
     {
        $sql = "SELECT SUM(value) AS total from `savings`  WHERE YEAR(addedAt) = YEAR(CURRENT_DATE()) AND  MONTH(addedAt) = MONTH(CURRENT_DATE()) AND userId = $userId";
        $result = $this->db()->query($sql);
        $row = $result->fetch_assoc();;
        return $row['total'];
     }

      //Get total expenses by selected year, month, category and userId
     public function getTotalSavingsByYearMonthAndUserId($year, $month, $userId)
     {
        $sql = "SELECT SUM(value) AS total from savings  
        WHERE YEAR(addedAt) = $year
         AND  MONTH(addedAt) = $month 
         AND userId = $userId  ;";
        $result = $this->db()->query($sql);
        $row = $result->fetch_assoc();;
        return $row['total'];

     }
      //Get total expenses by selected year and userId
     public function getTotalSavingsByYearAndUserId($year, $userId)
     {
        $sql = "SELECT SUM(value) AS total from savings  
        WHERE YEAR(addedAt) = $year
         AND userId = $userId ;";
        $result = $this->db()->query($sql);
        $row = $result->fetch_assoc();;
        return $row['total'];

     }


}