<?php
class AddIncomeCategoryController extends AppController
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
            $name = $_POST['name'];
            $data['title']="Add category";
            $incomeCategoryModel = new IncomeCategoriesModel;
            $newIncomeCategory=$incomeCategoryModel->addIncomeCategory($name);
            if($newIncomeCategory)
            {
                header("Refresh:0; url=incomeCategories");
            }else{
                $data['title']="Manager dasboard";
                $data['error']="Somethink went wrong cannot add into database!";
                $data['footer']=$this->render(APP_PATH.VIEWS.'managerFooterView.html', $data);
                $data['header']=$this->render(APP_PATH.VIEWS.'managerHeaderView.html', $data);
                $data['mainContent'] = $this->render(APP_PATH.VIEWS.'errorsView.html', $data);
                echo $this->render(APP_PATH.VIEWS.'managerLayoutView.html',$data);
            }
        
        }else{
            header("Location:home");
        }
    }
}