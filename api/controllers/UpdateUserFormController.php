<?php
class UpdateUserFormController extends AppController
{
    public function __construct()
    {
        $this->init();
    }
    public function init()
    {
        $email = $_POST['searchEmail'];
        $usersModel = new UsersModel;
        $user = $usersModel->getuserByEmail($email);
        $data['title']="Update user";
        if($user)
        {
           
            $data['message']="";
            $data['mainContent'] = $this->displayUser($user);
            
        }else{
            $this->my_custom_css();
            $data['message']="Something went wrong";
            $data['mainContent'] = $this->displayUser($user);
            
        }
        echo $this->render(APP_PATH.VIEWS.'publicLayoutView.html',$data);
    }

   public function displayUser($user)
   {
        $id = $user['id'];
        $username = $user['username'];
        $email = $user['email'];
        $output ="";

        $output .='<article id="updateUserForm">';
        $output .='<h3 class="major">Update User</h3>';
        $output .="<form method='post' action='updateUser/" . $id . "'>
                    <div class='fields'>
                    <div class='field'>
                            <label for='demo-name'>Username</label>
                            <input type='text' name='username' id='demo-name' value='" . $user['username'] . "'  required/>
                        </div>
                        <div class='field'>
                            <label for='demo-email'>Email</label>
                            <input type='email' name='email' id='demo-email' value='" . $user['email'] . "'  required/>
                        </div>
                        <div class='field'>
                            <label for='demo-password'>Password</label>
                            <input type='password' name='password' id='demo-password' value=''  required/>
                        </div>       
                    </div>
                    <ul class='actions'>
                        <li><input type='submit' value='SUBMIT' class='primary' /></li>
                    
                    </ul>
                </form>";
        $output .='<article>';
        return $output;
   }

    public function my_custom_css() 
    {
        echo '<style>
            p {color: red;
            font-size:2em;}
        </style>';
    }
   
}
/*
  public function displayUser($user)
    {
        $id = $user['id'];
        $username = $user['username'];
        $email = $user['email'];
        $output ="";
        $output .='<article id="updateUserForm">';
        $output .='<h3 class="major">Search User</h3>';
        $output .="<form method='post' action='updateUser/" . $id . "'>
            <div class='fields'>
             <div class='field'>
                    <label for='demo-name'>Username</label>
                    <input type='text' name='username' id='demo-name' value='" . $user['username'] . "'  required/>
                </div>
                <div class='field'>
                    <label for='demo-email'>EMAIL</label>
                    <input type='email' name='email' id='demo-email' value='" . $user['email'] . "'  required/>
                </div>
                 <div class='field'>
                    <label for='demo-password'>PASSWORD</label>
                    <input type='password' name='password' id='demo-password' value=''  required/>
                </div>       
            </div>
            <ul class='actions'>
                <li><input type='submit' value='SUBMIT' class='primary' /></li>
              
            </ul>
        </form>";
        $output .='</article>';

        return $output;
    }
*/