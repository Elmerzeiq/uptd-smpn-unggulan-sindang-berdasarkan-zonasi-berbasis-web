<?php

namespace App\Services;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Storage;
use App\Models\KartuPendaftaran;

class QRCodeService
{
    /**
     * Generate a styled QR code based on the card type.
     *
     * @param KartuPendaftaran $kartu
     * @param bool $isFinalCard
     * @return array
     */
    public function generateStyledQRCode(KartuPendaftaran $kartu, bool $isFinalCard = false): array
    {
        $data = $isFinalCard ? $this->prepareFinalCardData($kartu) : $this->prepareRegularCardData($kartu);
        $label = $isFinalCard ? 'FINAL - ' . $kartu->nomor_kartu : $kartu->nomor_kartu;

        // Define colors based on card type
        $foregroundColor = $isFinalCard ? new Color(5, 150, 105) : new Color(37, 99, 235); // Green for final, Blue for regular
        $backgroundColor = new Color(255, 255, 255);

        // Ensure logo path is valid
        $logoPath = public_path('assets/img/logo-sekolah.png');
        $logo = file_exists($logoPath) ? Logo::create($logoPath)->setResizeToWidth(50)->setPunchoutBackground(true) : null;

        $builder = Builder::create()
            ->writer(new PngWriter())
            ->data($data)
            ->encoding(new Encoding('UTF-8')) // This is the modern way
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size(200)
            ->margin(15)
            ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->foregroundColor($foregroundColor)
            ->backgroundColor($backgroundColor)
            ->labelText($label)
            ->labelAlignment(LabelAlignment::Center)
            ->validateResult(false);

        if ($logo) {
            $builder->logo($logo);
        }

        $result = $builder->build();

        return [
            'data_uri' => $result->getDataUri(),
            'mime_type' => $result->getMimeType(),
            'base64' => base64_encode($result->getString()),
        ];
    }

    /**
     * Verify data from a scanned QR code.
     *
     * @param string $qrData
     * @return array
     */
    public function verifyQRCodeData(string $qrData): array
    {
        try {
            $data = json_decode($qrData, true);

            if (json_last_error() !== JSON_ERROR_NONE || !isset($data['kartu_id'], $data['type'])) {
                return ['valid' => false, 'message' => 'Invalid or corrupted QR Code'];
            }

            if (!in_array($data['type'], ['kartu_pendaftaran', 'kartu_final'])) {
                return ['valid' => false, 'message' => 'Unrecognized QR Code type'];
            }

            $maxAgeInDays = 60; // QR code is valid for 60 days
            if (isset($data['generated_at']) && time() - $data['generated_at'] > ($maxAgeInDays * 24 * 60 * 60)) {
                return ['valid' => false, 'message' => 'QR Code has expired'];
            }

            return ['valid' => true, 'data' => $data];
        } catch (\Exception $e) {
            return ['valid' => false, 'message' => 'Failed to verify QR Code: ' . $e->getMessage()];
        }
    }

    /**
     * Prepare JSON data for a regular registration card QR code.
     */
    private function prepareRegularCardData(KartuPendaftaran $kartu): string
    {
        return json_encode([
            'type' => 'kartu_pendaftaran',
            'kartu_id' => $kartu->id,
            'nomor_kartu' => $kartu->nomor_kartu,
            'user_name' => $kartu->user->nama_lengkap,
            'jalur' => $kartu->jalur_pendaftaran,
            'generated_at' => now()->timestamp,
            'school' => 'SMPN Unggulan Sindang',
            'year' => date('Y'),
        ]);
    }

    /**
     * Prepare JSON data for a final registration card QR code.
     */
    private function prepareFinalCardData(KartuPendaftaran $kartu): string
    {
        return json_encode([
            'type' => 'kartu_final',
            'kartu_id' => $kartu->id,
            'nomor_kartu' => $kartu->nomor_kartu,
            'user_name' => $kartu->user->nama_lengkap,
            'status' => 'lulus_seleksi',
            'generated_at' => now()->timestamp,
            'school' => 'SMPN Unggulan Sindang',
            'year' => date('Y'),
            'final_card' => true,
        ]);
    }
}
