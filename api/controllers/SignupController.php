<?php
class SignupController extends AppController
{
    public function __construct()
    {
        $this->init();
    }
    public function init()
    {
         //FORM DATA
        
         $uUsername = $_POST['signupUsername'];
         $uEmail = $_POST['signupEmail'];
        
         $uPass = $_POST['signupPassword'];
         $uRole ='ADMIN';
         $uniq = uniqid();
         $uUniqueNumber= substr($uniq, -6);   
         
         $hash = password_hash($uPass, PASSWORD_DEFAULT);
        //INSTANTIATING A NEW USER
         $userModel = new UsersModel;
         if($userModel->addUser( $uUsername, $uEmail, $hash, $uRole, $uUniqueNumber))
         {
            session_start();
            
            $_SESSION['user']= $uUsername;
            $loggedInUser = $_SESSION['user'];
             $date = new DateTime();
            $today = $date->format('Y-m-d');
            $data['title'] = ' Admin Home PAGE';
            $data['mainContent']=$this->displayMainContent($today, $loggedInUser);
            $data['footer']=$this->render(APP_PATH.VIEWS.'adminFooterView.html', $data);
            
            echo $this->render(APP_PATH.VIEWS.'adminLayoutView.html',$data);
            
         }else{
            $data['title'] = 'Signup';
            //$data['mainContent'] =$this->render(APP_PATH.VIEWS.'mainPublicHomeView.html', $data);
            $data['mainContent'] ="<h2 class='fst-italic text-danger'>Username already associated with another account. Please try with diffrent username.' </h2>";
            echo $this->render(APP_PATH.VIEWS.'publicLayoutView.html',$data);
         }
    }

    public function displayMainContent($currentDate, $user)
   {
     $month = date('m', strtotime($currentDate));
      $todayDate = date('j', strtotime($currentDate)); 
        $day = date('D', strtotime($currentDate));
        
    $output ="";
    
    $output .= "<div id='header-wrapper'>";
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
                            <li><a href="addIncomesForm"><i class="bi bi-plus-square-fill">Venit</i></a></li>
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
    $output .='</div>';//container
    $output .="</div>";//headerWraper
    return $output;
   }

