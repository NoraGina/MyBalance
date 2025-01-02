<?php
class SearchExpensesController extends AppController
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
            $selecteDate = $_POST['searchDate'];
            $todayTimestamp = strtotime($selecteDate);
            $monthT = date('m', $todayTimestamp);
            $year = date('Y', $todayTimestamp);
            $month = date("M", $todayTimestamp);
            $loggedIn = $_SESSION['user'];
            $usersModel = new UsersModel;
            $user = $usersModel->getOne($loggedIn);
            $userId = $user['id'];
            $expensesModel = new ExpensesModel;
            $expenses = $expensesModel->getTotalExpensesByYearMonthAndUserId($year, $monthT, $userId);
            $data['title']="Admin expenses";
            $data['header']=$this->render(APP_PATH.VIEWS.'adminHeaderView.html', $data);
            $data['footer']=$this->render(APP_PATH.VIEWS.'adminFooterView.html', $data);
            if($expenses)
            {
                $total = $expensesModel->getTotalExpensesByYearMonthAndUserId($year, $monthT, $userId);
                $expenseCategoriesModel = new ExpenseCategoriesModel;
                $expenseCategories=$expenseCategoriesModel->getAllExpenseCategories();
                $data['mainContent']= $this->displayExpenses($expenses, $loggedIn, $total, $expenseCategories, $year, $month);
            }else{
                 $data['error']= "$loggedIn Nu ai nicio cheltuială introdusă în $year - $month caută altă lună sau <a href='addExpenseForm'>Adaugă cheltuială</a>";
                 $data['mainContent']=$this->render(APP_PATH.VIEWS.'expensesErrorsView.html', $data);
            }
            echo $this->render(APP_PATH.VIEWS.'adminLayoutView.html',$data);
        }else{
            header("Location:home");
        }

    }

     public function displayExpenses($expenses, $user, $total, $expenseCategories, $year, $month)
    {
        $output="";
        
        $selectCategory = $this->bindCategoriesIntoSelect($expenseCategories);
        $output .='<div id="main-wrapper">';
        $output .='<div class="wrapper style1">';
        $output .='<section class="container1 box feature1">';
        $output .="<div class='row aln-center'>
                    <div class='col-5 col-12-medium col-12-small'>
                        <form method='POST' action='' >
                        <input type='search' name='searchItem' placeholder='Chirie...' id='searchInput' minlength='3'>
                        <button id='searchButton' type='submit' name='submitForm' value='Caută'>Caută</button>
                        </form>
                                </div>
                    <div class='col-5 col-12-medium col-12-small'>
                        <form method='POST' action='' id='selectForm'>
                            <select  name='expenseCategory' id='searchOption'><option disable selected>--Caută după categorie--</option>$selectCategory</select>
                            <button id='searchSelect' type='submit' name='submitSelect' value='Caută'>Caută</button>
                        </form>
                    </div>
                    <div class='col-2 col-12-medium col-12-small'>
                        <button id='addExpenseTop'>
                        <a  href='addExpenseForm'><i class='bi bi-plus-square-fill'>Cheltuială nouă</i></a>
                        </button>
                        </div>
                    </div>";
        $output .='<h3 class="major" id="tableCaption">Toate cheltuielile tale  '. $user.' în '.$year.' - '.$month.'</h3>';
        
        $output .='<div class="table-wrapper clear">';
        $output .="<table class='alt incomes-table'>";
        $output .="<thead>
                        <tr>
                            <th scope='col'>#</th>
                            <th scope='col'>Data</th>
                            <th scope='col'>Utilizator</th>
                            <th scope='col'>Categoria</th>
                            <th scope='col'>Denumire</th>
                            <th scope='col'>Valoare</th>
                            <th scope='col'>Editează</th>
                            <th scope='col'>Șterge</th>
                        </tr>
                    </thead>";
        $output .="<tbody";
        if(is_array($expenses)){
            foreach($expenses as $key=>$row)
            {
                $id = $row['id'];
                $nrCrt = $key+1;
                $postvalue = $row['value'];
                $value = round($postvalue, 2);
                
                $output .= "<tr >";
                $output .= "<form method='POST' action='updateExpense/" . $id . "' >";
                $output .="<td data-label='#'>$nrCrt</td>";
                $output .="<td data-label='Data'>
                            <input value='" . $row['addedAt'] . "'type='date' name='addedAt' class='categories-table-input'>
                            </td>";
                $output .="<td data-label='Utilizator'>
                            <input value='" . $row['userId'] . "'type='text' name='userId' readOnly class='categories-table-input'>
                            </td>";
                $output .= "<td  data-label='Category'>" . "<select class='table-category'  aria-label='Default select example' value='" . $row['expenseCategory'] . "' name='expenseCategory'>" .
                           "<option value='" . $row['expenseCategory'] . "'>" . $row['expenseCategory'] . "</option>" .
                           $selectCategory . "</select>" . "</td>";
                $output .="<td data-label='Denumire'>
                            <input value='" . $row['name'] . "'type='text' name='name' >
                            </td>";
                 $output .="<td data-label='Valoare'>
                            <input value='" . $value . "'type='number' step='0.01' name='value'>
                            </td>";
                $output .="<td data-label='Editează'>
                            <button type='submit' class='edit-button' onclick='return confirm(\"Sigur? Vrei să editezi această intrare?\")'>
                            <i class='bi bi-pen-fill'>Edit</i></button></td>";
                $output .="</form>";
                $output .="<td data-label='Șterge'>
                 <button type='submit' class='delete-button' onclick='return confirm(\"Sigur? Vrei să ștergi această intrare?\")'>
                            <a href='deleteExpense/" . $id . "' ><i class='bi bi-dash-square-fill delete-icon'>Delete</i></a></td>";
                $output .="</tr>";
            }
        }
        $output .="</tbody>";
        $output .="<tfoot>";
        $output .="<tr>";
        $output .="<td colspan='4'></td>";
        $output .="<td>Total</td>";
        $output .='<td>'.$total.'</td>';
        $output .="</tr>";
        $output .="</tfoot>";
        $output .="</div>";
        $output .="</table>";
        $output .="<div class='table-footer'>
        <button class='add-button'>
                        <a  href='addExpenseForm'><i class='bi bi-plus-square-fill'>Cheltuială nouă</i></a>
                        </button>
                    </div>";

        $output .='</section>';//section
      
        $output .='</div>';//wrapper style1
       $output .= '</div>';//main-wrapper
        return $output;
    }

    public function bindCategoriesIntoSelect($categories)
    {
        $output = '';
        if (is_array($categories)) {
            foreach ($categories as $row) {
                $output .= '<option value="' . $row["name"] . '">' . $row["name"] . '</option>';
            }
            return $output;
        }
    }
}