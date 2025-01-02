<?php
class AddIncomeFormController extends AppController
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
            $incomeCategoriesModel = new IncomeCategoriesModel;
            $incomeCategories = $incomeCategoriesModel->getAllIncomeCategories();
            $data['title']="Manager add income category";
            $data['footer']=$this->render(APP_PATH.VIEWS.'adminFooterView.html', $data);
            $data['header']=$this->render(APP_PATH.VIEWS.'adminHeaderView.html', $data);
            $data['user'] =$this->displayUser($loggedIn);
            $data['categories']=$this->bindIncomeCategoriesIntoSelect($incomeCategories);
            $data['mainContent'] = $this->render(APP_PATH.VIEWS.'addIncomeFormView.html', $data);
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

    public function bindIncomeCategoriesIntoSelect($incomeCategories)
    {
        $output = '';
        if (is_array($incomeCategories)) {
            foreach ($incomeCategories as $row) {
                $output .= '<option value="' . $row["name"] . '">' . $row["name"] . '</option>';
            }
            return $output;
        }
    }
}
