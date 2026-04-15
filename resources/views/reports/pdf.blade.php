
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rapport mensuel</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .summary { margin-bottom: 30px; }
        .summary-box {
            display: inline-block;
            width: 30%;
            padding: 10px;
            margin: 5px;
            border-radius: 5px;
            color: white;
        }
        .income { background-color: #10B981; }
        .expense { background-color: #EF4444; }
        .balance { background-color: #3B82F6; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport financier mensuel</h1>
        <p>{{ \Carbon\Carbon::create()->month($month)->format('F') }} {{ $year }}</p>
        <p>Généré le {{ $generated_at->format('d/m/Y H:i') }}</p>
    </div>

    <div class="summary">
        <div class="summary-box income">
            <h3>Revenus</h3>
            <p>{{ number_format($totalIncome, 0, ',', ' ') }} FCFA</p>
        </div>
        <div class="summary-box expense">
            <h3>Dépenses</h3>
            <p>{{ number_format($totalExpense, 0, ',', ' ') }} FCFA</p>
        </div>
        <div class="summary-box balance">
            <h3>Solde</h3>
            <p>{{ number_format($balance, 0, ',', ' ') }} FCFA</p>
        </div>
    </div>

    <h3>Dépenses par catégorie</h3>
    <table>
        <thead>
            <tr>
                <th>Catégorie</th>
                <th>Montant</th>
                <th>Pourcentage</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expensesByCategory as $category => $amount)
            <tr>
                <td>{{ $category }}</td>
                <td>{{ number_format($amount, 0, ',', ' ') }} FCFA</td>
                <td>{{ round(($amount / $totalExpense) * 100, 1) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Ce rapport a été généré automatiquement par l'Application de Gestion Financière.</p>
        <p>{{ $user->company ?? $user->name }}</p>
    </div>
</body>
</html>