    public function welcomeMessage($today, $loggedInUser)
    {
        $output ="";
        $todayDate = date('j', strtotime($today)); 
        $day = date('D', strtotime($today));
        $month = date('M', strtotime($today)); 
        $commonUser = strtolower($loggedInUser);
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
       elseif($todayDate == 07 && $month == "Jan" && $commonUser =="calin")
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
                            Welcome   '. $loggedInUser.'
                    </h3>';
            break;
            case "Feb":
                $output .= '<h3 class="welcome-message hdark-color">
                          Welcome   '. $loggedInUser.'
                    </h3>';
            break;
            case "Mar":
                $output .= '<h3 class="welcome-message hlight-color">
                            Welcome   '. $loggedInUser.'  
                    </h3>';
            break;
            case "Apr":
                $output .= '<h3 class="welcome-message hdark-color">
                          Welcome   '. $loggedInUser.' 
                    </h3>';
            break;
            case "May":
                $output .= '<h3 class="welcome-message hlight-color">
                           Welcome   '. $loggedInUser.' 
                    </h3>';
            break;
            case "Jun":
                $output .= '<h3 class="welcome-message hdark-color">
                           Welcome   '. $loggedInUser.'
                    </h3>';
            break;
            case "Jul":
                $output .= '<h3 class="welcome-message hdark-color">
                            Welcome   '. $loggedInUser.' 
                    </h3>';
            break;
            case "Aug":
                $output .= '<h3 class="welcome-message hlight-color">
                           Welcome  '. $loggedInUser.' 
                    </h3>';
            break;
            case "Sep":
                $output .= '<h3 class="welcome-message hdark-color">
                            Welcome  '. $loggedInUser.'  
                    </h3>';
            break;
            case "Oct":
                $output .= '<h3 class="welcome-message hdark-color">
                           Welcome '. $loggedInUser.'
                    </h3>';
            break;
            case "Nov":
                $output .= '<h3 class="welcome-message hdark-color">
                            Welcome   '. $loggedInUser.' 
                    </h3>';
            break;
            case "Dec":
                $output .= '<h3 class="welcome-message hlight-color">
                            Welcome '. $loggedInUser.'
                    </h3>';
            break;
            default:
            $output .= '<h3 class="welcome-message hlight-color">
                           Welcome  '. $loggedInUser.'
                    </h3>';
        }
       }
        
        
        return $output;
    }
    public function getMessage($today, $user)
    {
        $commonUser = strtolower($user);
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
        if($user=="alexSergiu"|| $commonUser=="calin"){
            switch($month)
        {
            case "Jan":
            $output .= '<h3 class="welcome-message pdark-color">
                           '.$sonQuote.' <span class=emoji-span>🫶🏼</span> 
                    </h3>';
            break;
            case "Feb":
                $output .= '<h3 class="welcome-message plight-color">
                          '.$sonQuote.'  <span class=emoji-span>🫶🏼</span> 
                    </h3>';
            break;
            case "Mar":
                $output .= '<h3 class="welcome-message plight-color">
                            '.$sonQuote.'  <span class=emoji-span>🫶🏼</span>
                    </h3>';
            break;
            case "Apr":
                $output .= '<h3 class="welcome-message pdark-color">
                        '.$sonQuote.'    <span class=emoji-span>🫶🏼</span>
                    </h3>';
            break;
            case "May":
                $output .= '<h3 class="welcome-message plight-color">
                           '.$sonQuote.'   <span class=emoji-span>🫶🏼</span>  
                    </h3>';
            break;
            case "Jun":
                $output .= '<h3 class="welcome-message pdark-color">
                           '.$sonQuote.'   <span class=emoji-span>🫶🏼</span>
                    </h3>';
            break;
            case "Jul":
                $output .= '<h3 class="welcome-message pdark-color">
                           '.$sonQuote.'  <span class=emoji-span>🫶🏼</span>
                    </h3>';
            break;
            case "Aug":
                $output .= '<h3 class="welcome-message plight-color">
                         '.$sonQuote.'  <span class=emoji-span>🫶🏼</span> 
                    </h3>';
            break;
            case "Sep":
                $output .= '<h3 class="welcome-message pdark-color">
                           '.$sonQuote.' <span class=emoji-span>🫶🏼</span>
                    </h3>';
            break;
            case "Oct":
                $output .= '<h3 class="welcome-message pdark-color">
                           '.$sonQuote.'  <span class=emoji-span>🫶🏼</span>
                    </h3>';
            break;
            case "Nov":
                $output .= '<h3 class="welcome-message pdark-color">
                            '.$sonQuote.' <span class=emoji-span>🫶🏼</span>
                    </h3>';
            break;
             case "Dec":
                $output .= '<p class="welcome-message plight-color">
                            '.$sonQuote.' <span class=emoji-span>🫶🏼</span>
                    </p>';
            break;
            default:
            $output .= '<p class="welcome-message dark-color">
                         '.$sonQuote.' <span class=emoji-span>🫶🏼</span>
                    </p>';
        }
        }else{
                switch($month)
        {
            case "Jan":
            $output .= '<h3 class="welcome-message pdark-color">
                             '.$quote.'
                    </h3>';
            break;
            case "Feb":
                $output .= '<h3 class="welcome-message plight-color">
                           '.$quote.'  
                    </h3>';
            break;
            case "Mar":
                $output .= '<h3 class="welcome-message plight-color">
                               '.$quote.'   
                    </h3>';
            break;
            case "Apr":
                $output .= '<h3 class="welcome-message pdark-color">
                             '.$quote.'    
                    </h3>';
            break;
            case "May":
                $output .= '<h3 class="welcome-message plight-color">
                              '.$quote.'     
                    </h3>';
            break;
            case "Jun":
                $output .= '<h3 class="welcome-message pdark-color">
                              '.$quote.' 
                    </h3>';
            break;
            case "Jul":
                $output .= '<h3 class="welcome-message pdark-color">
                               '.$quote.' 
                    </h3>';
            break;
            case "Aug":
                $output .= '<h3 class="welcome-message plight-color">
                            '.$quote.'  
                    </h3>';
            break;
            case "Sep":
                $output .= '<h3 class="welcome-message pdark-color">
                               '.$quote.' 
                    </h3>';
            break;
            case "Oct":
                $output .= '<h3 class="welcome-message pdark-color">
                             '.$quote.'  
                    </h3>';
            break;
            case "Nov":
                $output .= '<h3 class="welcome-message pdark-color">
                              '.$quote.' 
                    </h3>';
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