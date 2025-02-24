<?php

namespace App\Http\Controllers;

use App\Models\CustomerAnalysis;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AnalysisController extends Controller
{
    /**
     * Download the result of the analysis as a PDF.
     */
    public function download($id)
    {
        $customerAnalysis = CustomerAnalysis::findOrFail($id);

        // Load a view and pass the data to generate PDF
        $pdf = Pdf::loadView('pdf.analysis_result', compact('customerAnalysis'));

        // Return the PDF for download
        return $pdf->download("analysis_result_{$id}.pdf");
    }
}
