<?php
 class UpdateExpenseCategoryController extends AppController
{
    public function __construct()
    {
        $this->init();
    }
    public function init()
    {
         session_start();
         if(isset($_SESSION['user'])){
            $id = $_GET['id'];
            $name = $_POST['name'];
            $expenseCategoryModel = new ExpenseCategoriesModel;
            $updatedExpenseCategory=$expenseCategoryModel->updateExpenseCategory($name, $id);
            if($updatedExpenseCategory){
                header("Refresh:0 ; url=../expenseCategories");
            }else{
                $data['title']="Manager dasboard";
                $data['error']="Somethink went wrong cannot the position was not updated!";
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