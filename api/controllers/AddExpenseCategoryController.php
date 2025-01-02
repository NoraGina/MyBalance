<?php
class AddExpenseCategoryController extends AppController
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
            $data['title']="Add expense category";
            $expenseCategoryModel = new ExpenseCategoriesModel;
            $newExpenseCategory=$expenseCategoryModel->addExpenseCategory($name);
            if($newExpenseCategory)
            {
                header("Refresh:0; url=expenseCategories");
            }else{
                $em = "Somethink went wrong cannot add into database!";
                $data['error']=$em;
                $data['header'] = $this->render(APP_PATH.VIEWS.'managerHeaderView.html', $data);
                $data['footer'] = $this->render(APP_PATH.VIEWS.'managerFooterView.html', $data);
                $data['mainContent'] = $this->render(APP_PATH.VIEWS.'errorsView.html', $data);
                echo $this->render(APP_PATH.VIEWS.'managerLayoutView.html',$data);
            }
        }else{
            header("Location:home");
           
        }
    }
}