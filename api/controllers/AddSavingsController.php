<?php
class AddSavingsController extends AppController
{
    public function __construct()
    {
        $this -> init();
    }

    public function init()
    {
        session_start();
        if(isset($_SESSION['user']))
        {
            $addedAt = $_POST['addedAt'];
            $userId = $_POST['userId'];
            $postvalue = $_POST['value'];
            $value = round($postvalue, 2);
            $savingsModel = new SavingsModel;
            $newSaving = $savingsModel->addSavings($addedAt, $userId, $value);
            if($newSaving)
            {
                 header("Refresh:0; url=savings");
            }else{
                $data['title']="Admin add saving";
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