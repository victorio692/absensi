<?php
// Partial view untuk menampilkan calendar absensi bulanan
// Include dari dashboard dengan: <?= view('siswa/partials/calendar', $calendarData) ?>
?>

<div class="card mb-4">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-calendar-alt"></i> Kalender Absensi Bulanan - 
                <strong><?= $monthName . ' ' . $year ?></strong>
            </h5>
        </div>
    </div>
    <div class="card-body">
        <!-- Legend -->
        <div class="mb-4 p-4 bg-light rounded border-start border-5 border-primary">
            <h6 class="mb-3 fw-bold">Keterangan Status:</h6>
            <div class="row g-4">
                <div class="col-md-2 col-sm-4 col-6">
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #28a745;"></div>
                        <small class="fw-semibold">Hadir</small>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6">
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #ffc107;"></div>
                        <small class="fw-semibold">Terlambat</small>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6">
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #0dcaf0;"></div>
                        <small class="fw-semibold">Izin</small>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6">
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #667eea;"></div>
                        <small class="fw-semibold">Sakit</small>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6">
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #dc3545;"></div>
                        <small class="fw-semibold">Alpha</small>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6">
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #6c757d;"></div>
                        <small class="fw-semibold">Libur</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar Grid -->
        <div class="calendar-container">
            <!-- Header - Hari dalam minggu -->
            <div class="calendar-header">
                <div class="calendar-day-header">Minggu</div>
                <div class="calendar-day-header">Senin</div>
                <div class="calendar-day-header">Selasa</div>
                <div class="calendar-day-header">Rabu</div>
                <div class="calendar-day-header">Kamis</div>
                <div class="calendar-day-header">Jumat</div>
                <div class="calendar-day-header">Sabtu</div>
            </div>

            <!-- Calendar Body -->
            <?php foreach ($calendar as $week): ?>
                <div class="calendar-week">
                    <?php foreach ($week as $day): ?>
                        <?php if ($day === null): ?>
                            <!-- Empty day from other month -->
                            <div class="calendar-day calendar-day-empty"></div>
                        <?php else: ?>
                            <div class="calendar-day calendar-day-<?= $day['status'] ?>" 
                                 data-date="<?= $day['date'] ?>"
                                 data-status="<?= $day['status'] ?>"
                                 <?php if ($day['data'] && $day['status'] !== 'alpha' && $day['status'] !== 'libur' && $day['status'] !== 'future'): ?>
                                    style="cursor: pointer;"
                                    role="button"
                                    tabindex="0"
                                    data-bs-toggle="modal"
                                    data-bs-target="#calendarDetailModal"
                                    data-detail='<?= json_encode($day['data']) ?>'
                                 <?php endif; ?>
                            >
                                <div class="calendar-day-number"><?= $day['day'] ?></div>
                                <div class="calendar-day-status">
                                    <i class="<?php 
                                        $icons = [
                                            'hadir' => 'fas fa-check-circle',
                                            'terlambat' => 'fas fa-clock',
                                            'izin' => 'fas fa-file-alt',
                                            'sakit' => 'fas fa-heartbeat',
                                            'alpha' => 'fas fa-times-circle',
                                            'libur' => 'fas fa-calendar-alt',
                                            'future' => 'fas fa-calendar',
                                        ];
                                        echo $icons[$day['status']] ?? 'fas fa-question-circle';
                                    ?>"></i>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Modal Detail Kalender -->
<div class="modal fade" id="calendarDetailModal" tabindex="-1" aria-labelledby="calendarDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="calendarDetailModalLabel">Detail Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="calendarDetailContent">
                <!-- Content akan di-fill oleh JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
.legend-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}

.legend-color {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    border: 2px solid rgba(0, 0, 0, 0.1);
    transition: transform 0.2s ease;
}

.legend-item:hover .legend-color {
    transform: scale(1.1);
}

.calendar-container {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 8px;
    margin-bottom: 20px;
}

.calendar-header {
    display: contents;
}

.calendar-day-header {
    font-weight: 600;
    text-align: center;
    padding: 12px 8px;
    background-color: #f8f9fa;
    border-radius: 6px;
    color: #495057;
    font-size: 0.875rem;
}

