<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Track Your Order | VLOCUS</title>
  <meta name="description" content="Track your VLOCUS order, contact your delivery partner, update delivery instructions and review order details." />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: { brand: { 50:'#f1faed', 100:'#dcf3d4', 500:'#2c9a1f', 600:'#218516', 700:'#176a10', 900:'#103c0d' } },
          boxShadow: { card:'0 8px 28px rgba(15, 23, 42, .07)', lift:'0 18px 60px rgba(15, 23, 42, .18)' },
          fontFamily: { sans:['Inter','ui-sans-serif','system-ui','sans-serif'] }
        }
      }
    }
  </script>
  <style>
    html { scroll-behavior: smooth; }
    body { -webkit-font-smoothing: antialiased; }
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { scrollbar-width: none; }
    .pulse-ring { animation: pulse-ring 1.9s ease-out infinite; }
    @keyframes pulse-ring { 0% { transform:scale(.72); opacity:.85 } 80%,100% { transform:scale(1.7); opacity:0 } }
    .sheet { transition: transform .28s cubic-bezier(.22,.9,.3,1); }
    .modal-backdrop { transition: opacity .2s ease; }
    .route-dash { stroke-dasharray:8 8; animation: route 12s linear infinite; }
    @keyframes route { to { stroke-dashoffset:-160; } }
  </style>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 selection:bg-brand-100">
@php
    $accepted = (bool) ($order->is_accepted ?? false);
    $delivered = (bool) ($order->is_delivered ?? false);
    $cancelled = $order->isCancelled();

    // Stage 2 (picked up) and stage 3 (out for delivery) share the single
    // "is_accepted" flag on the shop record - there is no separate flag for
    // each - so timelineStage() jumps straight from 1 to 3 and both steps
    // are shown done together.
    $currentStage = $order->timelineStage();
    $stage2Done = $currentStage >= 3;
    $stage3Done = $currentStage >= 3;
    $stage4Done = $currentStage >= 4;
    $progressPercent = $delivered ? 100 : ($accepted ? 66 : 20);

    $orderNumber = $delivery?->order_id ?? $order->invoice_no ?? 'N/A';
    $vehicleNumber = $vehicle?->vehicle_number;
    $driverName = $driver?->name;
    $driverPhone = $driver?->phone;

    $receiverName = $order->effectiveReceiverName();
    $receiverPhone = $order->effectiveReceiverPhone();
    $receiverPhoneMasked = maskPhoneNumber($receiverPhone);

    $addressLine = collect([
        $shop?->shop_street_address,
        $shop?->shop_address,
        $shop?->shop_district,
        $shop?->shop_state,
        $shop?->shop_pincode,
    ])->filter()->unique()->implode(', ');

    $shopLat = $shop?->shop_latitude;
    $shopLng = $shop?->shop_longitude;
    $driverLat = $driver_details?->latitude;
    $driverLng = $driver_details?->longitude;
    $hasShopCoords = $shopLat !== null && $shopLng !== null && $shopLat !== '' && $shopLng !== '';
    $hasDriverCoords = $driverLat !== null && $driverLng !== null && $driverLat !== '' && $driverLng !== '';

    $centerLat = $hasShopCoords ? (float) $shopLat : 22.5050;
    $centerLng = $hasShopCoords ? (float) $shopLng : 88.3520;
    if ($hasDriverCoords && $hasShopCoords) {
        $centerLat = ((float) $driverLat + (float) $shopLat) / 2;
        $centerLng = ((float) $driverLng + (float) $shopLng) / 2;
    } elseif ($hasDriverCoords) {
        $centerLat = (float) $driverLat;
        $centerLng = (float) $driverLng;
    }

    $buildBbox = fn (float $lat, float $lng, float $margin) =>
        sprintf('%F,%F,%F,%F', $lng - $margin, $lat - $margin, $lng + $margin, $lat + $margin);

    $thumbBbox = $buildBbox($centerLat, $centerLng, 0.02);
    $expandedBbox = $buildBbox($centerLat, $centerLng, 0.035);
    $fullBbox = $buildBbox($centerLat, $centerLng, 0.045);
    $markerLat = $hasShopCoords ? $shopLat : $centerLat;
    $markerLng = $hasShopCoords ? $shopLng : $centerLng;

    $products = $products ?? collect();
    $totalAmount = $order->amount ?? $delivery?->amount ?? 0;
    $paymentType = strtolower((string) ($order->payment_type ?? $delivery?->payment_type ?? ''));
    $isCod = str_contains($paymentType, 'cod') || str_contains($paymentType, 'cash');

    if ($cancelled) {
        $statusLabel = 'Order cancelled';
        $headline = 'This order was cancelled';
    } elseif ($delivered) {
        $statusLabel = 'Order delivered';
        $headline = $order->delivered_at ? 'Delivered on ' . $order->delivered_at->format('d M, g:i A') : 'Your order has been delivered';
    } elseif ($etaMinutes !== null) {
        $statusLabel = 'Order is on the way';
        $headline = null;
    } else {
        $statusLabel = $accepted ? 'Order is on the way' : 'Order confirmed';
        $headline = 'We\'ll share live tracking as soon as your driver starts the trip';
    }
