<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $invoices = Invoice::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        return view('invoices.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|unique:invoices',
            'client_name' => 'required',
            'amount' => 'required|numeric',
            'issue_date' => 'required|date',
            'due_date' => 'required|date',
        ]);

        $total = $request->amount + ($request->amount * $request->tax / 100);

        Invoice::create([
            'number' => $request->number,
            'client_name' => $request->client_name,
            'client_email' => $request->client_email,
            'client_phone' => $request->client_phone,
            'client_address' => $request->client_address,
            'amount' => $request->amount,
            'tax' => $request->tax ?? 0,
            'total' => $total,
            'issue_date' => $request->issue_date,
            'due_date' => $request->due_date,
            'notes' => $request->notes,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('invoices.index')->with('success', 'Facture créée avec succès');
    }
    public function show($id)
{
    $invoice = Invoice::where('user_id', Auth::id())->findOrFail($id);
    return view('invoices.show', compact('invoice'));
}

public function edit($id)
{
    $invoice = Invoice::where('user_id', Auth::id())->findOrFail($id);
    return view('invoices.edit', compact('invoice'));
}

public function update(Request $request, $id)
{
    $invoice = Invoice::where('user_id', Auth::id())->findOrFail($id);

    $request->validate([
        'client_name' => 'required|string|max:255',
        'amount' => 'required|numeric|min:0',
        'issue_date' => 'required|date',
        'due_date' => 'required|date',
    ]);

    $total = $request->amount + ($request->amount * $request->tax / 100);

    $invoice->update([
        'client_name' => $request->client_name,
        'client_email' => $request->client_email,
        'client_phone' => $request->client_phone,
        'client_address' => $request->client_address,
        'amount' => $request->amount,
        'tax' => $request->tax ?? 0,
        'total' => $total,
        'issue_date' => $request->issue_date,
        'due_date' => $request->due_date,
        'notes' => $request->notes,
    ]);

    return redirect()->route('invoices.index')->with('success', 'Facture modifiée avec succès');
}

public function destroy($id)
{
    $invoice = Invoice::where('user_id', Auth::id())->findOrFail($id);
    $invoice->delete();

    return redirect()->route('invoices.index')->with('success', 'Facture supprimée avec succès');
}
public function markAsPaid($id)
{
    $invoice = Invoice::where('user_id', Auth::id())->findOrFail($id);

    $invoice->update([
        'status' => 'paid',
        'paid_date' => now()
    ]);

    return redirect()->route('invoices.index')->with('success', 'Facture marquée comme payée');
}
}
