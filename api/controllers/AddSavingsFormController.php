<?php
class AddSavingsFormController extends AppController
{
    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        session_start();
        if(isset($_SESSION['user']))
        {
            $loggedIn = $_SESSION['user'];
            $data['title']="$loggedIn add savings";
            $data['footer']=$this->render(APP_PATH.VIEWS.'adminFooterView.html', $data);
            $data['header']=$this->render(APP_PATH.VIEWS.'adminHeaderView.html', $data);
            $data['user'] =$this->displayUser($loggedIn);
            $data['mainContent'] = $this->render(APP_PATH.VIEWS.'addSavingsFormView.html', $data);
            echo $this->render(APP_PATH.VIEWS.'adminLayoutView.html',$data);
        }else{
            header("Location:home");
        }
    }

      public function displayUser($user)
    {
        $output ='';
        $usersModel = new UsersModel;
        $loggedIn = $usersModel->getOne($user);
        $userId =$loggedIn['id'];

        $output .="<div class='add-form-group'>
                <span class='add-span'>User<span class='text-danger'> *</span> </span>
                <input type='text' name='userId' class='add-input' readOnly value='".$userId."'>
                </div>";
        return $output;
    }
}