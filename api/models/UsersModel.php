<?php
class UsersModel extends DBModel
{
    protected $username;
    protected $email;
    protected $password;
    protected $role;
    protected $uniqueNumber;

    public function __construct( $uUsername='D', $uEmail='email', 
                    $upassword='dfddfsff', $uRole='cust', $uUniqueNumber="214sa")
    {
       
        $this->username = $uUsername;
        $this->email = $uEmail;
        $this->password = $upassword;
        $this->role =$uRole;
        $this->uniqueNumber =$uUniqueNumber;
    }
    
    //add user
    public function addUser($user, $email, $password, $uRole, $uUniqueNumber )
    {
        
        $sql = "INSERT INTO `users`(`username`, `email`,  `password`, `role`, `uniqueNumber`) VALUES (?, ?, ?, ?, ?);";
        // prepared statements
        $myPrep = $this->db()->prepare($sql);
        // s - string, i - integer, d - double, b - blob
        if($myPrep){
            $myPrep->bind_param("sssss", $user, $email,  $password, $uRole, $uUniqueNumber);
       
            return $myPrep->execute();
        }else{
            echo "Error: " . $sql . "<br>" . $this->db()->error;
        }
        
        
        
        // $myPrep->close();
    }

    //check username and password for login
    public function isAuth($uName, $uPass){
        $sql = "SELECT * FROM `users` WHERE `username`= ? ";
        $myPrep = $this->db()->prepare($sql);
       
        if ($myPrep === TRUE) {
                     //  echo "New records selected successfully". $sql . "<br>";
                     $myPrep->bind_param("s", $uName);
                    $myPrep->execute();
                    $result = $myPrep->get_result()->fetch_assoc();
                    //var_dump($result);
                    //echo $result['password'];
                    if(password_verify($uPass, $result['hashedPassword'])){
                            return $result;
                        }
                        else{
                            return false;
                        } 
        }else{
            echo "Error: " . $sql . "<br>" . $this->db()->error;
        }
          
        
    }

     //function get one user user   
     public function getOne($uName){
        $sql = "SELECT * FROM `users` WHERE `username` = ? ; ";
        $myPrep = $this->db()->prepare($sql);
        if($myPrep){
            $myPrep->bind_param("s", $uName);
            $myPrep->execute();
            $result = $myPrep->get_result();
            return $result->fetch_assoc();
        }else{
            echo "Error: " . $sql . "<br>" . $this->db()->error;
        }
        
    }

     //function get user by email   
     public function getuserByEmail($uEmail){
        $sql = "SELECT * FROM `users` WHERE `email` = ? ; ";
        $myPrep = $this->db()->prepare($sql);
        if($myPrep){
            $myPrep->bind_param("s", $uEmail);
            $myPrep->execute();
            $result = $myPrep->get_result();
            return $result->fetch_assoc();
        }else{
            echo "Error: " . $sql . "<br>" . $this->db()->error;
        }
        
    }

    public function updateUser($id, $username, $email, $password)
    {
        $sql ="UPDATE `users` SET `username` = ?, `email`=?, `password`=?  WHERE  `id` = $id";
         $myPrep = $this->db()->prepare($sql);
         $myPrep->bind_param("sss", $username, $email, $password);
 
         return $myPrep->execute();
    }
}