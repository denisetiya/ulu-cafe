<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promo Baru dari Ulu Coffee</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f4;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f4f4f4; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #35c759 0%, #2a9d47 100%); padding: 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">
                                ☕ ULU COFFEE
                            </h1>
                            <p style="margin: 10px 0 0; color: rgba(255,255,255,0.95); font-size: 14px;">
                                Promo Spesial Untuk Anda!
                            </p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            @if($promoType === 'banner')
                                <!-- Banner Promo -->
                                @if(isset($promoData['image_url']))
                                    <img src="{{ $promoData['image_url'] }}" alt="Promo Banner" style="width: 100%; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                @endif
                                
                                <h2 style="color: #1a1a1a; font-size: 24px; margin: 0 0 15px; font-weight: bold;">
                                    {{ $promoData['title'] ?? 'Promo Spesial!' }}
                                </h2>
                                
                                @if(isset($promoData['description']))
                                    <p style="color: #666666; font-size: 16px; line-height: 1.6; margin: 0 0 25px;">
                                        {{ $promoData['description'] }}
                                    </p>
                                @endif

                            @elseif($promoType === 'voucher')
                                <!-- Voucher Promo -->
                                <div style="text-align: center; margin-bottom: 25px;">
                                    <p style="color: #666666; margin: 0 0 10px; font-size: 14px;">Gunakan Kode Voucher:</p>
                                    <div style="background: linear-gradient(135deg, #35c759 0%, #2a9d47 100%); display: inline-block; padding: 15px 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(53, 199, 89, 0.2);">
                                        <span style="color: #ffffff; font-size: 28px; font-weight: bold; letter-spacing: 3px; font-family: monospace;">
                                            {{ $promoData['code'] }}
                                        </span>
                                    </div>
                                </div>

                                <div style="background-color: #f8f9fa; border-radius: 12px; padding: 20px; text-align: center; border: 1px solid #e5e7eb;">
                                    <p style="color: #35c759; font-size: 32px; font-weight: bold; margin: 0;">
                                        @if($promoData['type'] === 'fixed')
                                            Rp {{ number_format($promoData['amount'], 0, ',', '.') }}
                                        @else
                                            {{ $promoData['amount'] }}% OFF
                                        @endif
                                    </p>
                                    @if(isset($promoData['min_purchase']) && $promoData['min_purchase'] > 0)
                                        <p style="color: #666666; font-size: 14px; margin: 10px 0 0;">
                                            Min. pembelian Rp {{ number_format($promoData['min_purchase'], 0, ',', '.') }}
                                        </p>
                                    @endif
                                </div>
                            @endif

                            <!-- CTA Button -->
                            <div style="text-align: center; margin-top: 30px;">
                                <a href="{{ config('app.url') }}/menu" style="display: inline-block; background: linear-gradient(135deg, #35c759 0%, #2a9d47 100%); color: #ffffff; text-decoration: none; padding: 15px 40px; border-radius: 50px; font-weight: bold; font-size: 16px; box-shadow: 0 4px 6px rgba(53, 199, 89, 0.25);">
                                    Pesan Sekarang →
                                </a>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 25px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="color: #999999; font-size: 12px; margin: 0;">
                                © {{ date('Y') }} Ulu Coffee. All rights reserved.
                            </p>
                            <p style="color: #999999; font-size: 11px; margin: 10px 0 0;">
                                Anda menerima email ini karena terdaftar sebagai pelanggan Ulu Coffee.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
