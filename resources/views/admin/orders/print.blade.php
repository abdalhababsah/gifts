<!doctype html>
<html lang="{{ app()->getLocale() ?? 'en' }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
  <meta charset="utf-8">
  <title>
    {{ __('Invoice') }} #{{ str_pad((string)$order->id, 6, '0', STR_PAD_LEFT) }}
  </title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  @php
    $isAr = app()->getLocale() === 'ar';
    $nameField = $isAr ? 'name_ar' : 'name_en';
    $slotName = optional($order->deliveryTimeSlot) ? ($order->deliveryTimeSlot->{$nameField} ?? $order->deliveryTimeSlot->name_en) : null;
    $cityName = optional($order->city) ? ($order->city->{$nameField} ?? $order->city->name_en) : null;

    $currency = $order->payment->currency ?? 'JOD';

    $items = $order->items ?? collect();
    $subtotal = $items->sum(fn($i) => (float)$i->unit_price * (int)$i->quantity);
    $discountValue = (float) optional($order->discount)->applied_value ?: 0.0;
    $total = (float) $order->total_amount; // already discounted per your flow
    $paid = in_array($order->status, ['paid', 'confirmed']);
    $statusLabel = ucfirst($order->status ?? 'pending');
    $deliveryStatusLabel = ucwords(str_replace('_', ' ', $order->delivery_status ?? 'pending'));
  @endphp

  <style>
    :root{
      --ink:#0f172a;          /* slate-900 */
      --muted:#475569;        /* slate-600 */
      --line:#e2e8f0;         /* slate-200 */
      --bg:#f8fafc;           /* slate-50 */
      --card:#ffffff;
      --brand:#0ea5e9;        /* sky-500 */
      --brand-600:#0284c7;    /* sky-600 */
      --success:#10b981;
      --warn:#f59e0b;
      --danger:#ef4444;
      --accent:#e0f2fe;       /* sky-100 */
    }

    *{box-sizing:border-box}
    html,body{margin:0;padding:0}
    body{
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue",
                   "Noto Sans", "Noto Naskh Arabic UI", Arial, "Apple Color Emoji",
                   "Segoe UI Emoji", "Segoe UI Symbol";
      color:var(--ink);
      background:var(--bg);
      line-height:1.6;
      -webkit-print-color-adjust:exact !important;
      print-color-adjust:exact !important;
      padding:24px;
    }

    .wrap{
      max-width:960px;
      margin-inline:auto;
      background:var(--card);
      border:1px solid var(--line);
      border-radius:16px;
      overflow:hidden;
      box-shadow: 0 8px 24px rgba(2,8,23,.06);
    }

    /* Header */
    .header{
      position:relative;
      background:linear-gradient(135deg, var(--brand) 0%, var(--brand-600) 100%);
      color:#fff;
      padding:28px 28px 20px;
    }
    .header-inner{
      display:flex;
      gap:24px;
      align-items:flex-start;
      justify-content:space-between;
      flex-wrap:wrap;
    }
    .brand{
      display:flex; gap:14px; align-items:center;
    }
    .brand .logo{
      width:44px;height:44px;border-radius:10px;
      background:#fff1; border:1px solid #fff3; display:grid; place-items:center;
      font-weight:800; font-size:18px;
    }
    .brand .name{
      font-weight:800; font-size:22px; letter-spacing:.2px;
    }
    .brand .meta{opacity:.9; font-size:12px}

    .doc{
      text-align:right;
    }
    [dir="rtl"] .doc{ text-align:left; }
    .doc .title{font-size:28px;font-weight:800;letter-spacing:.2px}
    .doc .num{opacity:.95;margin-top:4px;font-weight:600}
    .doc .date{opacity:.9;font-size:13px;margin-top:2px}

    .status-card{
      margin-top:16px;
      background:#fff;
      color:var(--ink);
      border-radius:12px;
      padding:14px 16px;
      border:1px solid #ffffff44;
      display:flex; gap:16px; align-items:center;
    }
    .chip{
      padding:6px 12px;border-radius:999px;font-weight:700;font-size:12px;color:#fff;display:inline-flex;align-items:center;gap:8px;
    }
    .chip .dot{width:8px;height:8px;border-radius:999px;background:#fff;opacity:.9}
    .chip.success{background:var(--success)}
    .chip.warn{background:var(--warn)}
    .chip.muted{background:#94a3b8}
    .chip.danger{background:var(--danger)}
    .mini{
      font-size:12px;color:#0f172a;opacity:.9
    }

    /* Body */
    .body{
      padding:28px;
    }

    .grid{
      display:grid;
      grid-template-columns: 1fr 1fr;
      gap:18px;
      margin-bottom:20px;
    }
    .card{
      background:var(--card);
      border:1px solid var(--line);
      border-radius:12px;
      padding:16px 18px;
    }
    .card h4{
      font-size:12px;text-transform:uppercase;letter-spacing:.6px;color:var(--brand-600);
      margin:0 0 10px 0; font-weight:800
    }
    .kv{font-size:14px;margin:6px 0}
    .muted{color:var(--muted)}
    .strong{font-weight:700}

    /* Table */
    .table{
      margin-top:8px;
      width:100%;
      border-collapse:separate;
      border-spacing:0;
      overflow:hidden;
      border:1px solid var(--line);
      border-radius:12px;
    }
    .table thead th{
      background:var(--accent);
      color:#0b3b53;
      padding:12px 14px;
      font-size:12px;
      text-transform:uppercase;
      letter-spacing:.6px;
      text-align:start;
      border-bottom:1px solid var(--line);
    }
    .table td{
      padding:14px;
      border-bottom:1px solid var(--line);
      vertical-align:top;
      font-size:14px;
    }
    .table tr:last-child td{border-bottom:none}
    .right{text-align:right}
    [dir="rtl"] .right{text-align:left}

    /* Totals */
    .totals{
      margin-top:18px;
      margin-inline-start:auto;
      max-width:420px;
      background:#f0f9ff;
      border:1px solid #bae6fd;
      border-radius:12px;
      padding:16px;
    }
    .trow{
      display:flex; justify-content:space-between; align-items:center;
      padding:8px 0; border-bottom:1px dashed #93c5fd;
    }
    .trow:last-child{
      border-bottom:none;
      margin-top:8px; padding-top:12px;
    }
    .grand{
      font-size:18px; font-weight:800; color:#0c4a6e;
    }

    /* Notes & Footer */
    .note{
      margin-top:16px;
      border-left:4px solid var(--warn);
      background:#fffbeb;
      padding:12px 14px;
      border-radius:10px;
      color:#92400e;
      font-size:14px;
    }

    .footer{
      padding:18px 28px 28px;
      display:flex; gap:12px; align-items:center; justify-content:space-between; flex-wrap:wrap;
      color:var(--muted); font-size:12px;
    }

    .btns{ display:flex; gap:10px }
    .btn{
      appearance:none; border:1px solid var(--brand-600); background:var(--brand); color:#fff;
      padding:10px 16px; border-radius:10px; font-weight:700; cursor:pointer;
      box-shadow: 0 6px 18px rgba(2,132,199,.25);
    }
    .btn:hover{ background:var(--brand-600) }
    .btn.secondary{
      background:transparent; color:var(--brand-600); border-color:var(--brand-600);
      box-shadow:none;
    }

    /* Print */
    @media print{
      body{ background:#fff; padding:0 }
      .wrap{ box-shadow:none; border-radius:0; border:none }
      .footer, .btns{ display:none !important }
      @page{ margin:12mm }
    }

    /* Small screens */
    @media (max-width:720px){
      .grid{ grid-template-columns: 1fr; }
      .doc{ text-align:inherit }
    }
  </style>
</head>
<body>
  <div class="wrap">
    <!-- HEADER -->
    <div class="header">
      <div class="header-inner">
        <div class="brand">
          {{-- <div class="logo">MD</div> --}}
          <div>
            <div class="name">Gifts shop</div>
            {{-- <div class="meta">{{ config('app.url') ?? 'menadevs.ai' }}</div> --}}
          </div>
        </div>

        <div class="doc">
          <div class="title">{{ __('Invoice') }}</div>
          <div class="num">#{{ str_pad((string)$order->id, 6, '0', STR_PAD_LEFT) }}</div>
          <div class="date">
            {{ $order->created_at?->timezone(config('app.timezone'))->format('Y-m-d • H:i') }}
          </div>
        </div>

        <div style="flex-basis:100%"></div>

        <div class="status-card">
          @php
            $chipClass = $paid ? 'success' : ($order->status === 'pending' ? 'warn' : ($order->status === 'cancelled' ? 'muted' : 'success'));
          @endphp
          <div class="chip {{ $chipClass }}">
            <span class="dot"></span>
            {{ $statusLabel }}
          </div>
          <div class="mini">
            {{ __('Delivery') }}:
            <strong>{{ $deliveryStatusLabel }}</strong>
            &nbsp;•&nbsp;
            {{ __('Date') }}:
            <strong>{{ $order->delivery_date?->format('Y-m-d') ?? '—' }}</strong>
            &nbsp;•&nbsp;
            {{ __('Time') }}:
            <strong>
              @if($order->deliveryTimeSlot)
                {{ \Illuminate\Support\Str::of($order->deliveryTimeSlot->window_start)->substr(0,5) }}
                &ndash;
                {{ \Illuminate\Support\Str::of($order->deliveryTimeSlot->window_end)->substr(0,5) }}
              @else
                —
              @endif
            </strong>
          </div>
        </div>
      </div>
    </div>

    <!-- BODY -->
    <div class="body">

      <div class="grid">
        <div class="card">
          <h4>{{ __('Bill To') }}</h4>
          <div class="kv strong">
            {{ $order->user->name ?? $order->receiver_name ?? __('Customer') }}
          </div>
          <div class="kv muted">
            {{ $order->user->email ?? $order->receiver_phone ?? '' }}
          </div>
          <div class="kv">{{ $order->shipping_address }}</div>
          <div class="kv muted">{{ $cityName }}</div>
        </div>

        <div class="card">
          <h4>{{ __('Order Summary') }}</h4>
          <div class="kv">
            <span class="muted">{{ __('Payment Method') }}:</span>
            <span class="strong">{{ strtoupper($order->payment_method ?? '—') }}</span>
          </div>
          <div class="kv">
            <span class="muted">{{ __('Order Status') }}:</span>
            <span class="strong">{{ $statusLabel }}</span>
          </div>
          <div class="kv">
            <span class="muted">{{ __('Total Items') }}:</span>
            <span class="strong">{{ $items->sum('quantity') }}</span>
          </div>
          <div class="kv">
            <span class="muted">{{ __('Time Slot') }}:</span>
            <span class="strong">{{ $slotName ?? '—' }}</span>
          </div>
          <div class="kv">
            <span class="muted">{{ __('Currency') }}:</span>
            <span class="strong">{{ $currency }}</span>
          </div>
        </div>
      </div>

      <table class="table">
        <thead>
          <tr>
            <th style="width:44%">{{ __('Product') }}</th>
            <th class="right" style="width:12%">{{ __('Qty') }}</th>
            <th class="right" style="width:22%">{{ __('Unit Price') }}</th>
            <th class="right" style="width:22%">{{ __('Line Total') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($items as $it)
            <tr>
              <td>
                {{ optional($it->product)->{$nameField} ?? (__('Product').' #'.$it->product_id) }}
                @if(optional($it->product)->sku)
                  <div class="muted" style="font-size:12px">{{ __('SKU') }}: {{ $it->product->sku }}</div>
                @endif
              </td>
              <td class="right">{{ (int)$it->quantity }}</td>
              <td class="right">
                {{ number_format((float)$it->unit_price, 2) }} {{ $currency }}
              </td>
              <td class="right">
                {{ number_format((float)$it->unit_price * (int)$it->quantity, 2) }} {{ $currency }}
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <div class="totals">
        <div class="trow">
          <span class="muted">{{ __('Subtotal') }}</span>
          <span class="strong">{{ number_format($subtotal, 2) }} {{ $currency }}</span>
        </div>

        @if($discountValue > 0)
          <div class="trow">
            <span class="muted">
              {{ __('Discount') }}
              @if(optional($order->discount->discountCode)->code)
                ({{ $order->discount->discountCode->code }})
              @endif
            </span>
            <span class="strong">-{{ number_format($discountValue, 2) }} {{ $currency }}</span>
          </div>
        @endif

        {{-- <div class="trow">
          <span class="muted">{{ __('Payment Status') }}</span>
          <span class="strong">
            {{ ucfirst($order->payment->status ?? ($paid ? 'succeeded' : 'pending')) }}
          </span>
        </div> --}}

        <div class="trow grand">
          <span>{{ __('Total') }}</span>
          <span>{{ number_format($total, 2) }} {{ $currency }}</span>
        </div>
      </div>

      @if($order->extra_notes)
        <div class="note">
          <strong style="display:block;margin-bottom:4px">{{ __('Additional Notes') }}</strong>
          {{ $order->extra_notes }}
        </div>
      @endif

    </div>

    <!-- FOOTER / ACTIONS -->
    <div class="footer">
      <div>
        © {{ date('Y') }} Gifts Shop — {{ __('Thank you for your purchase!') }}
      </div>
      <div class="btns">
        <button class="btn" onclick="window.print()">🖨️ {{ __('Print') }}</button>
        <button class="btn secondary" onclick="window.close()">{{ __('Close') }}</button>
      </div>
    </div>
  </div>
</body>
</html>
