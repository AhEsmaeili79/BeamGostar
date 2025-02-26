<?php

namespace App\Http\Controllers;

use App\Models\CustomerAnalysis;
use App\Models\Customers;
use App\Models\Analyze;
use App\Models\price_analysis_credit;
use App\Models\price_analysis;
use Filament\Forms\Form;
use Illuminate\Support\Facades\App;

class CustomerAnalysisResourceController extends Controller
{

    


    public function handleAfterStateUpdated($state, $set, $get)
    {
        // Handle logic after state updated for fields like total_cost, applicant_share, etc.
        $totalCost = $get('total_cost');
        if ($get('grant') == 0) {
            $set('applicant_share', $totalCost);
        } elseif ($get('grant') == 1) {
            $set('applicant_share', max($totalCost - ($get('network_share') ?? 0), 0));
        }
    }

    public function beforeSave($data)
    {
        // Handle the before save logic, including validations or any transformations needed
        return $data;
    }
}
