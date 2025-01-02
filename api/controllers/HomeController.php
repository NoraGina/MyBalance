<?php
class HomeController extends AppController
{
     public function __construct()
    {
        $this->init();
    }
    public function init()
    {
      
               $data['title']="Mounthly budget";
               $data['message']="Let your dreams be your wings";
               
               $data['mainContent']=$this->render(APP_PATH.VIEWS.'publicHomePageView.html', $data);
           // echo $this->render(APP_PATH.VIEWS.'homePageView.html',$data);
            echo $this->render(APP_PATH.VIEWS.'publicLayoutView.html',$data);

            
        
    }

   

   
   
}