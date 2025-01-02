<?php
class IncomesController extends AppController
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
            $loggedIn= $_SESSION['user'];
            $usersModel = new UsersModel;
            $user = $usersModel->getOne($loggedIn);
            $userId= $user['id'];
            $incomesModel = new IncomesModel;
            $currentIncomes = $incomesModel->getCurrentIncomes($userId);
           
            if($currentIncomes)
            {
                $total= $incomesModel->getTotalIncomesCurrentMonth($userId);
                $data['title']="Admin incomes";
                $data['header']=$this->render(APP_PATH.VIEWS.'adminHeaderView.html', $data);
                $data['footer']=$this->render(APP_PATH.VIEWS.'adminFooterView.html', $data);
                $data['mainContent']=$this->displayIncomes($currentIncomes, $loggedIn, $total);
                
            }else{
                    $data['title']="Admin incomes";
                    $data['header']=$this->render(APP_PATH.VIEWS.'adminHeaderView.html', $data);
                    $data['footer']=$this->render(APP_PATH.VIEWS.'adminFooterView.html', $data);
                    $data['error']= "$loggedIn Nu ai niciun venit introdus";
                    $data['mainContent']=$this->render(APP_PATH.VIEWS.'incomesErrorsView.html', $data);
                }
            
            echo $this->render(APP_PATH.VIEWS.'adminLayoutView.html',$data);
         }else{
             header("Location:home");
         }
    }

    public function displayIncomes($incomes, $user, $total)
    {
        $output="";
        $output .='<div id="main-wrapper">';
        $output .='<div class="wrapper style1">';
        $output .='<section class="container1 box feature1">';
        $output .='<h3 class="major" id="tableCaption">Toate veniturile tale  '. $user.' </h3>';
        $output .="<div class='table-header'>
                        <button class='add-button'>
                        <a  href='addIncomeForm'><i class='bi bi-plus-square-fill'>Venit nou</i></a>
                        </button>
                    </div>";
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
        if(is_array($incomes)){
            foreach($incomes as $key=>$row)
            {
                $id = $row['id'];
                $nrCrt = $key+1;
                $postvalue = $row['value'];
                $value = round($postvalue, 2);
                $incomeCategoriesModel = new IncomeCategoriesModel;
                $incomeCategories=$incomeCategoriesModel->getAllIncomeCategories();
                $selectCategory = $this->bindCategoriesIntoSelect($incomeCategories);
                $output .= "<tr >";
                $output .= "<form method='POST' action='updateIncome/" . $id . "' >";
                $output .="<td data-label='#'>$nrCrt</td>";
                $output .="<td data-label='Data'>
                            <input value='" . $row['addedAt'] . "'type='date' name='addedAt' class='categories-table-input'>
                            </td>";
                $output .="<td data-label='Utilizator'>
                            <input value='" . $row['userId'] . "'type='text' name='userId' readOnly class='categories-table-input'>
                            </td>";
                $output .= "<td  data-label='Category'>" . "<select class='table-category'  aria-label='Default select example' value='" . $row['incomeCategory'] . "' name='incomeCategory'>" .
                           "<option value='" . $row['incomeCategory'] . "'>" . $row['incomeCategory'] . "</option>" .
                           $selectCategory . "</select>" . "</td>";
                $output .="<td data-label='Denumire'>
                            <input value='" . $row['name'] . "'type='text' name='name' >
                            </td>";
                 $output .="<td data-label='Valoare'>
                            <input value='" . $value . "'type='number' name='value'>
                            </td>";
                $output .="<td data-label='Editează'>
                            <button type='submit' class='edit-button' onclick='return confirm(\"Sigur? Vrei să editezi această intrare?\")'>
                            <i class='bi bi-pen-fill'>Edit</i></button></td>";
                $output .="</form>";
                $output .="<td data-label='Șterge'>
                 <button type='submit' class='delete-button' onclick='return confirm(\"Sigur? Vrei să ștergi această intrare?\")'>
                            <a href='deleteIncome/" . $id . "' ><i class='bi bi-dash-square-fill delete-icon'>Delete</i></a></td>";
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
                        <a  href='addIncomeForm'><i class='bi bi-plus-square-fill'>Venit nou</i></a>
                        </button>
                    </div>";

        $output .='</section>';//section
       // $output .='</div>';//inner
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

    public function displayUser($user)
    {
        $output ='';
        $usersModel = new UsersModel;
        $loggedIn = $usersModel->getOne($user);
        $userId =$loggedIn['id'];

        $output .="<div class='add-form-group'>
                <span class='add-span'>User<span class='text-danger'> *</span> </span>
                <input type='text' name='userId' class='add-input' readOnly value='".$userId."'>
                </div>";
        return $output;
    }

    public function bindIncomeCategoriesIntoSelect($incomeCategories)
    {
        $output = '';
        if (is_array($incomeCategories)) {
            foreach ($incomeCategories as $row) {
                $output .= '<option value="' . $row["name"] . '">' . $row["name"] . '</option>';
            }
            return $output;
        }
    }
}