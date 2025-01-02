<?php
class LoginController extends AppController
{
    public function __construct()
    {
        $this->init();
       
    }
    public function init()
    {
        //POST FORM
        $uName = $_POST['loginUsername'];
        $uPass = $_POST['loginPassword'];
        //CREATE USER OBJ AND CHECK IS AUTH
        $newUser = new UsersModel;
        $user= $newUser->getOne($uName);
        if(!empty($user) && password_verify($uPass, $user['password']))
        {
            session_start();
            $_SESSION['user'] = $uName;
            $role=$user['role'];
            $userName = $user['username'];
            
           
                //echo __FILE__;
            $date = new DateTime();
            $today = $date->format('Y-m-d');
            
            
            $data['title']="Login";
           if($role=="ADMIN")
           {
            $data['header']="";
            $data['mainContent']=$this->displayMainContent($today, $userName);
            $data['footer']=$this->render(APP_PATH.VIEWS.'adminFooterView.html', $data);
             echo $this->render(APP_PATH.VIEWS.'adminLayoutView.html',$data);
           }else{
            $data['mainContent']=$this->displayManagerMainContent($today, $userName);
            $data['header']="";
            $data['footer']=$this->render(APP_PATH.VIEWS.'managerFooterView.html', $data);
             echo $this->render(APP_PATH.VIEWS.'managerLayoutView.html',$data);
           }
            
        }else{
             $this->my_custom_css();
            $data['title'] = 'Home PAGE';
            $data['message']="Wrong user name or password";
            $data['mainContent']=$this->render(APP_PATH.VIEWS.'publicHomePageView.html', $data);
            echo $this->render(APP_PATH.VIEWS.'publicLayoutView.html',$data);
        }
    }

    public function my_custom_css() {
  echo '<style>
    p {color: red;
    font-size:2em;}
  </style>';
}
   
     public function displayMainContent($currentDate, $user)
   {
         $month = date('m', strtotime($currentDate));
        $todayDate = date('j', strtotime($currentDate)); 
        $day = date('D', strtotime($currentDate));
        $newUser = new UsersModel;
        $user= $newUser->getOne($user);
        $role = $user['role'];
        $output ="";
    
    $output .="<div id='header-wrapper'>";
    $output .="<div class='container'>";
    $output .='<header id="header">
                    <div class="inner">
                        <!-- Logo -->
                        <h1><a href="adminHome" id="logo">
                            <span class="icon fa-gem"></span>
                        </a></h1>
                        <!-- Nav -->
                       
                        <nav id="nav">
                        <ul>
                            <li class="current_page_item"><a href="adminHome">Home</a></li>
                            <li><a href="addIncomeForm"><i class="bi bi-plus-square-fill">Venit</i></a></li>
                            <li><a href="incomes">Venituri</a></li>
                            <li><a href="addExpenseForm"><i class="bi bi-plus-square-fill">Cheltuială</i></a></li>
                            <li><a href="expenses">Cheltuieli</a></li>
                            <li><a href="savings">Economii</a></li>
                            <li><a href="balance">Balanța lunară</a></li>
                            <li><a href="searchBalanceSheetForm">Bilanț</a></li>
                            <li><a href="logout">Logout</a></li>
                        </ul>
                        </nav>
                    </div>
                </header>';
    $output .='<div id="banner">';
    $output .= $this->welcomeMessage($currentDate, $user);
    $output .= $this->getMessage($currentDate, $user);
    $output .='</div>';//banner
    $output .='</div>';//container
    $output .='</div>';//headerWraper
    return $output;
   }

