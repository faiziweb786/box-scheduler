<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Box Scheduler</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            color: white;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .info-panel {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        .stats {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-item {
            text-align: center;
            padding: 15px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border-radius: 10px;
            min-width: 150px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-item h3 {
            font-size: 2rem;
            margin-bottom: 5px;
        }

        .stat-item p {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .controls {
            text-align: center;
            margin-bottom: 20px;
        }

        .btn {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1rem;
            margin: 0 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .btn.reset {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
        }

        .boxes-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        .boxes-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            align-items: flex-start;
        }

        .box {
            width: 100px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 12px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            animation: fadeIn 0.5s ease-in;
        }

        .box:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        /* Color classes for boxes */
        .box.red {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
        }

        .box.yellow {
            background: linear-gradient(135deg, #feca57, #ff9ff3);
        }

        .box.green {
            background: linear-gradient(135deg, #48dbfb, #0abde3);
        }

        .box.blue {
            background: linear-gradient(135deg, #74b9ff, #0984e3);
        }

        .box.pink {
            background: linear-gradient(135deg, #fd79a8, #e84393);
        }

        .box.grey {
            background: linear-gradient(135deg, #636e72, #2d3436);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .timer {
            text-align: center;
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 15px;
        }

        .status {
            text-align: center;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .status.running {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status.stopped {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }

            .stats {
                flex-direction: column;
                align-items: center;
            }

            .boxes-container {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🎯 Box Scheduler System</h1>
            <p>Watch boxes double every minute until we reach 16!</p>
        </div>

        <div class="info-panel">
            <div class="stats">
                <div class="stat-item">
                    <h3 id="box-count">{{ $boxes->count() }}</h3>
                    <p>Total Boxes</p>
                </div>
                <div class="stat-item">
                    <h3 id="next-update">60</h3>
                    <p>Next Update (sec)</p>
                </div>
                <div class="stat-item">
                    <h3>40×100</h3>
                    <p>Box Size (px)</p>
                </div>
                <div class="stat-item">
                    <h3>6</h3>
                    <p>Available Colors</p>
                </div>
            </div>

            <div id="status" class="status running">
                🟢 Scheduler Running - Boxes will double every minute
            </div>

            <div class="timer">
                ⏰ Time until next update: <span id="countdown">60</span> seconds
            </div>

            <!-- Color Array Display -->
            <div class="color-array-section"
                style="text-align: center; margin-bottom: 20px; padding: 15px; background: rgba(255,255,255,0.1); border-radius: 10px;">
                <h3 style="color: #333; margin-bottom: 10px; font-size: 1.2rem;">🎨 Color Array:</h3>
                <div id="colorArray"
                    style="font-size: 1.1rem; font-weight: bold; color: #333; margin-bottom: 15px; font-family: monospace;">
                    [red, yellow, green, blue, pink, grey]
                </div>
                <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
                    <button class="btn" onclick="shuffleColors()"
                        style="background: linear-gradient(135deg, #ff9a56 0%, #ff6b6b 100%);">
                        🔀 Shuffle Colors
                    </button>
                    <button class="btn" onclick="sortBoxes()"
                        style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);">
                        📊 Sort Boxes
                    </button>
                </div>
            </div>

            <div class="controls">
                <button class="btn" onclick="refreshBoxes()">🔄 Refresh</button>
                <button class="btn reset" onclick="resetBoxes()">🗑️ Reset</button>
            </div>
        </div>

        <div class="boxes-container">
            <div class="boxes-grid" id="boxes-grid">
                @foreach ($boxes as $box)
                    <div class="box {{ $box->color }}" title="Box #{{ $box->id }} - {{ ucfirst($box->color) }}">
                        {{ ucfirst($box->color) }}
                    </div>
                @endforeach
            </div>

            @if ($boxes->count() === 0)
                <div style="text-align: center; color: #666; font-size: 1.2rem; padding: 40px;">
                    🎲 No boxes yet! The scheduler will create the first box soon.
                </div>
            @endif
        </div>
    </div>

    <script>
        let countdownTimer;
        let refreshInterval;
        let currentBoxes = [];
        let colorArray = ['red', 'yellow', 'green', 'blue', 'pink', 'grey'];

        // Set up CSRF token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Update color array display
        function updateColorArrayDisplay() {
            document.getElementById('colorArray').textContent = '[' + colorArray.join(', ') + ']';
        }

        // Shuffle colors function
        function shuffleColors() {
            // Fisher-Yates shuffle algorithm
            for (let i = colorArray.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [colorArray[i], colorArray[j]] = [colorArray[j], colorArray[i]];
            }
            updateColorArrayDisplay();
        }

        // Sort boxes according to color array order
        function sortBoxes() {
            if (currentBoxes.length === 0) {
                alert('No boxes to sort! Please wait for boxes to be created.');
                return;
            }

            // Sort boxes based on the current color array order
            currentBoxes.sort((a, b) => {
                const indexA = colorArray.indexOf(a.color);
                const indexB = colorArray.indexOf(b.color);
                return indexA - indexB;
            });

            // Update the display with sorted boxes and animation
            updateBoxesDisplay(currentBoxes, 'sorted');
        }

        // Start countdown and auto-refresh
        function startCountdown() {
            let seconds = 60;

            countdownTimer = setInterval(() => {
                seconds--;
                document.getElementById('countdown').textContent = seconds;
                document.getElementById('next-update').textContent = seconds;

                if (seconds <= 0) {
                    refreshBoxes();
                    seconds = 60;
                }
            }, 1000);
        }

        // Refresh boxes from server
        async function refreshBoxes() {
            try {
                const response = await fetch('/api/boxes', {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();
                updateBoxesDisplay(data.boxes);
                updateStats(data.count);

                // Check if we've reached the limit
                if (data.count >= 16) {
                    stopScheduler();
                }
            } catch (error) {
                console.error('Error refreshing boxes:', error);
            }
        }

        // Update the boxes display
        function updateBoxesDisplay(boxes) {
            // Store current boxes for sorting
            currentBoxes = [...boxes];

            const grid = document.getElementById('boxes-grid');
            grid.innerHTML = '';

            if (boxes.length === 0) {
                grid.innerHTML = `
                    <div style="text-align: center; color: #666; font-size: 1.2rem; padding: 40px; width: 100%;">
                        🎲 No boxes yet! The scheduler will create the first box soon.
                    </div>
                `;
                return;
            }

            boxes.forEach((box, index) => {
                const boxElement = document.createElement('div');
                boxElement.className = `box ${box.color}`;
                boxElement.title = `Box #${box.id} - ${box.color.charAt(0).toUpperCase() + box.color.slice(1)}`;
                boxElement.textContent = box.color.charAt(0).toUpperCase() + box.color.slice(1);

                // Add a small delay for animation effect when sorting
                if (arguments[1] === 'sorted') {
                    boxElement.style.animation = `fadeIn 0.3s ease-in-out ${index * 0.05}s both`;
                }

                grid.appendChild(boxElement);
            });
        }

        // Update statistics
        function updateStats(count) {
            document.getElementById('box-count').textContent = count;
        }

        // Stop the scheduler when limit is reached
        function stopScheduler() {
            clearInterval(countdownTimer);
            clearInterval(refreshInterval);

            const status = document.getElementById('status');
            status.className = 'status stopped';
            status.innerHTML = '🔴 Scheduler Stopped - Limit of 16 boxes reached! Email sent.';

            document.getElementById('countdown').textContent = 'STOPPED';
            document.getElementById('next-update').textContent = 'DONE';
        }

        // Reset all boxes
        async function resetBoxes() {
            if (!confirm('Are you sure you want to reset all boxes?')) {
                return;
            }

            try {
                const response = await fetch('/api/boxes/reset', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();
                if (data.success) {
                    location.reload(); // Reload the page to restart everything
                }
            } catch (error) {
                console.error('Error resetting boxes:', error);
            }
        }

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize color array display
            updateColorArrayDisplay();

            const boxCount = parseInt(document.getElementById('box-count').textContent);

            if (boxCount < 16) {
                startCountdown();
                // Also refresh every minute to sync with server
                refreshInterval = setInterval(refreshBoxes, 60000);
            } else {
                stopScheduler();
            }
        });
    </script>
</body>

</html>
