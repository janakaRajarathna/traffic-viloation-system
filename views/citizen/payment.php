<?php $bodyClass = 'bg-background font-body text-on-surface antialiased min-h-screen'; ?>
<style>
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
.glass-panel {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
}
body {
    min-height: max(884px, 100dvh);
}
/* Flip card classes */
.flip-card-inner {
    transition: transform 0.6s;
    transform-style: preserve-3d;
}
.flip-card-flipped {
    transform: rotateY(180deg);
}

/* Print Styling */
@media print {
    body * {
        visibility: hidden;
    }
    #printable-receipt, #printable-receipt * {
        visibility: visible;
    }
    #printable-receipt {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        border: none;
        box-shadow: none;
    }
}
</style>

<?php require APP_ROOT . 'views/partials/citizen_sidebar.php'; ?>

<header class="fixed top-0 w-full md:w-[calc(100%-16rem)] md:right-0 z-40 bg-surface/80 backdrop-blur-xl flex justify-between items-center px-6 h-16 border-b border-surface-container-highest transition-all">
    <div class="flex items-center gap-3">
        <button id="open-sidebar-btn" type="button" class="md:hidden w-10 h-10 flex items-center justify-center rounded-full bg-surface-container hover:bg-surface-container-high transition-colors text-on-surface">
            <span class="material-symbols-outlined" data-icon="menu">menu</span>
        </button>
        <span class="text-xl font-extrabold tracking-tight text-primary font-headline md:hidden">Dash Cam</span>
        <span class="text-xl font-extrabold tracking-tight text-on-surface font-headline hidden md:block">Settle Fine Payment</span>
    </div>
    <div class="flex items-center gap-4">
        <a href="<?= url('app_logout') ?>" class="w-10 h-10 flex items-center justify-center text-slate-500 hover:bg-error-container transition-colors rounded-full active:scale-95 duration-150">
            <span class="material-symbols-outlined" data-icon="logout">logout</span>
        </a>
    </div>
</header>