     public function displayManagerMainContent($currentDate, $user)
   {
        $month = date('m', strtotime($currentDate));
        $todayDate = date('j', strtotime($currentDate)); 
        $day = date('D', strtotime($currentDate));
        $newUser = new UsersModel;
        $user= $newUser->getOne($user);
        $role = $user['role'];
        $output ="";
    
    $output .="<div id='header-wrapper'>";
    $output .="<div class='container'>";
    $output .='<header id="header">
        <div class="inner">
            <!-- Logo -->
            <h1><a href="managerHome" id="logo">
                <span class="icon fa-gem"></span>
            </a></h1>
            <!-- Nav -->
           
            <nav id="nav">
            <ul>
                <li class="current_page_item"><a href="adminHome">Home</a></li>
                <li><a href="incomeCategories">Income Categories</a></li>
                <li><a href="expenseCategories">Expense Categories</a></li>
                <li><a href="logout">Logout</a></li>
            </ul>
            </nav>
        </div>
    </header>';
    $output .='<div id="banner">';
    $output .= $this->managerWelcomeMessage($currentDate, $user);
    $output .= $this->managerGetMessage($currentDate);
    $output .='</div>';//banner
    $output .='</div>';//container
    $output .='</div>';//headerWraper
     
    return $output;
   }

    public function managerWelcomeMessage($today, $loggedInUser)
    {
        $loggedInUser = $_SESSION['user'];
        $output ="";
        $todayDate = date('j', strtotime($today)); 
        $day = date('D', strtotime($today));
        $month = date('M', strtotime($today)); 
      
      
        if(($todayDate == 24 && $month == 'Dec') ||
        ($todayDate == 25 && $month == 'Dec') ||
        ($todayDate == 26 && $month == 'Dec'))
       {
         $output .= '<h3 class="welcome-message hlight-color">
                   Merry christmas   '. $loggedInUser.'  🎅🏼
                </h3>';
       }elseif($todayDate == 31 && $month == 'Dec') 
            
       {
         $output .= '<h3 class="welcome-message hlight-color">
                Happy new year  '. $loggedInUser.' 🥳 
                </h3>';
       }elseif(($todayDate == 01 && $month == 'Jan') ||
            ($todayDate == 02 && $month == 'Jan'))
            {
                $output .= '<h3 class="welcome-message hdark-color">
                Happy new year   '. $loggedInUser.' 🥳 
                </h3>';
            }else{
        switch($month)
        {
            case "Jan":
            $output .= '<h3 class="welcome-message hdark-color">
                            Welcome back  '. $loggedInUser.'
                    </h3>';
            break;
            case "Feb":
                $output .= '<h3 class="welcome-message hdark-color">
                          Welcome  back '. $loggedInUser.'
                    </h3>';
            break;
            case "Mar":
                $output .= '<h3 class="welcome-message hlight-color">
                            Welcome back  '. $loggedInUser.'  
                    </h3>';
            break;
            case "Apr":
                $output .= '<h3 class="welcome-message hdark-color">
                          Welcome  back '. $loggedInUser.' 
                    </h3>';
            break;
            case "May":
                $output .= '<h3 class="welcome-message hlight-color">
                           Welcome  back '. $loggedInUser.' 
                    </h3>';
            break;
            case "Jun":
                $output .= '<h3 class="welcome-message hdark-color">
                           Welcome  back '. $loggedInUser.'
                    </h3>';
            break;
            case "Jul":
                $output .= '<h3 class="welcome-message hdark-color">
                            Welcome  back '. $loggedInUser.' 
                    </h3>';
            break;
            case "Aug":
                $output .= '<h3 class="welcome-message hlight-color">
                           Welcome  back '. $loggedInUser.' 
                    </h3>';
            break;
            case "Sep":
                $output .= '<h3 class="welcome-message hdark-color">
                            Welcome  back '. $loggedInUser.'  
                    </h3>';
            break;
            case "Oct":
                $output .= '<h3 class="welcome-message hdark-color">
                           Welcome back '. $loggedInUser.'
                    </h3>';
            break;
            case "Nov":
                $output .= '<h3 class="welcome-message hdark-color">
                            Welcome  back '. $loggedInUser.' 
                    </h3>';
            break;
            case "Dec":
                $output .= '<h3 class="welcome-message hlight-color">
                            Welcome back '. $loggedInUser.'
                    </h3>';
            break;
            default:
            $output .= '<h3 class="welcome-message hlight-color">
                           Welcome back '. $loggedInUser.'
                    </h3>';
        }
       }
       return $output;
    }

