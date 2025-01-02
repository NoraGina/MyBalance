<?php
class DeleteIncomeController extends AppController
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
           
            $incomesModel = new IncomesModel;
            $deletedIncome = $incomesModel->deleteIncome($id);
            if($deletedIncome){
                header("Refresh:0 ; url=../incomes");
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