@endphp
  <div class="mx-auto min-h-screen max-w-[1440px] lg:grid lg:grid-cols-[340px_minmax(0,760px)] lg:justify-center lg:gap-6 lg:px-6 lg:py-6">
    <aside class="hidden lg:block">
      <div class="sticky top-6 overflow-hidden rounded-[28px] bg-slate-950 text-white shadow-lift">
        <div class="bg-gradient-to-br from-brand-600 to-emerald-950 p-7">
          <a href="/" class="inline-flex items-center gap-3" aria-label="VLOCUS home">
            <span class="grid h-11 w-11 place-items-center rounded-2xl bg-white text-2xl font-black text-brand-700">V</span>
            <span class="text-2xl font-black tracking-tight">VLOCUS</span>
          </a>
          <p class="mt-8 text-sm font-medium text-white/70">Live order tracking</p>
          <p class="mt-1 text-3xl font-black">{{ $statusLabel }}</p>
          <div class="mt-5 h-1.5 overflow-hidden rounded-full bg-white/20"><div id="desktopProgress" class="h-full rounded-full bg-lime-300 transition-all duration-500" style="width: {{ $progressPercent }}%"></div></div>
        </div>
        <div class="space-y-5 p-7">
          <div class="flex gap-4">
            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full {{ $cancelled ? 'bg-white/20 text-white/60' : 'bg-lime-300 text-brand-900' }}">✓</span>
            <div><p class="font-bold">Order confirmed</p><p class="text-sm text-white/50">{{ $order->created_at?->format('g:i A') }}</p></div>
          </div>
          <div class="flex gap-4 {{ $cancelled ? 'opacity-40' : '' }}">
            @if($stage2Done)
              <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-lime-300 text-brand-900">✓</span>
            @elseif($currentStage === 1 && !$cancelled)
              <span class="relative grid h-10 w-10 shrink-0 place-items-center rounded-full bg-white text-brand-700"><span class="absolute inset-0 rounded-full border-2 border-white pulse-ring"></span>●</span>
            @else
              <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full border border-white/40">2</span>
            @endif
            <div><p class="font-bold">Picked up</p><p class="text-sm text-white/50">{{ $stage2Done ? 'Your partner picked up the order' : 'Waiting for pickup' }}</p></div>
          </div>
          <div class="flex gap-4 {{ $cancelled ? 'opacity-40' : '' }}">
            @if($stage4Done)
              <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-lime-300 text-brand-900">✓</span>
            @elseif($currentStage === 3 && !$cancelled)
              <span class="relative grid h-10 w-10 shrink-0 place-items-center rounded-full bg-white text-brand-700"><span class="absolute inset-0 rounded-full border-2 border-white pulse-ring"></span>●</span>
            @else
              <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full border border-white/40">3</span>
            @endif
            <div><p class="font-bold">Out for delivery</p><p class="text-sm text-white/50">{{ $stage3Done ? 'Live location enabled' : 'Not started yet' }}</p></div>
          </div>
          <div class="flex gap-4 {{ $stage4Done ? '' : 'opacity-40' }}">
            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full {{ $stage4Done ? 'bg-lime-300 text-brand-900' : 'border border-white/40' }}">{{ $stage4Done ? '✓' : '4' }}</span>
            <div><p class="font-bold">Delivered</p><p class="text-sm text-white/50">{{ $stage4Done ? 'Order completed' : 'Almost there' }}</p></div>
          </div>
        </div>
      </div>
    </aside>

    <main class="min-w-0 bg-slate-100 lg:overflow-hidden lg:rounded-[28px] lg:border lg:border-white lg:shadow-lift">
      <header class="sticky top-0 z-30 bg-brand-600 px-4 pb-5 pt-4 text-white shadow-md sm:px-6 lg:relative">
        <div class="mx-auto flex max-w-2xl items-center gap-4">
          <button class="grid h-11 w-11 place-items-center rounded-full transition hover:bg-white/10" aria-label="Go back" onclick="history.back()">
            <svg viewBox="0 0 24 24" class="h-6 w-6 fill-none stroke-current" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
          </button>
          <div class="flex-1 text-center">
            <p class="text-xs font-semibold text-white/75">{{ $statusLabel }}</p>
            <h1 class="mt-0.5 text-lg font-black tracking-tight sm:text-xl">Order #{{ $orderNumber }}</h1>
          </div>
          <button class="grid h-11 w-11 place-items-center rounded-full bg-white/10" aria-label="More options">•••</button>
        </div>
      </header>

      <div class="mx-auto max-w-2xl space-y-3 px-3 py-3 sm:px-5 sm:py-5">
        @if($cancelled)
        <section class="rounded-3xl bg-white p-5 shadow-card">
          <div class="flex items-center gap-4">
            <span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-rose-50 text-2xl text-rose-600">✕</span>
            <div>
              <h2 class="text-lg font-black text-rose-700">This order was cancelled</h2>
              @if($order->cancel_reason)
                <p class="mt-1 text-sm text-slate-500">{{ $order->cancel_reason }}</p>
              @else
                <p class="mt-1 text-sm text-slate-500">Please contact support if you have questions about this order.</p>
              @endif
            </div>
          </div>
        </section>
        @endif

        <section id="bannerSlider" class="group relative overflow-hidden rounded-3xl bg-slate-900 text-white shadow-card" aria-label="Promotional Offers Slider">
          <!-- Slides Track Container -->
          <div id="sliderTrack" class="flex transition-transform duration-500 ease-out">
            <!-- Slide 1: VLOCUS Play -->
            <div class="w-full shrink-0 relative overflow-hidden p-7 text-white sm:p-9 md:p-10 min-h-[270px] sm:min-h-[320px] flex flex-col justify-between">
              <div class="absolute inset-0 bg-gradient-to-br from-purple-950 via-purple-900 to-purple-950"></div>
              <div class="absolute -right-10 -top-16 h-52 w-52 rounded-full bg-fuchsia-400/20 blur-2xl pointer-events-none"></div>

              <div class="relative z-10">
                <p class="text-xs font-bold uppercase tracking-[.22em] text-fuchsia-300">VLOCUS Play</p>
                <h2 class="mt-2.5 max-w-md text-2xl font-black leading-tight sm:text-3xl md:text-4xl drop-shadow-md">Entertainment unlocked with your order</h2>
                <p class="mt-2.5 text-sm sm:text-base text-white/90 drop-shadow">Movies, music and more from ₹149/month.</p>
              </div>
              <div class="relative z-10 mt-6 flex items-center justify-between">
                <button class="rounded-xl bg-white px-6 py-3 text-sm sm:text-base font-bold text-purple-900 transition hover:-translate-y-0.5 hover:shadow-lg active:scale-95">View offer</button>
              </div>
              <div class="absolute bottom-6 right-6 z-10 grid h-20 w-20 place-items-center rounded-full bg-white/20 text-4xl backdrop-blur-md sm:h-28 sm:w-28 sm:text-5xl shadow-lg select-none">▶</div>
            </div>

            <!-- Slide 2: Super Savings -->
            <div class="w-full shrink-0 relative overflow-hidden p-7 text-white sm:p-9 md:p-10 min-h-[270px] sm:min-h-[320px] flex flex-col justify-between">
              <div class="absolute inset-0 bg-gradient-to-br from-teal-950 via-teal-900 to-emerald-950"></div>
              <div class="absolute -right-10 -top-16 h-52 w-52 rounded-full bg-emerald-400/20 blur-2xl pointer-events-none"></div>

              <div class="relative z-10">
                <p class="text-xs font-bold uppercase tracking-[.22em] text-emerald-300">Super Savings</p>
                <h2 class="mt-2.5 max-w-md text-2xl font-black leading-tight sm:text-3xl md:text-4xl drop-shadow-md">Win up to ₹500 Cashback on UPI</h2>
                <p class="mt-2.5 text-sm sm:text-base text-white/90 drop-shadow">Use code <span class="font-bold text-emerald-200">SUPERPAY</span> on orders above ₹299.</p>
              </div>
              <div class="relative z-10 mt-6 flex items-center justify-between">
                <button class="rounded-xl bg-white px-6 py-3 text-sm sm:text-base font-bold text-teal-900 transition hover:-translate-y-0.5 hover:shadow-lg active:scale-95">Claim Cashback</button>
              </div>
              <div class="absolute bottom-6 right-6 z-10 grid h-20 w-20 place-items-center rounded-full bg-white/20 text-4xl backdrop-blur-md sm:h-28 sm:w-28 sm:text-5xl shadow-lg select-none">💰</div>
            </div>

            <!-- Slide 3: Gourmet Pass -->
            <div class="w-full shrink-0 relative overflow-hidden p-7 text-white sm:p-9 md:p-10 min-h-[270px] sm:min-h-[320px] flex flex-col justify-between">
              <div class="absolute inset-0 bg-gradient-to-br from-stone-950 via-amber-950 to-orange-950"></div>
              <div class="absolute -right-10 -top-16 h-52 w-52 rounded-full bg-amber-400/20 blur-2xl pointer-events-none"></div>

              <div class="relative z-10">
                <p class="text-xs font-bold uppercase tracking-[.22em] text-amber-300">Gourmet Pass</p>
                <h2 class="mt-2.5 max-w-md text-2xl font-black leading-tight sm:text-3xl md:text-4xl drop-shadow-md">Flat 50% OFF on Top Restaurants</h2>
                <p class="mt-2.5 text-sm sm:text-base text-white/90 drop-shadow">Exclusive dining perks & free delivery unlocked.</p>
              </div>
              <div class="relative z-10 mt-6 flex items-center justify-between">
                <button class="rounded-xl bg-white px-6 py-3 text-sm sm:text-base font-bold text-amber-900 transition hover:-translate-y-0.5 hover:shadow-lg active:scale-95">Join Gourmet</button>
              </div>
              <div class="absolute bottom-6 right-6 z-10 grid h-20 w-20 place-items-center rounded-full bg-white/20 text-4xl backdrop-blur-md sm:h-28 sm:w-28 sm:text-5xl shadow-lg select-none">🍔</div>
            </div>

            <!-- Slide 4: Express Direct -->
            <div class="w-full shrink-0 relative overflow-hidden p-7 text-white sm:p-9 md:p-10 min-h-[270px] sm:min-h-[320px] flex flex-col justify-between">
              <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-indigo-950 to-sky-950"></div>
              <div class="absolute -right-10 -top-16 h-52 w-52 rounded-full bg-sky-400/20 blur-2xl pointer-events-none"></div>

              <div class="relative z-10">
                <p class="text-xs font-bold uppercase tracking-[.22em] text-sky-300">VLOCUS Express</p>
                <h2 class="mt-2.5 max-w-md text-2xl font-black leading-tight sm:text-3xl md:text-4xl drop-shadow-md">Guaranteed 10-Min Express Delivery</h2>
                <p class="mt-2.5 text-sm sm:text-base text-white/90 drop-shadow">Priority rider assigned automatically for fast delivery.</p>
              </div>
              <div class="relative z-10 mt-6 flex items-center justify-between">
                <button class="rounded-xl bg-white px-6 py-3 text-sm sm:text-base font-bold text-indigo-950 transition hover:-translate-y-0.5 hover:shadow-lg active:scale-95">Explore Fast Deals</button>
              </div>
              <div class="absolute bottom-6 right-6 z-10 grid h-20 w-20 place-items-center rounded-full bg-white/20 text-4xl backdrop-blur-md sm:h-28 sm:w-28 sm:text-5xl shadow-lg select-none">⚡</div>
            </div>
          </div>

          <button id="sliderPrev" class="absolute left-3 top-1/2 -translate-y-1/2 z-20 grid h-9 w-9 place-items-center rounded-full bg-black/40 text-white backdrop-blur-md opacity-0 group-hover:opacity-100 transition-opacity duration-200 hover:bg-black/65 focus:opacity-100 active:scale-90" aria-label="Previous slide">
            <svg viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
          </button>
          <button id="sliderNext" class="absolute right-3 top-1/2 -translate-y-1/2 z-20 grid h-9 w-9 place-items-center rounded-full bg-black/40 text-white backdrop-blur-md opacity-0 group-hover:opacity-100 transition-opacity duration-200 hover:bg-black/65 focus:opacity-100 active:scale-90" aria-label="Next slide">
            <svg viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current" stroke-width="2.5"><path d="m9 5 6 6-6 6"/></svg>
          </button>

          <div id="sliderDots" class="absolute bottom-3 left-1/2 -translate-x-1/2 z-20 flex items-center gap-1.5 rounded-full bg-black/35 px-3 py-1.5 backdrop-blur-md"></div>
        </section>

        <!-- Order Status & Map Card -->
        <section class="overflow-hidden rounded-3xl bg-white p-4 shadow-card sm:p-5 transition-all duration-300">
          <div id="collapsedMapSection" class="flex items-center justify-between gap-2.5 sm:gap-6 p-1">
            <div class="min-w-0 flex-1">
              <p class="text-xs font-bold text-slate-800 sm:text-base whitespace-nowrap">{{ $statusLabel }}</p>
              @if($cancelled)
                <h2 class="mt-1 text-lg sm:text-2xl md:text-3xl font-black leading-tight text-rose-700 tracking-tight">Order cancelled</h2>
              @elseif($delivered)
                <h2 class="mt-1 text-lg sm:text-2xl md:text-3xl font-black leading-tight text-brand-700 tracking-tight">Delivered</h2>
              @elseif($etaMinutes !== null)
                <h2 class="mt-1 text-lg sm:text-2xl md:text-3xl font-black leading-tight text-brand-700 tracking-tight whitespace-nowrap">Arriving in <span id="etaMap">{{ $etaMinutes }}</span> minutes</h2>
              @else
                <h2 class="mt-1 text-lg sm:text-2xl md:text-3xl font-black leading-tight text-brand-700 tracking-tight">Calculating ETA…</h2>
              @endif
            </div>

            <div id="mapPreviewCard" class="group relative h-24 w-28 shrink-0 cursor-pointer overflow-hidden rounded-2xl bg-[#e5e3df] shadow-sm transition hover:shadow-md sm:h-36 sm:w-52 border border-slate-200/80">
              <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <iframe id="thumbMapFrame" title="Delivery route map preview" class="h-[180%] w-[180%] -translate-x-[25%] -translate-y-[25%] border-0 opacity-90 transition group-hover:scale-105 duration-300" loading="lazy" src="https://www.openstreetmap.org/export/embed.html?bbox={{ $thumbBbox }}&amp;layer=mapnik"></iframe>
              </div>

              <button id="triggerFullMapBtn" class="absolute right-2 top-2 z-10 grid h-8 w-8 place-items-center rounded-full bg-white/95 text-slate-800 shadow-md backdrop-blur-sm transition group-hover:bg-white group-hover:scale-110 active:scale-95 border border-slate-200/60" aria-label="Expand map">
                <svg viewBox="0 0 24 24" class="h-4 w-4 fill-none stroke-current" stroke-width="2.5"><path d="M15 3h6v6M14 10l7-7M9 21H3v-6M10 14l-7 7"/></svg>
              </button>

              <div class="pointer-events-none absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 flex flex-col items-center z-10">
                <span class="relative grid h-8.5 w-8.5 place-items-center rounded-full border-2 border-white bg-brand-600 text-sm text-white shadow-md">🛵</span>
              </div>

              <div class="pointer-events-none absolute bottom-2 left-2 z-10 select-none">
                <span class="rounded bg-white/95 px-1.5 py-0.5 text-[10px] font-black tracking-tight text-slate-800 shadow-xs border border-slate-200/60">Map</span>
              </div>
            </div>
          </div>

          <!-- Expanded View -->
          <div id="expandedMapSection" class="hidden space-y-3 p-1">
            <div class="flex items-start justify-between px-2 pt-1">
              <div>
                <p class="text-base font-bold text-slate-800 sm:text-lg">{{ $statusLabel }}</p>
                @if($etaMinutes !== null && !$delivered && !$cancelled)
                  <h2 class="mt-1 text-2xl font-black leading-tight text-brand-700 sm:text-3xl">Arriving in <span class="fullEta font-black">{{ $etaMinutes }}</span> minutes</h2>
                @else
                  <h2 class="mt-1 text-2xl font-black leading-tight text-brand-700 sm:text-3xl">{{ $headline ?? $statusLabel }}</h2>
                @endif
              </div>
            </div>

            <div class="relative h-72 sm:h-96 w-full overflow-hidden rounded-2xl bg-slate-300 shadow-inner">
              <iframe id="expandedMapFrame" title="Full expanded delivery map" class="h-full w-full border-0 contrast-100 opacity-95" loading="lazy" src="https://www.openstreetmap.org/export/embed.html?bbox={{ $expandedBbox }}&amp;layer=mapnik&amp;marker={{ $markerLat }}%2C{{ $markerLng }}"></iframe>

              <button id="collapseMapBtn" class="absolute right-3 top-3 z-20 grid h-9 w-9 place-items-center rounded-full bg-white/95 text-slate-800 shadow-lg backdrop-blur-md transition hover:bg-white hover:scale-110 active:scale-95" aria-label="Minimize map">
                <svg viewBox="0 0 24 24" class="h-4.5 w-4.5 fill-none stroke-current" stroke-width="2.5"><path d="M4 14h6v6M10 14L3 21M20 10h-6V4M14 10l7-7"/></svg>
              </button>

              @if($hasDriverCoords)
              <div class="pointer-events-none absolute left-1/2 top-[18%] z-10 -translate-x-1/2 flex flex-col items-center">
                <div class="relative grid h-10 w-10 place-items-center rounded-full border-2 border-white bg-slate-900 shadow-xl">
                  <span class="grid h-7 w-7 place-items-center rounded-full bg-brand-600 text-sm text-white">🛵</span>
                </div>
              </div>
              @endif

              <div class="pointer-events-none absolute left-[45%] top-[75%] z-10 -translate-x-1/2 flex flex-col items-center">
                <div class="relative grid h-9 w-9 place-items-center rounded-full border-2 border-blue-600 bg-white shadow-xl">
                  <span class="text-sm">🏠</span>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Free Movie Ticket Voucher -->
        <section class="flex items-center gap-4 rounded-3xl bg-white p-4 shadow-card">
          <span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-violet-600 text-xl text-white">🎟</span>
          <div class="min-w-0 flex-1"><h2 class="font-extrabold">Free movie voucher unlocked!</h2><p class="mt-0.5 text-sm text-slate-500">₹100 OFF on your next movie booking.</p><button class="mt-1 text-sm font-bold text-brand-700">Know more ›</button></div>
        </section>

        <!-- Order Summary -->
        <section class="rounded-3xl bg-white p-5 shadow-card">
          <div class="flex items-center gap-4">
            <span class="grid h-11 w-11 place-items-center rounded-2xl bg-slate-100 text-xl">🛍</span>
            <div class="flex-1">
              <h2 class="text-lg font-black">Order summary</h2>
              <p class="text-sm text-slate-500">Order ID · #{{ $orderNumber }}</p>
            </div>
            <button id="summaryOpen" class="rounded-xl bg-brand-50 px-3.5 py-1.5 text-sm font-extrabold text-brand-700 transition hover:bg-brand-100 active:scale-95">View</button>
          </div>
        </section>

        <!-- Delivery Location Details -->
        <section class="rounded-3xl bg-white p-5 shadow-card">
          <div class="flex items-center gap-4"><span class="grid h-11 w-11 place-items-center rounded-2xl bg-slate-100 text-xl">🛵</span><div><h2 class="text-lg font-black">Your delivery details</h2><p class="text-sm text-slate-500">Details of your current order</p></div></div>
          <div class="mt-5 space-y-5 border-t border-slate-100 pt-5">
            <div class="flex gap-4"><span class="text-xl">📍</span><div class="flex-1"><p class="font-extrabold">Deliver at {{ $shop?->shop_name ?? 'destination' }}</p><p class="mt-1 text-sm leading-relaxed text-slate-500">{{ $addressLine ?: 'Address not available' }}</p></div></div>
            <div class="rounded-xl bg-amber-50 px-4 py-3 text-sm text-amber-900">Update your address immediately if you have ordered at an incorrect location.</div>
            <button id="contactOpen" class="flex w-full items-center gap-4 text-left"><span class="text-xl">☎</span><span class="flex-1"><span id="receiverLabel" class="block font-extrabold">{{ $receiverName ?: 'Receiver' }}, {{ $receiverPhoneMasked ?: '—' }}</span><span class="block text-sm text-slate-500">Update receiver's contact</span></span><span>›</span></button>
          </div>
        </section>

        <!-- Driver / Vehicle -->
        <section class="rounded-3xl bg-white p-5 shadow-card">
          <div class="flex items-center gap-4"><span class="grid h-11 w-11 place-items-center rounded-2xl bg-slate-100 text-xl">🚚</span><div><h2 class="text-lg font-black">Delivery partner</h2><p class="text-sm text-slate-500">Assigned driver & vehicle</p></div></div>
          <div class="mt-5 grid grid-cols-2 gap-4 border-t border-slate-100 pt-5 text-sm">
            <div><p class="text-slate-500">Driver</p><p class="mt-0.5 font-extrabold">{{ $driverName ?: 'Not assigned yet' }}</p></div>
            <div><p class="text-slate-500">Vehicle No.</p><p class="mt-0.5 font-extrabold">{{ $vehicleNumber ?: '—' }}</p></div>
          </div>
          @if($driverPhone)
          <a href="tel:{{ $driverPhone }}" class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl bg-brand-600 py-3 text-sm font-extrabold text-white transition hover:bg-brand-700">☎ Call driver</a>
          @endif
        </section>

        <!-- Need Help? -->
        <section class="rounded-3xl bg-white p-5 shadow-card">
          <button class="flex w-full items-center gap-4 text-left"><span class="grid h-11 w-11 place-items-center rounded-2xl bg-slate-100 text-xl">💬</span><span class="flex-1"><span class="block text-lg font-black">Need help?</span><span class="block text-sm text-slate-500">Chat with us about any issue related to your order</span></span><span>›</span></button>
        </section>

        <!-- Rating Section -->
        <section class="mb-6 rounded-3xl bg-white p-5 text-center shadow-card">
          <p class="font-extrabold">Do you like our service?</p>
          <p class="mt-1 text-sm text-slate-500">Rate your delivery experience</p>
          <div id="ratingButtons" class="mt-3 flex justify-center gap-3.5 text-2xl sm:text-3xl">
            <button class="rating transition hover:scale-125 {{ (int) $order->rating === 1 ? 'scale-125' : '' }}" data-rating="1" aria-label="Very Bad" {{ $order->rated_at ? 'disabled' : '' }}>😡</button>
            <button class="rating transition hover:scale-125 {{ (int) $order->rating === 2 ? 'scale-125' : '' }}" data-rating="2" aria-label="Bad" {{ $order->rated_at ? 'disabled' : '' }}>😞</button>
            <button class="rating transition hover:scale-125 {{ (int) $order->rating === 3 ? 'scale-125' : '' }}" data-rating="3" aria-label="Neutral" {{ $order->rated_at ? 'disabled' : '' }}>😐</button>
            <button class="rating transition hover:scale-125 {{ (int) $order->rating === 4 ? 'scale-125' : '' }}" data-rating="4" aria-label="Good" {{ $order->rated_at ? 'disabled' : '' }}>😊</button>
            <button class="rating transition hover:scale-125 {{ (int) $order->rating === 5 ? 'scale-125' : '' }}" data-rating="5" aria-label="Loved It" {{ $order->rated_at ? 'disabled' : '' }}>😍</button>
          </div>
          <p id="ratingMessage" class="mt-2 {{ $order->rated_at ? '' : 'hidden' }} text-sm font-bold text-brand-700">Thank you for your feedback!</p>
        </section>
      </div>
    </main>
  </div>

  <div id="backdrop" class="modal-backdrop pointer-events-none fixed inset-0 z-40 bg-slate-950/55 opacity-0"></div>

  <section id="contactSheet" class="sheet fixed inset-x-0 bottom-0 z-50 mx-auto translate-y-full rounded-t-[28px] bg-white p-5 shadow-lift sm:max-w-xl sm:rounded-[28px] sm:bottom-6" role="dialog" aria-modal="true" aria-labelledby="contactTitle">
    <div class="mx-auto mb-5 h-1.5 w-12 rounded-full bg-slate-200"></div>
    <div class="flex items-center justify-between"><h2 id="contactTitle" class="text-xl font-black">Update receiver's contact</h2><button class="closeSheet grid h-10 w-10 place-items-center rounded-full bg-slate-100" aria-label="Close">×</button></div>
    <form id="contactForm" class="mt-5 space-y-4">
      <label class="block"><span class="text-sm font-bold">Mobile number</span><input id="mobile" name="receiver_phone" required pattern="[0-9]{10}" maxlength="10" value="{{ $receiverPhone }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-100" /></label>
      <label class="block"><span class="text-sm font-bold">Name</span><input id="receiver" name="receiver_name" required value="{{ $receiverName }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-100" /></label>
      <button class="w-full rounded-xl bg-brand-600 py-3.5 font-extrabold text-white transition hover:bg-brand-700">Save contact</button>
    </form>
  </section>

  <section id="summarySheet" class="sheet fixed inset-x-0 bottom-0 z-50 mx-auto max-h-[86vh] translate-y-full overflow-auto rounded-t-[28px] bg-white p-5 shadow-lift sm:max-w-xl sm:rounded-[28px] sm:bottom-6" role="dialog" aria-modal="true" aria-labelledby="summaryTitle">
    <div class="mx-auto mb-5 h-1.5 w-12 rounded-full bg-slate-200"></div>
    <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
      <div>
        <h2 id="summaryTitle" class="text-xl font-black text-slate-900">Order Bill Summary</h2>
        <p class="text-xs font-medium text-slate-500">Invoice / Order ID · #{{ $orderNumber }}</p>
      </div>
      <button class="closeSheet grid h-10 w-10 place-items-center rounded-full bg-slate-100 text-slate-600 transition hover:bg-slate-200 active:scale-95" aria-label="Close">×</button>
    </div>

    <div class="mt-4 rounded-2xl border border-slate-200 bg-white p-3.5 sm:p-4 shadow-xs">
      <div class="flex items-center justify-between border-b border-dashed border-slate-200 pb-2.5 text-[11px] text-slate-500">
        <span class="font-semibold text-slate-700">Receipt #{{ $order->invoice_no ?? $orderNumber }}</span>
        <span>{{ $order->created_at?->format('d M, g:i A') }}</span>
      </div>

      <table class="w-full text-left text-xs sm:text-sm mt-3">
        <thead>
          <tr class="border-b border-slate-200 text-[10px] sm:text-xs font-black uppercase tracking-wider text-slate-500">
            <th scope="col" class="pb-2.5 pr-1 font-extrabold">Product Details</th>
            <th scope="col" class="pb-2.5 px-1 text-center font-extrabold">QTY</th>
            <th scope="col" class="pb-2.5 px-1 text-center font-extrabold">Prepaid / COD</th>
            <th scope="col" class="pb-2.5 pl-1 text-right font-extrabold">Amount</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($products as $product)
          <tr>
            <td class="py-3 pr-1 align-top">
              <p class="font-bold text-slate-900 leading-snug">{{ $product->title }}</p>
              @if($product->unit_or_box)<p class="text-[11px] text-slate-500 mt-0.5">{{ $product->unit_or_box }}</p>@endif
            </td>
            <td class="py-3 px-1 text-center font-bold text-slate-800 align-top">{{ $product->qty }}</td>
            <td class="py-3 px-1 text-center align-top">
              @if($loop->first)
              <span class="inline-block rounded-full {{ $isCod ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200' }} px-2 py-0.5 text-[10px] sm:text-xs font-bold border">
                {{ $isCod ? 'COD' : 'Prepaid' }}
              </span>
              @endif
            </td>
            <td class="py-3 pl-1 text-right font-black text-slate-900 align-top">@if($loop->first)₹{{ number_format((float) $totalAmount, 0) }}@endif</td>
          </tr>
          @empty
          <tr>
            <td class="py-3 pr-1 align-top">
              <p class="font-bold text-slate-900 leading-snug">Order #{{ $order->invoice_no ?? $orderNumber }}</p>
            </td>
            <td class="py-3 px-1 text-center font-bold text-slate-800 align-top">1</td>
            <td class="py-3 px-1 text-center align-top">
              <span class="inline-block rounded-full {{ $isCod ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200' }} px-2 py-0.5 text-[10px] sm:text-xs font-bold border">
                {{ $isCod ? 'COD' : 'Prepaid' }}
              </span>
            </td>
            <td class="py-3 pl-1 text-right font-black text-slate-900 align-top">₹{{ number_format((float) $totalAmount, 0) }}</td>
          </tr>
          @endforelse
        </tbody>
        <tfoot>
          <tr class="border-t border-dashed border-slate-200">
            <td colspan="3" class="pt-3 pb-1 text-xs font-extrabold uppercase tracking-wider text-slate-600">
              Total Payable
            </td>
            <td class="pt-3 pb-1 text-right text-sm font-black text-slate-900">
              ₹{{ number_format((float) $totalAmount, 0) }}
            </td>
          </tr>
        </tfoot>
      </table>

      <div class="mt-3 flex items-center justify-between rounded-xl bg-slate-50 px-3 py-2 text-xs border border-slate-100">
        <span class="text-slate-500 font-medium">Payment Status</span>
        <span class="font-bold {{ $isCod ? 'text-amber-700' : 'text-emerald-700' }} flex items-center gap-1.5">
          <span class="inline-block h-2 w-2 rounded-full {{ $isCod ? 'bg-amber-500' : 'bg-emerald-500' }}"></span>
          {{ $isCod ? 'Cash on Delivery' : 'Prepaid · Paid Online' }}
        </span>
      </div>
    </div>
  </section>

  <!-- Full Width Map Modal Overlay -->
  <div id="fullMapModal" class="fixed inset-0 z-50 flex flex-col bg-slate-950 opacity-0 pointer-events-none transition-all duration-300">
    <header class="relative z-10 flex items-center justify-between bg-slate-950/85 px-4 py-3 text-white backdrop-blur-md border-b border-white/10 sm:px-6">
      <div class="flex items-center gap-3">
        <button id="closeFullMapBtn" class="grid h-10 w-10 place-items-center rounded-full bg-white/10 text-white transition hover:bg-white/20 active:scale-95" aria-label="Close full map">
          <svg viewBox="0 0 24 24" class="h-6 w-6 fill-none stroke-current" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </button>
        <div>
          <h2 class="text-base font-black sm:text-lg">Live Delivery Map</h2>
          <p class="text-xs text-white/70">Order #{{ $orderNumber }} @if($etaMinutes !== null && !$delivered && !$cancelled)· Arriving in <span class="font-bold text-lime-400"><span class="fullEta">{{ $etaMinutes }}</span> mins</span>@endif</p>
        </div>
      </div>
      <div class="flex items-center gap-2">
        <span class="rounded-full bg-brand-500/20 border border-brand-500/50 px-3 py-1 text-xs font-bold text-lime-300">{{ $hasDriverCoords ? 'LIVE GPS' : 'TRACKING' }}</span>
      </div>
    </header>

    <div class="relative flex-1 w-full bg-slate-900 overflow-hidden">
      <iframe id="fullMapFrame" title="Full screen delivery map" class="h-full w-full border-0" loading="lazy" src="https://www.openstreetmap.org/export/embed.html?bbox={{ $fullBbox }}&amp;layer=mapnik&amp;marker={{ $markerLat }}%2C{{ $markerLng }}"></iframe>

      <div class="absolute bottom-6 left-4 right-4 mx-auto max-w-lg rounded-3xl bg-slate-950/90 p-4 text-white backdrop-blur-xl border border-white/15 shadow-lift sm:left-6 sm:right-6">
        <div class="flex items-center gap-4">
          <div class="relative">
            <span class="grid h-12 w-12 place-items-center rounded-2xl bg-gradient-to-br from-brand-500 to-emerald-700 text-2xl shadow-lg">🛵</span>
            <span class="absolute -bottom-1 -right-1 grid h-5 w-5 place-items-center rounded-full bg-lime-400 text-[10px] font-black text-slate-950">✓</span>
          </div>
          <div class="min-w-0 flex-1">
            <div class="flex items-center justify-between">
              <h3 class="font-black text-white">{{ $driverName ? $driverName . ' is on the way' : 'Your order is on the way' }}</h3>
            </div>
            @if($etaMinutes !== null && !$delivered && !$cancelled)
            <p class="mt-0.5 text-xs text-white/70">Estimated arrival in <span class="fullEta font-bold text-white">{{ $etaMinutes }}</span> minutes</p>
            @endif
          </div>
        </div>
        <div class="mt-3 flex gap-2 border-t border-white/10 pt-3">
          @if($driverPhone)
          <a href="tel:{{ $driverPhone }}" class="flex-1 rounded-xl bg-brand-600 py-2.5 text-center text-xs font-extrabold text-white transition hover:bg-brand-500 active:scale-95">Call Partner</a>
          @endif
          <button id="minimizeFullMapBtn" class="flex-1 rounded-xl bg-white/10 py-2.5 text-center text-xs font-extrabold text-white transition hover:bg-white/20 active:scale-95">Minimize Map</button>
        </div>
      </div>
    </div>
  </div>

  <div id="toast" class="fixed bottom-5 left-1/2 z-[60] -translate-x-1/2 translate-y-20 rounded-full bg-slate-950 px-5 py-3 text-sm font-bold text-white opacity-0 shadow-lift transition">Saved successfully</div>

  <script>
    const qs = (s) => document.querySelector(s);
    const qsa = (s) => document.querySelectorAll(s);
    const backdrop = qs('#backdrop');
    const sheets = [qs('#contactSheet'), qs('#summarySheet')];
    const csrfToken = qs('meta[name="csrf-token"]').getAttribute('content');
    const trackingLocationUrl = @json($hasDriverCoords ? route('order.tracking.location', ['token' => $order->tracking_token]) : null);
    const contactUpdateUrl = @json(route('order.tracking.contact', ['token' => $order->tracking_token]));
    const ratingUrl = @json(route('order.tracking.rating', ['token' => $order->tracking_token]));
    const isDelivered = @json($delivered);
    const isCancelled = @json($cancelled);

    function openSheet(sheet) {
      sheets.forEach(s => s.classList.add('translate-y-full'));
      sheet.classList.remove('translate-y-full');
      backdrop.classList.remove('pointer-events-none','opacity-0');
      document.body.style.overflow = 'hidden';
    }
    function closeSheets() {
      sheets.forEach(s => s.classList.add('translate-y-full'));
      backdrop.classList.add('pointer-events-none','opacity-0');
      document.body.style.overflow = '';
    }
    function toast(message) {
      const el = qs('#toast'); el.textContent = message;
      el.classList.remove('translate-y-20','opacity-0');
      setTimeout(() => el.classList.add('translate-y-20','opacity-0'), 2200);
    }

    qs('#contactOpen').addEventListener('click', () => openSheet(qs('#contactSheet')));
    qs('#summaryOpen').addEventListener('click', () => openSheet(qs('#summarySheet')));
    backdrop.addEventListener('click', closeSheets);
    qsa('.closeSheet').forEach(btn => btn.addEventListener('click', closeSheets));

    // Map Expand / Collapse Handlers
    const mapPreviewCard = qs('#mapPreviewCard');
    const triggerFullMapBtn = qs('#triggerFullMapBtn');
    const collapseMapBtn = qs('#collapseMapBtn');
    const collapsedMapSection = qs('#collapsedMapSection');
    const expandedMapSection = qs('#expandedMapSection');
    const fullMapModal = qs('#fullMapModal');
    const closeFullMapBtn = qs('#closeFullMapBtn');
    const minimizeFullMapBtn = qs('#minimizeFullMapBtn');

    function toggleInPlaceMap(expand) {
      if (!collapsedMapSection || !expandedMapSection) return;
      if (expand) {
        collapsedMapSection.classList.add('hidden');
        expandedMapSection.classList.remove('hidden');
      } else {
        expandedMapSection.classList.add('hidden');
        collapsedMapSection.classList.remove('hidden');
      }
    }

    if (mapPreviewCard) mapPreviewCard.addEventListener('click', () => toggleInPlaceMap(true));
    if (triggerFullMapBtn) triggerFullMapBtn.addEventListener('click', (e) => { e.stopPropagation(); toggleInPlaceMap(true); });
    if (collapseMapBtn) collapseMapBtn.addEventListener('click', (e) => { e.stopPropagation(); toggleInPlaceMap(false); });

    function openFullMap() {
      if (!fullMapModal) return;
      fullMapModal.classList.remove('opacity-0', 'pointer-events-none');
      document.body.style.overflow = 'hidden';
    }
    function closeFullMap() {
      if (!fullMapModal) return;
      fullMapModal.classList.add('opacity-0', 'pointer-events-none');
      document.body.style.overflow = '';
    }
    if (closeFullMapBtn) closeFullMapBtn.addEventListener('click', closeFullMap);
    if (minimizeFullMapBtn) minimizeFullMapBtn.addEventListener('click', closeFullMap);

    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') {
        closeSheets();
        closeFullMap();
        toggleInPlaceMap(false);
      }
    });

    // Live driver location polling (updates ETA text only; the embedded
    // OSM iframes are cheap enough to leave centered on the initial fix
    // rather than reloading them every poll).
    if (trackingLocationUrl && !isDelivered && !isCancelled) {
      const updateEta = (minutes) => {
        if (minutes === null || minutes === undefined) return;
        const mapEta = qs('#etaMap');
        if (mapEta) mapEta.textContent = minutes;
        qsa('.fullEta').forEach(el => el.textContent = minutes);
      };

      setInterval(() => {
        fetch(trackingLocationUrl)
          .then(res => res.ok ? res.json() : Promise.reject())
          .then(payload => {
            if (payload && payload.response && payload.data) {
              updateEta(payload.data.eta_minutes);
            }
          })
          .catch(() => {});
      }, 20000);
    }

    // Contact update
    const contactForm = qs('#contactForm');
    if (contactForm) {
      contactForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const receiverName = qs('#receiver').value.trim();
        const receiverPhone = qs('#mobile').value.trim();

        fetch(contactUpdateUrl, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
          body: JSON.stringify({ _token: csrfToken, receiver_name: receiverName, receiver_phone: receiverPhone })
        })
          .then(res => res.json())
          .then(payload => {
            if (payload.response) {
              closeSheets();
              toast("Receiver's contact updated");
              const masked = receiverPhone.length > 5
                ? receiverPhone.slice(0, 5) + 'X'.repeat(receiverPhone.length - 5)
                : receiverPhone;
              const label = qs('#receiverLabel');
              if (label) label.textContent = receiverName + ', ' + masked;
            } else {
              toast('Could not update contact');
            }
          })
          .catch(() => toast('Could not update contact'));
      });
    }

    // Rating
    qsa('.rating').forEach(btn => btn.addEventListener('click', () => {
      if (btn.disabled) return;
      const rating = btn.dataset.rating;

      fetch(ratingUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ _token: csrfToken, rating: rating })
      })
        .then(res => res.json())
        .then(payload => {
          if (payload.response) {
            qsa('.rating').forEach(b => { b.disabled = true; b.classList.remove('scale-125'); });
            btn.classList.add('scale-125');
            qs('#ratingMessage').classList.remove('hidden');
          }
        })
        .catch(() => {});
    }));

    // Slider Script
    (function initSlider() {
      const sliderTrack = qs('#sliderTrack');
      const sliderPrev = qs('#sliderPrev');
      const sliderNext = qs('#sliderNext');
      const sliderDotsContainer = qs('#sliderDots');
      const bannerSlider = qs('#bannerSlider');

      if (!sliderTrack || !bannerSlider) return;

      const slides = Array.from(sliderTrack.children);
      let currentIndex = 0;
      let autoplayTimer = null;

      slides.forEach((_, idx) => {
        const dot = document.createElement('button');
        dot.className = `h-2 rounded-full transition-all duration-300 ${idx === 0 ? 'w-6 bg-white' : 'w-2 bg-white/50 hover:bg-white/80'}`;
        dot.setAttribute('aria-label', `Go to slide ${idx + 1}`);
        dot.addEventListener('click', () => {
          goToSlide(idx);
          resetAutoplay();
        });
        sliderDotsContainer.appendChild(dot);
      });

      function updateDots() {
        Array.from(sliderDotsContainer.children).forEach((dot, idx) => {
          if (idx === currentIndex) {
            dot.className = 'h-2 w-6 rounded-full bg-white transition-all duration-300';
          } else {
            dot.className = 'h-2 w-2 rounded-full bg-white/50 hover:bg-white/80 transition-all duration-300';
          }
        });
      }

      function goToSlide(index) {
        if (index < 0) {
          currentIndex = slides.length - 1;
        } else if (index >= slides.length) {
          currentIndex = 0;
        } else {
          currentIndex = index;
        }
        sliderTrack.style.transform = `translateX(-${currentIndex * 100}%)`;
        updateDots();
      }

      function nextSlide() { goToSlide(currentIndex + 1); }
      function prevSlide() { goToSlide(currentIndex - 1); }

      if (sliderNext) sliderNext.addEventListener('click', () => { nextSlide(); resetAutoplay(); });
      if (sliderPrev) sliderPrev.addEventListener('click', () => { prevSlide(); resetAutoplay(); });

      function startAutoplay() {
        stopAutoplay();
        autoplayTimer = setInterval(nextSlide, 4500);
      }
      function stopAutoplay() {
        if (autoplayTimer) clearInterval(autoplayTimer);
      }
      function resetAutoplay() {
        stopAutoplay();
        startAutoplay();
      }

      bannerSlider.addEventListener('mouseenter', stopAutoplay);
      bannerSlider.addEventListener('mouseleave', startAutoplay);

      let touchStartX = 0;
      bannerSlider.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
        stopAutoplay();
      }, { passive: true });

      bannerSlider.addEventListener('touchend', (e) => {
        const touchEndX = e.changedTouches[0].screenX;
        const diffX = touchStartX - touchEndX;
        if (Math.abs(diffX) > 40) {
          if (diffX > 0) nextSlide();
          else prevSlide();
        }
        startAutoplay();
      }, { passive: true });

      startAutoplay();
    })();
  </script>
</body>
</html>
