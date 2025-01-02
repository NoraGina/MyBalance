<?php
class SearchSavingsFormController extends AppController
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
            $loggedIn = $_SESSION['user'];
            $data['title']="$loggedIn search savings";
            $data['footer']=$this->render(APP_PATH.VIEWS.'adminFooterView.html', $data);
            $data['header']=$this->render(APP_PATH.VIEWS.'adminHeaderView.html', $data);
            $data['mainContent'] = $this->render(APP_PATH.VIEWS.'searchSavingsFormView.html', $data);
            echo $this->render(APP_PATH.VIEWS.'adminLayoutView.html',$data);

        }else{
            header('Location:home');
        }
    }
}