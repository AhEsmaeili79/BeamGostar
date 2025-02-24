<?php

namespace App\Http\Controllers;

use App\Models\CustomerAnalysis;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Generate and print an invoice.
     */
    public function print($id)
    {
        $customerAnalysis = CustomerAnalysis::findOrFail($id);

        // Add a custom font (example: Vazir)
        $pdf = Pdf::loadView('pdf.invoice', compact('customerAnalysis'));

        // Set the font to use the Persian font (Vazir)
        $pdf->setOption('font', 'Vazir'); // Replace 'Vazir' with the name of your font

        // Or you can add a specific font using the following method (if you have a TTF font)
        $pdf->setOption('font_dir', public_path('fonts')); // Make sure this directory exists and has fonts
        $pdf->setOption('default_font', 'Vazir'); // Set the default font to Vazir

        return $pdf->stream('invoice.pdf');
    }
}
