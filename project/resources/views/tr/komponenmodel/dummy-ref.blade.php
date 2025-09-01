<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Komponen Model Dashboard</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            color: #2d3748;
            font-size: 2.5rem;
            margin-bottom: 10px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .filters {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #4a5568;
        }

        .filter-group select, .filter-group input {
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }

        .filter-group select:focus, .filter-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(45deg, #667eea, #764ba2);
        }

        .card h3 {
            color: #2d3748;
            margin-bottom: 15px;
            font-size: 1.3rem;
        }

        .stat-card {
            text-align: center;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }

        .stat-label {
            color: #718096;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        .table-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        th {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            font-weight: 600;
            position: sticky;
            top: 0;
        }

        tr:hover {
            background: rgba(102, 126, 234, 0.05);
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .location-badge {
            display: inline-block;
            padding: 4px 8px;
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            border-radius: 12px;
            font-size: 0.8rem;
            margin: 2px;
        }

        .status-active {
            color: #38a169;
            font-weight: 600;
        }

        .status-inactive {
            color: #e53e3e;
            font-weight: 600;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background: white;
            margin: 5% auto;
            padding: 30px;
            border-radius: 20px;
            width: 80%;
            max-width: 800px;
            max-height: 80vh;
            overflow-y: auto;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: #000;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }

        .detail-item {
            background: #f7fafc;
            padding: 15px;
            border-radius: 12px;
            border-left: 4px solid #667eea;
        }

        .detail-label {
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 5px;
        }

        .detail-value {
            color: #2d3748;
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header with Filters -->
        <div class="header">
            <h1>Dashboard Komponen Model</h1>
            <p style="color: #718096; margin-bottom: 20px;">Monitoring dan analisis komponen model program</p>

            <div class="filters">
                <div class="filter-group">
                    <label for="program-select">Program</label>
                    <select id="program-select">
                        <option value="">Semua Program</option>
                        <option value="1">Program Gizi Berbasis Masyarakat</option>
                        <option value="2">Program Pemberdayaan Ekonomi</option>
                        <option value="3">Program Kesehatan Ibu Anak</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="komponen-select">Komponen Model</label>
                    <select id="komponen-select">
                        <option value="">Semua Komponen</option>
                        <option value="1">Model Kitchen Garden</option>
                        <option value="2">Model Posyandu Balita</option>
                        <option value="3">Model Dapur Sehat</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="tahun-select">Tahun</label>
                    <select id="tahun-select">
                        <option value="">Semua Tahun</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                        <option value="2022">2022</option>
                    </select>
                </div>

                <div class="filter-group">
                    <button class="btn btn-primary" onclick="applyFilters()" style="align-self: end;">
                        Filter Data
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="dashboard-grid">
            <div class="card stat-card">
                <div class="stat-number" id="total-komponen">24</div>
                <div class="stat-label">Total Komponen Model</div>
            </div>

            <div class="card stat-card">
                <div class="stat-number" id="total-lokasi">156</div>
                <div class="stat-label">Total Lokasi Implementasi</div>
            </div>

            <div class="card stat-card">
                <div class="stat-number" id="total-jumlah">2,847</div>
                <div class="stat-label">Total Unit Terpasang</div>
            </div>

            <div class="card stat-card">
                <div class="stat-number" id="active-programs">8</div>
                <div class="stat-label">Program Aktif</div>
            </div>
        </div>

        <!-- Charts -->
        <div class="dashboard-grid">
            <div class="card">
                <h3>Distribusi per Komponen Model</h3>
                <div class="chart-container">
                    <canvas id="komponenChart"></canvas>
                </div>
            </div>

            <div class="card">
                <h3>Trend Implementasi per Tahun</h3>
                <div class="chart-container">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>

            <div class="card">
                <h3>Sebaran per Provinsi</h3>
                <div class="chart-container">
                    <canvas id="provinsiChart"></canvas>
                </div>
            </div>

            <div class="card">
                <h3>Target vs Achievement</h3>
                <div class="chart-container">
                    <canvas id="targetChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Detailed Table -->
        <div class="table-container">
            <h2 style="margin-bottom: 20px; color: #2d3748;">Detail Komponen Model</h2>

            <table id="detailTable">
                <thead>
                    <tr>
                        <th>Program</th>
                        <th>Komponen Model</th>
                        <th>Total Unit</th>
                        <th>Jumlah Lokasi</th>
                        <th>Provinsi</th>
                        <th>Status</th>
                        <th>Target Reinstra</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <!-- Data will be populated by JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="detailModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modalTitle">Detail Komponen Model</h2>

            <div class="detail-grid" id="modalContent">
                <!-- Content will be populated by JavaScript -->
            </div>

            <h3 style="margin: 30px 0 15px 0;">Lokasi Implementasi</h3>
            <table id="locationTable" style="margin-top: 15px;">
                <thead>
                    <tr>
                        <th>Desa/Kelurahan</th>
                        <th>Kecamatan</th>
                        <th>Kabupaten</th>
                        <th>Provinsi</th>
                        <th>Jumlah</th>
                        <th>Satuan</th>
                        <th>Koordinat</th>
                    </tr>
                </thead>
                <tbody id="locationTableBody">
                    <!-- Location data will be populated -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Sample data - in real implementation, this would come from your API
        const sampleData = [
            {
                id: 1,
                program: 'Program Gizi Berbasis Masyarakat',
                komponen: 'Model Kitchen Garden',
                totalJumlah: 150,
                jumlahLokasi: 25,
                provinsi: ['Jawa Barat', 'Jawa Tengah'],
                status: 'Aktif',
                tahun: 2024,
                targetReinstra: ['Target Gizi 1.1', 'Target Gizi 1.2']
            },
            {
                id: 2,
                program: 'Program Pemberdayaan Ekonomi',
                komponen: 'Model Posyandu Balita',
                totalJumlah: 89,
                jumlahLokasi: 12,
                provinsi: ['Jawa Timur'],
                status: 'Aktif',
                tahun: 2024,
                targetReinstra: ['Target Ekonomi 2.1']
            },
            {
                id: 3,
                program: 'Program Kesehatan Ibu Anak',
                komponen: 'Model Dapur Sehat',
                totalJumlah: 67,
                jumlahLokasi: 18,
                provinsi: ['Bali', 'NTB'],
                status: 'Selesai',
                tahun: 2023,
                targetReinstra: ['Target Kesehatan 3.1', 'Target Kesehatan 3.2']
            }
        ];

        const locationData = {
            1: [
                { desa: 'Bandung Wetan', kecamatan: 'Bandung', kabupaten: 'Kota Bandung', provinsi: 'Jawa Barat', jumlah: 15, satuan: 'Unit', lat: -6.9175, lng: 107.6191 },
                { desa: 'Semarang Tengah', kecamatan: 'Semarang Tengah', kabupaten: 'Kota Semarang', provinsi: 'Jawa Tengah', jumlah: 12, satuan: 'Unit', lat: -7.0051, lng: 110.4381 }
            ],
            2: [
                { desa: 'Surabaya Pusat', kecamatan: 'Genteng', kabupaten: 'Kota Surabaya', provinsi: 'Jawa Timur', jumlah: 8, satuan: 'Unit', lat: -7.2504, lng: 112.7688 }
            ],
            3: [
                { desa: 'Denpasar Selatan', kecamatan: 'Denpasar Selatan', kabupaten: 'Kota Denpasar', provinsi: 'Bali', jumlah: 10, satuan: 'Unit', lat: -8.6705, lng: 115.2126 }
            ]
        };

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            populateTable();
            createCharts();
            setupModal();
        });

        function populateTable() {
            const tbody = document.getElementById('tableBody');
            tbody.innerHTML = '';

            sampleData.forEach(item => {
                const row = tbody.insertRow();
                row.innerHTML = `
                    <td>${item.program}</td>
                    <td>${item.komponen}</td>
                    <td><strong>${item.totalJumlah.toLocaleString()}</strong></td>
                    <td>${item.jumlahLokasi}</td>
                    <td>
                        ${item.provinsi.map(p => `<span class="location-badge">${p}</span>`).join('')}
                    </td>
                    <td class="${item.status === 'Aktif' ? 'status-active' : 'status-inactive'}">${item.status}</td>
                    <td>
                        ${item.targetReinstra.map(t => `<span class="location-badge">${t}</span>`).join('')}
                    </td>
                    <td>
                        <button class="btn btn-primary" onclick="showDetail(${item.id})">Detail</button>
                    </td>
                `;
            });
        }

        function createCharts() {
            // Komponen Chart
            const komponenCtx = document.getElementById('komponenChart').getContext('2d');
            new Chart(komponenCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Kitchen Garden', 'Posyandu Balita', 'Dapur Sehat'],
                    datasets: [{
                        data: [150, 89, 67],
                        backgroundColor: ['#667eea', '#764ba2', '#f093fb']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Trend Chart
            const trendCtx = document.getElementById('trendChart').getContext('2d');
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: ['2020', '2021', '2022', '2023', '2024'],
                    datasets: [{
                        label: 'Jumlah Implementasi',
                        data: [45, 78, 120, 186, 306],
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Provinsi Chart
            const provinsiCtx = document.getElementById('provinsiChart').getContext('2d');
            new Chart(provinsiCtx, {
                type: 'bar',
                data: {
                    labels: ['Jawa Barat', 'Jawa Tengah', 'Jawa Timur', 'Bali', 'NTB'],
                    datasets: [{
                        label: 'Jumlah Komponen',
                        data: [45, 32, 28, 25, 18],
                        backgroundColor: 'rgba(102, 126, 234, 0.8)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Target Chart
            const targetCtx = document.getElementById('targetChart').getContext('2d');
            new Chart(targetCtx, {
                type: 'radar',
                data: {
                    labels: ['Target 1.1', 'Target 1.2', 'Target 2.1', 'Target 3.1', 'Target 3.2'],
                    datasets: [{
                        label: 'Achievement',
                        data: [85, 92, 76, 88, 79],
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.2)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        function setupModal() {
            const modal = document.getElementById('detailModal');
            const span = document.getElementsByClassName('close')[0];

            span.onclick = function() {
                modal.style.display = 'none';
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }
        }

        function showDetail(id) {
            const item = sampleData.find(d => d.id === id);
            const locations = locationData[id] || [];

            document.getElementById('modalTitle').textContent = `Detail: ${item.komponen}`;

            const modalContent = document.getElementById('modalContent');
            modalContent.innerHTML = `
                <div class="detail-item">
                    <div class="detail-label">Program</div>
                    <div class="detail-value">${item.program}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Komponen Model</div>
                    <div class="detail-value">${item.komponen}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Total Unit</div>
                    <div class="detail-value">${item.totalJumlah.toLocaleString()}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Jumlah Lokasi</div>
                    <div class="detail-value">${item.jumlahLokasi}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Status</div>
                    <div class="detail-value">${item.status}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Tahun</div>
                    <div class="detail-value">${item.tahun}</div>
                </div>
            `;

            const locationTableBody = document.getElementById('locationTableBody');
            locationTableBody.innerHTML = '';

            locations.forEach(loc => {
                const row = locationTableBody.insertRow();
                row.innerHTML = `
                    <td>${loc.desa}</td>
                    <td>${loc.kecamatan}</td>
                    <td>${loc.kabupaten}</td>
                    <td>${loc.provinsi}</td>
                    <td>${loc.jumlah}</td>
                    <td>${loc.satuan}</td>
                    <td>${loc.lat}, ${loc.lng}</td>
                `;
            });

            document.getElementById('detailModal').style.display = 'block';
        }

        function applyFilters() {
            const programId = document.getElementById('program-select').value;
            const komponenId = document.getElementById('komponen-select').value;
            const tahun = document.getElementById('tahun-select').value;

            // Here you would make an API call with these filters
            console.log('Applying filters:', { programId, komponenId, tahun });

            // For demo, just show alert
            alert(`Filter applied:\nProgram: ${programId || 'All'}\nKomponen: ${komponenId || 'All'}\nTahun: ${tahun || 'All'}`);
        }
    </script>
</body>
</html>