    public function welcomeMessage($today, $loggedInUser)
    {
       
         $loggedInUser = $_SESSION['user'];
        $output ="";
        $todayDate = date('j', strtotime($today)); 
        $day = date('D', strtotime($today));
        $month = date('M', strtotime($today)); 
      
        if($todayDate == 22 && $month == "Feb" && $loggedInUser =="alexSergiu")
        {
            $output .= '<h3 class="welcome-message hdark-color">
                      Happy birthday Alex  🎉
                    </h3>';
        }elseif(($todayDate == 24 && $month == 'Dec') ||
        ($todayDate == 25 && $month == 'Dec') ||
        ($todayDate == 26 && $month == 'Dec'))
       {
         $output .= '<h3 class="welcome-message hlight-color">
                   Merry christmas   '. $loggedInUser.'  🎅🏼
                </h3>';
       }elseif($todayDate == 31 && $month == 'Dec') 
            
       {
         $output .= '<h3 class="welcome-message hlight-color">
                Happy new year  '. $loggedInUser.' 🥳 
                </h3>';
       }elseif(($todayDate == 01 && $month == 'Jan') ||
            ($todayDate == 02 && $month == 'Jan'))
            {
                $output .= '<h3 class="welcome-message hdark-color">
                Happy new year   '. $loggedInUser.' 🥳 
                </h3>';
            }
       elseif($todayDate == 07 && $month == "Jan" && ($loggedInUser =="calin" || $loggedInUser=="Calin" ))
       {
         $output .= '<h3 class="welcome-message hdark-color">
                      Happy birthday Călin  🎉
                    </h3>';    
       }else
       
       {
        switch($month)
        {
            case "Jan":
            $output .= '<h3 class="welcome-message hdark-color">
                            Welcome back  '. $loggedInUser.'
                    </h3>';
            break;
            case "Feb":
                $output .= '<h3 class="welcome-message hdark-color">
                          Welcome  back '. $loggedInUser.'
                    </h3>';
            break;
            case "Mar":
                $output .= '<h3 class="welcome-message hlight-color">
                            Welcome back  '. $loggedInUser.'  
                    </h3>';
            break;
            case "Apr":
                $output .= '<h3 class="welcome-message hdark-color">
                          Welcome  back '. $loggedInUser.' 
                    </h3>';
            break;
            case "May":
                $output .= '<h3 class="welcome-message hlight-color">
                           Welcome  back '. $loggedInUser.' 
                    </h3>';
            break;
            case "Jun":
                $output .= '<h3 class="welcome-message hdark-color">
                           Welcome  back '. $loggedInUser.'
                    </h3>';
            break;
            case "Jul":
                $output .= '<h3 class="welcome-message hdark-color">
                            Welcome  back '. $loggedInUser.' 
                    </h3>';
            break;
            case "Aug":
                $output .= '<h3 class="welcome-message hlight-color">
                           Welcome  back '. $loggedInUser.' 
                    </h3>';
            break;
            case "Sep":
                $output .= '<h3 class="welcome-message hdark-color">
                            Welcome  back '. $loggedInUser.'  
                    </h3>';
            break;
            case "Oct":
                $output .= '<h3 class="welcome-message hdark-color">
                           Welcome back '. $loggedInUser.'
                    </h3>';
            break;
            case "Nov":
                $output .= '<h3 class="welcome-message hdark-color">
                            Welcome  back '. $loggedInUser.' 
                    </h3>';
            break;
            case "Dec":
                $output .= '<h3 class="welcome-message hlight-color">
                            Welcome back '. $loggedInUser.'
                    </h3>';
            break;
            default:
            $output .= '<h3 class="welcome-message hlight-color">
                           Welcome back '. $loggedInUser.'
                    </h3>';
        }
       }
        
        
        return $output;
    }

