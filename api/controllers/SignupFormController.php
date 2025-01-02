<?php
class SignupFormController extends AppController
{
    public function __construct()
    {
        $this->init();
    }
    public function init()
    {
        $data['title']="Signup";
        $data['mainContent'] = $this->render(APP_PATH . VIEWS . 'signupFormView.html', $data);
        echo $this->render(APP_PATH . VIEWS . 'homePageView.html', $data);
    }
}