<main class="md:ml-64 pt-24 pb-32 px-4 md:px-8 max-w-7xl mx-auto flex-1 transition-all">
    <?php require APP_ROOT . 'views/partials/flashes.php'; ?>

    <!-- Main Payment Form Content -->
    <div id="payment-portal-content" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Summary Column (span 5) -->
        <div class="lg:col-span-5 space-y-6">
            <div class="glass-panel p-6 rounded-2xl border border-outline-variant/10 shadow-[0_12px_32px_rgba(0,97,164,0.04)] space-y-6">
                <div class="flex items-center gap-3 border-b border-surface-container-highest pb-4">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">receipt_long</span>
                    </div>
                    <div>
                        <h2 class="font-headline font-bold text-lg text-on-surface">Payment Summary</h2>
                        <p class="text-xs text-on-surface-variant font-medium">Verify outstanding fine details</p>
                    </div>
                </div>

                <!-- List of Violations to Pay -->
                <div class="space-y-4">
                    <?php foreach ($violations as $v): ?>
                    <div class="flex justify-between items-start gap-4 p-4 bg-surface-container-low rounded-xl border border-surface-container-high">
                        <div class="min-w-0">
                            <span class="text-xs font-bold text-primary uppercase tracking-wider block mb-0.5">Violation #<?= e((string)$v->id) ?></span>
                            <h3 class="font-headline font-bold text-sm text-on-surface truncate"><?= e($v->violationType) ?></h3>
                            <p class="text-xs text-on-surface-variant truncate mt-1 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[12px]">location_on</span>
                                <?= e($v->location) ?>
                            </p>
                            <p class="text-[10px] text-on-surface-variant/80 mt-0.5">
                                <?= $v->incidentDate ? e($v->incidentDate->format('M d, Y h:i A')) : '—' ?>
                            </p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span class="font-mono font-bold text-sm text-on-surface">$<?= e(number_format((float)$v->fineAmount, 2)) ?></span>
                            <span class="block mt-1 text-[9px] px-2 py-0.5 bg-error-container text-on-error-container font-extrabold rounded-full uppercase">UNPAID</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Totals Section -->
                <div class="border-t border-surface-container-highest pt-4 space-y-2">
                    <div class="flex justify-between text-sm text-on-surface-variant">
                        <span>Subtotal Fines</span>
                        <span class="font-mono">$<?= e(number_format((float)$totalAmount, 2)) ?></span>
                    </div>
                    <div class="flex justify-between text-sm text-on-surface-variant">
                        <span>Gateway Processing Fee</span>
                        <span class="text-emerald-600 font-medium">FREE</span>
                    </div>
                    <div class="flex justify-between items-center border-t border-surface-container-highest pt-4">
                        <span class="font-headline font-bold text-lg text-on-surface">Total Amount</span>
                        <span class="font-headline font-extrabold text-2xl text-primary">$<?= e(number_format((float)$totalAmount, 2)) ?></span>
                    </div>
                </div>
            </div>

            <!-- Security Trust Badges -->
            <div class="flex justify-center items-center gap-6 text-on-surface-variant/60 text-xs py-2">
                <span class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm text-emerald-600">verified</span>
                    Secure SSL
                </span>
                <span class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm text-emerald-600">lock</span>
                    PCI Compliant
                </span>
                <span class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm text-emerald-600">gpp_good</span>
                    3D Secure Auth
                </span>
            </div>
        </div>

        <!-- Right: Payment Card Form Column (span 7) -->
        <div class="lg:col-span-7 glass-panel p-8 rounded-2xl border border-outline-variant/10 shadow-[0_12px_32px_rgba(0,97,164,0.04)] space-y-6">
            
            <!-- Virtual Card Visualization -->
            <div class="w-full max-w-[340px] h-[200px] [perspective:1000px] mx-auto mb-8">
                <div id="credit-card" class="flip-card-inner relative w-full h-full rounded-2xl shadow-2xl">
                    <!-- Front of card -->
                    <div class="absolute inset-0 w-full h-full [backface-visibility:hidden] bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 text-white p-6 rounded-2xl flex flex-col justify-between overflow-hidden">
                        <!-- Card chip and type -->
                        <div class="flex justify-between items-start z-10">
                            <div class="w-12 h-10 bg-gradient-to-br from-yellow-300 to-amber-500 rounded-lg flex items-center justify-center shadow-inner overflow-hidden relative">
                                <div class="absolute inset-0 opacity-20 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-white to-transparent"></div>
                                <span class="material-symbols-outlined text-slate-900 text-2xl font-bold opacity-60">dns</span>
                            </div>
                            <span id="card-type-display" class="font-headline font-extrabold italic text-xl tracking-wider text-white opacity-90">VISA</span>
                        </div>
                        <div class="space-y-4 z-10">
                            <!-- Card Number -->
                            <div id="card-num-disp" class="font-mono text-xl tracking-widest text-center text-white drop-shadow">•••• •••• •••• ••••</div>
                            <div class="flex justify-between items-end">
                                <div>
                                    <div class="text-[9px] uppercase tracking-wider text-slate-400">Card Holder</div>
                                    <div id="card-name-disp" class="font-mono text-sm tracking-wide truncate max-w-[190px] text-white">YOUR NAME</div>
                                </div>
                                <div>
                                    <div class="text-[9px] uppercase tracking-wider text-slate-400">Expires</div>
                                    <div id="card-expiry-disp" class="font-mono text-sm text-white">MM/YY</div>
                                </div>
                            </div>
                        </div>
                        <!-- Background graphics -->
                        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-blue-500/10 rounded-full blur-2xl"></div>
                        <div class="absolute -left-10 -top-10 w-40 h-40 bg-emerald-500/10 rounded-full blur-2xl"></div>
                    </div>
                    <!-- Back of card -->
                    <div class="absolute inset-0 w-full h-full [backface-visibility:hidden] [transform:rotateY(180deg)] bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 text-white rounded-2xl flex flex-col justify-between py-5 overflow-hidden">
                        <div class="w-full h-11 bg-black/90 mt-2"></div>
                        <div class="px-6 flex justify-end items-center gap-3">
                            <div class="text-right">
                                <span class="text-[8px] uppercase tracking-wider text-slate-400 block mb-1">Security Code (CVV)</span>
                                <div class="flex items-center gap-2">
                                    <div class="w-36 h-9 bg-white text-slate-800 rounded flex items-center justify-end px-3 font-mono italic tracking-widest text-sm shadow-inner">
                                        ••••
                                    </div>
                                    <div id="card-cvv-disp" class="w-12 h-9 bg-amber-400 text-slate-900 font-bold rounded flex items-center justify-center font-mono text-sm shadow">
                                        •••
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="px-6 text-[8px] text-slate-400 text-center opacity-70">
                            This card is simulated for checkout demonstration purposes. No real funds will be charged.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form id="payment-form" class="space-y-5">
                <!-- Hidden inputs for mapping -->
                <input type="hidden" name="type" value="<?= e($paymentType) ?>">
                <?php if ($paymentType === 'single'): ?>
                <input type="hidden" name="violation_id" value="<?= e((string)$violations[0]->id) ?>">
                <?php endif; ?>

                <div>
                    <label for="card_name" class="text-xs font-bold uppercase tracking-wider text-on-surface-variant block mb-2">Cardholder Name</label>
                    <div class="relative">
                        <input type="text" id="card_name" name="card_name" required 
                               class="w-full pl-11 pr-4 py-3 rounded-xl border border-outline-variant/60 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-surface-container-lowest transition-all"
                               placeholder="e.g. John Doe">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/60">person</span>
                    </div>
                </div>

                <div>
                    <label for="card_number" class="text-xs font-bold uppercase tracking-wider text-on-surface-variant block mb-2">Card Number</label>
                    <div class="relative">
                        <input type="text" id="card_number" name="card_number" required maxlength="19"
                               class="w-full pl-11 pr-4 py-3 rounded-xl border border-outline-variant/60 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-surface-container-lowest transition-all font-mono tracking-widest"
                               placeholder="•••• •••• •••• ••••">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/60">credit_card</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="expiry_date" class="text-xs font-bold uppercase tracking-wider text-on-surface-variant block mb-2">Expiry Date</label>
                        <div class="relative">
                            <input type="text" id="expiry_date" name="expiry_date" required maxlength="5"
                                   class="w-full pl-11 pr-4 py-3 rounded-xl border border-outline-variant/60 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-surface-container-lowest transition-all font-mono"
                                   placeholder="MM/YY">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/60">calendar_month</span>
                        </div>
                    </div>

                    <div>
                        <label for="cvv" class="text-xs font-bold uppercase tracking-wider text-on-surface-variant block mb-2">CVV</label>
                        <div class="relative">
                            <input type="password" id="cvv" name="cvv" required maxlength="4"
                                   class="w-full pl-11 pr-4 py-3 rounded-xl border border-outline-variant/60 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 bg-surface-container-lowest transition-all font-mono"
                                   placeholder="•••">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/60">lock_open</span>
                        </div>
                    </div>
                </div>

                <!-- Error Message banner -->
                <div id="form-error-banner" class="hidden p-4 bg-error-container text-on-error-container text-xs font-semibold rounded-xl flex items-center gap-2 border border-error/10">
                    <span class="material-symbols-outlined text-sm">error</span>
                    <span id="form-error-text">An error occurred. Please check inputs.</span>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-4 mt-2 bg-gradient-to-r from-primary to-primary-container text-white font-headline font-bold rounded-xl shadow-[0_12px_24px_rgba(0,97,164,0.2)] hover:shadow-[0_16px_32px_rgba(0,97,164,0.3)] active:scale-95 hover:scale-[1.01] transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">payments</span>
                    Authorize Payment ($<?= e(number_format((float)$totalAmount, 2)) ?>)
                </button>
            </form>
        </div>
    </div>

    <!-- Receipt Container (Initially Hidden, Shown on Success) -->
    <div id="receipt-container" class="hidden max-w-2xl mx-auto space-y-6 py-4">
        <!-- Success Banner -->
        <div class="text-center space-y-3">
            <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto shadow-sm animate-bounce">
                <span class="material-symbols-outlined text-4xl">check_circle</span>
            </div>
            <h2 class="font-headline font-extrabold text-3xl text-on-surface">Payment Successful!</h2>
            <p class="text-on-surface-variant font-medium">Your transaction has been processed and a notification has been sent.</p>
        </div>
        
        <!-- Receipt Card -->
        <div id="printable-receipt" class="bg-surface-container-lowest p-8 rounded-2xl shadow-lg border border-surface-container-high space-y-6 relative overflow-hidden">
            <!-- Decorative Border -->
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-emerald-500 to-teal-600"></div>
            
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-headline font-bold text-on-surface">RECEIPT</h1>
                    <p class="text-xs text-on-surface-variant uppercase tracking-widest mt-1">Dash Cam Traffic System</p>
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full">PAID</span>
                </div>
            </div>

            <hr class="border-surface-container-high" />

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-xs text-on-surface-variant font-semibold uppercase">Receipt Reference</p>
                    <p id="receipt-ref" class="font-mono font-bold mt-1 text-on-surface">REC-00000000</p>
                </div>
                <div>
                    <p class="text-xs text-on-surface-variant font-semibold uppercase">Transaction Date</p>
                    <p id="receipt-date" class="font-bold mt-1 text-on-surface">--</p>
                </div>
                <div>
                    <p class="text-xs text-on-surface-variant font-semibold uppercase">Citizen Name</p>
                    <p class="font-bold mt-1 text-on-surface"><?= e($user->fullName) ?></p>
                </div>
                <div>
                    <p class="text-xs text-on-surface-variant font-semibold uppercase">Payment Method</p>
                    <p id="receipt-method" class="font-bold mt-1 text-on-surface">Card ending in ****</p>
                </div>
            </div>

            <hr class="border-surface-container-high" />

            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-3">Settled Violations</h3>
                <div class="space-y-4">
                    <?php foreach ($violations as $v): ?>
                    <div class="flex justify-between items-center text-sm">
                        <div>
                            <p class="font-semibold text-on-surface"><?= e($v->violationType) ?></p>
                            <p class="text-xs text-on-surface-variant">Violation ID: #<?= e((string)$v->id) ?> | Location: <?= e($v->location) ?></p>
                        </div>
                        <span class="font-mono font-bold text-on-surface">$<?= e(number_format((float)$v->fineAmount, 2)) ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <hr class="border-surface-container-high" />

            <div class="flex justify-between items-center">
                <span class="font-headline font-bold text-lg text-on-surface">Total Paid</span>
                <span id="receipt-total" class="font-headline font-extrabold text-2xl text-primary">$<?= e(number_format((float)$totalAmount, 2)) ?></span>
            </div>

            <div class="text-center text-[10px] text-on-surface-variant/60 pt-4 border-t border-dashed border-surface-container-high">
                This is a computer-generated receipt. No signature is required. For any inquiries, contact Dash Cam Support.
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4">
            <button id="print-receipt-btn" class="flex-1 py-3 border border-outline text-on-surface hover:bg-surface-container font-headline font-bold rounded-xl flex items-center justify-center gap-2 active:scale-95 transition-transform" type="button">
                <span class="material-symbols-outlined">print</span>
                Print / Save PDF
            </button>
            <a href="<?= url('app_citizen') ?>" class="flex-1 py-3 bg-primary text-white hover:bg-primary-container font-headline font-bold rounded-xl text-center flex items-center justify-center gap-2 active:scale-95 transition-transform">
                <span class="material-symbols-outlined">dashboard</span>
                Return to Dashboard
            </a>
        </div>
    </div>
