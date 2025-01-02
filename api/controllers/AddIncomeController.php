<?php
class AddIncomeController extends AppController
{
    public function __construct(){
        $this->init();
    }

    public function init()
    {
        session_start();
        if(isset($_SESSION['user']))
        {
            $addedAt = $_POST['addedAt'];
            $userId = $_POST['userId'];
            $incomeCategory = $_POST['incomeCategory'];
            $name = $_POST['name'];
            $postvalue = $_POST['value'];
            $value = round($postvalue, 2);
            $incomesModel = new incomesModel;
            $newIncome = $incomesModel->addIncome($addedAt, $userId, $incomeCategory, $name, $value);
            if($newIncome)
            {
                header("Refresh:0; url=incomes");
            }else{
                $data['title']="Admin dasboard";
                $data['error']="Ceva s-a întâmplat, poziția nu a fost adăugată în baza de date!";
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