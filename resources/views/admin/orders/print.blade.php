<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Invoice #{{ str_pad((string)$order->id,6,'0',STR_PAD_LEFT) }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    
    :root {
      --primary: #2563eb;
      --primary-light: #dbeafe;
      --success: #10b981;
      --warning: #f59e0b;
      --gray-50: #f9fafb;
      --gray-100: #f3f4f6;
      --gray-200: #e5e7eb;
      --gray-600: #4b5563;
      --gray-900: #111827;
    }
    
    body {
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
      color: var(--gray-900);
      background: var(--gray-50);
      padding: 40px 20px;
      line-height: 1.6;
    }
    
    .invoice-container {
      max-width: 850px;
      margin: 0 auto;
      background: white;
      box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
      border-radius: 12px;
      overflow: hidden;
    }
    
    /* Header Section */
    .invoice-header {
      background: linear-gradient(135deg, var(--primary) 0%, #1d4ed8 100%);
      color: white;
      padding: 40px;
      position: relative;
      overflow: hidden;
    }
    
    .invoice-header::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -10%;
      width: 400px;
      height: 400px;
      background: rgba(255,255,255,0.1);
      border-radius: 50%;
    }
    
    .header-content {
      position: relative;
      z-index: 1;
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 24px;
    }
    
    .invoice-title {
      font-size: 36px;
      font-weight: 700;
      margin-bottom: 8px;
      letter-spacing: -0.5px;
    }
    
    .invoice-number {
      font-size: 18px;
      opacity: 0.9;
      font-weight: 500;
    }
    
    .invoice-date {
      font-size: 14px;
      opacity: 0.8;
      margin-top: 4px;
    }
    
    /* Status Badge */
    .status-card {
      background: rgba(255,255,255,0.95);
      color: var(--gray-900);
      padding: 20px;
      border-radius: 8px;
      min-width: 240px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .status-label {
      font-size: 12px;
      text-transform: uppercase;
      color: var(--gray-600);
      font-weight: 600;
      letter-spacing: 0.5px;
      margin-bottom: 8px;
    }
    
    .status-badge {
      display: inline-flex;
      align-items: center;
      padding: 6px 14px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 600;
      background: var(--success);
      color: white;
      margin-bottom: 12px;
    }
    
    .status-badge::before {
      content: '●';
      margin-right: 6px;
      animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.5; }
    }
    
    .delivery-info {
      font-size: 14px;
      line-height: 1.8;
    }
    
    .delivery-info strong {
      color: var(--gray-900);
      font-weight: 600;
    }
    
    /* Content Section */
    .invoice-content {
      padding: 40px;
    }
    
    /* Info Cards */
    .info-section {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 24px;
      margin-bottom: 40px;
    }
    
    .info-card {
      background: var(--gray-50);
      border: 2px solid var(--gray-200);
      border-radius: 8px;
      padding: 24px;
    }
    
    .info-card h4 {
      font-size: 12px;
      text-transform: uppercase;
      color: var(--primary);
      font-weight: 700;
      letter-spacing: 1px;
      margin-bottom: 16px;
      padding-bottom: 8px;
      border-bottom: 2px solid var(--primary);
    }
    
    .info-card > div {
      margin-bottom: 6px;
      font-size: 14px;
    }
    
    .info-card .highlight {
      font-weight: 600;
      color: var(--gray-900);
    }
    
    .info-card .muted {
      color: var(--gray-600);
      font-size: 13px;
    }
    
    /* Table */
    .items-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      margin: 32px 0;
      border: 2px solid var(--gray-200);
      border-radius: 8px;
      overflow: hidden;
    }
    
    .items-table thead {
      background: var(--primary);
      color: white;
    }
    
    .items-table th {
      padding: 16px;
      font-weight: 600;
      font-size: 13px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      text-align: left;
    }
    
    .items-table th.right {
      text-align: right;
    }
    
    .items-table tbody tr {
      border-bottom: 1px solid var(--gray-200);
      transition: background 0.2s;
    }
    
    .items-table tbody tr:last-child {
      border-bottom: none;
    }
    
    .items-table tbody tr:hover {
      background: var(--gray-50);
    }
    
    .items-table td {
      padding: 16px;
      font-size: 14px;
    }
    
    .items-table td.right {
      text-align: right;
      font-weight: 500;
    }
    
    /* Totals */
    .totals-section {
      background: var(--primary-light);
      border: 2px solid var(--primary);
      border-radius: 8px;
      padding: 24px;
      margin: 32px 0;
      max-width: 400px;
      margin-left: auto;
    }
    
    .totals-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 0;
      border-bottom: 1px solid rgba(37,99,235,0.2);
    }
    
    .totals-row:last-child {
      border-bottom: none;
      border-top: 2px solid var(--primary);
      margin-top: 8px;
      padding-top: 16px;
      font-size: 20px;
      font-weight: 700;
      color: var(--primary);
    }
    
    .totals-label {
      font-weight: 600;
      color: var(--gray-900);
    }
    
    .totals-value {
      font-weight: 700;
      color: var(--gray-900);
    }
    
    /* Notes */
    .notes-section {
      background: #fef3c7;
      border-left: 4px solid var(--warning);
      border-radius: 8px;
      padding: 20px;
      margin: 24px 0;
    }
    
    .notes-section strong {
      display: block;
      color: #92400e;
      font-size: 14px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 8px;
    }
    
    .notes-section .muted {
      color: #78350f;
      font-size: 14px;
      line-height: 1.6;
    }
    
    /* Print Button */
    .print-button {
      text-align: center;
      margin-top: 32px;
      padding-top: 32px;
      border-top: 2px dashed var(--gray-200);
    }
    
    .print-button button {
      background: var(--primary);
      color: white;
      border: none;
      padding: 14px 32px;
      border-radius: 8px;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
      box-shadow: 0 4px 6px rgba(37,99,235,0.3);
    }
    
    .print-button button:hover {
      background: #1d4ed8;
      transform: translateY(-2px);
      box-shadow: 0 6px 12px rgba(37,99,235,0.4);
    }
    
    /* Print Styles */
    @media print {
      body {
        background: white;
        padding: 0;
      }
      
      .invoice-container {
        box-shadow: none;
        border-radius: 0;
      }
      
      .no-print {
        display: none !important;
      }
      
      .invoice-header::before {
        display: none;
      }
      
      .items-table tbody tr:hover {
        background: transparent;
      }
    }
    
    @page {
      margin: 15mm;
    }
  </style>
