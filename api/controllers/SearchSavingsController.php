<?php
class SearchSavingsController extends AppController
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
            $selectedDate = $_POST['searchDate'];
            $todayTimestamp = strtotime($selectedDate);
            $monthT = date('m', $todayTimestamp);
            $year = date('Y', $todayTimestamp);
            $month = date("M", $todayTimestamp);
            $savingsModel = new SavingsModel;
            $savings = $savingsModel->getCurrentSavingsByMonthYearAndUserId($userId,$monthT, $year);
            $data['title']="$loggedIn search savings";
            $data['footer']=$this->render(APP_PATH.VIEWS.'adminFooterView.html', $data);
            $data['header']=$this->render(APP_PATH.VIEWS.'adminHeaderView.html', $data);
            if($savings)
            {
                $total = $savingsModel->getTotalSavingsByYearMonthAndUserId($year, $monthT, $userId);
                $data['mainContent'] = $this->displaySavings($savings, $loggedIn, $total, $year, $month);
            }else{
               
                $data['error']="Ceva s-a întâmplat, poziția nu a fost adăugată în baza de date!";
                $data['mainContent'] = $this->render(APP_PATH.VIEWS.'errorsView.html', $data);
            }
              echo $this->render(APP_PATH.VIEWS.'adminLayoutView.html',$data);
        }else{
            header("Location:home");
        }
    }

      public function displaySavings($savings, $user, $total, $year, $month)
    {
        $output="";
        $output .='<div id="main-wrapper">';
        $output .='<div class="wrapper style1">';
        $output .='<section class="container1 box feature1">';
        $output .='<h3 class="major" id="tableCaption">Economiile tale  '. $user.' în '.$year.' - '.$month.' </h3>';
        $output .="<div class='table-header'>
                        <button class='add-button'>
                        <a  href='addSavingsForm'><i class='bi bi-plus-square-fill'>Economie nouă</i></a>
                        </button>
                    </div>";
        $output .='<div class="table-wrapper clear">';
        $output .="<table class='alt incomes-table'>";
        $output .="<thead>
                        <tr>
                            <th scope='col'>#</th>
                            <th scope='col'>Data</th>
                            <th scope='col'>Utilizator</th>
                            <th scope='col'>Valoare</th>
                            <th scope='col'>Editează</th>
                            <th scope='col'>Șterge</th>
                        </tr>
                    </thead>";
        $output .="<tbody";
        if(is_array($savings)){
            foreach($savings as $key=>$row)
            {
                $id = $row['id'];
                $nrCrt = $key+1;
                $postvalue = $row['value'];
                $value = round($postvalue, 2);
                $output .= "<tr >";
                $output .= "<form method='POST' action='updateSaving/" . $id . "' >";
                $output .="<td data-label='#'>$nrCrt</td>";
                $output .="<td data-label='Data'>
                            <input value='" . $row['addedAt'] . "'type='date' name='addedAt' class='categories-table-input'>
                            </td>";
                $output .="<td data-label='Utilizator'>
                            <input value='" . $row['userId'] . "'type='text' name='userId' readOnly class='categories-table-input'>
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
                            <a href='deleteSaving/" . $id . "' ><i class='bi bi-dash-square-fill delete-icon'>Delete</i></a></td>";
                $output .="</tr>";
            }
        }
        $output .="</tbody>";
         $output .="<tfoot>";
        $output .="<tr>";
        $output .="<td colspan='2'></td>";
        $output .="<td>Total</td>";
        $output .='<td>'.$total.'</td>';
        $output .="</tr>";
        $output .="</tfoot>";
        $output .="</div>";
        $output .="</table>";

        $output .='</section>';//section
       // $output .='</div>';//inner
        $output .='</div>';//wrapper style1
       $output .= '</div>';//main-wrapper
        return $output;
    }
}