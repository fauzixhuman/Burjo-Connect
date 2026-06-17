<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .stepper-item {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
        }
        .stepper-item::before {
            position: absolute;
            content: "";
            border-bottom: 2px solid #ccc;
            width: 100%;
            top: 15px;
            left: -50%;
            z-index: 2;
        }
        .stepper-item::after {
            position: absolute;
            content: "";
            border-bottom: 2px solid #ccc;
            width: 100%;
            top: 15px;
            left: 50%;
            z-index: 2;
        }
        .stepper-item .step-counter {
            position: relative;
            z-index: 5;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #ccc;
            margin-bottom: 6px;
        }
        .stepper-item.active .step-counter {
            background-color: #f59e0b; /* yellow-500 */
            color: white;
        }
        .stepper-item.completed .step-counter {
            background-color: #10b981; /* emerald-500 */
            color: white;
        }
        .stepper-item.completed::after,
        .stepper-item.completed::before {
            border-color: #10b981;
        }
        .stepper-item:first-child::before {
            content: none;
        }
        .stepper-item:last-child::after {
            content: none;
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="max-w-md mx-auto bg-white min-h-screen relative shadow-lg">
        <!-- Header -->
        <div class="bg-yellow-500 text-white px-6 py-4 flex items-center justify-between">
            <h1 class="text-xl font-bold">Status Pesanan</h1>
            <span class="text-sm">Meja <?= esc($order['table_number']) ?></span>
        </div>

        <div class="p-6">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800">Order #<?= str_pad($order['id'], 4, '0', STR_PAD_LEFT) ?></h2>
                <p class="text-gray-500 mt-1">Total Tagihan: <span class="font-bold text-gray-800">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></span></p>
            </div>

            <!-- Stepper -->
            <div class="w-full flex justify-between items-center mb-10">
                <div class="stepper-item" id="step-pending">
                    <div class="step-counter text-sm">1</div>
                    <div class="step-name text-xs font-medium text-gray-500">Pending</div>
                </div>
                <div class="stepper-item" id="step-cooking">
                    <div class="step-counter text-sm">2</div>
                    <div class="step-name text-xs font-medium text-gray-500">Cooking</div>
                </div>
                <div class="stepper-item" id="step-ready">
                    <div class="step-counter text-sm">3</div>
                    <div class="step-name text-xs font-medium text-gray-500">Ready</div>
                </div>
            </div>

            <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                <h3 class="font-bold text-yellow-800 mb-2">Informasi Status</h3>
                <p id="status-message" class="text-sm text-yellow-700">
                    Memuat status terbaru...
                </p>
            </div>

            <div class="mt-8 text-center">
                <a href="<?= base_url('/?table=' . esc($order['table_number'])) ?>" class="text-yellow-600 hover:text-yellow-700 text-sm font-medium">
                    &larr; Kembali ke Menu
                </a>
            </div>
        </div>
    </div>

    <script>
        const orderId = <?= $order['id'] ?>;
        const initialStatus = "<?= esc($order['status']) ?>";

        function updateStepper(status) {
            const stepPending = document.getElementById('step-pending');
            const stepCooking = document.getElementById('step-cooking');
            const stepReady   = document.getElementById('step-ready');
            const statusMessage = document.getElementById('status-message');

            // Reset
            stepPending.className = 'stepper-item';
            stepCooking.className = 'stepper-item';
            stepReady.className   = 'stepper-item';

            if (status === 'pending') {
                stepPending.classList.add('active');
                statusMessage.innerHTML = 'Pesanan Anda sedang menunggu konfirmasi dari dapur.';
            } else if (status === 'cooking') {
                stepPending.classList.add('completed');
                stepCooking.classList.add('active');
                statusMessage.innerHTML = 'Pesanan Anda sedang dimasak. Mohon tunggu sebentar.';
            } else if (status === 'ready' || status === 'completed') {
                stepPending.classList.add('completed');
                stepCooking.classList.add('completed');
                stepReady.classList.add('completed');
                statusMessage.innerHTML = 'Pesanan sudah siap! Silakan dinikmati.';
            }
        }

        // Initialize with initial status
        updateStepper(initialStatus);

        // Polling every 5 seconds
        setInterval(() => {
            fetch('/api/track/' + orderId)
                .then(res => res.json())
                .then(data => {
                    if (data && data.status) {
                        updateStepper(data.status);
                    }
                })
                .catch(err => console.error('Error fetching status:', err));
        }, 5000);
    </script>
</body>
</html>
