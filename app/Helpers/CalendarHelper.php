<?php

namespace App\Helpers;

use App\Models\AbsensiModel;
use App\Models\IzinSakitModel;

class CalendarHelper
{
    /**
     * Generate calendar data untuk siswa
     * 
     * @param int $siswa_id
     * @param int $month
     * @param int $year
     * @return array
     */
    public function generateCalendarData($siswa_id, $month = null, $year = null)
    {
        if ($month === null) $month = (int)date('m');
        if ($year === null) $year = (int)date('Y');

        $absensiModel = new AbsensiModel();
        $izinSakitModel = new IzinSakitModel();

        // Ambil semua data absensi untuk bulan ini
        $absensiData = $absensiModel
            ->where('siswa_id', $siswa_id)
            ->where("MONTH(tanggal)", $month)
            ->where("YEAR(tanggal)", $year)
            ->findAll();

        // Ambil semua approved izin/sakit untuk bulan ini
        $izinSakitData = $izinSakitModel
            ->where('siswa_id', $siswa_id)
            ->where('status', 'approved')
            ->where("MONTH(tanggal)", $month)
            ->where("YEAR(tanggal)", $year)
            ->findAll();

        // Buat array dengan key tanggal
        $absensiByDate = [];
        foreach ($absensiData as $absensi) {
            $absensiByDate[$absensi['tanggal']] = $absensi;
        }

        $izinSakitByDate = [];
        foreach ($izinSakitData as $izin) {
            $izinSakitByDate[$izin['tanggal']] = $izin;
        }

        // Generate calendar structure
        $firstDay = strtotime("$year-$month-01");
        $daysInMonth = (int)date('t', $firstDay);
        $startingDayOfWeek = (int)date('w', $firstDay); // 0 = Sunday, 6 = Saturday

        $calendar = [];
        $currentDay = 1;

        // Isi minggu pertama
        for ($i = 0; $i < 6; $i++) {
            $week = [];
            for ($j = 0; $j < 7; $j++) {
                if (($i === 0 && $j < $startingDayOfWeek) || $currentDay > $daysInMonth) {
                    // Hari dari bulan lain atau kosong
                    $week[] = null;
                } else {
                    $date = sprintf("%04d-%02d-%02d", $year, $month, $currentDay);
                    $status = self::getDateStatus($date, $absensiByDate, $izinSakitByDate, $siswa_id);
                    $week[] = [
                        'date' => $date,
                        'day' => $currentDay,
                        'status' => $status['status'],
                        'jenis' => $status['jenis'] ?? null, // 'izin' atau 'sakit' jika approved
                        'data' => $status['data'] ?? null,
                    ];
                    $currentDay++;
                }
            }
            $calendar[] = $week;
            if ($currentDay > $daysInMonth) break;
        }

        return [
            'month' => $month,
            'year' => $year,
            'monthName' => self::getMonthName($month),
            'calendar' => $calendar,
        ];
    }

    /**
     * Tentukan status untuk tanggal tertentu
     * 
     * @param string $date (Y-m-d format)
     * @param array $absensiByDate
     * @param array $izinSakitByDate
     * @param int $siswa_id
     * @return array
     */
    private function getDateStatus($date, $absensiByDate, $izinSakitByDate, $siswa_id)
    {
        // Priority 1: Check approved izin/sakit
        if (isset($izinSakitByDate[$date])) {
            return [
                'status' => $izinSakitByDate[$date]['jenis'], // 'izin' atau 'sakit'
                'jenis' => $izinSakitByDate[$date]['jenis'],
                'data' => $izinSakitByDate[$date],
            ];
        }

        // Priority 2: Check absensi (QR scan atau manual)
        if (isset($absensiByDate[$date])) {
            $absensi = $absensiByDate[$date];
            return [
                'status' => $absensi['status'], // 'hadir', 'terlambat', 'alpha', dll
                'data' => $absensi,
            ];
        }

        // Priority 3: Check if weekend or holiday
        $dayOfWeek = date('w', strtotime($date)); // 0 = Sunday, 6 = Saturday
        if ($dayOfWeek == 0 || $dayOfWeek == 6) {
            return ['status' => 'libur'];
        }

        // Priority 4: Default = Alpha atau belum diisi
        $currentDate = date('Y-m-d');
        if ($date > $currentDate) {
            return ['status' => 'future']; // Tanggal di masa depan
        } else {
            return ['status' => 'alpha']; // Alpha (belum absen, tidak ada izin/sakit)
        }
    }

    /**
     * Get month name in Indonesian
     */
    private function getMonthName($month)
    {
        $months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        return $months[$month - 1] ?? '';
    }

    /**
     * Get color class untuk status
     */
    public function getStatusColor($status)
    {
        $colors = [
            'hadir' => 'bg-success text-white',      // Green
            'terlambat' => 'bg-warning text-dark',   // Yellow
            'izin' => 'bg-info text-white',          // Blue
            'sakit' => 'bg-primary text-white',      // Purple
            'alpha' => 'bg-danger text-white',       // Red
            'libur' => 'bg-secondary text-white',    // Gray
            'future' => 'bg-light text-muted',       // Light gray
        ];
        return $colors[$status] ?? 'bg-light text-muted';
    }

    /**
     * Get icon untuk status
     */
    public function getStatusIcon($status)
    {
        $icons = [
            'hadir' => 'fas fa-check-circle',
            'terlambat' => 'fas fa-clock',
            'izin' => 'fas fa-file-alt',
            'sakit' => 'fas fa-heartbeat',
            'alpha' => 'fas fa-times-circle',
            'libur' => 'fas fa-calendar-alt',
            'future' => 'fas fa-calendar',
        ];
        return $icons[$status] ?? 'fas fa-question-circle';
    }

    /**
     * Get label untuk status
     */
    public function getStatusLabel($status)
    {
        $labels = [
            'hadir' => 'Hadir',
            'terlambat' => 'Terlambat',
            'izin' => 'Izin',
            'sakit' => 'Sakit',
            'alpha' => 'Alpha',
            'libur' => 'Libur',
            'future' => '-',
        ];
        return $labels[$status] ?? 'Unknown';
    }
}
