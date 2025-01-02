<?php
class AddExpenseCategoryFormController extends AppController
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
           
            $data['title']="Add expense category";
            $data['header'] = $this->render(APP_PATH.VIEWS.'managerHeaderView.html', $data);
            $data['footer'] = $this->render(APP_PATH.VIEWS.'managerFooterView.html', $data);
            $data['mainContent'] = $this->render(APP_PATH.VIEWS.'addExpenseCategoryFormView.html');
             echo $this->render(APP_PATH.VIEWS.'managerLayoutView.html',$data);
        }else{
             header("Location:home");
        }
    }
}