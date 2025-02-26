<?php

namespace App\Http\Controllers;

use App\Models\CustomerAnalysis;
use App\Models\Customers;
use App\Models\Analyze;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use App\Models\price_analysis;

class CustomerAnalysisController extends Controller
{
    public function handleAcceptanceDateChange($state, $set)
    {
        $trackingCode = $this->generateTrackingCode($state);
        $set('tracking_code', $trackingCode);
    }
    
    public function beforeSave(Request $request)
{
    $data = $request->all();

    // If total_cost is not set, calculate it based on additional_cost (or other logic)
    if (!isset($data['total_cost'])) {
        $additionalCost = $data['additional_cost'] ?? 0; // Default to 0 if not set
        $data['total_cost'] = $additionalCost;  // You can add more logic to calculate total_cost if needed
    }

    $data['base_total_cost'] = $data['total_cost'];

    // Apply discount logic
    if (isset($data['discount']) && isset($data['discount_num'])) {
        if ($data['discount'] == 1) { // Percentage discount
            $discountAmount = ($data['total_cost'] * $data['discount_num']) / 100;
            $data['total_cost'] -= $discountAmount;
        } elseif ($data['discount'] == 2) { // Fixed amount discount
            $data['total_cost'] = max(0, $data['total_cost'] - $data['discount_num']);
        }
    }

    // Calculate applicant_share and network_share based on the grant value
    if (isset($data['grant'])) {
        $grant = $data['grant'];
        $totalCost = $data['total_cost'];
        $networkShare = $data['network_share'] ?? 0;

        if ($grant == 0) {
            // If grant is 0, make applicant_share equal to total_cost
            $data['applicant_share'] = $totalCost;
            $data['network_share'] = 0; // Set network share to 0
        } elseif ($grant == 1) {
            // If grant is 1, calculate applicant_share based on network_share
            $data['applicant_share'] = $totalCost - $networkShare;
        }
    }

    if (isset($data['samples_number'])) {
        $maxSamplesNumber = 20; // Set the maximum allowed value for samples_number
        if ($data['samples_number'] > $maxSamplesNumber) {
            // Optionally, you can throw an exception or return a validation error
            return response()->json(['error' => 'The samples_number exceeds the maximum allowed value of ' . $maxSamplesNumber], 400);
        }
    }
    // Handle tracking code and other data saving logic
    if (isset($data['acceptance_date'])) {
        $data['tracking_code'] = $this->generateTrackingCode($data['acceptance_date']);
    }

    return $data;
}


    
    public function generateTrackingCode($acceptanceDate)
    {
        // Convert the acceptance date to the Persian (Jalali) calendar
        $persianDate = Jalalian::fromCarbon(\Carbon\Carbon::parse($acceptanceDate))->format('Ymd');

        // Look for the latest record with the same Persian date in the tracking code
        $lastTrackingCode = CustomerAnalysis::where('tracking_code', 'like', $persianDate . '%')
            ->orderBy('tracking_code', 'desc')
            ->first();

        if ($lastTrackingCode) {
            // If a previous record exists, increment the last 5 digits of the tracking code
            $lastNumber = substr($lastTrackingCode->tracking_code, -5);
            $nextNumber = (int)$lastNumber + 1;
            // Pad the incremented number to ensure it's always 5 digits
            $trackingCode = str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
        } else {
            // If no previous record, start from "00001"
            $trackingCode = '00001';
        }

        // Combine the Persian date with the incremented tracking number
        $trackingCode = $persianDate . $trackingCode;

        return $trackingCode;
    }

    public static function statusOptions()
    {
        return [
            0 => 'منتظر پرداخت',
            1 => 'پذیرش کامل',
            2 => 'در انتظار',
            3 => 'کنسل',
            4 => 'تایید مالی',
            5 => 'منتظر تایید مدیریت فنی',
            6 => 'تایید مدیریت فنی',
            7 => 'آنالیز تکمیل',
            8 => 'منتظر تایید مدیریت مالی',
        ];
    }

    public static function getStatusLabel($state)
    {
        $statusOptions = self::statusOptions();
        return $statusOptions[$state] ?? 'نامشخص';
    }
}