</main>

<!-- Overlay loader simulated -->
<div id="loading-overlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-surface-container-lowest p-8 rounded-2xl shadow-2xl max-w-sm w-full mx-4 text-center space-y-6 border border-surface-container-high">
        <div class="relative w-20 h-20 mx-auto">
            <div class="absolute inset-0 border-4 border-primary/20 rounded-full"></div>
            <div class="absolute inset-0 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
        </div>
        <div class="space-y-2">
            <h3 class="font-headline font-bold text-xl text-on-surface">Processing Payment</h3>
            <p id="loading-status" class="text-on-surface-variant text-sm font-medium">Verifying card details...</p>
        </div>
        <div class="w-full bg-surface-container-high rounded-full h-1.5 overflow-hidden">
            <div id="loading-progress" class="bg-primary h-1.5 transition-all duration-300 w-0"></div>
        </div>
    </div>
</div>

<!-- OTP authentication overlay simulated -->
<div id="otp-overlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-surface-container-lowest p-8 rounded-2xl shadow-2xl max-w-sm w-full mx-4 space-y-6 border border-surface-container-high">
        <div class="text-center space-y-2">
            <div class="w-12 h-12 bg-primary/10 text-primary rounded-full flex items-center justify-center mx-auto mb-2">
                <span class="material-symbols-outlined text-2xl">security</span>
            </div>
            <h3 class="font-headline font-bold text-xl text-on-surface">Security Verification</h3>
            <p class="text-on-surface-variant text-sm font-medium">We have sent a verification code to your registered mobile number ending in ****.</p>
        </div>
        <div class="p-4 bg-primary/5 rounded-xl border border-primary/10 text-center">
            <span class="text-xs text-primary font-bold uppercase tracking-widest block mb-1">Your Mock Code</span>
            <span class="font-mono text-2xl font-bold tracking-widest text-primary">482 915</span>
        </div>
        <div class="space-y-4">
            <div>
                <label for="otp-input" class="text-xs font-bold uppercase tracking-wider text-on-surface-variant block mb-2">Enter Verification Code</label>
                <input type="text" id="otp-input" class="w-full px-4 py-3 rounded-xl border border-outline-variant/60 focus:outline-none focus:border-primary text-center font-mono text-xl tracking-widest bg-surface-container-lowest" placeholder="••••••" maxlength="6">
                <p id="otp-error" class="text-error text-xs font-semibold mt-2 hidden flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">warning</span>
                    Incorrect code. Please try again.
                </p>
            </div>
            <button id="otp-submit" class="w-full py-4 bg-primary text-white font-headline font-bold rounded-xl shadow-lg hover:bg-primary-container active:scale-95 transition-transform" type="button">
                Verify &amp; Settle Payment
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Sidebar Controls
    const sidebar = document.getElementById("citizen-sidebar");
    const overlay = document.getElementById("sidebar-overlay");
    const openBtn = document.getElementById("open-sidebar-btn");
    const closeBtn = document.getElementById("close-sidebar-btn");

    function openSidebar() {
        sidebar.classList.remove("-translate-x-full");
        overlay.classList.remove("hidden");
    }

    function closeSidebar() {
        sidebar.classList.add("-translate-x-full");
        overlay.classList.add("hidden");
    }

    if (openBtn) openBtn.addEventListener("click", openSidebar);
    if (closeBtn) closeBtn.addEventListener("click", closeSidebar);
    if (overlay) overlay.addEventListener("click", closeSidebar);

    // Card inputs and displays
    const inputCardName = document.getElementById("card_name");
    const inputCardNumber = document.getElementById("card_number");
    const inputExpiryDate = document.getElementById("expiry_date");
    const inputCVV = document.getElementById("cvv");

    const dispCardName = document.getElementById("card-name-disp");
    const dispCardNumber = document.getElementById("card-num-disp");
    const dispExpiryDate = document.getElementById("card-expiry-disp");
    const dispCVV = document.getElementById("card-cvv-disp");
    const dispCardType = document.getElementById("card-type-display");

    const creditCardContainer = document.getElementById("credit-card");

    // Helper: detect card type
    function detectCardType(number) {
        const cleaned = number.replace(/\D/g, '');
        if (cleaned.startsWith('4')) return 'VISA';
        if (cleaned.startsWith('51') || cleaned.startsWith('52') || cleaned.startsWith('53') || cleaned.startsWith('54') || cleaned.startsWith('55')) return 'MASTERCARD';
        if (cleaned.startsWith('34') || cleaned.startsWith('37')) return 'AMEX';
        if (cleaned.startsWith('6011') || cleaned.startsWith('65')) return 'DISCOVER';
        return 'CREDIT';
    }

    // Card flips on CVV focus
    inputCVV.addEventListener("focus", function() {
        creditCardContainer.classList.add("flip-card-flipped");
    });
    inputCVV.addEventListener("blur", function() {
        creditCardContainer.classList.remove("flip-card-flipped");
    });

    // Inputs real-time syncing
    inputCardName.addEventListener("input", function() {
        dispCardName.textContent = this.value.toUpperCase() || "YOUR NAME";
    });

    inputCardNumber.addEventListener("input", function() {
        // Strip out non-digits and space format
        let cleaned = this.value.replace(/\D/g, '');
        
        // Apply card type detection
        const cardType = detectCardType(cleaned);
        dispCardType.textContent = cardType;

        // Apply formatting (group of 4 digits)
        let formatted = '';
        for (let i = 0; i < cleaned.length; i++) {
            if (i > 0 && i % 4 === 0) {
                formatted += ' ';
            }
            formatted += cleaned[i];
        }
        this.value = formatted;
        
        // Sync display
        dispCardNumber.textContent = formatted || "•••• •••• •••• ••••";
    });

    inputExpiryDate.addEventListener("input", function() {
        let cleaned = this.value.replace(/\D/g, '');
        
        if (cleaned.length > 2) {
            this.value = cleaned.substring(0, 2) + '/' + cleaned.substring(2, 4);
        } else {
            this.value = cleaned;
        }

        dispExpiryDate.textContent = this.value || "MM/YY";
    });

    inputCVV.addEventListener("input", function() {
        this.value = this.value.replace(/\D/g, '').substring(0, 4);
        dispCVV.textContent = '•'.repeat(this.value.length) || "•••";
    });

    // Form Submission & Processing Simulation
    const form = document.getElementById("payment-form");
    const errorBanner = document.getElementById("form-error-banner");
    const errorText = document.getElementById("form-error-text");

    const loadingOverlay = document.getElementById("loading-overlay");
    const loadingStatus = document.getElementById("loading-status");
    const loadingProgress = document.getElementById("loading-progress");

    const otpOverlay = document.getElementById("otp-overlay");
    const otpInput = document.getElementById("otp-input");
    const otpError = document.getElementById("otp-error");
    const otpSubmit = document.getElementById("otp-submit");

    const paymentPortalContent = document.getElementById("payment-portal-content");
    const receiptContainer = document.getElementById("receipt-container");

    const receiptRef = document.getElementById("receipt-ref");
    const receiptDate = document.getElementById("receipt-date");
    const receiptMethod = document.getElementById("receipt-method");
    const printBtn = document.getElementById("print-receipt-btn");

    form.addEventListener("submit", function(e) {
        e.preventDefault();
        errorBanner.classList.add("hidden");

        // Front-end check
        const cardNumVal = inputCardNumber.value.replace(/\s+/g, '');
        const expiryVal = inputExpiryDate.value;
        const cvvVal = inputCVV.value;

        if (cardNumVal.length < 13 || cardNumVal.length > 19) {
            showError("Invalid card number length.");
            return;
        }
        if (!/^\d{2}\/\d{2}$/.test(expiryVal)) {
            showError("Invalid expiry date format. Use MM/YY.");
            return;
        }
        if (cvvVal.length < 3 || cvvVal.length > 4) {
            showError("CVV must be 3 or 4 digits.");
            return;
        }

        // Show loading progress bar
        showLoader();
    });

    function showError(message) {
        errorText.textContent = message;
        errorBanner.classList.remove("hidden");
        // Scroll to the error
        errorBanner.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function showLoader() {
        loadingOverlay.classList.remove("hidden");
        
        let progress = 0;
        const steps = [
            { limit: 30, text: "Verifying card details..." },
            { limit: 65, text: "Contacting the issuing bank..." },
            { limit: 100, text: "Securing connection..." }
        ];
        
        const interval = setInterval(() => {
            progress += 5;
            loadingProgress.style.width = progress + "%";
            
            // Update status text
            const step = steps.find(s => progress <= s.limit);
            if (step) {
                loadingStatus.textContent = step.text;
            }
            
            if (progress >= 100) {
                clearInterval(interval);
                setTimeout(() => {
                    loadingOverlay.classList.add("hidden");
                    // Reset progress bar
                    loadingProgress.style.width = "0%";
                    // Open OTP security verification
                    showOTP();
                }, 300);
            }
        }, 150);
    }

    function showOTP() {
        otpOverlay.classList.remove("hidden");
        otpInput.value = "";
        otpError.classList.add("hidden");
        otpInput.focus();
    }

    // OTP verification
    otpSubmit.addEventListener("click", function() {
        const otpVal = otpInput.value.replace(/\s+/g, '');
        
        if (otpVal === "482915") {
            // Correct mock OTP, submit data to backend!
            otpOverlay.classList.add("hidden");
            submitPayment();
        } else {
            otpError.classList.remove("hidden");
            otpInput.classList.add("border-error");
            otpInput.focus();
        }
    });

    otpInput.addEventListener("input", function() {
        this.classList.remove("border-error");
        otpError.classList.add("hidden");
    });

    function submitPayment() {
        // Show loader again briefly representing database finalization
        loadingOverlay.classList.remove("hidden");
        loadingStatus.textContent = "Finalizing transaction...";
        loadingProgress.style.width = "100%";

        const formData = new FormData(form);

        fetch("<?= url('app_payment_process') ?>", {
            method: "POST",
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw new Error(err.error || "Payment failed"); });
            }
            return response.json();
        })
        .then(data => {
            loadingOverlay.classList.add("hidden");
            if (data.success) {
                // Populate receipt details
                receiptRef.textContent = data.receipt_id;
                
                // Format transaction date nicely
                const d = new Date(data.date);
                receiptDate.textContent = d.toLocaleString();

                const last4 = inputCardNumber.value.replace(/\s+/g, '').slice(-4);
                const cardBrand = dispCardType.textContent;
                receiptMethod.textContent = cardBrand + " ending in •••• " + last4;

                // Hide Form layout, show receipt
                paymentPortalContent.classList.add("hidden");
                receiptContainer.classList.remove("hidden");
                
                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                showError("Payment processed but server returned status failure.");
            }
        })
        .catch(err => {
            loadingOverlay.classList.add("hidden");
            showError(err.message);
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // Print Receipt
    printBtn.addEventListener("click", function() {
        window.print();
    });
});
</script>