    public function managerGetMessage($today){
         $output ="";
        $todayDate = date('j', strtotime($today)); 
        $day = date('D', strtotime($today));
        $month = date('M', strtotime($today)); 
        $quotes=array("Be kind to yourself. ",
                        "Mindset first, action second.",
                        "Just keep moving forward.",
                        "Confine yourself to the present."
                        );
        $quote = $quotes[array_rand($quotes, 1)];
        switch($month)
        {
            case "Jan":
            $output .= '<p class="welcome-message pdark-color">
                             '.$quote.'
                    </p>';
            break;
            case "Feb":
                $output .= '<p class="welcome-message plight-color">
                           '.$quote.'  
                    </p>';
            break;
            case "Mar":
                $output .= '<p class="welcome-message plight-color">
                               '.$quote.'   
                    </p>';
            break;
            case "Apr":
                $output .= '<p class="welcome-message pdark-color">
                             '.$quote.'    
                    </p>';
            break;
            case "May":
                $output .= '<p class="welcome-message plight-color">
                              '.$quote.'     
                    </p>';
            break;
            case "Jun":
                $output .= '<p class="welcome-message pdark-color">
                              '.$quote.' 
                    </p>';
            break;
            case "Jul":
                $output .= '<p class="welcome-message pdark-color">
                               '.$quote.' 
                    </p>';
            break;
            case "Aug":
                $output .= '<p class="welcome-message plight-color">
                            '.$quote.'  
                    </p>';
            break;
            case "Sep":
                $output .= '<p class="welcome-message pdark-color">
                               '.$quote.' 
                    </p>';
            break;
            case "Oct":
                $output .= '<p class="welcome-message pdark-color">
                             '.$quote.'  
                    </p>';
            break;
            case "Nov":
                $output .= '<p class="welcome-message pdark-color">
                              '.$quote.' 
                    </p>';
            break;
             case "Dec":
                $output .= '<p class="welcome-message plight-color">
                              '.$quote.'
                    </p>';
            break;
            default:
            $output .= '<p class="welcome-message dark-color">
                            '.$quote.'
                    </p>';
        }
        return $output;
        }
        
        
    

