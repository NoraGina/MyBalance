<?php
class BalanceSheetController extends AppController
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
            $usersModel = new UsersModel;
            $user = $usersModel->getOne($loggedIn);
            $userId = $user['id'];
            $expensesModel = new ExpensesModel;
            $incomesModel = new IncomesModel;
            $savingsModel = new SavingsModel;
            $selecteDate = $_POST['selectedDate'];
            $todayTimestamp = strtotime($selecteDate);
            $monthT = date('m', $todayTimestamp);
            $year = date('Y', $todayTimestamp);
            $month = date("M", $todayTimestamp);
            $data['title']="$loggedIn balance sheet";
            $data['header']=$this->render(APP_PATH.VIEWS.'adminHeaderView.html', $data);
            $data['footer']=$this->render(APP_PATH.VIEWS.'adminFooterView.html', $data);
            $currentExpenses = $expensesModel->getDistinctExpenseCategoryByYearAndUserId($year, $userId);
            $currentIncomes = $incomesModel->getDistinctIncomeCategoryByYearAndUserId($year, $userId);
            $currentSavings = $savingsModel->getCurrentSavingsByYearAndUserId($userId, $year);
            if($currentExpenses && $currentIncomes && $currentSavings)
            {
                $totalIncomes = $incomesModel->getTotalIncomesByYearAndUserId($year, $userId);
                $totalExpensesBrut = $expensesModel-> getTotalExpensesByYearAndUserId($year, $userId);
                $totalExpenses =round($totalExpensesBrut, 2);
                $totalSavingsBrut = $savingsModel -> getTotalSavingsByYearAndUserId($year, $userId);
                $totalSavings = round($totalSavingsBrut);
                $data['mainContent'] = $this->displayMainContentWithSavingsByDate($currentExpenses, $currentIncomes, $currentSavings, $totalIncomes, $totalExpenses, $totalSavings, $year);
               
            }elseif($currentExpenses && $currentIncomes)
            {
                $totalIncomes = $incomesModel->getTotalIncomesByYearAndUserId($year, $userId);
                $totalExpensesBrut = $expensesModel-> getTotalExpensesByYearAndUserId($year, $userId);
                $totalExpenses =round($totalExpensesBrut, 2);
                $data['mainContent']=$this->displayMainContentByDate($currentExpenses, $currentIncomes, $totalIncomes, $totalExpenses, $year);
            }else{
                $data['error']= "$loggedIn Nu ai cheltuieli sau venituri  $year";
                $data['mainContent']=$this->render(APP_PATH.VIEWS.'errorsView.html', $data);
            }
            echo $this->render(APP_PATH.VIEWS.'adminLayoutView.html',$data);
        }else{
             header("Location:home");
        }
    }

      public function displayMainContentByDate($expenses, $incomes, $totalIncomes, $totalExpenses, $year)
    {
        $output = "";
       
        $loggedIn = $_SESSION['user'];
        $usersModel = new UsersModel;
        $user = $usersModel->getOne($loggedIn);
        $userId = $user['id'];
        $sold = $totalIncomes - $totalExpenses;
        $output .='<div id="main-wrapper">';
        $output .='<div class="wrapper style1">';
        $output .='<section class="container1 box feature1">';
         $output .="<div class='row aln-center'>";
        $output .='<div class="col-12 align-center">
                        <form method="POST" action="">
                        <input type="date" name="selectedDate"/>
                        <input type = "submit" name="submitDate" id="searchButton"/>
                        </form>
                    </div>';
        $output .="</div>";//row header

        $output .="<div class='row aln-center row-margin-top' >";
        $output .='<div class="col-12 align-center">';
        $output .="<header class='major'>
                        <h3 class='balance-title'>Bilanț $year Sold: $sold</h3>
                    </header>";
        
        $output .='</div>';//col bala
        $output .='</div>';//row header

        $output .='<div class="row aln-center">';
        $output .='<div class="col-6 col-12-medium">';
        $output .='<div class="row aln-center">';
        $output .='<div class="col-12 align-center">';
        $output .="<header class='major'>
                        <h3 class='balance-title'>Venituri $year</h3>
                    </header>";
        $output .='</div>';//col-12 venituri title
        $output .='</div>';//row venituri title
       
     
         if(is_array($incomes))
         {  
            $incomesModel = new IncomesModel;
            $output .="<table class='alt' id='incomeBalanceTable'>";
             $output .="<tbody>";
            
            foreach($incomes as $key=>$income)
            {
                $currentIncome = $income['incomeCategory'];
                $totalIncome = $incomesModel-> getTotalIncomesByYearCategoryAndUserId($year,  $currentIncome, $userId);
                
                $output .="<tr><td>$currentIncome</td>
                            <td>$totalIncome</td></tr>";
               
            }
            
             
             $output .='</tbody>';
              $output .="<tfoot>";
        $output .="<tr>";
        $output .="<td>Total venituri</td>";
        $output .='<td>'.$totalIncomes.'</td>';
        $output .="</tr>";
        $output .="</tfoot>";
            $output .='</table>';
         }
        
        $output .='</div>';//col-6 1

        $output .='<div class="col-6 col-12-medium">';

        $output .='<div class="row aln-center">';
        $output .='<div class="col-12 align-center">';
        $output .="<header class='major'>
                        <h3 class='balance-title'>Cheltuieli $year</h3>
                    </header>";
        $output .='</div>';//row cheltuieli title
        $output .='</div>';//col-12 cheltuieli title
        if(is_array($expenses))
         {  
            $expensesModel = new ExpensesModel;
            $output .="<table class='alt' id='expenseBalanceTable'>";
             $output .="<tbody>";
             
            foreach($expenses as $key=>$expense)
            {
                $currentExpense = $expense['expenseCategory'];
                $totalExpense = $expensesModel-> getTotalExpensesByYearCategoryAndUserId($year, $currentExpense, $userId);
                
                $output .="<tr><td>$currentExpense</td>
                            <td>$totalExpense</td></tr>";
               
            }
            
            
             $output .='</tbody>';
              $output .="<tfoot>";
        $output .="<tr>";
        $output .="<td>Total cheltuieli</td>";
        $output .='<td>'.$totalExpenses.'</td>';
        $output .="</tr>";
        $output .="</tfoot>";
            $output .='</table>';
         }
        $output .='</div>';//col-6 2

       
         $output .='</div>';//row
       
        $output .='</section>';//section
        $output .='</div>';//wrapper
        $output .='</div>';//main-wrapper
        return $output;
    }
     public function displayMainContentWithSavingsByDate($expenses, $incomes, $savings, $totalIncomes, $totalExpenses, $totalSavings , $year)
    {
        $output = "";
       
        $loggedIn = $_SESSION['user'];
        $usersModel = new UsersModel;
        $user = $usersModel->getOne($loggedIn);
        $userId = $user['id'];
        $sold = round($totalIncomes - $totalExpenses + $totalSavings);
        $output .='<div id="main-wrapper">';
        $output .='<div class="wrapper style1">';
        $output .='<section class="container1 box feature1">';
         $output .="<div class='row aln-center'>";
        $output .='<div class="col-12 align-center">
                        <form method="POST" action="">
                        <input type="date" name="selectedDate"/>
                        <input type = "submit" name="submitDate" id="searchButton"/>
                        </form>
                    </div>';
        $output .="</div>";//row header

        $output .="<div class='row aln-center row-margin-top' >";
        $output .='<div class="col-12 align-center">';
        $output .="<header class='major'>
                        <h3 class='balance-title'>$loggedIn  Bilanț $year Sold: $sold</h3>
                    </header>";
        
        $output .='</div>';//col bala
        $output .='</div>';//row header

        $output .='<div class="row aln-center">';
        $output .='<div class="col-4 col-12-medium">';
        $output .='<div class="row aln-center">';
        $output .='<div class="col-12 align-center">';
        $output .="<header class='major'>
                        <h3 class='balance-title'>Venituri $year</h3>
                    </header>";
        $output .='</div>';//col-12 venituri title
        $output .='</div>';//row venituri title
       
     
         if(is_array($incomes))
         {  
            $incomesModel = new IncomesModel;
            $output .="<table class='alt' id='incomeBalanceTable'>";
             $output .="<tbody>";
            
            foreach($incomes as $key=>$income)
            {
                $currentIncome = $income['incomeCategory'];
                $totalIncome = $incomesModel-> getTotalIncomesByYearCategoryAndUserId($year,  $currentIncome, $userId);
                
                $output .="<tr><td>$currentIncome</td>
                            <td>$totalIncome</td></tr>";
               
            }
            
             
             $output .='</tbody>';
              $output .="<tfoot>";
        $output .="<tr>";
        $output .="<td>Total venituri</td>";
        $output .='<td>'.$totalIncomes.'</td>';
        $output .="</tr>";
        $output .="</tfoot>";
            $output .='</table>';
         }
        
        $output .='</div>';//col-6 1

        $output .='<div class="col-4 col-12-medium">';

        $output .='<div class="row aln-center">';
        $output .='<div class="col-12 align-center">';
        $output .="<header class='major'>
                        <h3 class='balance-title'>Cheltuieli  $year</h3>
                    </header>";
        $output .='</div>';//row cheltuieli title
        $output .='</div>';//col-12 cheltuieli title
        if(is_array($expenses))
         {  
            $expensesModel = new ExpensesModel;
            $output .="<table class='alt' id='expenseBalanceTable'>";
             $output .="<tbody>";
             
            foreach($expenses as $key=>$expense)
            {
                $currentExpense = $expense['expenseCategory'];
                $totalExpense = $expensesModel-> getTotalExpensesByYearCategoryAndUserId($year, $currentExpense, $userId);
                
                $output .="<tr><td>$currentExpense</td>
                            <td>$totalExpense</td></tr>";
               
            }
            
            
             $output .='</tbody>';
              $output .="<tfoot>";
        $output .="<tr>";
        $output .="<td>Total cheltuieli</td>";
        $output .='<td>'.$totalExpenses.'</td>';
        $output .="</tr>";
        $output .="</tfoot>";
            $output .='</table>';
         }
        $output .='</div>';//col-6 2

         $output .='<div class="col-4 col-12-medium">';

        $output .='<div class="row aln-center">';
        $output .='<div class="col-12 align-center">';
        $output .="<header class='major'>
                        <h3 class='balance-title'>Economii $year</h3>
                    </header>";
        $output .='</div>';//row cheltuieli title
        $output .='</div>';//col-12 cheltuieli title
        if(is_array($savings))
         {  
            $output .="<table class='alt' id='expenseBalanceTable'>";
            $output .="<tbody>";
            $output .="<tr><td colspan='2'>$totalSavings</td>
                            </tr>";
            $output .='</tbody>';
            $output .="<tfoot>";
            $output .="<tr>";
            $output .="<td>Total economii</td>";
            $output .='<td>'.$totalSavings.'</td>';
            $output .="</tr>";
            $output .="</tfoot>";
            $output .='</table>';
         }
        $output .='</div>';//col-4 3

       
         $output .='</div>';//row
       
        $output .='</section>';//section
        $output .='</div>';//wrapper
        $output .='</div>';//main-wrapper
        return $output;
    }
}