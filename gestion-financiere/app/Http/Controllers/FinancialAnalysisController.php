<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\FinancialAnalysisService;

class FinancialAnalysisController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $analysis = new FinancialAnalysisService(Auth::user());
        $data = $analysis->analyze();

        return view('analysis.index', $data);
    }

    public function recommendations()
    {
        $analysis = new FinancialAnalysisService(Auth::user());
        $recommendations = $analysis->generateRecommendations();

        return view('analysis.recommendations', compact('recommendations'));
    }

    public function forecast()
    {
        $analysis = new FinancialAnalysisService(Auth::user());
        $forecast = $analysis->forecastCashFlow();
        $trends = $analysis->analyzeTrends();

        return view('analysis.forecast', compact('forecast', 'trends'));
    }
}
