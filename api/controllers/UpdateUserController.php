<?php
class UpdateUserController extends AppController
{
    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $id = $_GET['id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $pwd = $_POST['password'];
        $password = password_hash($pwd, PASSWORD_DEFAULT);
        $usersModel = new UsersModel;
        $updateduser = $usersModel->updateUser($id, $username, $email, $password);
        $data['title']="Update user";
        if($updateduser)
        {
            $this->successText();
            
            $data['message']="$username successfully updated";
            $data['mainContent']=$this->render(APP_PATH.VIEWS.'publicHomePageView.html', $data);
        }else{
           
            $data['message']="$username not updated";
            $this->errorText();
            $data['mainContent']=$this->render(APP_PATH.VIEWS.'publicHomePageView.html', $data);
        }
        echo $this->render(APP_PATH.VIEWS.'publicLayoutView.html',$data);
    }

    public function errorText() 
    {
        echo '<style>
            p {color: red;
            font-size:2em;}
        </style>';
    }
     public function successText() 
    {
        echo '<style>
            p {color: white;
            font-size:2em;}
        </style>';
    }
}