    public function getMessage($today, $user)
    {
        
        $sonQuotes=array("Sons are the anchors of a mother’s life.",
                "I told you today that I love you? Well I love you.",
                "The best gift I have to give you is my forever love.",
                "Nothing you can do will change the way I love you.",
                "I love you for the beautiful person you are, and that can never change.",
                "There’s no way to be a perfect mother and a million ways to be a good one.",
                "A dad for his son is always caring, while Mommy is always loving.",
                "In your eyes, I see my greatest gift; in my heart, I hold our unbreakable bond.",
                "My love for you is not tied to what you achieve but to who you are.",
                "Through all your wins and losses, my love has been the constant.",
                "I’ll always be your first cheerleader, no matter how tough the game of life gets.",
                "I don’t need a reason to love you, my love for you simply exists.",
                "When the world doubts you, know that my belief in you is unshakable.");
        $sonQuote =$sonQuotes[array_rand($sonQuotes, 1)];
        $output ="";
        $todayDate = date('j', strtotime($today)); 
        $day = date('D', strtotime($today));
        $month = date('M', strtotime($today)); 
        $quotes=array("Be kind to yourself. ",
                        "Mindset first, action second.",
                        "Just keep moving forward.",
                        "Confine yourself to the present."
                        );
        $quote = $quotes[array_rand($quotes, 1)];
        if($user=="alexSergiu"|| $user=="calin" || $user=="Calin"){
            switch($month)
        {
            case "Jan":
            $output .= '<p class="welcome-message pdark-color">
                           '.$sonQuote.' <span class=emoji-span>🫶🏼</span> 
                    </p>';
            break;
            case "Feb":
                $output .= '<p class="welcome-message plight-color">
                          '.$sonQuote.'   <span class=emoji-span>🫶🏼</span>
                    </p>';
            break;
            case "Mar":
                $output .= '<p class="welcome-message plight-color">
                            '.$sonQuote.' <span class=emoji-span>🫶🏼</span> 
                    </p>';
            break;
            case "Apr":
                $output .= '<p class="welcome-message pdark-color">
                        '.$sonQuote.' <span class=emoji-span>🫶🏼</span>   
                    </p>';
            break;
            case "May":
                $output .= '<p class="welcome-message plight-color">
                           '.$sonQuote.' <span class=emoji-span>🫶🏼</span>  
                    </p>';
            break;
            case "Jun":
                $output .= '<p class="welcome-message pdark-color">
                           '.$sonQuote.' <span class=emoji-span>🫶🏼</span>  
                    </p>';
            break;
            case "Jul":
                $output .= '<p class="welcome-message pdark-color">
                           '.$sonQuote.'  <span class=emoji-span>🫶🏼</span>
                    </p>';
            break;
            case "Aug":
                $output .= '<p class="welcome-message plight-color">
                         '.$sonQuote.' <span class=emoji-span>🫶🏼</span>  
                    </p>';
            break;
            case "Sep":
                $output .= '<p class="welcome-message pdark-color">
                           '.$sonQuote.'<span class=emoji-span>🫶🏼</span>
                    </p>';
            break;
            case "Oct":
                $output .= '<p class="welcome-message pdark-color">
                           '.$sonQuote.'  <span class=emoji-span>🫶🏼</span>
                    </p>';
            break;
            case "Nov":
                $output .= '<p class="welcome-message pdark-color">
                            '.$sonQuote.'<span class=emoji-span>🫶🏼</span>
                    </p>';
            break;
             case "Dec":
                $output .= '<p class="welcome-message plight-color">
                            '.$sonQuote.' <span class=emoji-span>🫶🏼</span>
                    </p>';
            break;
            default:
            $output .= '<p class="welcome-message dark-color">
                         '.$sonQuote.'<span class=emoji-span>🫶🏼</span>
                    </p>';
        }
        }else{
                switch($month)
        {
            case "Jan":
            $output .= '<p class="welcome-message pdark-color">
                             '.$quote.'
                    </p>';
            break;
            case "Feb":
                $output .= '<p class="welcome-message plight-color">
                           '.$quote.'  
                    </p>';
            break;
            case "Mar":
                $output .= '<p class="welcome-message plight-color">
                               '.$quote.'   
                    </p>';
            break;
            case "Apr":
                $output .= '<p class="welcome-message pdark-color">
                             '.$quote.'    
                    </p>';
            break;
            case "May":
                $output .= '<p class="welcome-message plight-color">
                              '.$quote.'     
                    </p>';
            break;
            case "Jun":
                $output .= '<p class="welcome-message pdark-color">
                              '.$quote.' 
                    </p>';
            break;
            case "Jul":
                $output .= '<p class="welcome-message pdark-color">
                               '.$quote.' 
                    </p>';
            break;
            case "Aug":
                $output .= '<p class="welcome-message plight-color">
                            '.$quote.'  
                    </p>';
            break;
            case "Sep":
                $output .= '<p class="welcome-message pdark-color">
                               '.$quote.' 
                    </p>';
            break;
            case "Oct":
                $output .= '<p class="welcome-message pdark-color">
                             '.$quote.'  
                    </p>';
            break;
            case "Nov":
                $output .= '<p class="welcome-message pdark-color">
                              '.$quote.' 
                    </p>';
            break;
             case "Dec":
                $output .= '<p class="welcome-message plight-color">
                              '.$quote.'
                    </p>';
            break;
            default:
            $output .= '<p class="welcome-message dark-color">
                            '.$quote.'
                    </p>';
        }
        }
        
        return $output;
    }
   
}