.calendar-week {
    display: contents;
}

.calendar-day {
    min-height: 100px;
    padding: 8px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 6px;
    background-color: #fff;
    transition: all 0.3s ease;
}

.calendar-day-empty {
    background-color: transparent;
    border: none;
    cursor: default !important;
}

.calendar-day-number {
    font-weight: 700;
    font-size: 1.1rem;
    color: #212529;
}

.calendar-day-status {
    font-size: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 30px;
}

/* Status Colors */
.calendar-day-hadir {
    background-color: #d4edda;
    border-color: #28a745;
    color: #155724;
}

.calendar-day-hadir .calendar-day-status {
    color: #28a745;
}

.calendar-day-terlambat {
    background-color: #fff3cd;
    border-color: #ffc107;
    color: #856404;
}

.calendar-day-terlambat .calendar-day-status {
    color: #ffc107;
}

.calendar-day-izin {
    background-color: #cfe2ff;
    border-color: #0dcaf0;
    color: #084298;
}

.calendar-day-izin .calendar-day-status {
    color: #0dcaf0;
}

.calendar-day-sakit {
    background-color: #cff4fc;
    border-color: #667eea;
    color: #0c5460;
}

.calendar-day-sakit .calendar-day-status {
    color: #667eea;
}

.calendar-day-alpha {
    background-color: #f8d7da;
    border-color: #dc3545;
    color: #721c24;
}

.calendar-day-alpha .calendar-day-status {
    color: #dc3545;
}

.calendar-day-libur {
    background-color: #e2e3e5;
    border-color: #6c757d;
    color: #383d41;
}

.calendar-day-libur .calendar-day-status {
    color: #6c757d;
}

.calendar-day-future {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    color: #6c757d;
}

.calendar-day-future .calendar-day-status {
    color: #adb5bd;
}

/* Hover effect untuk hari yang clickable */
.calendar-day[style*="cursor: pointer"]:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
    border-width: 3px;
}

/* Responsive */
@media (max-width: 768px) {
    .calendar-container {
        gap: 6px;
    }

    .calendar-day {
        min-height: 80px;
        padding: 6px;
        font-size: 0.9rem;
    }

    .calendar-day-number {
        font-size: 0.95rem;
    }

    .calendar-day-status {
        font-size: 1.2rem;
    }

    .calendar-day-header {
        padding: 10px 6px;
        font-size: 0.75rem;
    }
}

@media (max-width: 480px) {
    .calendar-container {
        gap: 4px;
    }

    .calendar-day {
        min-height: 70px;
        padding: 4px;
    }

    .calendar-day-number {
        font-size: 0.85rem;
    }

    .calendar-day-status {
        font-size: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarDetailModal = document.getElementById('calendarDetailModal');
    
    if (calendarDetailModal) {
        calendarDetailModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            if (!button) return;

            const detailData = button.dataset.detail;
            if (!detailData) return;

            try {
                const data = JSON.parse(detailData);
                const modalBody = document.getElementById('calendarDetailContent');
                
                const jamMasuk = data.jam_masuk ? data.jam_masuk.substring(0, 5) : '-';
                const jamPulang = data.jam_pulang ? data.jam_pulang.substring(0, 5) : '-';
                const status = data.status ? data.status.charAt(0).toUpperCase() + data.status.slice(1) : '-';
                
                let html = `
                    <div class="calendar-detail">
                        <p class="mb-2">
                            <strong>Tanggal:</strong> ${data.tanggal || '-'}
                        </p>
                        <p class="mb-2">
                            <strong>Jam Masuk:</strong> ${jamMasuk}
                        </p>
                        <p class="mb-2">
                            <strong>Jam Pulang:</strong> ${jamPulang}
                        </p>
                        <p class="mb-2">
                            <strong>Status:</strong> <span class="badge bg-info">${status}</span>
                        </p>
                        <p class="mb-0">
                            <strong>Sumber:</strong> ${data.source ? data.source.charAt(0).toUpperCase() + data.source.slice(1) : '-'}
                        </p>
                    </div>
                `;
                
                modalBody.innerHTML = html;
            } catch (e) {
                console.error('Error parsing detail data:', e);
            }
        });
    }
});
</script>
