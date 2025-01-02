<?php
class UpdateExpenseController extends AppController
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
            $id = $_GET['id'];
            $addedAt =$_POST['addedAt'];
            $userId = $_POST['userId'];
            $category = $_POST['expenseCategory'];
            $name = $_POST['name'];
            $postvalue = $_POST['value'];
            $value = round($postvalue, 2);
            $expensesModel = new ExpensesModel;
            $updatedExpense = $expensesModel->updateExpense($addedAt, $userId, $category, $name, $value, $id);
            if($updatedExpense)
            {
                 header("Refresh:0 ; url=../expenses");
            }else{
                $data['title']="Admin dasboard";
                $data['error']="Somethink went wrong the position was not updated!";
                $data['footer']=$this->render(APP_PATH.VIEWS.'adminFooterView.html', $data);
                $data['header']=$this->render(APP_PATH.VIEWS.'adminHeaderView.html', $data);
                $data['mainContent'] = $this->render(APP_PATH.VIEWS.'errorsView.html', $data);
                echo $this->render(APP_PATH.VIEWS.'adminLayoutView.html',$data);
            }
        }else{
            header("Location:home");
        }
    }
}