<?php
class AddIncomeCategoryFormController extends AppController
{
    public function __construct(){
        $this->init();
    }

    public function init()
    {
         session_start();
        if(isset($_SESSION['user']))
        {
             $data['title']="Manager add income category";
             $data['footer']=$this->render(APP_PATH.VIEWS.'managerFooterView.html', $data);
             $data['header']=$this->render(APP_PATH.VIEWS.'managerHeaderView.html', $data);
             $data['mainContent'] = $this->render(APP_PATH.VIEWS.'addIncomeCategoryFormView.html', $data);
             echo $this->render(APP_PATH.VIEWS.'managerLayoutView.html',$data);
        }
    }
}