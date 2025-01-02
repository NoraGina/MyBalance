<?php
class AppController
{
    protected $routes=[
        "home"=>"HomeController",
        "signupForm"=>"SignupFormController",
        "signup"=>"SignupController",
        "loginForm"=>"LoginFormController",
        "login"=>"LoginController",
        "logout"=>"LogoutController",
        "updateUserForm"=>"UpdateUserFormController",
        "updateUser"=>"UpdateUserController",
        "addIncomeCategoryForm"=>"AddIncomeCategoryFormController",
        "addIncomeCategory"=>"AddIncomeCategoryController",
        "incomeCategories"=>"IncomeCategoriesController",
        "deleteIncomeCategory"=>"DeleteIncomeCategoryController",
        "updateIncomeCategory"=>"UpdateIncomeCategoryController",
        "incomeCategories"=>"IncomeCategoriesController",
        "addExpenseCategoryForm"=>"AddExpenseCategoryFormController",
        "addExpenseCategory"=>"AddExpenseCategoryController",
        "deleteExpenseCategory"=>"DeleteExpenseCategoryController",
        "updateExpenseCategory"=>"UpdateExpenseCategoryController",
        "expenseCategories"=>"ExpenseCategoriesController",
        "adminHome"=>"AdminHomeController",
        "addIncome"=>"AddIncomeController",
        "addIncomeForm"=>"AddIncomeFormController",
        "incomes"=>"IncomesController",
        "updateIncome"=>"UpdateIncomeController",
        "deleteIncome"=>"DeleteIncomeController",
        "searchIncomes" =>"SearchIncomesController",
        "addExpenseForm"=>"AddExpenseFormController",
        "addExpense"=>"AddExpenseController",
        "expenses"=>"ExpensesController",
        "updateExpense"=>"UpdateExpenseController",
        "deleteExpense"=>"DeleteExpenseController",
        "searchExpenses" => "SearchExpensesController",
        "addSavingsForm" => "AddSavingsFormController",
        "addSavings"=>"AddSavingsController",
        "savings" => "SavingsController",
        "updateSavings" => "UpdateSavingsController",
        "deleteSavings" => "DeleteSavingsController",
        "searchSavingsForm" => "SearchSavingsFormController",
        "searchSavings" => "SearchSavingsController",
        "balance"=>"BalanceController",
        "searchBalance"=>"SearchBalanceController",
        "searchBalanceSheetForm"=>"SearchBalanceSheetFormController",
        "balanceSheet" =>"BalanceSheetController",
    ];
    public function __construct(){
        
        $this->init();
    }

    public function init(){
        // redirect, page navigation

        if(isset($_GET['page'])){
            $page = $_GET['page'];
        }
        else {
            $page = 'home';
        }

        if(array_key_exists($page, $this->routes)){
            $className = $this->routes[$page];
        }
        else {
            $className = $this->routes['home'];
        }
        new $className;
    }

    public function render($page, $data=array())
    {
        $template = file_get_contents($page);
            
        // look for all the placeholders
        preg_match_all("[{{\w+}}]", $template, $matches);

        // var_dump($matches[0]);

        foreach($matches[0] as $value){
            // take out all the braces
            // replace them with the information in the date array
            $item = str_replace('{{', '', $value);
            $item = str_replace('}}', '', $item);

            if(array_key_exists($item, $data)){
                $template = str_replace($value, $data[$item], $template);
            }
        }
        return $template;
    }
}