</head>
<body onload="window.print()">
  <div class="invoice-container">
    <!-- Header -->
    <div class="invoice-header">
      <div class="header-content">
        <div>
          <div class="invoice-title">INVOICE</div>
          <div class="invoice-number">#{{ str_pad((string)$order->id,6,'0',STR_PAD_LEFT) }}</div>
          <div class="invoice-date">{{ $order->created_at?->format('F d, Y • H:i') }}</div>
        </div>
        <div class="status-card">
          <div class="status-label">Delivery Status</div>
          <div class="status-badge">{{ ucwords(str_replace('_',' ',$order->delivery_status ?? 'pending')) }}</div>
          <div class="delivery-info">
            <div><strong>Date:</strong> {{ $order->delivery_date?->format('M d, Y') ?? '—' }}</div>
            <div>
              <strong>Time:</strong>
              @if($order->deliveryTimeSlot)
                {{ \Illuminate\Support\Str::of($order->deliveryTimeSlot->window_start)->substr(0,5) }}
                –
                {{ \Illuminate\Support\Str::of($order->deliveryTimeSlot->window_end)->substr(0,5) }}
              @else — @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Content -->
    <div class="invoice-content">
      <!-- Info Section -->
      <div class="info-section">
        <div class="info-card">
          <h4>Bill To</h4>
          <div class="highlight">{{ $order->user->name ?? $order->receiver_name }}</div>
          <div class="muted">{{ $order->user->email ?? $order->receiver_phone }}</div>
          <div style="margin-top: 12px;">{{ $order->shipping_address }}</div>
          <div class="muted">{{ $order->city->name_en ?? '' }}</div>
        </div>
        
        <div class="info-card">
          <h4>Order Summary</h4>
          <div><strong>Payment Method:</strong> <span class="highlight">{{ strtoupper($order->payment_method ?? '—') }}</span></div>
          <div><strong>Total Items:</strong> <span class="highlight">{{ $order->items->sum('quantity') }}</span></div>
          <div><strong>Order Status:</strong> <span class="highlight">{{ ucfirst($order->status ?? 'pending') }}</span></div>
        </div>
      </div>

      <!-- Items Table -->
      <table class="items-table">
        <thead>
          <tr>
            <th>Product</th>
            <th class="right">Qty</th>
            <th class="right">Unit Price</th>
            <th class="right">Line Total</th>
          </tr>
        </thead>
        <tbody>
          @foreach($order->items as $it)
            <tr>
              <td>{{ $it->product->name_en ?? ('Product #'.$it->product_id) }}</td>
              <td class="right">{{ $it->quantity }}</td>
              <td class="right">${{ number_format((float)$it->unit_price,2) }}</td>
              <td class="right">${{ number_format((float)$it->unit_price * (float)$it->quantity,2) }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <!-- Totals -->
      <div class="totals-section">
        <div class="totals-row">
          <span class="totals-label">TOTAL AMOUNT</span>
          <span class="totals-value">${{ number_format((float)$order->total_amount,2) }}</span>
        </div>
      </div>

      <!-- Notes -->
      @if($order->extra_notes)
        <div class="notes-section">
          <strong>📝 Additional Notes</strong>
          <div class="muted">{{ $order->extra_notes }}</div>
        </div>
      @endif

      <!-- Print Button -->
      <div class="no-print print-button">
        <button onclick="window.print()">🖨️ Print Invoice</button>
      </div>
    </div>
  </div>
</body>
</html>