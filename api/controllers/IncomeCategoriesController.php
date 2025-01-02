<?php
class IncomeCategoriesController extends AppController
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
            $incomeCategoriesModel = new IncomeCategoriesModel;
            $incomeCategories=$incomeCategoriesModel->getAllIncomeCategories();
            if($incomeCategories)
            {
            $data['title']="Manager categories";
            $data['header']=$this->render(APP_PATH.VIEWS.'managerHeaderView.html', $data);
            $data['footer']=$this->render(APP_PATH.VIEWS.'managerFooterView.html', $data);
            $data['mainContent']=$this->displayAdminIncomeCategories($incomeCategories);
            echo $this->render(APP_PATH.VIEWS.'managerLayoutView.html',$data);
            }else{
                $data['title']="Manager add category";
                $data['header']=$this->render(APP_PATH.VIEWS.'managerHeaderView.html', $data);
                $data['footer']=$this->render(APP_PATH.VIEWS.'managerFooterView.html', $data);
                $data['mainContent'] = $this->render(APP_PATH.VIEWS.'addIncomeCategoryFormView.html', $data);
                echo $this->render(APP_PATH.VIEWS.'managerLayoutView.html',$data);
            }
           
        }else{
            header("Location:home");
        }
    }

    public function displayAdminIncomeCategories($incomeCategories)
    {
        $output="";
       
        $output .='<div id="main-wrapper">';
        $output .='<div class="wrapper style1">';
        $output .='<div class="inner">';
        $output .='<section class="container box feature1">';
        $output .='<div class="table-wrapper">';
        $output .="<table class='alt categories-table'>";
        $output .="<thead>
                        <tr>
                            <th scope='col'>#</th>
                            <th scope='col'>Name</th>
                            <th scope='col'>Edit</th>
                            <th scope='col'>Delete</th>
                        </tr>
                    </thead>";
        $output .="<tbody";
        if(is_array($incomeCategories)){
            foreach($incomeCategories as $key=>$row)
            {
                $id = $row['id'];
                $nrCrt = $key+1;
                $output .= "<tr >";
                $output .= "<form method='POST' action='updateIncomeCategory/" . $id . "' >";
                $output .="<td data-label='#'>$nrCrt</td>";
                $output .="<td data-label='Name'>
                            <input value='" . $row['name'] . "'type='text' name='name' class='categories-table-input'>
                            </td>";
                $output .="<td data-label='Edit'>
                            <button type='submit' class='edit-button' onclick='return confirm(\"Are you sure? Do you really want to update this category?\")'>
                            <i class='bi bi-pen-fill'>Edit</i></button></td>";
                $output .="</form>";
                $output .="<td data-label='Delete'>
                 <button type='submit' class='delete-button' onclick='return confirm(\"Are you sure? Do you really want to delete this category?\")'>
                            <a href='deleteIncomeCategory/" . $id . "' ><i class='bi bi-dash-square-fill delete-icon'>Delete</i></a></td>";
                $output .="</tr>";
            }
        }
        $output .="</tbody>";
        $output .="</div>";
        $output .="</table>";
        $output .="<div class='table-footer'>
        <button class='add-button'>
                        <a  href='addIncomeCategoryForm'><i class='bi bi-plus-square-fill'>Income Category</i></a>
                        </button>
                    </div>";

        $output .='</section>';//section
        $output .='</div>';//inner
        $output .='</div>';//wrapper style1
       $output .= '</div>';//main-wrapper

        return $output;
    }
}