<?php
class DeleteExpenseController extends AppController
{
      public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        
         session_start();
         if(isset($_SESSION['user'])){
            $id=$_GET['id'];
           
            $expensesModel = new ExpensesModel;
            $deletedExpense = $expensesModel->deleteExpense($id);
            if($deletedExpense){
                header("Refresh:0 ; url=../expenses");
            }else{
                $data['title']="Admin dasboard";
                $data['error']="Something happened, the position was not deleted.";
                $data['footer']=$this->render(APP_PATH.VIEWS.'adminFooterView.html', $data);
                $data['header']=$this->render(APP_PATH.VIEWS.'adminHeaderView.html', $data);
                $data['mainContent'] = $this->render(APP_PATH.VIEWS.'errorsView.html', $data);
                echo $this->render(APP_PATH.VIEWS.'adminLayoutView.html',$data);
            }
         }
         else{
             header("Location:home");
         }
    }
}